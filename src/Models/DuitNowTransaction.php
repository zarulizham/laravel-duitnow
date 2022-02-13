<?php

namespace ZarulIzham\DuitNowPayment\Models;

use Illuminate\Database\Eloquent\Model;

class DuitNowTransaction extends Model
{
    protected $table = 'duitnow_transactions';

    protected $fillable = [
        'transaction_id',
        'reference_id',
        'request_payload',
        'response_payload',
        'end_to_end_id',
        'payment_status_code',
        'payment_substate',
    ];

    protected $casts = [
        'request_payload' => 'object',
        'response_payload' => 'object',
    ];
}
