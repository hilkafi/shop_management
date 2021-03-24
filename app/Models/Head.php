<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Head extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function subheads()
    {
        return $this->hasMany(SubHead::class, 'head_id');
    }

    public function from_purchases() {
        return $this->hasMany(Purchase::class, 'from_head_id');
    }

    public function to_purchases() {
        return $this->hasMany(Purchase::class, 'to_head_id');
    }

    public function from_sales() {
        return $this->hasMany(Sale::class, 'from_head_id');
    }

    public function to_sales() {
        return $this->hasMany(Sale::class, 'to_head_id');
    }

    public function from_transactions() {
        return $this->hasMany(Transaction::class, 'from_head_id');
    }

    public function to_transactions() {
        return $this->hasMany(Transaction::class, 'to_head_id');
    }
}
