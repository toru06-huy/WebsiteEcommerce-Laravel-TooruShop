<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $primaryKey = 'imageID';

    protected $fillable = ['productID', 'imageURL'];

    public function product() { return $this->belongsTo(Product::class, 'productID', 'productID'); }
}