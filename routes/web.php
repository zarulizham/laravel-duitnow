<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\DuitNow\Controller;
use ZarulIzham\DuitNowPayment\Http\Controllers\PaymentController;

Route::middleware(['web'])->group(function () {

    $directPath = Config::get('duitnow.direct_path');
    $callbackPath = Config::get('duitnow.callback_path');

    Route::post('duitnow/payment/auth', [PaymentController::class, 'handle'])->name('duitnow.payment.auth.request');

    Route::match(['get', 'post'], $directPath, [Controller::class, 'webhook'])->name('duitnow.payment.direct');
    Route::post($callbackPath, [Controller::class, 'callback'])->name('duitnow.payment.callback');

    Route::match(
        ['get', 'post'],
        'duitnow/initiate/payment',
        [Controller::class, 'initiatePayment']
    )->name('duitnow.initiate.payment');
});
