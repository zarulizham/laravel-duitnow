<?php

namespace ZarulIzham\DuitNowPayment\Messages;

use ZarulIzham\DuitNowPayment\Contracts\Message as Contract;
use ZarulIzham\DuitNowPayment\DuitNowPayment;
use ZarulIzham\DuitNowPayment\Models\DuitNowTransaction;

class AuthorizationConfirmation implements Contract
{
    public $paymentStatusCode;
    public $responsePayload;
    public $endToEndId;
    public $transactionStatus;

    /**
     * handle a message
     *
     * @param array $options
     * @return mixed
     */
    public function handle($options)
    {
        if ($options['type'] == 'callback') {
            $this->paymentStatusCode = @$options['Notification']['EventInfo']['PaymentStatus']['Code'];
            $this->endToEndId = @$options['Notification']['EventInfo']['EndToEndID'];
            $this->endToEndIdSignature = @$options['Notification']['EventInfo']['Signature'];
        } else {
            $this->paymentStatusCode = null;
            $this->endToEndId = @$options['EndtoEndId'];
            $this->endToEndIdSignature = @$options['EndtoEndIdSignature'];
        }

        $this->transactionStatus = null;
        $this->responsePayload = @$options;

        $this->getTransaction();

        return $this;
    }

    /**
     * Format data for checksum
     * @return string
     */
    public function format()
    {
        return $this->list()->join('|');
    }

    /**
     * returns collection of all fields
     */
    public function list()
    {
        return collect($this->responsePayload);
    }

    /**
     * Save response to transaction
     *
     * @return DuitNowTransaction
     */
    private function saveTransaction(): DuitNowTransaction
    {
        $transaction = DuitNowTransaction::where(['end_to_end_id' => $this->endToEndId])->firstOrNew();

        $transaction->end_to_end_id = $this->endToEndId;
        $transaction->payment_status_code = $this->paymentStatusCode;
        $transaction->payment_substate = $this->transactionStatus;
        $transaction->response_payload = $this->responsePayload;
        $transaction->save();

        return $transaction;
    }

    private function getTransaction()
    {
        $duitNowPayment = new DuitNowPayment();

        $response = $duitNowPayment->statusInquiry($this->endToEndId);
        if (isset($response['errorCode'])) {
            $this->saveTransaction();

            throw new \Exception($response['description'], 400);
        } else {
            try {
                $this->transactionStatus = $response['transactionStatus'];
                $this->transactionId = $response['transactionId'];
                $this->responsePayload = $response;
                $this->paymentStatusCode = $response['header']['status']['code'];
                $this->saveTransaction();
            } catch (\Throwable $th) {
                \Log::debug('DuitNow.Messages.AuthorizationConfirmation', [
                    'message' => $th->getMessage(),
                ]);

                throw new \Exception($th->getMessage(), 400);
            }
        }
    }
}
