<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';

    protected $fillable = [
        'MemberNo', 'FirstName', 'LastName', 'MiddleName', 'TelNo',
        'MobileNo', 'EmailAddress', 'CityID', 'StateProvince', 'ZipCode', 'CountryID', 'Status',
        'CreatedByID', 'UpdatedByID', 'DateTimeCreated', 'DateTimeUpdated'
    ];

    public function member_entry()
    {
        return $this->hasOne(MemberEntry::class, 'MemberID', 'MemberID');
    }

    public function memberentrycutoff()
    {
        return $this->hasOne(MemberEntryCutoff::class, 'MemberEntryID', 'MemberID');
    }
}
