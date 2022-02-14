<?php

namespace ZarulIzham\DuitNowPayment;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ZarulIzham\DuitNowPayment\Commands\DuitNowBankList;
use ZarulIzham\DuitNowPayment\Commands\DuitNowTransactionStatus;

class DuitNowPaymentServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-duitnow')
            ->hasConfigFile('duitnow')
            ->hasViews()
            ->hasRoutes('web', 'api')
            ->hasMigrations('create_duitnow_banks_table', 'create_duitnow_bank_urls_table', 'create_duitnow_transactions_table')
            ->hasCommands(DuitNowBankList::class, DuitNowTransactionStatus::class);

        $this->publishes([
            $this->package->basePath('/../stubs/Controller.php') => app_path("Http/Controllers/DuitNow/Controller.php"),
        ], "{$this->package->shortName()}-controller");

        $this->loadViewsFrom(base_path("resources/views/vendor/{$this->package->shortName()}"), "{$this->package->shortName()}");
    }
}
