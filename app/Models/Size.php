<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $primaryKey = 'sizeID';
    public $timestamps = false;
    protected $fillable = ['sizeCode', 'sizeName'];

    public function variant() { return $this->hasMany(ProductVariant::class, 'sizeID', 'sizeID'); }
    
}
