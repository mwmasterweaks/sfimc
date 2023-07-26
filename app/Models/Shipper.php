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

class Shipper extends Model
{


    public function getShipperList(){

      $TODAY = date("Y-m-d H:i:s");

      ini_set('memory_limit', '999999M');

      $query = DB::table('shipper')
        ->join('useraccount as encode', 'shipper.EncodedByID', '=', 'encode.UserAccountID')
        ->join('useraccount as ctdby', 'shipper.CreatedByID', '=', 'ctdby.UserAccountID')
        ->join('useraccount as utdby', 'shipper.UpdatedByID', '=', 'utdby.UserAccountID')
        ->selectraw("
            COALESCE(shipper.ShipperID,0) as ShipperID,
            COALESCE(shipper.ShipperCode,'') as ShipperCode,
            COALESCE(shipper.ShipperName,'') as ShipperName,
            COALESCE(shipper.Status,'') as Status,

            COALESCE(shipper.EncodedByID,0) as EncodedByID,
            COALESCE(encode.Fullname,'') as EncodedBy,

            COALESCE(shipper.CreatedByID,0) as CreatedByID,
            COALESCE(ctdby.Fullname,'') as CreatedBy,
            shipper.DateTimeCreated as DateTimeCreated,

            COALESCE(shipper.UpdatedByID,0) as UpdatedByID,
            COALESCE(utdby.Fullname,'') as UpdatedBy,
            shipper.DateTimeUpdated as DateTimeUpdated

        ");
      $query->orderByraw("COALESCE(shipper.ShipperName,'')","ASC");

      $list = $query->get();

      return $list;

    }

    public function getShipperInfo($ShipperID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('shipper')
        ->join('useraccount as encode', 'shipper.EncodedByID', '=', 'encode.UserAccountID')
        ->join('useraccount as ctdby', 'shipper.CreatedByID', '=', 'ctdby.UserAccountID')
        ->join('useraccount as utdby', 'shipper.UpdatedByID', '=', 'utdby.UserAccountID')
        ->selectraw("
            COALESCE(shipper.ShipperID,0) as ShipperID,
            COALESCE(shipper.ShipperCode,'') as ShipperCode,
            COALESCE(shipper.ShipperName,'') as ShipperName,
            COALESCE(shipper.Status,'') as Status,

            COALESCE(shipper.EncodedByID,0) as EncodedByID,
            COALESCE(encode.Fullname,'') as EncodedBy,

            COALESCE(shipper.CreatedByID,0) as CreatedByID,
            COALESCE(ctdby.Fullname,'') as CreatedBy,
            shipper.DateTimeCreated as DateTimeCreated,

            COALESCE(shipper.UpdatedByID,0) as UpdatedByID,
            COALESCE(utdby.Fullname,'') as UpdatedBy,
            shipper.DateTimeUpdated as DateTimeUpdated

        ")
        ->where('shipper.ShipperID',$ShipperID)
        ->first();

      return $info;

    }

    public function getShipperInfoByCode($ShipperCode){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('shipper')
        ->join('useraccount as encode', 'shipper.EncodedByID', '=', 'encode.UserAccountID')
        ->join('useraccount as ctdby', 'shipper.CreatedByID', '=', 'ctdby.UserAccountID')
        ->join('useraccount as utdby', 'shipper.UpdatedByID', '=', 'utdby.UserAccountID')
        ->selectraw("
            COALESCE(shipper.ShipperID,0) as ShipperID,
            COALESCE(shipper.ShipperCode,'') as ShipperCode,
            COALESCE(shipper.ShipperName,'') as ShipperName,
            COALESCE(shipper.Status,'') as Status,

            COALESCE(shipper.EncodedByID,0) as EncodedByID,
            COALESCE(encode.Fullname,'') as EncodedBy,

            COALESCE(shipper.CreatedByID,0) as CreatedByID,
            COALESCE(ctdby.Fullname,'') as CreatedBy,
            shipper.DateTimeCreated as DateTimeCreated,

            COALESCE(shipper.UpdatedByID,0) as UpdatedByID,
            COALESCE(utdby.Fullname,'') as UpdatedBy,
            shipper.DateTimeUpdated as DateTimeUpdated

        ")
        ->whereraw("COALESCE(shipper.ShipperCode,'') = '".$ShipperCode."'")
        ->first();

      return $info;

    }

    public function doSaveUpdateShipper($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $ShipperID = $data['ShipperID'];
      $ShipperCode = $data['ShipperCode'];
      $ShipperName = $data['ShipperName'];

      $Status = $data['Status'];

      $EncodedByID = $data['EncodedByID'];
      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      //Save Info
      if($ShipperID > 0){
        DB::table('shipper')
        ->where('ShipperID',$ShipperID)
        ->update([
          'ShipperCode' => $ShipperCode,
          'ShipperName' => $ShipperName,

          'Status' => $Status,

          'EncodedByID' => $EncodedByID,
          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $ShipperID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Shipper";
        $logData['TransType'] = "Update Shipper";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        if(empty($ShipperCode)){
          $ShipperCode = $Misc->GenerateRandomNo(6,'shipper','ShipperCode');
        }

        $ShipperID =  DB::table('shipper')
          ->insertGetId([
            'ShipperCode' => $ShipperCode,
            'ShipperName' => $ShipperName,

            'Status' => $Status,

            'EncodedByID' => $EncodedByID,
            'CreatedByID' => $CreatedByID,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeCreated' =>$TODAY,
            'DateTimeUpdated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $ShipperID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "Shipper";
        $logData['TransType'] = "New Shipper";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);
      }

      return $ShipperID;

    }




}
