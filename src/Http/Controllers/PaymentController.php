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
     */
    public function handle(Request $request)
    {
        $duitNowPayment = new DuitNowPayment();

        $bankInfo = explode('|', $request->bank);
        $bankId = $bankInfo[0];
        $bankType = $bankInfo[1];

        $redirectUrl = $duitNowPayment->initiatePayment(
            amount: $request->amount, 
            customerName: "Zarul Zubir", 
            bankType: $bankType, 
            bankId: $bankId, 
            recipientReference: $request->recipient_reference ?? $request->reference_id, 
            coordinate: $request->coordinate, 
            ipAddress: $request->ip_address,
            paymentDescription: $request->payment_description ?? $request->recipient_reference,
            referenceId: $request->reference_id,
            referenceType: $request->reference_type,
        );

        return redirect($redirectUrl);
    }
}
