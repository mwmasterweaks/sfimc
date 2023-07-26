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

class Center extends Model
{


    public function getCenterList($param){

      $TODAY = date("Y-m-d H:i:s");

      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('centers as ctr')
        ->join('useraccount as usr', 'ctr.InchargeID', '=', 'usr.UserAccountID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->join('useraccount as ctdby', 'ctr.CreatedByID', '=', 'ctdby.UserAccountID')
        ->join('useraccount as utdby', 'ctr.UpdatedByID', '=', 'utdby.UserAccountID')
        ->selectraw("
            COALESCE(ctr.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,

            COALESCE(ctr.InchargeID,0) as InchargeID,
            COALESCE(usr.Fullname,'') as Incharge,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(ctr.Address,'') as Address,
            COALESCE(ctr.CityID,0) as CityID,
            COALESCE(cty.City,'') as City,
            COALESCE(ctr.StateProvince,'') as StateProvince,
            COALESCE(ctr.ZipCode,'') as ZipCode,
            COALESCE(ctr.CountryID,0) as CountryID,
            COALESCE(ctry.Country,'') as Country,

            COALESCE(ctr.Status,'') as Status,

            COALESCE(ctr.CreatedByID,0) as CreatedByID,
            COALESCE(ctdby.Fullname,'') as CreatedBy,
            ctr.DateTimeCreated as DateTimeCreated,

            COALESCE(ctr.UpdatedByID,0) as UpdatedByID,
            COALESCE(utdby.Fullname,'') as UpdatedBy,
            ctr.DateTimeUpdated as DateTimeUpdated

        ");

      if($Status != ''){
        $query->whereraw("COALESCE(ctr.Status,'') = '".$Status."'");
      }

      if($SearchText != ''){
        $query->whereraw(
            "CONCAT(
              COALESCE(ctr.CenterNo,''),' ',
              COALESCE(ctr.Center,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("COALESCE(ctr.Center,'')","ASC");

      $list = $query->get();

      return $list;

    }

    public function getCenterInfo($CenterID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('centers as ctr')
        ->join('useraccount as usr', 'ctr.InchargeID', '=', 'usr.UserAccountID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->join('useraccount as ctdby', 'ctr.CreatedByID', '=', 'ctdby.UserAccountID')
        ->join('useraccount as utdby', 'ctr.UpdatedByID', '=', 'utdby.UserAccountID')
        ->selectraw("
            COALESCE(ctr.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,

            COALESCE(ctr.InchargeID,0) as InchargeID,
            COALESCE(usr.Fullname,'') as Incharge,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(ctr.Address,'') as Address,
            COALESCE(ctr.CityID,0) as CityID,
            COALESCE(cty.City,'') as City,
            COALESCE(ctr.StateProvince,'') as StateProvince,
            COALESCE(ctr.ZipCode,'') as ZipCode,
            COALESCE(ctr.CountryID,0) as CountryID,
            COALESCE(ctry.Country,'') as Country,

            COALESCE(ctr.Status,'') as Status,

            COALESCE(ctr.CreatedByID,0) as CreatedByID,
            COALESCE(ctdby.Fullname,'') as CreatedBy,
            ctr.DateTimeCreated as DateTimeCreated,

            COALESCE(ctr.UpdatedByID,0) as UpdatedByID,
            COALESCE(utdby.Fullname,'') as UpdatedBy,
            ctr.DateTimeUpdated as DateTimeUpdated
        ")
        ->where('ctr.CenterID',$CenterID)
        ->first();

      return $info;

    }

    public function getCenterInfoByNo($CenterNo){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('centers as ctr')
        ->join('useraccount as usr', 'ctr.InchargeID', '=', 'usr.UserAccountID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->join('useraccount as ctdby', 'ctr.CreatedByID', '=', 'ctdby.UserAccountID')
        ->join('useraccount as utdby', 'ctr.UpdatedByID', '=', 'utdby.UserAccountID')
        ->selectraw("
            COALESCE(ctr.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,

            COALESCE(ctr.InchargeID,0) as InchargeID,
            COALESCE(usr.Fullname,'') as Incharge,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(ctr.Address,'') as Address,
            COALESCE(ctr.CityID,0) as CityID,
            COALESCE(cty.City,'') as City,
            COALESCE(ctr.StateProvince,'') as StateProvince,
            COALESCE(ctr.ZipCode,'') as ZipCode,
            COALESCE(ctr.CountryID,0) as CountryID,
            COALESCE(ctry.Country,'') as Country,

            COALESCE(ctr.Status,'') as Status,

            COALESCE(ctr.CreatedByID,0) as CreatedByID,
            COALESCE(ctdby.Fullname,'') as CreatedBy,
            ctr.DateTimeCreated as DateTimeCreated,

            COALESCE(ctr.UpdatedByID,0) as UpdatedByID,
            COALESCE(utdby.Fullname,'') as UpdatedBy,
            ctr.DateTimeUpdated as DateTimeUpdated
        ")
        ->whereraw("COALESCE(ctr.CenterNo,'') = '".$CenterNo."'")
        ->first();

      return $info;

    }

    public function doSaveUpdateCenter($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $CenterID = $data['CenterID'];
      $CenterNo = $data['CenterNo'];
      $Center = $data['Center'];

      $InchargeID = $data['InchargeID'] ;
      $TelNo = $data['TelNo'] ;
      $MobileNo = $data['MobileNo'] ;
      $EmailAddress = $data['EmailAddress'] ;

      $Address = $data['Address'];
      $CityID = $data['CityID'];
      $StateProvince = $data['StateProvince'];
      $ZipCode = $data['ZipCode'];
      $CountryID = $data['CountryID'];

      $Status = $data['Status'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      //Save Product Info
      if($CenterID > 0){
        DB::table('centers')
        ->where('CenterID',$CenterID)
        ->update([
          'Center' => $Center,

          'InchargeID' => $InchargeID,
          'TelNo' => $TelNo,
          'MobileNo'=> $MobileNo,
          'EmailAddress' => $EmailAddress,

          'Address' => $Address,
          'CityID' => $CityID,
          'StateProvince' => $StateProvince,
          'ZipCode' => $ZipCode,
          'CountryID' => $CountryID,

          'Status' => $Status,

          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $CenterID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Center";
        $logData['TransType'] = "Update Center";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        if(empty($CenterNo)){
          $CenterNo = $Misc->GenerateRandomNo(6,'centers','CenterNo');
        }

        $CenterID =  DB::table('centers')
          ->insertGetId([
            'CenterNo' => $CenterNo,
            'Center' => $Center,

            'InchargeID' => $InchargeID,
            'TelNo' => $TelNo,
            'MobileNo'=> $MobileNo,
            'EmailAddress' => $EmailAddress,

            'Address' => $Address,
            'CityID' => $CityID,
            'StateProvince' => $StateProvince,
            'ZipCode' => $ZipCode,
            'CountryID' => $CountryID,

            'Status' => $Status,

            'CreatedByID' => $CreatedByID,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeCreated' =>$TODAY,
            'DateTimeUpdated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $CenterID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "Center";
        $logData['TransType'] = "New Center";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);
      }

      //Save Inventory
      $Inventory = new Inventory();
      $data["CenterID"] = $CenterID;
      $Inventory->doSaveProductInventory($data);

      return $CenterID;

    }




}
