<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuotationOffer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'quotation_id',
        'version',
        'offered_by',
        'currency',
        'total_amount',
        'trade_terms',
        'payment_terms',
        'notes',
        'valid_until',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'valid_until' => 'date',
        ];
    }

    /**
     * Get the quotation that this offer belongs to.
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Get the line items in this quotation offer.
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuotationOfferItem::class);
    }

    /**
     * Get the user (admin/exporter) who created this offer.
     */
    public function offeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'offered_by');
    }

    /**
     * Get the order generated from this offer if accepted.
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'quotation_offer_id');
    }

    /**
     * Check if the offer validity has expired.
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        if ($this->valid_until && $this->valid_until->endOfDay()->isPast()) {
            return true;
        }

        return false;
    }

    /**
     * Check if the offer is active and valid.
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !in_array($this->status, ['rejected', 'cancelled', 'superseded']);
    }

    /**
     * Check if the offer can currently be accepted.
     */
    public function canBeAccepted(): bool
    {
        return $this->isValid() && $this->status !== 'accepted';
    }
}
