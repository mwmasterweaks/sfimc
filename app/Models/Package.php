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

class Package extends Model
{

  public function getPackageList($param){

    $Status = $param['Status'];
    $SearchText = trim($param['SearchText']);
    $Limit = $param['Limit'];
    $PageNo = $param['PageNo'];

    ini_set('memory_limit', '999999M');

    $query = DB::table('package as pckg')
      ->join('packageentry as pckgentry', 'pckg.PackageID', '=', 'pckgentry.PackageID')
      ->join('packagematchingcomm as pckgmtch', 'pckg.PackageID', '=', 'pckgmtch.PackageID')
      ->join('packagerebates as pckgrbt', 'pckg.PackageID', '=', 'pckgrbt.PackageID')
      ->join('packagerank as pckgrank', 'pckg.PackageID', '=', 'pckgrank.PackageID')
      ->leftjoin('product as prd', 'pckg.ProductID', '=', 'prd.ProductID')
      ->selectraw("

            COALESCE(pckg.PackageID,0) as PackageID,
            COALESCE(pckg.Package,'') as Package,
            COALESCE(pckg.Description,'') as Description,

            COALESCE(pckg.PackagePrice,0) as PackagePrice,
            COALESCE(pckg.ProductWorth,0) as ProductWorth,
            COALESCE(pckg.ProductID,0) as ProductID,
            COALESCE(prd.ProductName,'') as ProductName,

            COALESCE(pckg.SponsorCommission,0) as SponsorCommission,

            COALESCE(pckg.PackageColor,'') as PackageColor,

            COALESCE(pckgentry.NoOfEntryShare,0) as NoOfEntryShare,
            COALESCE(pckgentry.EntryShareAmount,0) as EntryShareAmount,
            COALESCE(pckgentry.MaxShareAmount,0) as MaxShareAmount,

            COALESCE(pckgmtch.RequiredBPV,0) as RequiredBPV,
            COALESCE(pckgmtch.PairingAmount,0) as PairingAmount,
            COALESCE(pckgmtch.MaxMatchPerDay,0) as MaxMatchPerDay,
            COALESCE(pckgmtch.VoucherOnNthPair,0) as VoucherOnNthPair,

            COALESCE(pckgrbt.RebatesMaintainingBal,0) as RebatesMaintainingBal,
            COALESCE(pckgrbt.PersonalRebatesPercent,0) as PersonalRebatesPercent,
            COALESCE(pckgrbt.RebateLevel1Percent,0) as RebateLevel1Percent,
            COALESCE(pckgrbt.RebateLevel2Percent,0) as RebateLevel2Percent,
            COALESCE(pckgrbt.RebateLevel3Percent,0) as RebateLevel3Percent,
            COALESCE(pckgrbt.RebateLevel4Percent,0) as RebateLevel4Percent,
            COALESCE(pckgrbt.RebateLevel5Percent,0) as RebateLevel5Percent,
            COALESCE(pckgrbt.RebateLevel6Percent,0) as RebateLevel6Percent,
            COALESCE(pckgrbt.RebateLevel7Percent,0) as RebateLevel7Percent,
            COALESCE(pckgrbt.RebateLevel8Percent,0) as RebateLevel8Percent,
            COALESCE(pckgrbt.RebateLevel9Percent,0) as RebateLevel9Percent,

            COALESCE(pckgrank.RankLevel1,0) as RankLevel1,
            COALESCE(pckgrank.RankLevel1APPRV,0) as RankLevel1APPRV,
            COALESCE(pckgrank.RankLevel1AGPRV,0) as RankLevel1AGPRV,
            COALESCE(pckgrank.RankLevel1Percent,0) as RankLevel1Percent,
            COALESCE(pckgrank.RankLevel2,0) as RankLevel2,
            COALESCE(pckgrank.RankLevel2APPRV,0) as RankLevel2APPRV,
            COALESCE(pckgrank.RankLevel2AGPRV,0) as RankLevel2AGPRV,
            COALESCE(pckgrank.RankLevel2Percent,0) as RankLevel2Percent,
            COALESCE(pckgrank.RankLevel3,0) as RankLevel3,
            COALESCE(pckgrank.RankLevel3APPRV,0) as RankLevel3APPRV,
            COALESCE(pckgrank.RankLevel3AGPRV,0) as RankLevel3AGPRV,
            COALESCE(pckgrank.RankLevel3Percent,0) as RankLevel3Percent,
            COALESCE(pckgrank.RankLevel4,0) as RankLevel4,
            COALESCE(pckgrank.RankLevel4APPRV,0) as RankLevel4APPRV,
            COALESCE(pckgrank.RankLevel4AGPRV,0) as RankLevel4AGPRV,
            COALESCE(pckgrank.RankLevel4Percent,0) as RankLevel4Percent,
            COALESCE(pckgrank.RankLevel5,0) as RankLevel5,
            COALESCE(pckgrank.RankLevel5APPRV,0) as RankLevel5APPRV,
            COALESCE(pckgrank.RankLevel5AGPRV,0) as RankLevel5AGPRV,
            COALESCE(pckgrank.RankLevel5Percent,0) as RankLevel5Percent,
            COALESCE(pckgrank.RankLevel6,0) as RankLevel6,
            COALESCE(pckgrank.RankLevel6APPRV,0) as RankLevel6APPRV,
            COALESCE(pckgrank.RankLevel6AGPRV,0) as RankLevel6AGPRV,
            COALESCE(pckgrank.RankLevel6Percent,0) as RankLevel6Percent,
            COALESCE(pckgrank.RankLevel7,0) as RankLevel7,
            COALESCE(pckgrank.RankLevel7APPRV,0) as RankLevel7APPRV,
            COALESCE(pckgrank.RankLevel7AGPRV,0) as RankLevel7AGPRV,
            COALESCE(pckgrank.RankLevel7Percent,0) as RankLevel7Percent,
            COALESCE(pckgrank.RankLevel8,0) as RankLevel8,
            COALESCE(pckgrank.RankLevel8APPRV,0) as RankLevel8APPRV,
            COALESCE(pckgrank.RankLevel8AGPRV,0) as RankLevel8AGPRV,
            COALESCE(pckgrank.RankLevel8Percent,0) as RankLevel8Percent,
            COALESCE(pckgrank.RankLevel9,0) as RankLevel9,
            COALESCE(pckgrank.RankLevel9APPRV,0) as RankLevel9APPRV,
            COALESCE(pckgrank.RankLevel9AGPRV,0) as RankLevel9AGPRV,
            COALESCE(pckgrank.RankLevel9Percent,0) as RankLevel9Percent,

            COALESCE(pckg.Status,'') as Status,

            COALESCE(pckg.ApprovedByID,0) as ApprovedByID,
            COALESCE(pckg.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(pckg.CreatedByID,0) as CreatedByID,
            COALESCE(pckg.DateTimeCreated,'') as DateTimeCreated,

            COALESCE(pckg.UpdatedByID,0) as UpdatedByID,
            COALESCE(pckg.DateTimeUpdated,'') as DateTimeUpdated

        ");

      if($Status != ""){
        $query->whereraw("COALESCE(pckg.Status,'') = '".$Status."'");
      }

      if($SearchText != ''){
        $query->whereraw(
            "COALESCE(pckg.Package,'') like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderBy("pckg.PackagePrice","ASC");

      $list = $query->get();

      return $list;
    }

    public function getPackageInfo($PackageID){

      $info = DB::table('package as pckg')
      ->join('packageentry as pckgentry', 'pckg.PackageID', '=', 'pckgentry.PackageID')
      ->join('packagematchingcomm as pckgmtch', 'pckg.PackageID', '=', 'pckgmtch.PackageID')
      ->join('packagerebates as pckgrbt', 'pckg.PackageID', '=', 'pckgrbt.PackageID')
      ->join('packagerank as pckgrank', 'pckg.PackageID', '=', 'pckgrank.PackageID')
      ->leftjoin('product as prd', 'pckg.ProductID', '=', 'prd.ProductID')
      ->selectraw("

            COALESCE(pckg.PackageID,0) as PackageID,
            COALESCE(pckg.Package,'') as Package,
            COALESCE(pckg.Description,'') as Description,

            COALESCE(pckg.PackagePrice,0) as PackagePrice,
            COALESCE(pckg.ProductWorth,0) as ProductWorth,
            COALESCE(pckg.ProductID,0) as ProductID,
            COALESCE(prd.ProductName,'') as ProductName,

            COALESCE(pckg.SponsorCommission,0) as SponsorCommission,

            COALESCE(pckg.PackageColor,'') as PackageColor,

            COALESCE(pckgentry.NoOfEntryShare,0) as NoOfEntryShare,
            COALESCE(pckgentry.EntryShareAmount,0) as EntryShareAmount,
            COALESCE(pckgentry.MaxShareAmount,0) as MaxShareAmount,

            COALESCE(pckgmtch.RequiredBPV,0) as RequiredBPV,
            COALESCE(pckgmtch.PairingAmount,0) as PairingAmount,
            COALESCE(pckgmtch.MaxMatchPerDay,0) as MaxMatchPerDay,
            COALESCE(pckgmtch.VoucherOnNthPair,0) as VoucherOnNthPair,

            COALESCE(pckgrbt.RebatesMaintainingBal,0) as RebatesMaintainingBal,
            COALESCE(pckgrbt.PersonalRebatesPercent,0) as PersonalRebatesPercent,
            COALESCE(pckgrbt.RebateLevel1Percent,0) as RebateLevel1Percent,
            COALESCE(pckgrbt.RebateLevel2Percent,0) as RebateLevel2Percent,
            COALESCE(pckgrbt.RebateLevel3Percent,0) as RebateLevel3Percent,
            COALESCE(pckgrbt.RebateLevel4Percent,0) as RebateLevel4Percent,
            COALESCE(pckgrbt.RebateLevel5Percent,0) as RebateLevel5Percent,
            COALESCE(pckgrbt.RebateLevel6Percent,0) as RebateLevel6Percent,
            COALESCE(pckgrbt.RebateLevel7Percent,0) as RebateLevel7Percent,
            COALESCE(pckgrbt.RebateLevel8Percent,0) as RebateLevel8Percent,
            COALESCE(pckgrbt.RebateLevel9Percent,0) as RebateLevel9Percent,

            COALESCE(pckgrank.RankLevel1,0) as RankLevel1,
            COALESCE(pckgrank.RankLevel1APPRV,0) as RankLevel1APPRV,
            COALESCE(pckgrank.RankLevel1AGPRV,0) as RankLevel1AGPRV,
            COALESCE(pckgrank.RankLevel1Percent,0) as RankLevel1Percent,
            COALESCE(pckgrank.RankLevel2,0) as RankLevel2,
            COALESCE(pckgrank.RankLevel2APPRV,0) as RankLevel2APPRV,
            COALESCE(pckgrank.RankLevel2AGPRV,0) as RankLevel2AGPRV,
            COALESCE(pckgrank.RankLevel2Percent,0) as RankLevel2Percent,
            COALESCE(pckgrank.RankLevel3,0) as RankLevel3,
            COALESCE(pckgrank.RankLevel3APPRV,0) as RankLevel3APPRV,
            COALESCE(pckgrank.RankLevel3AGPRV,0) as RankLevel3AGPRV,
            COALESCE(pckgrank.RankLevel3Percent,0) as RankLevel3Percent,
            COALESCE(pckgrank.RankLevel4,0) as RankLevel4,
            COALESCE(pckgrank.RankLevel4APPRV,0) as RankLevel4APPRV,
            COALESCE(pckgrank.RankLevel4AGPRV,0) as RankLevel4AGPRV,
            COALESCE(pckgrank.RankLevel4Percent,0) as RankLevel4Percent,
            COALESCE(pckgrank.RankLevel5,0) as RankLevel5,
            COALESCE(pckgrank.RankLevel5APPRV,0) as RankLevel5APPRV,
            COALESCE(pckgrank.RankLevel5AGPRV,0) as RankLevel5AGPRV,
            COALESCE(pckgrank.RankLevel5Percent,0) as RankLevel5Percent,
            COALESCE(pckgrank.RankLevel6,0) as RankLevel6,
            COALESCE(pckgrank.RankLevel6APPRV,0) as RankLevel6APPRV,
            COALESCE(pckgrank.RankLevel6AGPRV,0) as RankLevel6AGPRV,
            COALESCE(pckgrank.RankLevel6Percent,0) as RankLevel6Percent,
            COALESCE(pckgrank.RankLevel7,0) as RankLevel7,
            COALESCE(pckgrank.RankLevel7APPRV,0) as RankLevel7APPRV,
            COALESCE(pckgrank.RankLevel7AGPRV,0) as RankLevel7AGPRV,
            COALESCE(pckgrank.RankLevel7Percent,0) as RankLevel7Percent,
            COALESCE(pckgrank.RankLevel8,0) as RankLevel8,
            COALESCE(pckgrank.RankLevel8APPRV,0) as RankLevel8APPRV,
            COALESCE(pckgrank.RankLevel8AGPRV,0) as RankLevel8AGPRV,
            COALESCE(pckgrank.RankLevel8Percent,0) as RankLevel8Percent,
            COALESCE(pckgrank.RankLevel9,0) as RankLevel9,
            COALESCE(pckgrank.RankLevel9APPRV,0) as RankLevel9APPRV,
            COALESCE(pckgrank.RankLevel9AGPRV,0) as RankLevel9AGPRV,
            COALESCE(pckgrank.RankLevel9Percent,0) as RankLevel9Percent,

            COALESCE(pckg.Status,'') as Status,

            COALESCE(pckg.ApprovedByID,0) as ApprovedByID,
            COALESCE(pckg.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(pckg.CreatedByID,0) as CreatedByID,
            COALESCE(pckg.DateTimeCreated,'') as DateTimeCreated,

            COALESCE(pckg.UpdatedByID,0) as UpdatedByID,
            COALESCE(pckg.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('pckg.PackageID',$PackageID)
        ->first();

      return $info;

    }

    public function doSaveUpdatePackage($data){

      $TODAY = date("Y-m-d H:i:s");

      $Misc  = new Misc();

      $PackageID = $data['PackageID'];

      $Package = $data['Package'];
      $Description = $data['Description'];

      $PackagePrice = $data['PackagePrice'];
      $ProductWorth = $data['ProductWorth'];
      $SponsorCommission = $data['SponsorCommission'];

      $ProductID = $data['ProductID'];

      $PackageColor = $data['PackageColor'];

      $Status = $data['Status'];

      $ApprovedByID = $data['ApprovedByID'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      if($PackageID > 0){
        DB::table('package')
          ->where('PackageID',$PackageID)
          ->update([
            'Package' => trim($Package),
            'Description' => trim($Description),
            
            'PackagePrice' => $PackagePrice,
            'ProductWorth'=> $ProductWorth,
            'SponsorCommission' => $SponsorCommission,

            'ProductID' => $ProductID,
            'PackageColor' => $PackageColor,

            'Status'=> $Status,

            'ApprovedByID' => $ApprovedByID,
            'ApprovedDateTime' => $TODAY,
           
            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $PackageID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Package";
        $logData['TransType'] = "Update Package Information";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        $PackageID =  DB::table('package')
          ->insertGetId([
            'Package' => trim($Package),
            'Description' => trim($Description),
            
            'PackagePrice' => $PackagePrice,
            'ProductWorth'=> $ProductWorth,
            'SponsorCommission' => $SponsorCommission,

            'ProductID' => $ProductID,
            'PackageColor' => $PackageColor,

            'Status'=> $Status,

            'ApprovedByID' => $ApprovedByID,
            'ApprovedDateTime' => $TODAY,
           
            'CreatedByID' => $CreatedByID,
            'DateTimeCreated' =>$TODAY,

            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
            
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $PackageID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "Package";
        $logData['TransType'] = "New Package";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);
      }

      return $PackageID;
    }

    public function doSaveUpdatePackageEntry($data){

      $TODAY = date("Y-m-d H:i:s");

      $Misc  = new Misc();

      $PackageID = $data['PackageID'];

      $NoOfEntryShare = $data['NoOfEntryShare'];
      $EntryShareAmount = $data['EntryShareAmount'];
      $MaxShareAmount = $data['MaxShareAmount'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      //Check if Entry exist
      $info = DB::table('packageentry')
        ->where('PackageID',$PackageID)
        ->first();

      if(isset($info)){

        DB::table('packageentry')
          ->where('PackageID',$PackageID)
          ->update([
            'NoOfEntryShare' => $NoOfEntryShare,
            'EntryShareAmount' => $EntryShareAmount,
            'MaxShareAmount' => $MaxShareAmount,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
        ]);

      }else{

        $EntryID =  DB::table('packageentry')
          ->insertGetId([
            'PackageID' => $PackageID,
            'NoOfEntryShare' => $NoOfEntryShare,
            'EntryShareAmount' => $EntryShareAmount,
            'MaxShareAmount' => $MaxShareAmount,
            'CreatedByID' => $CreatedByID,
            'DateTimeCreated' =>$TODAY,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
        ]);
      }

      return $PackageID;
    }

    public function doSaveUpdatePackageMatchingComm($data){

      $TODAY = date("Y-m-d H:i:s");

      $Misc  = new Misc();

      $PackageID = $data['PackageID'];

      $RequiredBPV = $data['RequiredBPV'];
      $PairingAmount = $data['PairingAmount'];
      $MaxMatchPerDay = $data['MaxMatchPerDay'];
      $VoucherOnNthPair = $data['VoucherOnNthPair'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      //Check if Entry exist
      $info = DB::table('packagematchingcomm')
        ->where('PackageID',$PackageID)
        ->first();

      if(isset($info)){

        DB::table('packagematchingcomm')
          ->where('PackageID',$PackageID)
          ->update([
            'RequiredBPV' => $RequiredBPV,
            'PairingAmount' => $PairingAmount,
            'MaxMatchPerDay' => $MaxMatchPerDay,
            'VoucherOnNthPair' => $VoucherOnNthPair,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
        ]);

      }else{

        $EntryID =  DB::table('packagematchingcomm')
          ->insertGetId([
            'PackageID' => $PackageID,
            'RequiredBPV' => $RequiredBPV,
            'PairingAmount' => $PairingAmount,
            'MaxMatchPerDay' => $MaxMatchPerDay,
            'VoucherOnNthPair' => $VoucherOnNthPair,
            'CreatedByID' => $CreatedByID,
            'DateTimeCreated' =>$TODAY,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
        ]);
      }

      return $PackageID;
    }

    public function doSaveUpdatePackageRebates($data){

      $TODAY = date("Y-m-d H:i:s");

      $Misc  = new Misc();

      $PackageID = $data['PackageID'];

      $RebatesMaintainingBal = $data['RebatesMaintainingBal'];
      $PersonalRebatesPercent = $data['PersonalRebatesPercent'];
      $RebateLevel1Percent = $data['RebateLevel1Percent'];
      $RebateLevel2Percent = $data['RebateLevel2Percent'];
      $RebateLevel3Percent = $data['RebateLevel3Percent'];
      $RebateLevel4Percent = $data['RebateLevel4Percent'];
      $RebateLevel5Percent = $data['RebateLevel5Percent'];
      $RebateLevel6Percent = $data['RebateLevel6Percent'];
      $RebateLevel7Percent = $data['RebateLevel7Percent'];
      $RebateLevel8Percent = $data['RebateLevel8Percent'];
      $RebateLevel9Percent = $data['RebateLevel9Percent'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      //Check if Entry exist
      $info = DB::table('packagerebates')
        ->where('PackageID',$PackageID)
        ->first();

      if(isset($info)){

        DB::table('packagerebates')
          ->where('PackageID',$PackageID)
          ->update([
            'RebatesMaintainingBal' => $RebatesMaintainingBal,
            'PersonalRebatesPercent' => $PersonalRebatesPercent,
            'RebateLevel1Percent' => $RebateLevel1Percent,
            'RebateLevel2Percent' => $RebateLevel2Percent,
            'RebateLevel3Percent' => $RebateLevel3Percent,
            'RebateLevel4Percent' => $RebateLevel4Percent,
            'RebateLevel5Percent' => $RebateLevel5Percent,
            'RebateLevel6Percent' => $RebateLevel6Percent,
            'RebateLevel7Percent' => $RebateLevel7Percent,
            'RebateLevel8Percent' => $RebateLevel8Percent,
            'RebateLevel9Percent' => $RebateLevel9Percent,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
        ]);

      }else{

        $EntryID =  DB::table('packagerebates')
          ->insertGetId([
            'PackageID' => $PackageID,
            'RebatesMaintainingBal' => $RebatesMaintainingBal,
            'PersonalRebatesPercent' => $PersonalRebatesPercent,
            'RebateLevel1Percent' => $RebateLevel1Percent,
            'RebateLevel2Percent' => $RebateLevel2Percent,
            'RebateLevel3Percent' => $RebateLevel3Percent,
            'RebateLevel4Percent' => $RebateLevel4Percent,
            'RebateLevel5Percent' => $RebateLevel5Percent,
            'RebateLevel6Percent' => $RebateLevel6Percent,
            'RebateLevel7Percent' => $RebateLevel7Percent,
            'RebateLevel8Percent' => $RebateLevel8Percent,
            'RebateLevel9Percent' => $RebateLevel9Percent,
            'CreatedByID' => $CreatedByID,
            'DateTimeCreated' =>$TODAY,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
        ]);
      }

      return $PackageID;
    }

    public function doSaveUpdatePackageRanks($data){

      $TODAY = date("Y-m-d H:i:s");

      $Misc  = new Misc();

      $PackageID = $data['PackageID'];

      $RankLevel1 = $data['RankLevel1'];
      $RankLevel1APPRV = $data['RankLevel1APPRV'];
      $RankLevel1AGPRV = $data['RankLevel1AGPRV'];
      $RankLevel1Percent = $data['RankLevel1Percent'];

      $RankLevel2 = $data['RankLevel2'];
      $RankLevel2APPRV = $data['RankLevel2APPRV'];
      $RankLevel2AGPRV = $data['RankLevel2AGPRV'];
      $RankLevel2Percent = $data['RankLevel2Percent'];

      $RankLevel3 = $data['RankLevel3'];
      $RankLevel3APPRV = $data['RankLevel3APPRV'];
      $RankLevel3AGPRV = $data['RankLevel3AGPRV'];
      $RankLevel3Percent = $data['RankLevel3Percent'];

      $RankLevel4 = $data['RankLevel4'];
      $RankLevel4APPRV = $data['RankLevel4APPRV'];
      $RankLevel4AGPRV = $data['RankLevel4AGPRV'];
      $RankLevel4Percent = $data['RankLevel4Percent'];

      $RankLevel5 = $data['RankLevel5'];
      $RankLevel5APPRV = $data['RankLevel5APPRV'];
      $RankLevel5AGPRV = $data['RankLevel5AGPRV'];
      $RankLevel5Percent = $data['RankLevel5Percent'];

      $RankLevel6 = $data['RankLevel6'];
      $RankLevel6APPRV = $data['RankLevel6APPRV'];
      $RankLevel6AGPRV = $data['RankLevel6AGPRV'];
      $RankLevel6Percent = $data['RankLevel6Percent'];

      $RankLevel7 = $data['RankLevel7'];
      $RankLevel7APPRV = $data['RankLevel7APPRV'];
      $RankLevel7AGPRV = $data['RankLevel7AGPRV'];
      $RankLevel7Percent = $data['RankLevel7Percent'];

      $RankLevel8 = $data['RankLevel8'];
      $RankLevel8APPRV = $data['RankLevel8APPRV'];
      $RankLevel8AGPRV = $data['RankLevel8AGPRV'];
      $RankLevel8Percent = $data['RankLevel8Percent'];

      $RankLevel9 = $data['RankLevel9'];
      $RankLevel9APPRV = $data['RankLevel9APPRV'];
      $RankLevel9AGPRV = $data['RankLevel9AGPRV'];
      $RankLevel9Percent = $data['RankLevel9Percent'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      //Check if Entry exist
      $info = DB::table('packagerank')
        ->where('PackageID',$PackageID)
        ->first();

      if(isset($info)){

        DB::table('packagerank')
          ->where('PackageID',$PackageID)
          ->update([
            'RankLevel1' => $RankLevel1,
            'RankLevel1APPRV' => $RankLevel1APPRV,
            'RankLevel1AGPRV' => $RankLevel1AGPRV,
            'RankLevel1Percent' => $RankLevel1Percent,

            'RankLevel2' => $RankLevel2,
            'RankLevel2APPRV' => $RankLevel2APPRV,
            'RankLevel2AGPRV' => $RankLevel2AGPRV,
            'RankLevel2Percent' => $RankLevel2Percent,

            'RankLevel3' => $RankLevel3,
            'RankLevel3APPRV' => $RankLevel3APPRV,
            'RankLevel3AGPRV' => $RankLevel3AGPRV,
            'RankLevel3Percent' => $RankLevel3Percent,

            'RankLevel4' => $RankLevel4,
            'RankLevel4APPRV' => $RankLevel4APPRV,
            'RankLevel4AGPRV' => $RankLevel4AGPRV,
            'RankLevel4Percent' => $RankLevel4Percent,

            'RankLevel5' => $RankLevel5,
            'RankLevel5APPRV' => $RankLevel5APPRV,
            'RankLevel5AGPRV' => $RankLevel5AGPRV,
            'RankLevel5Percent' => $RankLevel5Percent,

            'RankLevel6' => $RankLevel6,
            'RankLevel6APPRV' => $RankLevel6APPRV,
            'RankLevel6AGPRV' => $RankLevel6AGPRV,
            'RankLevel6Percent' => $RankLevel6Percent,

            'RankLevel7' => $RankLevel7,
            'RankLevel7APPRV' => $RankLevel7APPRV,
            'RankLevel7AGPRV' => $RankLevel7AGPRV,
            'RankLevel7Percent' => $RankLevel7Percent,

            'RankLevel8' => $RankLevel8,
            'RankLevel8APPRV' => $RankLevel8APPRV,
            'RankLevel8AGPRV' => $RankLevel8AGPRV,
            'RankLevel8Percent' => $RankLevel8Percent,

            'RankLevel9' => $RankLevel9,
            'RankLevel9APPRV' => $RankLevel9APPRV,
            'RankLevel9AGPRV' => $RankLevel9AGPRV,
            'RankLevel9Percent' => $RankLevel9Percent,

            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
        ]);

      }else{

        $EntryID =  DB::table('packagerank')
          ->insertGetId([
            'PackageID' => $PackageID,
            'RankLevel1' => $RankLevel1,
            'RankLevel1APPRV' => $RankLevel1APPRV,
            'RankLevel1AGPRV' => $RankLevel1AGPRV,
            'RankLevel1Percent' => $RankLevel1Percent,

            'RankLevel2' => $RankLevel2,
            'RankLevel2APPRV' => $RankLevel2APPRV,
            'RankLevel2AGPRV' => $RankLevel2AGPRV,
            'RankLevel2Percent' => $RankLevel2Percent,

            'RankLevel3' => $RankLevel3,
            'RankLevel3APPRV' => $RankLevel3APPRV,
            'RankLevel3AGPRV' => $RankLevel3AGPRV,
            'RankLevel3Percent' => $RankLevel3Percent,

            'RankLevel4' => $RankLevel4,
            'RankLevel4APPRV' => $RankLevel4APPRV,
            'RankLevel4AGPRV' => $RankLevel4AGPRV,
            'RankLevel4Percent' => $RankLevel4Percent,

            'RankLevel5' => $RankLevel5,
            'RankLevel5APPRV' => $RankLevel5APPRV,
            'RankLevel5AGPRV' => $RankLevel5AGPRV,
            'RankLevel5Percent' => $RankLevel5Percent,

            'RankLevel6' => $RankLevel6,
            'RankLevel6APPRV' => $RankLevel6APPRV,
            'RankLevel6AGPRV' => $RankLevel6AGPRV,
            'RankLevel6Percent' => $RankLevel6Percent,

            'RankLevel7' => $RankLevel7,
            'RankLevel7APPRV' => $RankLevel7APPRV,
            'RankLevel7AGPRV' => $RankLevel7AGPRV,
            'RankLevel7Percent' => $RankLevel7Percent,

            'RankLevel8' => $RankLevel8,
            'RankLevel8APPRV' => $RankLevel8APPRV,
            'RankLevel8AGPRV' => $RankLevel8AGPRV,
            'RankLevel8Percent' => $RankLevel8Percent,

            'RankLevel9' => $RankLevel9,
            'RankLevel9APPRV' => $RankLevel9APPRV,
            'RankLevel9AGPRV' => $RankLevel9AGPRV,
            'RankLevel9Percent' => $RankLevel9Percent,

            'CreatedByID' => $CreatedByID,
            'DateTimeCreated' =>$TODAY,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
        ]);
      }

      return $PackageID;
    }











}
