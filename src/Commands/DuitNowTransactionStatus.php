<?php

namespace ZarulIzham\DuitNowPayment\Commands;

use Illuminate\Console\Command;
use ZarulIzham\DuitNowPayment\DuitNowPayment;
use ZarulIzham\DuitNowPayment\Models\DuitNowTransaction;

class DuitNowTransactionStatus extends Command
{
    public $signature = 'duitnow:transaction:status {endToEndId}';

    public $description = 'Get transaction status of duitnow';

    public function handle(): int
    {
        $endToEndId = $this->argument('endToEndId');

        $duitNowPayment = new DuitNowPayment();
        $status = $duitNowPayment->statusInquiry($endToEndId);

        if (!isset($status['errorCode'])) {
            $this->updateTransaction($endToEndId, $status);
        } else {
            $this->warn($status['description']);
        }

        return self::SUCCESS;
    }

    protected function updateTransaction($endToEndId, $status)
    {
        $transaction = DuitNowTransaction::where('end_to_end_id', $endToEndId)->first();

        $transaction->update([
            'response_payload' => $status,
            'payment_substate' => $status['transactionStatus'],
        ]);

        $columns = [
            'reference_id', 'transaction_id', 'end_to_end_id', 'payment_status_code', 'payment_substate', 'created_at', 'updated_at',
        ];

        $this->table($columns, [
            $transaction->only($columns),
        ]);
    }
}
