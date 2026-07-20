<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $primaryKey = 'variantID';
    public $timestamps = false;
    protected $table = 'product_variants';
    protected $fillable = ['productID', 'sizeID', 'colorID', 'stockQuantity'];

    public function product() { return $this->belongsTo(Product::class, 'productID', 'productID'); }
    public function size()    { return $this->belongsTo(Size::class, 'sizeID', 'sizeID'); }
    public function color()   { return $this->belongsTo(Color::class, 'colorID', 'colorID'); }
    public function orderDetails() { return $this->hasMany(OrderDetail::class, 'variantID', 'variantID'); }
}
