<?php

namespace ZarulIzham\DuitNowPayment\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ZarulIzham\DuitNowPayment\Models\BankUrl;

class BankUrlController extends Controller
{
    public function index(Request $request)
    {
        $bankUrls = BankUrl::whereType($request->type)->with('bank')
            ->whereHas('bank', function ($query) use ($request) {
                return $query->where('name', 'LIKE', "%$request->name%");
            })
            ->get();

        return response()->json([
            'bank_urls' => $bankUrls,
        ], 200);
    }
}
