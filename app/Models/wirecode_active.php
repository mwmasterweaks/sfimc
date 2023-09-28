<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wirecode_active extends Model
{

    protected $table = 'wirecode_active';
    protected $fillable = [
        'wirecode_id', 'start_date', 'end_date'
    ];

    public function wirecode()
    {
        return $this->hasOne(wirecode::class, 'id', 'wirecode_id');
    }
}
