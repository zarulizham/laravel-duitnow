<?php

namespace App\Http\Controllers\DuitNow;

use ZarulIzham\DuitNowPayment\Http\Requests\AuthorizationConfirmation as ConfirmationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * @param ZarulIzham\DuitNowPayment\Http\Requests\AuthorizationConfirmation $request
     * @return Response
     */
    public function callback(ConfirmationRequest $request)
    {
        $response = $request->handle();
        // Update your order status

        return response()->make('OK', 200);
    }

    /**
     * @param ZarulIzham\DuitNowPayment\Http\Requests\AuthorizationConfirmation $request
     * @return string
     */
    public function webhook(ConfirmationRequest $request)
    {
        $response = $request->handle();
        // Update your order status

        return 'OK';
    }

    /**
     * @param Request $request
     * @return string
     */
    public function initiatePayment(Request $request)
    {
        return view('laravel-duitnow::payment');
    }
}
