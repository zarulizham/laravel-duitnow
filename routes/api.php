<?php

use Illuminate\Support\Facades\Route;
use ZarulIzham\DuitNowPayment\Http\Controllers\Api\BankUrlController;

Route::prefix('api')->group(function () {
    Route::get('duitnow/bank-urls', [BankUrlController::class, 'index'])->name('api.duitnow.bank-urls.index');
});
