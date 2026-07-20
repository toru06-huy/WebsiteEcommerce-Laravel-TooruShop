<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $primaryKey = 'cartID';
    protected $table      = 'cart';
    protected $fillable   = ['userID', 'variantID', 'quantity', 'abandoned_notified'];

    public function variant() { return $this->belongsTo(ProductVariant::class, 'variantID', 'variantID'); }
    public function user()    { return $this->belongsTo(User::class, 'userID', 'userID'); }
}