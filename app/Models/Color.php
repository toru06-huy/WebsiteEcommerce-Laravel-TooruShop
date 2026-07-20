<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
        protected $primaryKey = 'colorID';
    public $timestamps = false;
    protected $fillable = ['colorCode', 'colorName'];

    public function variant() { return $this->hasMany(ProductVariant::class, 'colorID', 'colorID'); }

}
