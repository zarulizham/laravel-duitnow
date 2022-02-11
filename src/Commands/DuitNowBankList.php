<?php

namespace ZarulIzham\DuitNowPayment\Commands;

use Illuminate\Console\Command;
use ZarulIzham\DuitNowPayment\DuitNowPayment;

class DuitNowBankList extends Command
{
    public $signature = 'duitnow:bank-list';

    public $description = 'Get bank list of duitnow';

    public function handle(): int
    {
        $duitNowPayment = new DuitNowPayment();
        $banks = $duitNowPayment->bankList();

        if ($banks->count() > 0) {
            $this->table(['id', 'code', 'status', 'name', 'URLs'], $banks->map->only(['id', 'code', 'status', 'name', 'combined_urls']));
        }

        return self::SUCCESS;
    }
}
