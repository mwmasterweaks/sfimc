<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member_activate_wire extends Model
{

    protected $table = 'member_activate_wire';

    protected $fillable = [
        'memberID', 'orderID', 'wirecode_active_id'
    ];

    public function wirecode_active()
    {
        return $this->hasOne(wirecode_active::class, 'id', 'wirecode_active_id');
    }
    public function member()
    {
        return $this->hasOne(Member::class, 'MemberID', 'memberID');
    }
    public function order()
    {
        return $this->hasOne(Order::class, 'OrderID', 'orderID');
    }
}
