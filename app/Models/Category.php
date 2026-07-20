<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     protected $primaryKey = 'categoryID';
    public $timestamps = false;
    protected $fillable = ['categoryName', 'description', 'parentID'];

    public function parent()   { return $this->belongsTo(Category::class, 'parentID', 'categoryID'); }
    public function children() { return $this->hasMany(Category::class, 'parentID', 'categoryID'); }
    public function products() { return $this->hasMany(Product::class, 'categoryID', 'categoryID'); }
}
