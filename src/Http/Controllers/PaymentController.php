<?php

namespace ZarulIzham\DuitNowPayment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ZarulIzham\DuitNowPayment\DuitNowPayment;

class PaymentController extends Controller
{
    /**
     * Initiate the request authorization message to FPX
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        $duitNowPayment = new DuitNowPayment();

        $bankInfo = explode('|', $request->bank);
        $bankId = $bankInfo[0];
        $bankType = $bankInfo[1];

        $redirectUrl = $duitNowPayment->initiatePayment($request->amount, "Zarul Zubir", $bankType, $bankId, $request->reference_id, $request->coordinate, $request->ip_address);

        return redirect($redirectUrl);
    }
}
