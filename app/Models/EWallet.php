<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use Session;
use Hash;
use View;
use Input;
use Image;
use DB;

class EWallet extends Model
{

  public function getMemberEwalletLedger($param)
  {

    $EntryID = $param['EntryID'];
    $MemberID = $param['MemberID'];
    $Limit = $param['Limit'];
    $PageNo = $param['PageNo'];

    $query = DB::table('ewalletledger as eledger')
      ->join('memberentry as mbrentry', 'mbrentry.MemberID', '=', 'eledger.MemberID')
      ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
      ->leftjoin('memberentry as embrentry', 'embrentry.MemberID', '=', 'eledger.EarnedFromMemberID')
      ->leftjoin('member as embr', 'embr.MemberID', '=', 'embrentry.MemberID')
      ->leftjoin('member as mmbr', 'mmbr.MemberID', '=', 'eledger.MissedByEntryID')
      ->selectraw("
        eledger.LedgerID,
        eledger.ComplanID,
        eledger.MemberID,
        mbrentry.EntryCode as EarnedByEntryCode,
        CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,''),' ',if(COALESCE(mbr.MiddleName,'') != '', CONCAT(LEFT(COALESCE(mbr.MiddleName,''),1),'.'),'')) as EarnedBy,
        eledger.EarnedFromMemberID,
        embrentry.EntryCode as EarnedFromEntryCode,
        CONCAT(COALESCE(embr.FirstName,''),' ',COALESCE(embr.LastName,''),' ',if(COALESCE(embr.MiddleName,'') != '', CONCAT(LEFT(COALESCE(embr.MiddleName,''),1),'.'),'')) as EarnedFrom,
        eledger.MissedByEntryID,
        mmbr.MemberNo as MissedByMemberNo,
        CONCAT(COALESCE(mmbr.FirstName,''),' ',COALESCE(mmbr.LastName,''),' ',if(COALESCE(mmbr.MiddleName,'') != '', CONCAT(LEFT(COALESCE(mmbr.MiddleName,''),1),'.'),'')) as MissedBy,
        eledger.LevelNo,
        eledger.DateTimeEarned,
        eledger.EarnedMonth,
        eledger.EarnedYear,
        eledger.INAmount,
        eledger.OUTAmount,
        eledger.OldBalance,
        eledger.RunningBalance,
        eledger.Remarks,
        eledger.Status,
        eledger.TransactionRefID,
        eledger.DateTimeCreated,
        eledger.DateTimeUpdated
      ")
      ->where("eledger.MemberID", $MemberID);

    if ($Limit > 0) {
      $query->limit($Limit);
      $query->offset(($PageNo - 1) * $Limit);
    }

    $query->orderby('eledger.LedgerID', 'DESC');
    $query->orderby('eledger.DateTimeEarned', 'DESC');

    $list = $query->get();

    return $list;
  }

  public function getMemberEwalletList($param)
  {

    $DateFrom = $param['DateFrom'];
    $DateTo = $param['DateTo'];
    $Limit = $param['Limit'];
    $PageNo = $param['PageNo'];

    $query = DB::table('memberentry as mbrentry')
      ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
      ->selectraw("
            COALESCE(mbrentry.EntryID,0) as EntryID,
            COALESCE(mbrentry.EntryCode,'') as EntryCode,
            mbrentry.EntryDateTime,

            COALESCE(mbrentry.MemberID,0) as MemberID,
            COALESCE(mbr.MemberNo,'') as MemberNo,
            CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
            COALESCE(mbr.FirstName,'') as FirstName,
            COALESCE(mbr.LastName,'') as LastName,
            COALESCE(mbr.MiddleName,'') as MiddleName,
            COALESCE(mbr.EmailAddress,'') as EmailAddress,
            COALESCE(mbr.TelNo,'') as TelNo,
            COALESCE(mbr.MobileNo,'') as MobileNo,

            COALESCE((SELECT 
                SUM(COALESCE(INAmount,0)) as TotalINAmount
              FROM ewalletledger
              WHERE MemberID = mbrentry.MemberID
              AND DateTimeEarned BETWEEN '" . $DateFrom . " 00:00:00' AND '" . $DateTo . " 23:59:59'
              )
            ,0) as TotalCommission,

            COALESCE(mbr.Status,'') as Status

      ");

    if ($Limit > 0) {
      $query->limit($Limit);
      $query->offset(($PageNo - 1) * $Limit);
    }

    $query->orderbyraw("(COALESCE((SELECT 
                SUM(COALESCE(INAmount,0)) as TotalCommission
              FROM ewalletledger
              WHERE MemberID = mbrentry.MemberID
              AND DateTimeEarned BETWEEN '" . $DateFrom . " 00:00:00' AND '" . $DateTo . " 23:59:59'
              )
            ,0)) DESC");

    $list = $query->get();

    return $list;
  }

  public function getMemberEWalletBalance($MemberID)
  {

    $info = DB::table('ewalletledger')
      ->selectraw("
            COALESCE(RunningBalance,0) as RunningBalance
        ")
      ->where('MemberID', $MemberID)
      ->orderby('LedgerID', 'DESC')
      ->first();

    $EWalletBalance = 0;
    if (isset($info)) {
      $EWalletBalance = $info->RunningBalance;
    }

    return $EWalletBalance;
  }
}
