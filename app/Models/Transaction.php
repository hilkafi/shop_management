<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'purchase_id',
        'sale_id',
        'product_id',
        'quantity',
        'rate',
        'amount',
        'from_head_id',
        'from_subhead_id',
        'to_head_id',
        'to_subhead_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function from_head()
    {
        return $this->belongsTo(Head::class, 'from_head_id');
    }

    
    public function from_subhead()
    {
        return $this->belongsTo(SubHead::class, 'from_subhead_id');
    }

    public function to_head()
    {
        return $this->belongsTo(Head::class, 'to_head_id');
    }

    
    public function to_subhead()
    {
        return $this->belongsTo(SubHead::class, 'to_subhead_id');
    }
    
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
