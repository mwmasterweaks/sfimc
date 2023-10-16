<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wirecode_gen extends Model
{
    protected $table = "wirecode_gen";

    protected $fillable = [
        'wirecode_id', 'wirecode_active_id', 'date_gen', 'center_id', 'ceated_by',
        'code_count', 'code_used'
    ];


    public function wirecode_list()
    {
        return $this->hasMany(wirecode_list::class, 'wirecode_gen_id', 'id');
    }
    public function wirecode()
    {
        return $this->hasOne(wirecode::class, 'id', 'wirecode_id');
    }
    public function center()
    {
        return $this->hasOne(Center::class, 'CenterID', 'center_id');
    }
}
