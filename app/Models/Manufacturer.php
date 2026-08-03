<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $primaryKey = 'manufacturerID';
    public $timestamps = false;
    protected $fillable = ['manufacturerCode', 'manufacturerName', 'email', 'country', 'website'];

    public function products() { return $this->hasMany(Product::class, 'manufacturerID', 'manufacturerID'); }
     public function restockRequests() { return $this->hasMany(RestockRequest::class, 'manufacturerID', 'manufacturerID'); }
}
