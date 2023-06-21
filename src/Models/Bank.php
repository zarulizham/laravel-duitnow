<?php

namespace ZarulIzham\DuitNowPayment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;
    
    protected $table = 'duitnow_banks';

    protected $fillable = [
        'code',
        'name',
        'status',
    ];

    /**
     * Get all of the urls for the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function urls(): HasMany
    {
        return $this->hasMany(BankUrl::class, 'duitnow_bank_id', 'id');
    }

    public function scopeOnline($query)
    {
        return $query->whereStatus('Online');
    }

    public function getCombinedUrlsAttribute()
    {
        $data = "";
        foreach ($this->urls as $url) {
            $data .= "[" . $url->type_text . "] " . $url->url . "\n";
        }

        return $data;
    }
}
