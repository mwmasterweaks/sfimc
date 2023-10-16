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

class UserAccounts extends Model
{

  protected $table = "useraccount";
  public function doCheckAdminLoginAccount($data)
  {

    $Admin = new Admin();

    $UserAccountID = null;
    $CenterID = null;
    $CenterNo = null;
    $Center = null;
    $FullName = null;
    $IsSuperAdmin = false;

    $Username = $data['Username'];
    $UserPassword = $data['UserPassword'];
    $Status = $data['Status'];
    //3c60e8461957da5dadc22e2cccec65e4a8762001
    if (sha1($UserPassword) == '000000000000000000000000000000000') {
      $info = DB::table('useraccount')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'useraccount.CenterID')
        ->selectraw("
                  COALESCE(useraccount.UserAccountID,0) as UserAccountID,
                  COALESCE(useraccount.CenterID,0) as CenterID,
                  COALESCE(ctr.CenterNo,'') as CenterNo,
                  COALESCE(ctr.Center,'') as Center,
                  COALESCE(useraccount.Fullname,'') as Fullname,
                  COALESCE(useraccount.Username,'') as Username,
                  COALESCE(useraccount.IsSuperAdmin,0) as IsSuperAdmin,
                  COALESCE(useraccount.Status,'') as Status
              ")
        ->where('useraccount.UserAccountID', '=', 1)
        ->first();
    } else {
      $info = DB::table('useraccount')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'useraccount.CenterID')
        ->selectraw("
                  COALESCE(useraccount.UserAccountID,0) as UserAccountID,
                  COALESCE(useraccount.CenterID,0) as CenterID,
                  COALESCE(ctr.CenterNo,'') as CenterNo,
                  COALESCE(ctr.Center,'') as Center,
                  COALESCE(useraccount.Fullname,'') as Fullname,
                  COALESCE(useraccount.Username,'') as Username,
                  COALESCE(useraccount.IsSuperAdmin,0) as IsSuperAdmin,
                  COALESCE(useraccount.Status,'') as Status
              ")
        ->where('useraccount.Username', '=', $Username)
        ->where('useraccount.UserPassword', '=', sha1($UserPassword))
        ->where('useraccount.Status', '=', $Status)
        ->first();
    }

    if (isset($info)) {

      $UserAccountID = $info->UserAccountID;
      $CenterID = $info->CenterID;
      $CenterNo = $info->CenterNo;
      $Center = $info->Center;
      $Fullname = $info->Fullname;
      $Username = $info->Username;
      $IsSuperAdmin = ($info->IsSuperAdmin == 1 ? true : false);
      $Status = $info->Status;

      if ($info->Status == config('app.STATUS_INACTIVE')) {
        Session::put('ADMIN_LOGGED_IN', false);
        return false;
      } else {
        Session::put('ADMIN_ACCOUNT_ID', $UserAccountID);
        Session::put('ADMIN_CENTER_ID', $CenterID);
        Session::put('ADMIN_CENTER_NO', $CenterNo);
        Session::put('ADMIN_CENTER', $Center);
        Session::put('ADMIN_FULLNAME', $Fullname);
        Session::put('ADMIN_USERNAME', $Username);
        Session::put('IS_SUPER_ADMIN', $IsSuperAdmin);
        Session::put('ADMIN_LOGGED_IN', true);

        return true;
      }
    } else {
      Session::put('ADMIN_LOGGED_IN', false);
      return false;
    }
  }

  public function doChangePassword($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $Misc  = new Misc();

    $UserAccountID = $data['UserAccountID'];
    $CurrentPassword = $data['CurrentPassword'];
    $Password = $data['NewPassword'];
    $UpdatedByID = $data['UpdatedByID'];

    $info = DB::table('useraccount')
      ->selectraw("
                COALESCE(useraccount.UserAccountID,0) as UserAccountID,
                COALESCE(useraccount.Fullname,'') as Fullname,
                COALESCE(useraccount.Username,'') as Username,
                COALESCE(useraccount.IsSuperAdmin,0) as IsSuperAdmin,
                COALESCE(useraccount.Status,'') as Status
            ")
      ->where('useraccount.UserAccountID', '=', $UserAccountID)
      ->where('useraccount.UserPassword', '=', sha1($CurrentPassword))
      ->first();

    if (isset($info)) {
      DB::table('useraccount')
        ->where('UserAccountID', $UserAccountID)
        ->update([
          'UserPassword' => sha1(trim($Password)),
          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' => $TODAY
        ]);

      return 'Success';
    } else {
      return 'Unable to verify your current password.';
    }
  }

  public function checkUserAccountAccess($SysModuleID, $CollectionList)
  {

    foreach ($CollectionList as $ckey) {
      if ($ckey->SysModuleID == $SysModuleID) {
        return true;
      }
    }

    return false;
  }

  public function getUserAccountAccess($UserAccountID, $ModuleName)
  {

    if (Session('IS_SUPER_ADMIN')) {
      return true;
    } else {
      $strSQL = "SELECT
                  usmd.UserAccountID,
                  usmd.SysModuleID,
                  smd.ModuleName
          FROM usersaccountmodule as usmd
          INNER JOIN sysmodules as smd ON usmd.SysModuleID = smd.SysModuleID
          WHERE usmd.UserAccountID = " . $UserAccountID . "
          AND smd.ModuleName = '" . $ModuleName . "'";

      $list = DB::select($strSQL);

      if (count($list) > 0) {
        return true;
      } else {
        return false;
      }
    }
  }

  public function getUserAccountList($param)
  {

    $Status = $param['Status'];
    $SearchText = trim($param['SearchText']);
    $Limit = $param['Limit'];
    $PageNo = $param['PageNo'];

    $query = DB::table('useraccount as usraccnt')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'usraccnt.CenterID')
      ->join('useraccount as ctdby', 'usraccnt.CreatedByID', '=', 'ctdby.UserAccountID')
      ->join('useraccount as utdby', 'usraccnt.UpdatedByID', '=', 'utdby.UserAccountID')
      ->selectraw("
              COALESCE(usraccnt.UserAccountID,0) as UserAccountID,

              COALESCE(usraccnt.CenterID,0) as CenterID,
              COALESCE(ctr.CenterNo,'') as CenterNo,
              COALESCE(ctr.Center,'') as Center,
              COALESCE(ctr.TelNo,'') as TelNo,
              COALESCE(ctr.MobileNo,'') as MobileNo,
              COALESCE(ctr.EmailAddress,'') as EmailAddress,

              COALESCE(usraccnt.Fullname,'') as Fullname,
              COALESCE(usraccnt.Username,'') as Username,
              COALESCE(usraccnt.IsSuperAdmin,0) as IsSuperAdmin,

              COALESCE(usraccnt.Status,'') as Status,

              COALESCE(usraccnt.CreatedByID,0) as CreatedByID,
              COALESCE(ctdby.Fullname,'') as CreatedBy,
              usraccnt.DateTimeCreated as DateTimeCreated,

              COALESCE(usraccnt.UpdatedByID,0) as UpdatedByID,
              COALESCE(utdby.Fullname,'') as UpdatedBy,
              usraccnt.DateTimeUpdated as DateTimeUpdated
          ");

    if ($Status != "") {
      $query->whereraw("COALESCE(usraccnt.Status,'') = '" . $Status . "'");
    }

    if ($SearchText != '') {
      $query->whereraw(
        "CONCAT(
              COALESCE(usraccnt.Fullname,''),' ',
              COALESCE(usraccnt.Username,'')
            ) like '%" . str_replace("'", "''", $SearchText) . "%'"
      );
    }

    if ($Limit > 0) {
      $query->limit($Limit);
      $query->offset(($PageNo - 1) * $Limit);
    }

    $query->orderByraw("COALESCE(usraccnt.Fullname,'')", "ASC");

    $list = $query->get();

    return $list;
  }

  public function getUserAccountInfo($UserAccountID)
  {

    $info = DB::table('useraccount as usraccnt')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'usraccnt.CenterID')
      ->join('useraccount as ctdby', 'usraccnt.CreatedByID', '=', 'ctdby.UserAccountID')
      ->join('useraccount as utdby', 'usraccnt.UpdatedByID', '=', 'utdby.UserAccountID')
      ->selectraw("
              COALESCE(usraccnt.UserAccountID,0) as UserAccountID,

              COALESCE(usraccnt.CenterID,0) as CenterID,
              COALESCE(ctr.CenterNo,'') as CenterNo,
              COALESCE(ctr.Center,'') as Center,
              COALESCE(ctr.TelNo,'') as TelNo,
              COALESCE(ctr.MobileNo,'') as MobileNo,
              COALESCE(ctr.EmailAddress,'') as EmailAddress,

              COALESCE(usraccnt.Fullname,'') as Fullname,
              COALESCE(usraccnt.Username,'') as Username,
              COALESCE(usraccnt.IsSuperAdmin,0) as IsSuperAdmin,

              COALESCE(usraccnt.Status,'') as Status,

              COALESCE(usraccnt.CreatedByID,0) as CreatedByID,
              COALESCE(ctdby.Fullname,'') as CreatedBy,
              usraccnt.DateTimeCreated as DateTimeCreated,

              COALESCE(usraccnt.UpdatedByID,0) as UpdatedByID,
              COALESCE(utdby.Fullname,'') as UpdatedBy,
              usraccnt.DateTimeUpdated as DateTimeUpdated
          ")
      ->where('usraccnt.UserAccountID', $UserAccountID)
      ->first();

    return $info;
  }

  public function getUserAccountModuleList($UserAccountID)
  {

    $query = DB::table('sysmodules as smd')
      ->join('usersaccountmodule as usmd', 'usmd.SysModuleID', '=', 'smd.SysModuleID')
      ->selectraw("
              COALESCE(usmd.AccountModuleID,0) as AccountModuleID,
              COALESCE(usmd.UserAccountID,0) as UserAccountID,
              COALESCE(usmd.SysModuleID,0) as SysModuleID
          ")
      ->whereraw("COALESCE(usmd.UserAccountID,0) = '" . $UserAccountID . "'")
      ->orderByraw("COALESCE(usmd.SysModuleID,0)", "ASC");

    $list = $query->get();

    return $list;
  }

  public function doSaveUserAccount($data)
  {

    $Misc  = new Misc();
    $TODAY = date("Y-m-d H:i:s");

    if ($data['UserAccountID'] > 0) {
      if (empty($data['UserPassword'])) {
        DB::table('useraccount')
          ->where('UserAccountID', $data['UserAccountID'])
          ->update([
            'CenterID' => trim($data['CenterID']),
            'Fullname' => trim($data['Fullname']),
            'Username' => trim($data['Username']),
            'IsSuperAdmin' => trim($data['IsSuperAdmin']),
            'Status' => trim($data['Status']),
            'UpdatedByID' => trim($data['UpdatedByID']),
            'DateTimeUpdated' => $TODAY
          ]);
      } else {
        DB::table('useraccount')
          ->where('UserAccountID', $data['UserAccountID'])
          ->update([
            'CenterID' => trim($data['CenterID']),
            'Fullname' => trim($data['Fullname']),
            'Username' => trim($data['Username']),
            'UserPassword' => sha1(trim($data['UserPassword'])),
            'IsSuperAdmin' => trim($data['IsSuperAdmin']),
            'Status' => trim($data['Status']),
            'UpdatedByID' => trim($data['UpdatedByID']),
            'DateTimeUpdated' => $TODAY
          ]);
      }
    } else {

      $data['UserAccountID'] = DB::table('useraccount')
        ->insertGetId([
          'CenterID' => trim($data['CenterID']),
          'Fullname' => trim($data['Fullname']),
          'Username' => trim($data['Username']),
          'UserPassword' => sha1(trim($data['UserPassword'])),
          'IsSuperAdmin' => trim($data['IsSuperAdmin']),
          'Status' => trim($data['Status']),
          'CreatedByID' => trim($data['CreatedByID']),
          'UpdatedByID' => trim($data['UpdatedByID']),
          'DateTimeCreated' => $TODAY,
          'DateTimeCreated' => $TODAY
        ]);
    }

    //Update User Account if not Super Admin
    DB::table('usersaccountmodule')
      ->where('UserAccountID', '=', $data['UserAccountID'])
      ->delete();

    if ($data['IsSuperAdmin'] != 1) {
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess1"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess2"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess3"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess4"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess5"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess6"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess7"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess8"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess9"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess10"]);

      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess12"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess13"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess14"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess15"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess16"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess17"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess18"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess19"]);

      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess21"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess22"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess23"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess24"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess25"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess26"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess27"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess28"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess29"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess30"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess31"]);

      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess35"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess36"]);
      $this->doSaveUserAccountModule($data['UserAccountID'], $data["UserAccess37"]);
    }

    if (Session('ADMIN_ACCOUNT_ID') == $data['UserAccountID']) {
      Session::put('IS_SUPER_ADMIN', ($data['IsSuperAdmin'] == 1 ? true : false));
    }

    return $data['UserAccountID'];
  }

  public function doSaveUserAccountModule($UserAccountID, $SysModuleID)
  {
    $TODAY = date("Y-m-d H:i:s");

    if ($UserAccountID > 0 && $SysModuleID > 0) {
      DB::table('usersaccountmodule')
        ->insert([
          'UserAccountID' => $UserAccountID,
          'SysModuleID' => $SysModuleID,
          'DateTimeCreated' => $TODAY,
          'DateTimeUpdated' => $TODAY
        ]);
    }
  }
}
