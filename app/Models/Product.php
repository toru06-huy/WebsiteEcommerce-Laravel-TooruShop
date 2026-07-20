<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'productID';
    public $timestamps = true;

    protected $fillable = [
        'productName',
        'categoryID',
        'manufacturerID',
        'basePrice',
        'description',
        'imageID',
    ];

    protected $casts = [
        'basePrice' => 'decimal:2',
    ];

    public function coverImage()   { return $this->belongsTo(ProductImage::class, 'imageID', 'imageID'); }

    public function images()       { return $this->hasMany(ProductImage::class, 'productID', 'productID'); }

    public function category()     { return $this->belongsTo(Category::class,     'categoryID', 'categoryID'); }
    public function manufacturer() { return $this->belongsTo(Manufacturer::class, 'manufacturerID', 'manufacturerID'); }
    public function variants()     { return $this->hasMany(ProductVariant::class,  'productID', 'productID'); }

    public function productDiscounts() { return $this->hasMany(ProductDiscount::class, 'productID', 'productID'); }
    
    public function wishlists()        { return $this->hasMany(Wishlist::class, 'productID', 'productID'); }

    public function activeDiscount()
    {
        $now = now();

        return $this->productDiscounts()
            ->where('isActive', true)
            ->where('startDate', '<=', $now)
            ->where('endDate', '>=', $now)
            ->get()
            ->sortByDesc(fn($d) => $d->discountValue)
            ->first();
    }

    /**
     * Giá sau khi áp dụng giảm giá sản phẩm (tính động, không ghi vào DB).
     */
    public function getDiscountedPriceAttribute(): float
    {
        $discount = $this->activeDiscount();

        if (!$discount) {
            return (float) $this->basePrice;
        }

        return $discount->calcDiscountedPrice((float) $this->basePrice);
    }

    public function getIsOnSaleAttribute(): bool
    {
        return $this->discounted_price < (float) $this->basePrice;
    }
}