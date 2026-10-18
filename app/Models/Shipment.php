<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Shipment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'shipment_number',
        'origin',
        'destination',
        'carrier',
        'tracking_reference',
        'estimated_arrival',
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
            'estimated_arrival' => 'date',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Shipment $shipment) {
            if (empty($shipment->shipment_number)) {
                do {
                    $candidate = 'SHP-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
                } while (static::where('shipment_number', $candidate)->exists());
                $shipment->shipment_number = $candidate;
            }

            if (empty($shipment->status)) {
                $shipment->status = 'preparing';
            }
        });
    }

    /**
     * Get the order that this shipment belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get all tracking updates for this shipment.
     */
    public function updates(): HasMany
    {
        return $this->hasMany(ShipmentUpdate::class)->orderBy('event_date', 'desc');
    }

    /**
     * Get the latest update for this shipment.
     */
    public function latestUpdate(): HasOne
    {
        return $this->hasOne(ShipmentUpdate::class)->latestOfMany();
    }
}
