<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockRequestItem extends Model
{
    protected $table = 'restock_request_items';
    protected $primaryKey = 'itemID';

    protected $fillable = [
        'restockRequestID',
        'variantID',
        'quantity',
    ];

    public function restockRequest()
    {
        return $this->belongsTo(RestockRequest::class, 'restockRequestID', 'restockRequestID');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variantID', 'variantID');
    }
}
