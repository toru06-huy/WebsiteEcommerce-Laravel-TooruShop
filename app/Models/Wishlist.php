<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $primaryKey = 'wishlistID';

    protected $fillable = ['userID', 'productID'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'productID', 'productID');
    }
}
