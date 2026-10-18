<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Quotation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'quotation_number',
        'user_id',
        'destination_country',
        'destination_port',
        'notes',
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
            'status' => 'string',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Quotation $quotation) {
            if (empty($quotation->quotation_number)) {
                do {
                    $candidate = 'QT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
                } while (static::where('quotation_number', $candidate)->exists());
                $quotation->quotation_number = $candidate;
            }

            if (empty($quotation->status)) {
                $quotation->status = 'pending';
            }
        });
    }

    /**
     * Get the buyer user that owns this quotation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items requested in this quotation.
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    /**
     * Get all offers submitted for this quotation.
     */
    public function offers(): HasMany
    {
        return $this->hasMany(QuotationOffer::class);
    }

    /**
     * Get the latest offer for this quotation.
     */
    public function latestOffer(): HasOne
    {
        return $this->hasOne(QuotationOffer::class)->latestOfMany();
    }

    /**
     * Get the accepted offer for this quotation.
     */
    public function acceptedOffer(): HasOne
    {
        return $this->hasOne(QuotationOffer::class)->where('status', 'accepted');
    }

    /**
     * Get the converted order if this quotation was accepted.
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    /**
     * Determine if the quotation or its active offer is expired.
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        $latest = $this->latestOffer;
        if ($latest && $latest->isExpired()) {
            return true;
        }

        return false;
    }

    /**
     * Determine if this quotation can be accepted by the buyer.
     */
    public function canBeAccepted(): bool
    {
        if (in_array($this->status, ['accepted', 'rejected', 'expired', 'cancelled', 'closed'])) {
            return false;
        }

        $latest = $this->latestOffer;
        return $latest !== null && $latest->canBeAccepted();
    }

    /**
     * Determine if this quotation can be responded to (e.g., admin sending an offer).
     */
    public function canBeResponded(): bool
    {
        return !in_array($this->status, ['accepted', 'rejected', 'expired', 'cancelled', 'closed'])
            && !$this->isExpired();
    }
}
