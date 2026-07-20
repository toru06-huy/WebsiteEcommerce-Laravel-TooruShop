<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductDiscount extends Model
{
    protected $table = 'product_discounts';
    protected $primaryKey = 'productDiscountID';

    protected $fillable = ['productID', 'discountValue', 'startDate', 'endDate', 'isActive'];

    protected $casts = [
        'discountValue' => 'decimal:2',
        'startDate'     => 'datetime',
        'endDate'       => 'datetime',
        'isActive'      => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productID', 'productID');
    }
    
    public function calcDiscountedPrice(float $basePrice): float
    {
        return max(0, $basePrice - ($basePrice * $this->discountValue / 100));
    }
}
