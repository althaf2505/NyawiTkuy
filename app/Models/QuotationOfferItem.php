<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationOfferItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'quotation_offer_id',
        'product_id',
        'product_name',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:4',
            'total_price' => 'decimal:2',
        ];
    }

    /**
     * Get the offer that this item belongs to.
     */
    public function offer(): BelongsTo
    {
        return $this->belongsTo(QuotationOffer::class, 'quotation_offer_id');
    }

    /**
     * Alias relationship to get the quotation offer.
     */
    public function quotationOffer(): BelongsTo
    {
        return $this->belongsTo(QuotationOffer::class, 'quotation_offer_id');
    }

    /**
     * Get the product associated with this item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
