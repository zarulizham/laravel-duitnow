<?php

namespace ZarulIzham\DuitNowPayment;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use ZarulIzham\DuitNowPayment\Models\Bank;
use ZarulIzham\DuitNowPayment\Models\BankUrl;
use ZarulIzham\DuitNowPayment\Models\DuitNowTransaction;
use ZarulIzham\DuitNowPayment\Traits\SignMessage;

class DuitNowPayment
{
    use SignMessage;

    protected $token;

    protected $sequence;

    protected $messageId;

    protected $transactionId;

    public function commonParameters($transactionType = null)
    {
        $this->token = Cache::remember('duitnow_token', config('duitnow.token_expiry'), function () {
            return $this->authenticate();
        });

        $this->sequence = str_pad(Cache::increment('duitnow_sequence'), 8, "0", STR_PAD_LEFT);

        $this->messageId = date('Ymd') . config('duitnow.merchant_id') . $transactionType . 'O' . 'BW' . $this->sequence;

        $this->transactionId = date('Ymd') . config('duitnow.merchant_id') . $transactionType . $this->sequence;
    }

    public function authenticate()
    {
        $response = Http::asForm()->post(config('duitnow.url') . '/auth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('duitnow.client_id'),
            'client_secret' => config('duitnow.client_secret'),
        ]);

        $data = $response->object();
        return $data->access_token;
    }

    public function bankList($pageKey = '')
    {
        $this->commonParameters('650');

        $message = config('duitnow.merchant_id') . 'RPPEMYKL' . $this->messageId . $this->transactionId . $this->messageId . config('duitnow.merchant_id');

        $signedMessage = $this->sign($message);

        $url = config('duitnow.url') . '/merchants/v1/payments/lists/bank?clientId=' . config('duitnow.merchant_id') . "&messageId=$this->messageId&transactionId=$this->transactionId" . ($pageKey ? "&pageKey=$pageKey" : "");
        $response = Http::withToken($this->token)
            ->withHeaders([
                'X-Signature-Key' => config('duitnow.x_signature_key'),
                'X-Signature' => $signedMessage,
                'X-Gps-Coordinates' => '40.689263,74.044505',
                'X-Ip-Address' => '127.0.0.1',
            ])->get($url);

        $this->syncBanks($response->object()?->banks);

        if ($response->object()?->pageKey) {
            $this->bankList($response->object()->pageKey);
        }

        $banks = Bank::with('urls')->get();
        return $banks;
    }

    protected function syncBanks($banks)
    {
        foreach ($banks ?? [] as $record) {
            $bank = Bank::updateOrCreate([
                'code' => $record->code,
                'name' => $record->name,
            ], [
                'status' => $record->active ? 'Online' : 'Offline',
            ]);

            foreach ($record->redirectUrls ?? [] as $redirectUrl) {
                $bank->urls()->updateOrCreate([
                    'type' => $redirectUrl->type,
                ], [
                    'url' => $redirectUrl->url,
                ]);
            }
        }
    }

    public function initiatePayment($amount, $customerName, $bankType, $reference, $bankId, $referenceId)
    {
        $amount = (float) $amount;

        $this->commonParameters('861');

        $message = config('duitnow.merchant_id') . 'RPPEMYKL' . $this->messageId . $this->transactionId . 'RPPEMYKL' .  $this->messageId . number_format($amount, 2) . config('duitnow.merchant_id');

        $signedMessage = $this->sign($message);

        $body = [
            'clientId' => config('duitnow.merchant_id'),
            'messageId' => $this->messageId,
            'transactionId' => $this->transactionId,
            'endToEndId' => $this->messageId,
            'currency' => 'MYR',
            'amount' => $amount,
            'productId' => config('duitnow.product_id'),
            'customer' => [
                'name' => $customerName,
                'bankType' => $bankType,
            ],
            'merchant' => [
                'name' => config('duitnow.merchant_name'),
                'accountType' => config('duitnow.account_type'),
            ],
            'sourceOfFunds' => config('duitnow.source_of_funds'),
            'recipientReference' => $reference,
        ];

        $url = config('duitnow.url') . '/merchants/v1/payments/redirect';
        $response = Http::withToken($this->token)
            ->withOptions([
                'debug' => false,
            ])
            ->withHeaders([
                'X-Signature-Key' => config('duitnow.x_signature_key'),
                'X-Signature' => $signedMessage,
                'X-Gps-Coordinates' => '40.689263,74.044505',
                'X-Ip-Address' => '127.0.0.1',
                'Content-Type' => 'application/json',
            ])->post($url, $body);

        $this->saveTransaction($this->transactionId, $referenceId, $body, $this->messageId);

        if ($response->status() == 200) {
            $redirectUrl = $this->getUrl($bankId, $bankType, $this->messageId, $response->object()->endToEndIdSignature);
        } else {
            throw new \Exception($response->object()->header->status->description, $response->status());
        }

        return $redirectUrl;
    }

    protected function getUrl($bankId, $bankType, $messageId, $endToEndIdSignature)
    {
        $bankUrl = BankUrl::whereHas('bank', function ($query) use ($bankId) {
            return $query->whereId($bankId);
        })->whereType($bankType)->first();

        if (str_contains("RPP/MY/Redirect/RTP", $bankUrl->url)) {
            $endToEndId = "&EndtoEndId=" . $messageId;
        } else {
            $endToEndId = "?EndtoEndId=" . $messageId;
        }
        return sprintf(
            "%s%s%s%s",
            $bankUrl->url,
            $endToEndId,
            "&EndtoEndIdSignature=" . urlencode($endToEndIdSignature),
            "&DbtrAgt=" . $bankUrl->bank->code,
        );
    }

    protected function saveTransaction($transactionId, $referenceId, $requestPayload, $endToEndId): DuitNowTransaction
    {
        return DuitNowTransaction::create([
            'transaction_id' => $transactionId,
            'reference_id' => $referenceId,
            'request_payload' => $requestPayload,
            'end_to_end_id' => $endToEndId,
        ]);
    }

    public function statusInquiry($endToEndId)
    {
        $this->commonParameters('864');

        $message = config('duitnow.merchant_id') . 'RPPEMYKL' . $this->messageId . $this->transactionId . $endToEndId . $endToEndId;

        $signedMessage = $this->sign($message);

        $url = config('duitnow.url') . "/merchants/v1/payments/payment/status?clientId=" . config('duitnow.merchant_id') . "&messageId=$this->messageId&transactionId=$this->transactionId&endToEndId=$endToEndId";
        $response = Http::withToken($this->token)
            ->withOptions([
                'debug' => false,
            ])
            ->withHeaders([
                'X-Signature-Key' => config('duitnow.x_signature_key'),
                'X-Signature' => $signedMessage,
                'X-Gps-Coordinates' => '40.689263,74.044505',
                'X-Ip-Address' => '127.0.0.1',
                'Content-Type' => 'application/json',
            ])->get($url);

        return $response->json();
    }
}
