<?php

namespace ZarulIzham\DuitNowPayment\Exceptions;

use Exception;
use Illuminate\Http\Request;

class AuthenticationException extends Exception
{
    public function __construct()
    {
        parent::__construct('Authentication failed. Please try again later or use different payment options.');
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
            ], 503);
        }

        return response()->view('laravel-duitnow::error', [
            'message' => $this->getMessage(),
        ], 503);
    }
}
