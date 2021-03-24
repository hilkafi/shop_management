<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'purchase_id',
        'sale_id',
        'product_id',
        'quantity',
        'stock_type'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function stock($id){
        $purchase_quantity = $this->where([['product_id', $id], ['stock_type', 'purchase']])->sum('quantity');
        $sale_quantity = $this->where([['product_id', $id], ['stock_type', 'sale']])->sum('quantity');
        return $purchase_quantity - $sale_quantity;
    }
}
