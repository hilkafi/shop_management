<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'head_id',
        'name',
        'description',
    ];

    public function head()
    {
        return $this->belongsTo(Head::class, 'head_id');
    }

    public function from_purchases() {
        return $this->hasMany(Purchase::class, 'from_subhead_id');
    }

    public function to_purchases() {
        return $this->hasMany(Purchase::class, 'to_subhead_id');
    }

    public function from_sales() {
        return $this->hasMany(Sale::class, 'from_subhead_id');
    }

    public function to_sales() {
        return $this->hasMany(Sale::class, 'to_subhead_id');
    }

    public function from_transactions() {
        return $this->hasMany(Transaction::class, 'from_subhead_id');
    }

    public function to_transactions() {
        return $this->hasMany(Transaction::class, 'to_subhead_id');
    }
}
