<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=['name','slug','image','description','category_id','brand_id','price','is_featured','on_sale','in_stock','is_active'];
    protected $casts=[
        'image'=>'array'
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function orderItems(){
       return $this->hasMany(OrderItem::class);
    }

}
