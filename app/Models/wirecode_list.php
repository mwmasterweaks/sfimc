<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wirecode_list extends Model
{

    protected $table = "wirecode_list";
    public $timestamps = false;

    protected $fillable = [
        'wirecode_gen_id', 'code', 'status'
    ];

    public function wirecode_gen()
    {
        return $this->hasOne(wirecode_gen::class, 'id', 'wirecode_gen_id');
    }
    public function issued_to()
    {
        return $this->hasOne(Member::class, 'MemberID', 'issued_to');
    }
    public function issued_by()
    {
        return $this->hasOne(UserAccounts::class, 'UserAccountID', 'issued_by');
    }
}
