<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
     protected $primaryKey = 'orderDetailID';
    public $timestamps = false;
    protected $table = 'order_details';
    protected $fillable = ['orderID', 'variantID', 'quantity', 'unitPrice'];

    public function order()   { return $this->belongsTo(Order::class, 'orderID', 'orderID'); }
    public function variant() { return $this->belongsTo(ProductVariant::class, 'variantID', 'variantID'); }
}
