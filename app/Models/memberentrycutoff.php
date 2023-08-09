<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class memberentrycutoff extends Model
{
    protected $table = 'memberentrycutoff';

    protected $fillable = [
        'MemberEntryID', 'AcquiredByEntryID', 'StartDate', 'EndDate', 'TotalPurchases',
        'TotalRebatableValue', 'MaintainingBalance', 'TotalAquiredRebatableValue',
        'Remarks', 'IsRebatesGenerated', 'DateTimeCreated', 'DateTimeUpdated'
    ];

    public function member_entry()
    {
        return $this->hasOne(MemberEntry::class, 'id', 'MemberEntryID');
    }

    public function acquired_by()
    {
        return $this->hasOne(MemberEntry::class, 'id', 'AcquiredByEntryID');
    }
}
