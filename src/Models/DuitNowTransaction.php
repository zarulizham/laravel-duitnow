<?php

namespace ZarulIzham\DuitNowPayment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DuitNowTransaction extends Model
{
    protected $table = 'duitnow_transactions';

    protected $fillable = [
        'transaction_id',
        'reference_id',
        'request_payload',
        'response_payload',
        'end_to_end_id',
        'sale_cleared_at',
        'payment_status_code',
        'payment_substate',
    ];

    protected $casts = [
        'request_payload' => 'object',
        'response_payload' => 'object',
    ];

    public function reference() : MorphTo
    {
        return $this->morphTo();
    }
}
