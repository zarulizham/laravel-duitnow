<?php

namespace ZarulIzham\DuitNowPayment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankUrl extends Model
{
    protected $table = 'duitnow_bank_urls';

    protected $fillable = [
        'type',
        'url',
    ];

    protected $appends = [
        'type_text',
    ];

    /**
     * Get the bank that owns the BankUrl
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'duitnow_bank_id');
    }

    public function getTypeTextAttribute()
    {
        return match ($this->type) {
            'RET' => 'Retail',
            'COR' => 'Corporate',
            default => $this->type,
        };
    }
}
