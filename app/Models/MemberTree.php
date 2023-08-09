<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberTree extends Model
{
    protected $table = 'member_tree';
    public $timestamps = false;
    protected $fillable = [
        'ancestor_id', 'descendant_id', 'depth'
    ];
}
