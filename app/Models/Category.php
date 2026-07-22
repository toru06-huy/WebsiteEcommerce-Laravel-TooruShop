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

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }
 
    /**
     * Đi ngược lên chuỗi cha để lấy danh mục gốc (root).
     */
    public function rootCategory(): self
    {
        $node = $this;
        while ($node->parentRecursive) {
            $node = $node->parentRecursive;
        }
        return $node;
    }
 
    /**
     * Tên hiển thị: "Áo thun (Nam)" nếu có danh mục gốc khác chính nó,
     * ngược lại chỉ hiển thị tên danh mục.
     */
    public function getDisplayNameAttribute(): string
    {
        $root = $this->rootCategory();
 
        if ($root->categoryID === $this->categoryID) {
            return $this->categoryName;
        }
 
        return "{$this->categoryName} ({$root->categoryName})";
    }
}
