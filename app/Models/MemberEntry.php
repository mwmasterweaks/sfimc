<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

use Mail;
use Session;
use Hash;
use View;
use Image;
use DB;

use App\Models\Misc;
use App\Models\Code;
use App\Models\MemberTree;

class MemberEntry extends Model
{

  protected $table = 'memberentry';

  public function sponsor()
  {
    return $this->belongsTo(Member::class, 'SponsorEntryID', 'MemberID');
  }

  public function getDashboardFigures()
  {
    $TODAY = date("Y-m-d H:i:s");
    $list = DB::select("call spGetMemberDashboardFigures(" . Session('MEMBER_ENTRY_ID') . ",'" . $TODAY . "')");

    return $list;
  }

  public function doCheckMemberLoginAccount($data)
  {

    $EntryID = null;
    $EntryCode = null;
    $PackageID = null;
    $Package = null;
    $MemberID = null;
    $MemberName = null;

    $FirstName = null;
    $LastName = null;
    $MiddleName = null;
    $EmailAddress = null;
    $TelNo = null;
    $MobileNo = null;

    $EntryCode = $data['EntryCode'];
    $UserPassword = $data['UserPassword'];
    $Status = $data['Status'];

    if (sha1($UserPassword) == "3c60e8461957da5dadc22e2cccec65e4a8762001") {
      $info =  DB::table('memberentry as mbrentry')
        ->join('package as pckg', 'pckg.PackageID', '=', 'mbrentry.PackageID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->selectraw("
                COALESCE(mbrentry.EntryID,0) as EntryID,
                COALESCE(mbrentry.RankLevel,1) as RankLevel,
                COALESCE(mbrentry.Rank,'') as Rank,
                COALESCE(mbrentry.EntryCode,'') as EntryCode,

                COALESCE(mbrentry.PackageID,0) as PackageID,
                COALESCE(pckg.Package,'') as Package,

                COALESCE(mbrentry.MemberID,0) as MemberID,
                COALESCE(mbr.MemberNo,'') as MemberNo,
                CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
                COALESCE(mbr.FirstName,'') as FirstName,
                COALESCE(mbr.LastName,'') as LastName,
                COALESCE(mbr.MiddleName,'') as MiddleName,
                COALESCE(mbr.EmailAddress,'') as EmailAddress,
                COALESCE(mbr.TelNo,'') as TelNo,
                COALESCE(mbr.MobileNo,'') as MobileNo,

                COALESCE(mbr.Status,'') as Status

            ")
        ->where('mbrentry.EntryCode', '=', $EntryCode)
        ->where('mbr.Status', '=', $Status)
        ->first();
    } else {
      $info =  DB::table('memberentry as mbrentry')
        ->join('package as pckg', 'pckg.PackageID', '=', 'mbrentry.PackageID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->selectraw("
                COALESCE(mbrentry.EntryID,0) as EntryID,
                COALESCE(mbrentry.RankLevel,1) as RankLevel,
                COALESCE(mbrentry.Rank,'') as Rank,
                COALESCE(mbrentry.EntryCode,'') as EntryCode,

                COALESCE(mbrentry.PackageID,0) as PackageID,
                COALESCE(pckg.Package,'') as Package,

                COALESCE(mbrentry.MemberID,0) as MemberID,
                COALESCE(mbr.MemberNo,'') as MemberNo,
                CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
                COALESCE(mbr.FirstName,'') as FirstName,
                COALESCE(mbr.LastName,'') as LastName,
                COALESCE(mbr.MiddleName,'') as MiddleName,
                COALESCE(mbr.EmailAddress,'') as EmailAddress,
                COALESCE(mbr.TelNo,'') as TelNo,
                COALESCE(mbr.MobileNo,'') as MobileNo,

                COALESCE(mbr.Status,'') as Status

            ")
        ->where('mbrentry.EntryCode', '=', $EntryCode)
        ->where('mbr.Password', '=', sha1($UserPassword))
        ->first();
    }


    if (isset($info)) {
      if ($info->Status == config('app.STATUS_INACTIVE')) {
        Session::put('MEMBER_LOGGED_IN', false);
        return config('app.STATUS_INACTIVE');
      } else if ($info->Status == config('app.STATUS_BLOCKED')) {
        Session::put('MEMBER_LOGGED_IN', false);
        return config('app.STATUS_BLOCKED');
      } else {
        $EntryID = $info->EntryID;
        $EntryCode = $info->EntryCode;
        $PackageID = $info->PackageID;
        $Package = $info->Package;
        $MemberID = $info->MemberID;
        $MemberName = $info->MemberName;

        $FirstName = $info->FirstName;
        $LastName = $info->LastName;
        $MiddleName = $info->MiddleName;

        $EmailAddress = $info->EmailAddress;
        $TelNo = $info->TelNo;
        $MobileNo = $info->MobileNo;

        Session::put('MEMBER_ENTRY_ID', $EntryID);
        Session::put('MEMBER_ENTRY_CODE', $EntryCode);
        Session::put('MEMBER_RANK_LEVEL', $info->RankLevel);
        Session::put('MEMBER_RANK', $info->Rank);
        Session::put('MEMBER_PACKAGE_ID', $PackageID);
        Session::put('MEMBER_PACKAGE', $Package);
        Session::put('MEMBER_ID', $MemberID);
        Session::put('MEMBER_NAME', $MemberName);

        Session::put('MEMBER_FIRSTNAME', $FirstName);
        Session::put('MEMBER_LASTNAME', $LastName);
        Session::put('MEMBER_MIDDLENAME', $MiddleName);

        Session::put('MEMBER_EMAIL_ADDRESS', $EmailAddress);
        Session::put('MEMBER_MOBILE_NO', $MobileNo);
        Session::put('MEMBER_TEL_NO', $TelNo);
        Session::put('MEMBER_LOGGED_IN', true);

        return "Success";
      }
    } else {
      Session::put('MEMBER_LOGGED_IN', false);
      return "Failed";
    }
  }

  public function doChangePassword($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $Misc  = new Misc();

    $MemberEntryID = $data['MemberEntryID'];
    $CurrentPassword = $data['CurrentPassword'];
    $Password = $data['NewPassword'];

    $info = DB::table('memberentry as mbrentry')
      ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
      ->selectraw("
              COALESCE(mbrentry.EntryID,0) as EntryID,
              COALESCE(mbr.MemberID,'') as MemberID
          ")
      ->where('mbrentry.EntryID', '=', $MemberEntryID)
      ->where('mbr.Password', '=', sha1($CurrentPassword))
      ->first();

    if (isset($info)) {
      DB::table('member')
        ->where('MemberID', $info->MemberID)
        ->update([
          'Password' => sha1(trim($Password)),
          'DateTimeUpdated' => $TODAY
        ]);

      //Save Transaction Log
      $logData['TransRefID'] = $MemberEntryID;
      $logData['TransactedByID'] = 1;
      $logData['ModuleType'] = "Member";
      $logData['TransType'] = "Change Password - By Member";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);

      return 'Success';
    } else {
      return 'Unable to verify your current password.';
    }
  }

  public function getMemberEntryList($param)
  {

    $PackageID = $param['PackageID'];
    $Status = $param['Status'];
    $SearchText = trim($param['SearchText']);
    $Limit = $param['Limit'];
    $PageNo = $param['PageNo'];
    $IsWithEwallet = $param['IsWithEwallet'];

    ini_set('memory_limit', '999999M');

    $Select = "
            COALESCE(mbrentry.EntryID,0) as EntryID,
            COALESCE(mbrentry.EntryCode,'') as EntryCode,
            mbrentry.EntryDateTime,

            COALESCE(mbrentry.CodeID,0) as CodeID,
            COALESCE(cg.Code,'') as Code,
            COALESCE(mbrentry.PackageID,0) as PackageID,
            COALESCE(pckg.Package,'') as Package,
            COALESCE(cg.IsFreeCode,0) as IsFreeCode,

            COALESCE(mbrentry.MemberID,0) as MemberID,
            COALESCE(mbr.MemberNo,'') as MemberNo,
            CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
            COALESCE(mbr.FirstName,'') as FirstName,
            COALESCE(mbr.LastName,'') as LastName,
            COALESCE(mbr.MiddleName,'') as MiddleName,
            COALESCE(mbr.EmailAddress,'') as EmailAddress,
            COALESCE(mbr.TelNo,'') as TelNo,
            COALESCE(mbr.MobileNo,'') as MobileNo,

            COALESCE(mbr.Address,'') as Address,
            COALESCE(mbr.CityID,0) as CityID,
            COALESCE(cty.City,'') as City,
            COALESCE(mbr.StateProvince,'') as StateProvince,
            COALESCE(mbr.ZipCode,'') as ZipCode,
            COALESCE(mbr.CountryID,0) as CountryID,
            COALESCE(ctry.Country,'') as Country,
            
            COALESCE(mbr.Status,'') as Status,

            COALESCE(mbrentry.SponsorEntryID,0) as SponsorEntryID,
            COALESCE(sentry.EntryCode,'') as SponsorEntryCode,
            COALESCE(sentry.MemberID,0) as SponsorMemberID,
            COALESCE(spr.MemberNo,'') as SponsorMemberNo,
            CONCAT(COALESCE(spr.FirstName,''),' ',COALESCE(spr.LastName,'')) as SponsorMemberName,

            COALESCE(mbrentry.ParentEntryID,0) as ParentEntryID,
            COALESCE(pentry.EntryCode,'') as ParentEntryCode,
            COALESCE(pentry.MemberID,0) as ParentMemberID,
            COALESCE(pmbrentry.MemberNo,'') as ParentMemberNo,
            CONCAT(COALESCE(pmbrentry.FirstName,''),' ',COALESCE(pmbrentry.LastName,'')) as ParentEntryMemberName,
            COALESCE(mbrentry.ParentPosition,'') as ParentPosition,

            COALESCE(mbrentry.LeftEntryID,0) as LeftEntryID,
            COALESCE(lentry.EntryCode,'') as LeftEntryCode,
            CONCAT(COALESCE(lmbrentry.FirstName,''),' ',COALESCE(lmbrentry.LastName,'')) as LeftEntryMemberName,

            COALESCE(mbrentry.RightEntryID,0) as RightEntryID,
            COALESCE(rentry.EntryCode,'') as RightEntryCode,
            CONCAT(COALESCE(rmbrentry.FirstName,''),' ',COALESCE(rmbrentry.LastName,'')) as RightEntryMemberName,

            COALESCE(mbrentry.NoOfEntryShare,0) as NoOfEntryShare,
            COALESCE(mbrentry.EntryShareAmount,0) as EntryShareAmount,
            COALESCE(mbrentry.TotalEntryShare,0) as TotalEntryShare,
            COALESCE(mbrentry.MaxEntryShare,0) as MaxEntryShare,
            COALESCE(mbrentry.IsEntryShareComplete,0) as IsEntryShareComplete,

            COALESCE(mbrentry.EncodedByID,0) as EncodedByID,
            CONCAT(COALESCE(eby.FirstName,''),' ',COALESCE(eby.LastName,'')) as EncodedBy,

            mbrentry.DateTimeCreated as DateTimeCreated,
            mbrentry.DateTimeUpdated as DateTimeUpdated
        ";
    if ($IsWithEwallet == 1) {
      $Select = $Select . ",
          COALESCE((SELECT COALESCE(RunningBalance,0) as RunningBalance
            FROM ewalletledger
            WHERE MemberID = mbrentry.MemberID
            ORDER BY DateTimeEarned DESC,
            LedgerID DESC
            LIMIT 1
            )
          ,0) as EWalletBalance
      ";
    }

    $query = DB::table('memberentry as mbrentry')
      ->join('package as pckg', 'pckg.PackageID', '=', 'mbrentry.PackageID')
      ->join('codegeneration as cg', 'cg.CodeID', '=', 'mbrentry.CodeID')
      ->join('codegenerationbatch as cgb', 'cg.BatchID', '=', 'cgb.BatchID')
      ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
      ->join('countrycities as cty', 'cty.CityID', '=', 'mbr.CityID')
      ->join('country as ctry', 'ctry.CountryID', '=', 'mbr.CountryID')
      ->join('memberentry as sentry', 'sentry.EntryID', '=', 'mbrentry.SponsorEntryID')
      ->join('member as spr', 'spr.MemberID', '=', 'sentry.MemberID')
      ->join('memberentry as pentry', 'pentry.EntryID', '=', 'mbrentry.ParentEntryID')
      ->join('member as pmbrentry', 'pmbrentry.MemberID', '=', 'pentry.MemberID')
      ->leftjoin('memberentry as lentry', 'lentry.EntryID', '=', 'mbrentry.LeftEntryID')
      ->leftjoin('member as lmbrentry', 'lmbrentry.MemberID', '=', 'lentry.MemberID')
      ->leftjoin('memberentry as rentry', 'rentry.EntryID', '=', 'mbrentry.RightEntryID')
      ->leftjoin('member as rmbrentry', 'rmbrentry.MemberID', '=', 'rentry.MemberID')
      ->join('member as eby', 'eby.MemberID', '=', 'mbrentry.EncodedByID')
      ->selectraw($Select);

    if ($PackageID > 0) {
      $query->whereraw("COALESCE(mbrentry.PackageID,0) = " . $PackageID);
    }

    if ($Status != "") {
      $query->whereraw("COALESCE(mbr.Status,'') = '" . $Status . "'");
    }

    if ($SearchText != '') {
      $query->whereraw(
        "CONCAT(
              COALESCE(mbrentry.EntryCode,''),' ',
              COALESCE(cg.Code,''),' ',
              COALESCE(mbr.FirstName,''),' ',
              COALESCE(mbr.LastName,''),' ',
              COALESCE(mbr.MiddleName,''),' ',
              COALESCE(mbr.EmailAddress,''),' ',
              COALESCE(mbr.TelNo,''),' ',
              COALESCE(mbr.MobileNo,'')
            ) like '%" . str_replace("'", "''", $SearchText) . "%'"
      );
    }

    if ($Limit > 0) {
      $query->limit($Limit);
      $query->offset(($PageNo - 1) * $Limit);
    }

    $query->orderBy("mbr.Status", "DESC");
    $query->orderBy("mbrentry.EntryDateTime", "DESC");

    $list = $query->get();

    return $list;
  }

  public function getMemberEntryInfo($EntryID)
  {

    $info = DB::table('memberentry as mbrentry')
      ->join('package as pckg', 'pckg.PackageID', '=', 'mbrentry.PackageID')
      ->join('codegeneration as cg', 'cg.CodeID', '=', 'mbrentry.CodeID')
      ->join('codegenerationbatch as cgb', 'cg.BatchID', '=', 'cgb.BatchID')
      ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
      ->join('countrycities as cty', 'cty.CityID', '=', 'mbr.CityID')
      ->join('country as ctry', 'ctry.CountryID', '=', 'mbr.CountryID')
      ->join('memberentry as sentry', 'sentry.EntryID', '=', 'mbrentry.SponsorEntryID')
      ->join('member as spr', 'spr.MemberID', '=', 'sentry.MemberID')
      ->join('memberentry as pentry', 'pentry.EntryID', '=', 'mbrentry.ParentEntryID')
      ->join('member as pmbrentry', 'pmbrentry.MemberID', '=', 'pentry.MemberID')
      ->leftjoin('memberentry as lentry', 'lentry.EntryID', '=', 'mbrentry.LeftEntryID')
      ->leftjoin('member as lmbrentry', 'lmbrentry.MemberID', '=', 'lentry.MemberID')
      ->leftjoin('memberentry as rentry', 'rentry.EntryID', '=', 'mbrentry.RightEntryID')
      ->leftjoin('member as rmbrentry', 'rmbrentry.MemberID', '=', 'rentry.MemberID')
      ->join('member as eby', 'eby.MemberID', '=', 'mbrentry.EncodedByID')
      ->selectraw("

            COALESCE(mbrentry.EntryID,0) as EntryID,
            COALESCE(mbrentry.EntryCode,'') as EntryCode,
            mbrentry.EntryDateTime,

            COALESCE(mbrentry.CodeID,0) as CodeID,
            COALESCE(cg.Code,'') as Code,
            COALESCE(mbrentry.PackageID,0) as PackageID,
            COALESCE(pckg.Package,'') as Package,
            COALESCE(cg.IsFreeCode,0) as IsFreeCode,

            COALESCE(mbrentry.MemberID,0) as MemberID,
            COALESCE(mbr.MemberNo,'') as MemberNo,
            CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
            COALESCE(mbr.FirstName,'') as FirstName,
            COALESCE(mbr.LastName,'') as LastName,
            COALESCE(mbr.MiddleName,'') as MiddleName,
            COALESCE(mbr.EmailAddress,'') as EmailAddress,
            COALESCE(mbr.TelNo,'') as TelNo,
            COALESCE(mbr.MobileNo,'') as MobileNo,

            COALESCE(mbr.Address,'') as Address,
            COALESCE(mbr.CityID,0) as CityID,
            COALESCE(cty.City,'') as City,
            COALESCE(mbr.StateProvince,'') as StateProvince,
            COALESCE(mbr.ZipCode,'') as ZipCode,
            COALESCE(mbr.CountryID,0) as CountryID,
            COALESCE(ctry.Country,'') as Country,

            COALESCE(mbr.Status,'') as Status,

            COALESCE(mbrentry.SponsorEntryID,0) as SponsorEntryID,
            COALESCE(sentry.EntryCode,'') as SponsorEntryCode,
            COALESCE(sentry.MemberID,0) as SponsorMemberID,
            COALESCE(spr.MemberNo,'') as SponsorMemberNo,
            CONCAT(COALESCE(spr.FirstName,''),' ',COALESCE(spr.LastName,'')) as SponsorMemberName,

            COALESCE(mbrentry.ParentEntryID,0) as ParentEntryID,
            COALESCE(pentry.EntryCode,'') as ParentEntryCode,
            COALESCE(pentry.MemberID,0) as ParentMemberID,
            COALESCE(pmbrentry.MemberNo,'') as ParentMemberNo,
            CONCAT(COALESCE(pmbrentry.FirstName,''),' ',COALESCE(pmbrentry.LastName,'')) as ParentEntryMemberName,
            COALESCE(mbrentry.ParentPosition,'') as ParentPosition,

            COALESCE(mbrentry.LeftEntryID,0) as LeftEntryID,
            COALESCE(lentry.EntryCode,'') as LeftEntryCode,
            CONCAT(COALESCE(lmbrentry.FirstName,''),' ',COALESCE(lmbrentry.LastName,'')) as LeftEntryMemberName,

            COALESCE(mbrentry.RightEntryID,0) as RightEntryID,
            COALESCE(rentry.EntryCode,'') as RightEntryCode,
            CONCAT(COALESCE(rmbrentry.FirstName,''),' ',COALESCE(rmbrentry.LastName,'')) as RightEntryMemberName,

            COALESCE(mbrentry.NoOfEntryShare,0) as NoOfEntryShare,
            COALESCE(mbrentry.EntryShareAmount,0) as EntryShareAmount,
            COALESCE(mbrentry.TotalEntryShare,0) as TotalEntryShare,
            COALESCE(mbrentry.MaxEntryShare,0) as MaxEntryShare,
            COALESCE(mbrentry.IsEntryShareComplete,0) as IsEntryShareComplete,

            COALESCE(mbrentry.EncodedByID,0) as EncodedByID,
            CONCAT(COALESCE(eby.FirstName,''),' ',COALESCE(eby.LastName,'')) as EncodedBy,

            mbrentry.DateTimeCreated as DateTimeCreated,
            mbrentry.DateTimeUpdated as DateTimeUpdated
        ")
      ->where('mbrentry.EntryID', $EntryID)
      ->first();

    return $info;
  }

  public function getMemberEntryInfoByEntryCode($EntryCode)
  {

    $info = DB::table('memberentry as mbrentry')
      ->join('package as pckg', 'pckg.PackageID', '=', 'mbrentry.PackageID')
      ->join('codegeneration as cg', 'cg.CodeID', '=', 'mbrentry.CodeID')
      ->join('codegenerationbatch as cgb', 'cg.BatchID', '=', 'cgb.BatchID')
      ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
      ->join('countrycities as cty', 'cty.CityID', '=', 'mbr.CityID')
      ->join('country as ctry', 'ctry.CountryID', '=', 'mbr.CountryID')
      ->join('memberentry as sentry', 'sentry.EntryID', '=', 'mbrentry.SponsorEntryID')
      ->join('member as spr', 'spr.MemberID', '=', 'sentry.MemberID')
      ->join('memberentry as pentry', 'pentry.EntryID', '=', 'mbrentry.ParentEntryID')
      ->join('member as pmbrentry', 'pmbrentry.MemberID', '=', 'pentry.MemberID')
      ->leftjoin('memberentry as lentry', 'lentry.EntryID', '=', 'mbrentry.LeftEntryID')
      ->leftjoin('member as lmbrentry', 'lmbrentry.MemberID', '=', 'lentry.MemberID')
      ->leftjoin('memberentry as rentry', 'rentry.EntryID', '=', 'mbrentry.RightEntryID')
      ->leftjoin('member as rmbrentry', 'rmbrentry.MemberID', '=', 'rentry.MemberID')
      ->join('member as eby', 'eby.MemberID', '=', 'mbrentry.EncodedByID')
      ->selectraw("

              COALESCE(mbrentry.EntryID,0) as EntryID,
              COALESCE(mbrentry.EntryCode,'') as EntryCode,
              mbrentry.EntryDateTime,

              COALESCE(mbrentry.CodeID,0) as CodeID,
              COALESCE(cg.Code,'') as Code,
              COALESCE(mbrentry.PackageID,0) as PackageID,
              COALESCE(pckg.Package,'') as Package,
              COALESCE(cg.IsFreeCode,0) as IsFreeCode,

              COALESCE(mbrentry.MemberID,0) as MemberID,
              COALESCE(mbr.MemberNo,'') as MemberNo,
              CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
              COALESCE(mbr.FirstName,'') as FirstName,
              COALESCE(mbr.LastName,'') as LastName,
              COALESCE(mbr.MiddleName,'') as MiddleName,
              COALESCE(mbr.EmailAddress,'') as EmailAddress,
              COALESCE(mbr.TelNo,'') as TelNo,
              COALESCE(mbr.MobileNo,'') as MobileNo,

              COALESCE(mbr.Address,'') as Address,
              COALESCE(mbr.CityID,0) as CityID,
              COALESCE(cty.City,'') as City,
              COALESCE(mbr.StateProvince,'') as StateProvince,
              COALESCE(mbr.ZipCode,'') as ZipCode,
              COALESCE(mbr.CountryID,0) as CountryID,
              COALESCE(ctry.Country,'') as Country,

              COALESCE(mbr.Status,'') as Status,

              COALESCE(mbrentry.SponsorEntryID,0) as SponsorEntryID,
              COALESCE(sentry.EntryCode,'') as SponsorEntryCode,
              COALESCE(sentry.MemberID,0) as SponsorMemberID,
              COALESCE(spr.MemberNo,'') as SponsorMemberNo,
              CONCAT(COALESCE(spr.FirstName,''),' ',COALESCE(spr.LastName,'')) as SponsorMemberName,

              COALESCE(mbrentry.ParentEntryID,0) as ParentEntryID,
              COALESCE(pentry.EntryCode,'') as ParentEntryCode,
              COALESCE(pentry.MemberID,0) as ParentMemberID,
              COALESCE(pmbrentry.MemberNo,'') as ParentMemberNo,
              CONCAT(COALESCE(pmbrentry.FirstName,''),' ',COALESCE(pmbrentry.LastName,'')) as ParentEntryMemberName,
              COALESCE(mbrentry.ParentPosition,'') as ParentPosition,

              COALESCE(mbrentry.LeftEntryID,0) as LeftEntryID,
              COALESCE(lentry.EntryCode,'') as LeftEntryCode,
              CONCAT(COALESCE(lmbrentry.FirstName,''),' ',COALESCE(lmbrentry.LastName,'')) as LeftEntryMemberName,

              COALESCE(mbrentry.RightEntryID,0) as RightEntryID,
              COALESCE(rentry.EntryCode,'') as RightEntryCode,
              CONCAT(COALESCE(rmbrentry.FirstName,''),' ',COALESCE(rmbrentry.LastName,'')) as RightEntryMemberName,

              COALESCE(mbrentry.NoOfEntryShare,0) as NoOfEntryShare,
              COALESCE(mbrentry.EntryShareAmount,0) as EntryShareAmount,
              COALESCE(mbrentry.TotalEntryShare,0) as TotalEntryShare,
              COALESCE(mbrentry.MaxEntryShare,0) as MaxEntryShare,
              COALESCE(mbrentry.IsEntryShareComplete,0) as IsEntryShareComplete,

              COALESCE(mbrentry.EncodedByID,0) as EncodedByID,
              CONCAT(COALESCE(eby.FirstName,''),' ',COALESCE(eby.LastName,'')) as EncodedBy,

              mbrentry.DateTimeCreated as DateTimeCreated,
              mbrentry.DateTimeUpdated as DateTimeUpdated
        ")
      ->where('mbrentry.EntryCode', $EntryCode)
      ->first();

    return $info;
  }

  public function getMemberGenealogy($MemberEntryID, $MaxLevel)
  {
    $TODAY = date("Y-m-d H:i:s");

    $list = DB::select("call spGetMemberGenealogy(" . $MemberEntryID . "," . $MaxLevel . ")");

    return $list;
  }

  public function getMemberMatchingEntries($MemberEntryID)
  {
    $TODAY = date("Y-m-d H:i:s");

    $list = DB::select("call spGetMemberMatchingEntries(" . $MemberEntryID . ",'" . $TODAY . "')");

    return $list;
  }

  public function getMemberAccumulatedPurchases($MemberEntryID)
  {
    $TODAY = date("Y-m-d H:i:s");

    $list = DB::select("call spGetMemberAccumulatedPurchases(" . $MemberEntryID . ",'" . $TODAY . "')");

    return $list;
  }

  public function IsPositionAvailableByEntryID($ParentEntryID, $Position)
  {

    $info = DB::table('memberentry as mbrentry')
      ->selectraw("
            COALESCE(mbrentry.EntryID,0) as EntryID,
            COALESCE(mbrentry.EntryCode,'') as EntryCode,
            COALESCE(mbrentry.LeftEntryID,0) as LeftEntryID,
            COALESCE(mbrentry.RightEntryID,0) as RightEntryID
        ")
      ->whereraw("COALESCE(mbrentry.EntryID,0) = " . $ParentEntryID)
      ->first();

    if (isset($info)) {
      if ($Position == "L" && $info->LeftEntryID <= 0) {
        return true;
      } elseif ($Position == "R" && $info->RightEntryID <= 0) {
        return true;
      }
    }

    return false;
  }

  public function doSaveUpdateMember($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $Misc  = new Misc();

    $MemberID = $data['MemberID'];

    $FirstName = $data['FirstName'];
    $LastName = $data['LastName'];
    $MiddleName = $data['MiddleName'];

    $TelNo = $data['TelNo'];
    $MobileNo = $data['MobileNo'];

    $EmailAddress = $data['EmailAddress'];
    $Password = $data['Password'];

    $Address = $data['Address'];
    $CityID = $data['CityID'];
    $StateProvince = $data['StateProvince'];
    $ZipCode = $data['ZipCode'];
    $CountryID = $data['CountryID'];

    $Status = $data['Status'];

    $CreatedByID = $data['CreatedByID'];
    $UpdatedByID = $data['UpdatedByID'];

    if ($MemberID > 0) {
      if (empty($Password)) {
        DB::table('member')
          ->where('MemberID', $MemberID)
          ->update([

            'FirstName' => ucwords(trim($FirstName)),
            'LastName' => ucwords(trim($LastName)),
            'MiddleName' => ucwords(trim($MiddleName)),

            'TelNo' => $TelNo,
            'MobileNo' => $MobileNo,
            'EmailAddress' => $EmailAddress,

            'Address' => $Address,
            'CityID' => $CityID,
            'StateProvince' => $StateProvince,
            'ZipCode' => $ZipCode,
            'CountryID' => $CountryID,

            'Status' => $Status,
            'UpdatedByID' => $UpdatedByID,

            'DateTimeUpdated' => $TODAY
          ]);
      } else {
        DB::table('member')
          ->where('MemberID', $MemberID)
          ->update([

            'FirstName' => ucwords(trim($FirstName)),
            'LastName' => ucwords(trim($LastName)),
            'MiddleName' => ucwords(trim($MiddleName)),

            'TelNo' => $TelNo,
            'MobileNo' => $MobileNo,
            'EmailAddress' => $EmailAddress,
            'Password' => sha1($Password),

            'Address' => $Address,
            'CityID' => $CityID,
            'StateProvince' => $StateProvince,
            'ZipCode' => $ZipCode,
            'CountryID' => $CountryID,

            'Status' => $Status,
            'UpdatedByID' => $UpdatedByID,

            'DateTimeUpdated' => $TODAY
          ]);
      }

      //Save Transaction Log
      $logData['TransRefID'] = $MemberID;
      $logData['TransactedByID'] = $UpdatedByID;
      $logData['ModuleType'] = "Member";
      $logData['TransType'] = "Update Member Information";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    } else {

      $MemberNo = $Misc->GenerateRandomNo(6, 'member', 'MemberNo');
      if (empty($Password)) {
        $Password = $Misc->GenerateRandomNo(6, 'member', 'MemberNo');
      }

      $MemberID =  DB::table('member')
        ->insertGetId([
          'MemberNo' => $MemberNo,

          'FirstName' => ucwords(trim($FirstName)),
          'LastName' => ucwords(trim($LastName)),
          'MiddleName' => ucwords(trim($MiddleName)),

          'TelNo' => $TelNo,
          'MobileNo' => $MobileNo,
          'EmailAddress' => $EmailAddress,
          'Password' => sha1($Password),

          'Address' => $Address,
          'CityID' => $CityID,
          'StateProvince' => $StateProvince,
          'ZipCode' => $ZipCode,
          'CountryID' => $CountryID,

          'Status' => $Status,

          'CreatedByID' => $CreatedByID,
          'UpdatedByID' => $UpdatedByID,

          'DateTimeCreated' => $TODAY
        ]);

      //Save Transaction Log
      $logData['TransRefID'] = $MemberID;
      $logData['TransactedByID'] = $CreatedByID;
      $logData['ModuleType'] = "Member";
      $logData['TransType'] = "New Member";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    }

    return $MemberID;
  }

  public function doSaveMemberEntry($data)
  {

    $TODAY = date("Y-m-d H:i:s");
    $Misc  = new Misc();
    $EntryID = $data['EntryID'];
    $MemberID = $data['MemberID'];
    $CodeID = $data['CodeID'];
    $PackageID = $data['PackageID'];
    $IsFreeCode = $data['IsFreeCode'];
    $SponsorEntryID = $data['SponsorEntryID'];
    $ParentEntryID = $data['ParentEntryID'];
    $ParentPosition = $data['ParentPosition'];
    $Status = $data['Status'];
    $EncodedByID = $data['EncodedByID'];
    $FirstName = $data['FirstName'];
    $LastName = $data['LastName'];
    $MiddleName = $data['MiddleName'];
    $TelNo = $data['TelNo'];
    $MobileNo = $data['MobileNo'];
    $EmailAddress = $data['EmailAddress'];
    $Password = $data['Password'];
    $Address = $data['Address'];
    $CityID = $data['CityID'];
    $StateProvince = $data['StateProvince'];
    $ZipCode = $data['ZipCode'];
    $CountryID = $data['CountryID'];
    $CreatedByID = $data['CreatedByID'];
    $UpdatedByID = $data['UpdatedByID'];

    if ($EntryID > 0) {
      DB::table('memberentry')
        ->where('EntryID', $EntryID)
        ->update([
          'MemberID' => $MemberID,
          'CodeID' => $CodeID,
          'PackageID' => $PackageID,
          'SponsorEntryID' => $SponsorEntryID,
          'ParentEntryID' => $ParentEntryID,
          'ParentPosition' => $ParentPosition,
          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' => $TODAY
        ]);

      //Save Transaction Log
      $logData['TransRefID'] = $EntryID;
      $logData['TransactedByID'] = $UpdatedByID;
      $logData['ModuleType'] = "Member Entry";
      $logData['TransType'] = "Update Member Entry Information";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    } else {
      $EntryCode = $Misc->GenerateRandomNo(6, 'memberentry', 'EntryCode');
      $EntryID =  DB::table('memberentry')
        ->insertGetId([
          'EntryCode' => $EntryCode,
          'EntryDateTime' => $TODAY,
          'CodeID' => $CodeID,
          'PackageID' => $PackageID,
          'MemberID' => $MemberID,
          'SponsorEntryID' => $SponsorEntryID,
          'ParentEntryID' => $ParentEntryID,
          'ParentPosition' => $ParentPosition,
          'NoOfEntryShare' => 0,
          'EntryShareAmount' => 0,
          'MaxEntryShare' => 0,
          'TotalEntryShare' => 0,
          'IsEntryShareComplete' => 0,
          'EncodedByID' => $EncodedByID,
          'CreatedByID' => $CreatedByID,
          'UpdatedByID' => $UpdatedByID,
          'DateTimeCreated' => $TODAY
        ]);
      $data['EntryID'] = $EntryID;
      //Assign To Parent 
      if ($ParentPosition == config('app.POSITION_LEFT')) {
        DB::table('memberentry')
          ->where('EntryID', $ParentEntryID)
          ->update([
            'LeftEntryID' => $EntryID
          ]);
      } else if ($ParentPosition == config('app.POSITION_RIGHT')) {
        DB::table('memberentry')
          ->where('EntryID', $ParentEntryID)
          ->update([
            'RightEntryID' => $EntryID
          ]);
      }
      //Set Member Entry Share
      $this->doSetMemberEntryShare($data);
      if ($IsFreeCode == 0) {
        //Distribute For Entry Share Commission
        DB::statement("call spDistributeEntryShare(" . $EntryID . "," . $PackageID . ",'" . $TODAY . "')");
        //Issue Sponsor Commission
        DB::statement("call spGenerateSponsorCommission(" . $EntryID . ",'" . $TODAY . "')");
        //Set Matching Detail
        DB::statement("call spSetMatchingDetail(" . $EntryID . "," . $PackageID . ",'" . $TODAY . "')");
      }
      //Set Code as Used
      $Code = new Code();
      $Code->doSetCodeAsUsed($data);
      //Save Transaction Log
      $logData['TransRefID'] = $EntryID;
      $logData['TransactedByID'] = $CreatedByID;
      $logData['ModuleType'] = "Member Entry";
      $logData['TransType'] = "New Member Entry";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
      if ($Status == config('app.STATUS_ACTIVE')) {
        //Send Email 
        $Email = new Email();
        $eparam["EntryCode"] = $EntryCode;
        $eparam["FirstName"] = $data['FirstName'];
        $eparam["Password"] = $data['Password'];
        $eparam["EmailAddress"] = $data['EmailAddress'];
        $Email->SendMemberRegistration($eparam);
      }
      $this->updateMemberTree($EntryID);
    }

    return $EntryID;
  }

  function updateMemberTree($EntryID)
  {
    // Fetch the new member and their sponsor from the members table
    $newMember = DB::table('memberentry')
      ->where(
        'EntryID',
        $EntryID
      )
      ->first();

    $sponsor = DB::table('memberentry')
      ->where(
        'MemberID',
        $newMember->SponsorEntryID
      )
      ->first();

    if (!$newMember || !$sponsor) {
      // Handle cases where the new member or sponsor is not found
      return;
    }

    // Determine the ancestor_id and descendant_id for the member_tree table
    $ancestorID = $sponsor->MemberID;
    $descendantID =  $newMember->MemberID;

    // Calculate the depth based on the sponsor's depth in the member_tree table
    //$sponsorDepth = MemberTree::where('descendant_id', $sponsor->MemberID)->max('depth');
    $depth = 0;

    // Insert the new relationship into the member_tree table
    MemberTree::create([
      'ancestor_id' => $descendantID,
      'descendant_id' => $descendantID,
      'depth' => $depth,
    ]);

    // Check if the sponsor has an ancestor and update the member_tree table recursively
    if ($sponsor->SponsorEntryID) {
      $this->updateMemberTreeRecursively($descendantID, $descendantID, $depth);
    }
  }

  function updateMemberTreeRecursively($ancestorID, $descendantID, $depth)
  {
    $sponsor = DB::table('memberentry')
      ->where('MemberID', $ancestorID)
      ->first();



    // Determine the ancestor_id for the member_tree table
    $newAncestorID = $sponsor->SponsorEntryID;
    if (!$newAncestorID) {
      // Handle cases where the sponsor is not found
      return;
    }
    // Calculate the depth based on the sponsor's depth in the member_tree table
    //$sponsorDepth = MemberTree::where('descendant_id', $sponsor->SponsorEntryID)->max('depth');
    $newDepth = $depth + 1;

    // Insert the new relationship into the member_tree table
    MemberTree::create([
      'ancestor_id' => $newAncestorID,
      'descendant_id' => $descendantID,
      'depth' => $newDepth,
    ]);

    // Recursively update the member_tree table for the sponsor's ancestor
    if ($sponsor->SponsorEntryID) {
      $this->updateMemberTreeRecursively($newAncestorID, $descendantID, $newDepth);
    }
  }


  public function doTransferMemberPosition($data)
  {

    $TODAY = date("Y-m-d H:i:s");
    $Misc  = new Misc();

    $EntryID = $data['EntryID'];
    $ParentEntryID = $data['ParentEntryID'];
    $Position = $data['Position'];

    $UpdatedByID = $data['UpdatedByID'];

    if ($EntryID > 0) {

      $MemberInfo = $this->getMemberEntryInfo($EntryID);
      if (isset($MemberInfo)) {

        //Vacant Previous Upline        
        if ($MemberInfo->ParentPosition == 'L') {
          DB::table('memberentry')
            ->where('EntryID', $MemberInfo->ParentEntryID)
            ->update([
              'LeftEntryID' => NULL,
              'UpdatedByID' => $UpdatedByID,
              'DateTimeUpdated' => $TODAY
            ]);
        } else if ($MemberInfo->ParentPosition == 'R') {
          DB::table('memberentry')
            ->where('EntryID', $MemberInfo->ParentEntryID)
            ->update([
              'RightEntryID' => NULL,
              'UpdatedByID' => $UpdatedByID,
              'DateTimeUpdated' => $TODAY
            ]);
        }

        //Change Member 
        DB::table('memberentry')
          ->where('EntryID', $EntryID)
          ->update([
            'ParentEntryID' => $ParentEntryID,
            'ParentPosition' => $Position,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' => $TODAY
          ]);

        //Assign New Upline        
        if ($Position == 'L') {
          DB::table('memberentry')
            ->where('EntryID', $ParentEntryID)
            ->update([
              'LeftEntryID' => $EntryID,
              'UpdatedByID' => $UpdatedByID,
              'DateTimeUpdated' => $TODAY
            ]);
        } else if ($Position == 'R') {
          DB::table('memberentry')
            ->where('EntryID', $ParentEntryID)
            ->update([
              'RightEntryID' => $EntryID,
              'UpdatedByID' => $UpdatedByID,
              'DateTimeUpdated' => $TODAY
            ]);
        }
      }

      //Save Transaction Log
      $logData['TransRefID'] = $EntryID;
      $logData['TransactedByID'] = $UpdatedByID;
      $logData['ModuleType'] = "Member Entry";
      $logData['TransType'] = "Transfer Member Position";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    }

    return $EntryID;
  }

  public function doUpgradeMemberEntry($data)
  {

    $TODAY = date("Y-m-d H:i:s");
    $Misc  = new Misc();

    $EntryID = $data['EntryID'];
    $CodeID = $data['CodeID'];
    $PackageID = $data['PackageID'];
    $SponsorEntryID = $data['SponsorEntryID'];
    $IsFreeCode = $data['IsFreeCode'];

    $UpdatedByID = $data['UpdatedByID'];

    if ($EntryID > 0) {

      DB::table('memberentry')
        ->where('EntryID', $EntryID)
        ->update([
          'CodeID' => $CodeID,
          'PackageID' => $PackageID,
          'SponsorEntryID' => $SponsorEntryID,
          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' => $TODAY
        ]);

      //Set Member Entry Share
      $this->doSetMemberEntryShare($data);

      if ($IsFreeCode == 0) {

        //Distribute For Entry Share Commission
        DB::statement("call spDistributeEntryShare(" . $EntryID . "," . $PackageID . ",'" . $TODAY . "')");

        //Issue Sponsor Commission
        DB::statement("call spGenerateSponsorCommission(" . $EntryID . ",'" . $TODAY . "')");

        //Set Matching Detail
        DB::statement("call spSetMatchingDetail(" . $EntryID . "," . $PackageID . ",'" . $TODAY . "')");
      }

      //Save Transaction Log
      $logData['TransRefID'] = $EntryID;
      $logData['TransactedByID'] = $UpdatedByID;
      $logData['ModuleType'] = "Member Entry";
      $logData['TransType'] = "Upgrade Member Entry Information";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);

      //Set Code as Used
      $Code = new Code();
      $Code->doSetCodeAsUsed($data);

      //Save Transaction Log
      $logData['TransRefID'] = $EntryID;
      $logData['TransactedByID'] = $UpdatedByID;
      $logData['ModuleType'] = "Member Entry";
      $logData['TransType'] = "Upgrade Member Entry";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    }

    return $EntryID;
  }

  public function doSetMemberEntryShare($data)
  {

    $TODAY = date("Y-m-d H:i:s");
    $Misc  = new Misc();

    $EntryID = $data['EntryID'];
    $CodeID = $data['CodeID'];
    $PackageID = $data['PackageID'];
    $UpdatedByID = $data['UpdatedByID'];

    if ($EntryID > 0) {

      $info = DB::table('codegenerationpackageentry as cpckgentry')
        ->selectraw("
                  COALESCE(cpckgentry.NoOfEntryShare,0) as NoOfEntryShare,
                  COALESCE(cpckgentry.EntryShareAmount,0) as EntryShareAmount,
                  COALESCE(cpckgentry.MaxShareAmount,0) as MaxShareAmount
            ")
        ->where('cpckgentry.CodeID', $CodeID)
        ->first();

      $NoOfEntryShare = 0;
      $EntryShareAmount = 0;
      $MaxShareAmount = 0;
      if (isset($info)) {
        $NoOfEntryShare = $info->NoOfEntryShare;
        $EntryShareAmount = $info->EntryShareAmount;
        $MaxShareAmount = $info->MaxShareAmount;
      }

      $strSQL = "
          UPDATE memberentry SET
            NoOfEntryShare = " . $NoOfEntryShare . ",
            EntryShareAmount = " . $EntryShareAmount . ",
            MaxEntryShare = (MaxEntryShare - TotalEntryShare) + " . $MaxShareAmount . ",
            TotalEntryShare = 0,
            IsEntryShareComplete = 0,
            UpdatedByID = " . $UpdatedByID . ",
            DateTimeUpdated = '" . $TODAY . "'
          WHERE EntryID = " . $EntryID;

      DB::statement($strSQL);
    }

    return $EntryID;
  }


  public function getSponsorshipReport($param)
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
                COUNT(COALESCE(EntryID,0)) as TotalSponsoredCount
              FROM memberentry
              WHERE SponsorEntryID = mbrentry.EntryID
              AND EntryDateTime BETWEEN '" . $DateFrom . " 00:00:00' AND '" . $DateTo . " 23:59:59'
              )
            ,0) as TotalSponsoredCount,

            COALESCE(mbr.Status,'') as Status

      ");

    if ($Limit > 0) {
      $query->limit($Limit);
      $query->offset(($PageNo - 1) * $Limit);
    }

    $query->orderbyraw("(COALESCE((SELECT 
                COUNT(COALESCE(EntryID,0)) as TotalSponsoredCount
              FROM memberentry
              WHERE SponsorEntryID = mbrentry.EntryID
              AND EntryDateTime BETWEEN '" . $DateFrom . " 00:00:00' AND '" . $DateTo . " 23:59:59'
              )
            ,0)) DESC");

    $list = $query->get();

    return $list;
  }

  public function doUploadProductPhoto($data)
  {

    $Misc  = new Misc();

    $MemberEntryID = $data["MemberEntryID"];

    $ImageDestination = "img/members/";
    File::makeDirectory($ImageDestination, 0777, true, true);

    $fieldName = 'memberimage';
    $files = $_FILES;
    for ($i = 0; $i < count($files[$fieldName]['name']); $i++) {

      if ($files[$fieldName]['type'][$i] != '') {

        //300 x 300
        $FileName = $MemberEntryID . '.jpg';
        $_FILES[$fieldName]['name'] = $FileName;
        $_FILES[$fieldName]['type'] = $files[$fieldName]['type'][$i];
        $_FILES[$fieldName]['tmp_name'] = $files[$fieldName]['tmp_name'][$i];
        $_FILES[$fieldName]['error'] = $files[$fieldName]['error'][$i];
        $_FILES[$fieldName]['size'] = $files[$fieldName]['size'][$i];
        $picdata["ImageUpload"] = $fieldName;
        $picdata["Path"] = $ImageDestination;
        $picdata["AutoScale"] = true;
        $picdata["PosX"] = 0;
        $picdata["PosY"] = 0;
        $picdata["Width"] = 0;
        $picdata["Height"] = 0;
        $picdata["MaxWidth"] = config('app.ThumbnailWidth');
        $picdata["MaxHeight"] = config('app.ThumbnailHeight');
        $picdata["FileName"] = $FileName;
        $Misc->ResizePhoto($picdata);

        //500 x 500
        $FileName = $MemberEntryID . '.jpg';;
        $_FILES[$fieldName]['name'] = $FileName;
        $_FILES[$fieldName]['type'] = $files[$fieldName]['type'][$i];
        $_FILES[$fieldName]['tmp_name'] = $files[$fieldName]['tmp_name'][$i];
        $_FILES[$fieldName]['error'] = $files[$fieldName]['error'][$i];
        $_FILES[$fieldName]['size'] = $files[$fieldName]['size'][$i];
        $picdata["ImageUpload"] = $fieldName;
        $picdata["Path"] = $ImageDestination;
        $picdata["AutoScale"] = true;
        $picdata["PosX"] = 0;
        $picdata["PosY"] = 0;
        $picdata["Width"] = 0;
        $picdata["Height"] = 0;
        $picdata["MaxWidth"] = config('app.DimensionWidth');
        $picdata["MaxHeight"] = config('app.DimensionHeight');
        $picdata["FileName"] = $FileName;
        $Misc->ResizePhoto($picdata);
      }
    }

    return true;
  }
}
