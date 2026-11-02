<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recei_Add extends Model
{
    use HasFactory;

    protected $table = 'recei__adds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'receiptname',
        'service_charge',
        'documentCharges',
        'stampduty',
        'validPeriod',
        'period1',
        'rate1',
        'period2',
        'rate2',
        'period3',
        'rate3',
        'pawn_amount',
        'Postage_charge',
        's_charge_less',
        's_charge_greater',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'service_charge' => 'decimal:2',
        'documentCharges' => 'decimal:2',
        'stampduty' => 'decimal:2',
        'validPeriod' => 'integer',
        'period1' => 'integer',
        'rate1' => 'decimal:2',
        'period2' => 'integer',
        'rate2' => 'decimal:2',
        'period3' => 'integer',
        'rate3' => 'decimal:2',
        'pawn_amount' => 'decimal:2',
        'Postage_charge' => 'decimal:2',
        's_charge_less' => 'decimal:2',
        's_charge_greater' => 'decimal:2',
    ];

    /**
     * Get the formatted service charge.
     */
    public function getFormattedServiceChargeAttribute()
    {
        return number_format($this->service_charge, 2);
    }

    /**
     * Get the formatted postage charge.
     */
    public function getFormattedPostageChargeAttribute()
    {
        return number_format($this->Postage_charge, 2);
    }

    /**
     * Get the formatted less than 25000 service charge.
     */
    public function getFormattedSChargeLessAttribute()
    {
        return number_format($this->s_charge_less, 2);
    }

    /**
     * Scope a query to only include active receipts.
     */
    public function scopeActive($query)
    {
        return $query->where('validPeriod', '>', 0);
    }

    /**
     * Scope a query to search receipts by name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('receiptname', 'like', "%{$search}%");
    }
}