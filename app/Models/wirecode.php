<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wirecode extends Model
{
    protected $fillable = [
        'code', 'productID', 'description', 'amount_acquired', 'max_level', 'minimum_qty', 'status'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'ProductID', 'productID');
    }
}
