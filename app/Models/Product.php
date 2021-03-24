<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $appends = ['stocks'];

    protected $fillable = [
        'category_id',
        'name',
        'unit',
        'purchase_price',
        'sale_price',
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function purchases() {
        return $this->hasMany(Purchase::class, 'product_id');
    }

    public function sales() {
        return $this->hasMany(Sale::class, 'product_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'product_id');
    }

    public function stocks() {
        return $this->hasMany(Stock::class, 'product_id');
    }

    public function getStocksAttribute(){
        $purchase_quantity = Stock::where([['product_id', $this->id], ['stock_type', 'purchase']])->sum('quantity');
        $sale_quantity = Stock::where([['product_id', $this->id], ['stock_type', 'sale']])->sum('quantity');
        return $purchase_quantity - $sale_quantity;
    }
}
