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
use App\Models\Member;
use App\Models\Package;

class Code extends Model
{

  public function getCodeGenerationBatchList($param)
  {

    $Status = $param['Status'];
    $SearchText = trim($param['SearchText']);
    $Limit = $param['Limit'];
    $PageNo = $param['PageNo'];

    $Package = new Package();
    $param["Status"] = "";
    $param["SearchText"] = "";
    $param["Limit"] = 0;
    $param["PageNo"] = 0;
    $PackageList = $Package->getPackageList($param);

    $strFields = "
              COALESCE(cgb.BatchID,0) as BatchID,
              COALESCE(cgb.BatchNo,'') as BatchNo,
              COALESCE(cgb.DateTimeGenerated,'') as DateTimeGenerated,

              COALESCE(cgb.CenterID,0) as CenterID,
              COALESCE(ctr.CenterNo,'') as CenterNo,
              COALESCE(ctr.Center,'') as Center,
              COALESCE(ctr.TelNo,'') as TelNo,
              COALESCE(ctr.MobileNo,'') as MobileNo,
              COALESCE(ctr.EmailAddress,'') as EmailAddress,
              ";
    if (count($PackageList) > 0) {

      foreach ($PackageList as $package) {
        $strFields = $strFields . " 
                        COALESCE((
                            SELECT cpck.PackageCount
                            FROM codegenerationpackage as cpck
                            WHERE cpck.BatchID = cgb.BatchID
                            AND cpck.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "Count,

                        COALESCE((
                            SELECT cpck.PackagePrice
                            FROM codegenerationpackage as cpck
                            WHERE cpck.BatchID = cgb.BatchID
                            AND cpck.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "Price,

                        COALESCE((
                            SELECT cpck.PackageProductWorth
                            FROM codegenerationpackage as cpck
                            WHERE cpck.BatchID = cgb.BatchID
                            AND cpck.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "ProductWorth,

                        COALESCE((
                            SELECT COUNT(*) 
                            FROM codegeneration 
                            WHERE codegeneration.BatchID = cgb.BatchID
                            AND codegeneration.IssuedToMemberEntryID > 0
                            AND codegeneration.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "IssuedCode,

                        COALESCE((
                            SELECT COUNT(*) 
                            FROM codegeneration 
                            WHERE codegeneration.BatchID = cgb.BatchID
                            AND codegeneration.PackageID = " . $package->PackageID . "
                            AND codegeneration.Status = 'Used'
                          )
                          ,0) as Package" . $package->PackageID . "UsedCode,
          ";
      }
    }

    $strFields =  $strFields . " 
              COALESCE(cgb.TotalGrossAmount,0) as TotalGrossAmount,
              COALESCE(cgb.TotalDiscount,0) as TotalDiscount,
              COALESCE(cgb.TotalAmountDue,0) as TotalAmountDue,
              COALESCE(cgb.AmountPaid,0) as AmountPaid,
              COALESCE(cgb.AmountChange,0) as AmountChange,

              COALESCE(cgb.IsFreeCode,0) as IsFreeCode,
              COALESCE(cgb.Remarks,'') as Remarks,
              COALESCE(cgb.Status,'') as Status,

              COALESCE(cgb.CancelledByID,0) as CancelledByID,
              COALESCE(cnby.Fullname,'') as CancelledBy,
              COALESCE(cgb.CancelledDateTime,'') as CancelledDateTime,
              COALESCE(cgb.CancellationReason,'') as CancellationReason,

              COALESCE(cgb.CreatedByID,0) as CreatedByID,
              COALESCE(ctdby.Fullname,'') as CreatedBy,
              cgb.DateTimeCreated as DateTimeCreated,

              COALESCE(cgb.UpdatedByID,0) as UpdatedByID,
              COALESCE(utdby.Fullname,'') as UpdatedBy,
              cgb.DateTimeUpdated as DateTimeUpdated
          ";

    $query = DB::table('codegenerationbatch as cgb')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'cgb.CenterID')
      ->join('useraccount as ctdby', 'cgb.CreatedByID', '=', 'ctdby.UserAccountID')
      ->join('useraccount as utdby', 'cgb.UpdatedByID', '=', 'utdby.UserAccountID')
      ->leftjoin('useraccount as cnby', 'cgb.CancelledByID', '=', 'cnby.UserAccountID')
      ->selectraw($strFields);

    if ($Status != "") {
      $query->whereraw("COALESCE(cgb.Status,'') = '" . $Status . "'");
    }

    if ($SearchText != '') {
      $query->whereraw(
        "CONCAT(
              COALESCE(cgb.BatchNo,''),' ',
              COALESCE(ctr.Center,'')
            ) like '%" . str_replace("'", "''", $SearchText) . "%'"
      );
    }

    if ($Limit > 0) {
      $query->limit($Limit);
      $query->offset(($PageNo - 1) * $Limit);
    }

    $query->orderByraw("COALESCE(cgb.DateTimeGenerated,'')", "DESC");
    $query->orderByraw("COALESCE(cgb.BatchNo,'')", "DESC");

    $list = $query->get();

    return $list;
  }

  public function getCodeGenerationBatchInfo($BatchID)
  {

    $Package = new Package();
    $param["Status"] = "";
    $param["SearchText"] = "";
    $param["Limit"] = 0;
    $param["PageNo"] = 0;
    $PackageList = $Package->getPackageList($param);

    $strFields = "
              COALESCE(cgb.BatchID,0) as BatchID,
              COALESCE(cgb.BatchNo,'') as BatchNo,
              COALESCE(cgb.DateTimeGenerated,'') as DateTimeGenerated,

              COALESCE(cgb.CenterID,0) as CenterID,
              COALESCE(ctr.CenterNo,'') as CenterNo,
              COALESCE(ctr.Center,'') as Center,
              COALESCE(ctr.TelNo,'') as TelNo,
              COALESCE(ctr.MobileNo,'') as MobileNo,
              COALESCE(ctr.EmailAddress,'') as EmailAddress,
              ";
    if (count($PackageList) > 0) {

      foreach ($PackageList as $package) {
        $strFields = $strFields . " 
                        COALESCE((
                            SELECT cpck.PackageCount
                            FROM codegenerationpackage as cpck
                            WHERE cpck.BatchID = cgb.BatchID
                            AND cpck.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "Count,

                        COALESCE((
                            SELECT cpck.PackagePrice
                            FROM codegenerationpackage as cpck
                            WHERE cpck.BatchID = cgb.BatchID
                            AND cpck.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "Price,

                        COALESCE((
                            SELECT cpck.PackageProductWorth
                            FROM codegenerationpackage as cpck
                            WHERE cpck.BatchID = cgb.BatchID
                            AND cpck.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "ProductWorth,

                        COALESCE((
                            SELECT COUNT(*) 
                            FROM codegeneration 
                            WHERE codegeneration.BatchID = cgb.BatchID
                            AND codegeneration.IssuedToMemberEntryID > 0
                            AND codegeneration.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "IssuedCode,

                        COALESCE((
                            SELECT COUNT(*) 
                            FROM codegeneration 
                            WHERE codegeneration.BatchID = cgb.BatchID
                            AND codegeneration.PackageID = " . $package->PackageID . "
                            AND codegeneration.Status = 'Used'
                          )
                          ,0) as Package" . $package->PackageID . "UsedCode,
          ";
      }
    }

    $strFields =  $strFields . " 
              COALESCE(cgb.TotalGrossAmount,0) as TotalGrossAmount,
              COALESCE(cgb.TotalDiscount,0) as TotalDiscount,
              COALESCE(cgb.TotalAmountDue,0) as TotalAmountDue,
              COALESCE(cgb.AmountPaid,0) as AmountPaid,
              COALESCE(cgb.AmountChange,0) as AmountChange,

              COALESCE(cgb.IsFreeCode,0) as IsFreeCode,
              COALESCE(cgb.Remarks,'') as Remarks,
              COALESCE(cgb.Status,'') as Status,

              COALESCE(cgb.CancelledByID,0) as CancelledByID,
              COALESCE(cnby.Fullname,'') as CancelledBy,
              COALESCE(cgb.CancelledDateTime,'') as CancelledDateTime,
              COALESCE(cgb.CancellationReason,'') as CancellationReason,

              COALESCE(cgb.CreatedByID,0) as CreatedByID,
              COALESCE(ctdby.Fullname,'') as CreatedBy,
              cgb.DateTimeCreated as DateTimeCreated,

              COALESCE(cgb.UpdatedByID,0) as UpdatedByID,
              COALESCE(utdby.Fullname,'') as UpdatedBy,
              cgb.DateTimeUpdated as DateTimeUpdated
          ";

    $info = DB::table('codegenerationbatch as cgb')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'cgb.CenterID')
      ->join('useraccount as ctdby', 'cgb.CreatedByID', '=', 'ctdby.UserAccountID')
      ->join('useraccount as utdby', 'cgb.UpdatedByID', '=', 'utdby.UserAccountID')
      ->leftjoin('useraccount as cnby', 'cgb.CancelledByID', '=', 'cnby.UserAccountID')
      ->selectraw($strFields)
      ->where('cgb.BatchID', $BatchID)
      ->first();

    return $info;
  }

  public function getCodeGenerationInfoByBatchNo($BatchNo)
  {

    $Package = new Package();
    $param["Status"] = "";
    $param["SearchText"] = "";
    $param["Limit"] = 0;
    $param["PageNo"] = 0;
    $PackageList = $Package->getPackageList($param);

    $strFields = "
              COALESCE(cgb.BatchID,0) as BatchID,
              COALESCE(cgb.BatchNo,'') as BatchNo,
              COALESCE(cgb.DateTimeGenerated,'') as DateTimeGenerated,

              COALESCE(cgb.CenterID,0) as CenterID,
              COALESCE(ctr.CenterNo,'') as CenterNo,
              COALESCE(ctr.Center,'') as Center,
              COALESCE(ctr.TelNo,'') as TelNo,
              COALESCE(ctr.MobileNo,'') as MobileNo,
              COALESCE(ctr.EmailAddress,'') as EmailAddress,
              ";
    if (count($PackageList) > 0) {

      foreach ($PackageList as $package) {
        $strFields = $strFields . " 
                        COALESCE((
                            SELECT cpck.PackageCount
                            FROM codegenerationpackage as cpck
                            WHERE cpck.BatchID = cgb.BatchID
                            AND cpck.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "Count,

                        COALESCE((
                            SELECT cpck.PackagePrice
                            FROM codegenerationpackage as cpck
                            WHERE cpck.BatchID = cgb.BatchID
                            AND cpck.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "Price,

                        COALESCE((
                            SELECT cpck.PackageProductWorth
                            FROM codegenerationpackage as cpck
                            WHERE cpck.BatchID = cgb.BatchID
                            AND cpck.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "ProductWorth,

                        COALESCE((
                            SELECT COUNT(*) 
                            FROM codegeneration 
                            WHERE codegeneration.BatchID = cgb.BatchID
                            AND codegeneration.IssuedToMemberEntryID > 0
                            AND codegeneration.PackageID = " . $package->PackageID . "
                          )
                          ,0) as Package" . $package->PackageID . "IssuedCode,

                        COALESCE((
                            SELECT COUNT(*) 
                            FROM codegeneration 
                            WHERE codegeneration.BatchID = cgb.BatchID
                            AND codegeneration.PackageID = " . $package->PackageID . "
                            AND codegeneration.Status = 'Used'
                          )
                          ,0) as Package" . $package->PackageID . "UsedCode,
          ";
      }
    }

    $strFields =  $strFields . " 
              COALESCE(cgb.TotalGrossAmount,0) as TotalGrossAmount,
              COALESCE(cgb.TotalDiscount,0) as TotalDiscount,
              COALESCE(cgb.TotalAmountDue,0) as TotalAmountDue,
              COALESCE(cgb.AmountPaid,0) as AmountPaid,
              COALESCE(cgb.AmountChange,0) as AmountChange,

              COALESCE(cgb.IsFreeCode,0) as IsFreeCode,
              COALESCE(cgb.Remarks,'') as Remarks,
              COALESCE(cgb.Status,'') as Status,

              COALESCE(cgb.CancelledByID,0) as CancelledByID,
              COALESCE(cnby.Fullname,'') as CancelledBy,
              COALESCE(cgb.CancelledDateTime,'') as CancelledDateTime,
              COALESCE(cgb.CancellationReason,'') as CancellationReason,

              COALESCE(cgb.CreatedByID,0) as CreatedByID,
              COALESCE(ctdby.Fullname,'') as CreatedBy,
              cgb.DateTimeCreated as DateTimeCreated,

              COALESCE(cgb.UpdatedByID,0) as UpdatedByID,
              COALESCE(utdby.Fullname,'') as UpdatedBy,
              cgb.DateTimeUpdated as DateTimeUpdated
          ";

    $info = DB::table('codegenerationbatch as cgb')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'cgb.CenterID')
      ->join('useraccount as ctdby', 'cgb.CreatedByID', '=', 'ctdby.UserAccountID')
      ->join('useraccount as utdby', 'cgb.UpdatedByID', '=', 'utdby.UserAccountID')
      ->leftjoin('useraccount as cnby', 'cgb.CancelledByID', '=', 'cnby.UserAccountID')
      ->selectraw($strFields)
      ->where('cgb.BatchNo', $BatchNo)
      ->first();

    return $info;
  }

  public function doSaveCodeGenerationBatch($data)
  {

    $Misc  = new Misc();
    $TODAY = date("Y-m-d H:i:s");

    $BatchID = $data['BatchID'];
    $BatchNo = $data['BatchNo'];

    $CenterID = $data['CenterID'];

    $TotalGrossAmount = $data['TotalGrossAmount'];
    $TotalDiscount = $data['TotalDiscount'];
    $TotalAmountDue = $data['TotalAmountDue'];
    $AmountPaid = $data['AmountPaid'];
    $AmountChange = $data['AmountChange'];

    $IsFreeCode = $data['IsFreeCode'];
    $Remarks = $data['Remarks'];
    $Status = $data['Status'];

    $CreatedByID = $data['CreatedByID'];
    $UpdatedByID = $data['UpdatedByID'];

    //Save
    if ($BatchID > 0) {

      DB::table('codegenerationbatch')
        ->where('BatchID', $BatchID)
        ->update([
          'CenterID' => $CenterID,

          'TotalGrossAmount' => $TotalGrossAmount,
          'TotalDiscount' => $TotalDiscount,
          'TotalAmountDue' => $TotalAmountDue,
          'AmountPaid' => $AmountPaid,
          'AmountChange' => $AmountChange,

          'IsFreeCode' => $IsFreeCode,
          'Remarks' => $Remarks,
          'Status' => $Status,

          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' => $TODAY
        ]);

      //Save Transaction Log
      $logData['TransRefID'] = $BatchID;
      $logData['TransactedByID'] = $UpdatedByID;
      $logData['ModuleType'] = "Code Generation Batch";
      $logData['TransType'] = "Update Code Generation Batch Information";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    } else {

      $BatchNo = $Misc->GetSettingsNextBatchNo();

      $BatchID =  DB::table('codegenerationbatch')
        ->insertGetId([

          'BatchNo' => $BatchNo,
          'DateTimeGenerated' => $TODAY,

          'CenterID' => $CenterID,

          'TotalGrossAmount' => $TotalGrossAmount,
          'TotalDiscount' => $TotalDiscount,
          'TotalAmountDue' => $TotalAmountDue,
          'AmountPaid' => $AmountPaid,
          'AmountChange' => $AmountChange,

          'IsFreeCode' => $IsFreeCode,
          'Remarks' => $Remarks,
          'Status' => $Status,

          'CreatedByID' => $CreatedByID,
          'DateTimeCreated' => $TODAY,
          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' => $TODAY
        ]);

      //Update PO Number counter
      $Misc->SetSettingsNextBatchNo($BatchNo);

      //Save Transaction Log
      $logData['TransRefID'] = $BatchID;
      $logData['TransactedByID'] = Session('ADMIN_ACCOUNT_ID');
      $logData['ModuleType'] = "Code Generation Batch";
      $logData['TransType'] = "New Code Generation Batch";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);

      if ($Status == config('app.STATUS_APPROVED')) {
        $data['BatchID'] = $BatchID;
        $this->doGenerateCode($data);
      }
    }

    return $BatchID;
  }

  public function doApproveCodeGenerationBatch($data)
  {

    $Misc  = new Misc();
    $TODAY = date("Y-m-d H:i:s");

    $BatchID = $data['BatchID'];
    $BronzeCount = $data['BronzeCount'];
    $SilverCount = $data['SilverCount'];
    $GoldCount = $data['GoldCount'];
    $IsFreeCode = $data['IsFreeCode'];

    $UpdatedByID = $data['UpdatedByID'];

    //Save
    if ($BatchID > 0) {

      DB::table('codegenerationbatch')
        ->where('BatchID', $BatchID)
        ->update([
          'Status' => config('app.STATUS_APPROVED'),
          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' => $TODAY
        ]);

      //Save Transaction Log
      $logData['TransRefID'] = $BatchID;
      $logData['TransactedByID'] = $UpdatedByID;
      $logData['ModuleType'] = "Code Generation Batch";
      $logData['TransType'] = "Approve Code Generation Batch";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);

      $data['BatchID'] = $BatchID;
      $this->doGenerateCode($data);
    }

    return $BatchID;
  }

  public function doGenerateCode($data)
  {

    $TODAY = date("Y-m-d H:i:s");
    $Misc = new Misc();

    $BatchID = $data['BatchID'];
    $IsFreeCode = $data['IsFreeCode'];

    $CreatedByID = $data['CreatedByID'];
    $UpdatedByID = $data['UpdatedByID'];

    $PackageList = $data['PackageList'];

    foreach ($PackageList as $package) {

      $PackageID = $package->PackageID;
      $PackageCount = $data["Package" . $package->PackageID . "Count"];
      $PackagePrice = $data["Package" . $package->PackageID . "Price"];
      $PackageProductWorth = $data["Package" . $package->PackageID . "ProductWorth"];

      //code generation package
      $CodePackageID =  DB::table('codegenerationpackage')
        ->insertGetId([
          'BatchID' => $BatchID,
          'PackageID' => $PackageID,
          'PackageCount' => $PackageCount,
          'PackageProductWorth' => $PackageProductWorth,
          'PackagePrice' => $PackagePrice
        ]);

      if ($PackageCount > 0) {
        for ($i = 0; $i < $PackageCount; $i++) {

          $CodeNo = $Misc->GenerateRandomNo(6, 'codegeneration', 'Code');

          $CodeID =  DB::table('codegeneration')
            ->insertGetId([
              'BatchID' => $BatchID,
              'PackageID' => $PackageID,
              'SeriesNo' => ($i + 1),
              'Code' => $CodeNo,
              'IsFreeCode' => $IsFreeCode,
              'Status' => config('app.STATUS_AVAILABLE'),
              'CreatedByID' => $CreatedByID,
              'DateTimeCreated' => $TODAY,
              'UpdatedByID' => $UpdatedByID,
              'DateTimeUpdated' => $TODAY
            ]);

          //Insert Product to Center
          $strSQL = "INSERT INTO codegenerationpackageentry(
                          CodeID, 
                          NoOfEntryShare, 
                          EntryShareAmount, 
                          MaxShareAmount)
                      SELECT 
                        " . $CodeID . " as CodeID, 
                        NoOfEntryShare, 
                        EntryShareAmount, 
                        MaxShareAmount
                      FROM packageentry as pckgentry
                      WHERE PackageID = " . $PackageID .
            " LIMIT 1";
          DB::statement($strSQL);
        }
      }
    }
  }

  public function doCancelCodeGenerationBatch($data)
  {

    $Misc  = new Misc();

    $TODAY = date("Y-m-d H:i:s");
    $BatchID = $data['BatchID'];
    $CancelledByID = $data['CancelledByID'];
    $CancellationReason = $data['CancellationReason'];

    //Save
    if ($BatchID > 0) {
      DB::table('codegenerationbatch')
        ->where('BatchID', $BatchID)
        ->update([
          'CancelledByID' => $CancelledByID,
          'CancelledDateTime' => $TODAY,
          'CancellationReason' => $CancellationReason,
          'Status' => config('app.STATUS_CANCELLED'),
          'UpdatedByID' => $CancelledByID,
          'DateTimeUpdated' => $TODAY
        ]);

      DB::table('codegeneration')
        ->where('BatchID', $BatchID)
        ->update([
          'CancelledByID' => $CancelledByID,
          'CancelledDateTime' => $TODAY,
          'CancellationReason' => $CancellationReason,
          'Status' => config('app.STATUS_CANCELLED'),
          'UpdatedByID' => $CancelledByID,
          'DateTimeUpdated' => $TODAY
        ]);

      //Save Transaction Log
      $logData['TransRefID'] = $BatchID;
      $logData['TransactedByID'] = $CancelledByID;
      $logData['ModuleType'] = "Code Generation Batch";
      $logData['TransType'] = "Cancel Code Generation Batch";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    }

    return $BatchID;
  }

  public function doCancelCode($data)
  {

    $Misc  = new Misc();

    $TODAY = date("Y-m-d H:i:s");
    $CodeID = $data['CodeID'];
    $CancelledByID = $data['CancelledByID'];
    $CancellationReason = $data['CancellationReason'];

    //Save
    if ($CodeID > 0) {
      DB::table('codegeneration')
        ->where('CodeID', $CodeID)
        ->update([
          'CancelledByID' => $CancelledByID,
          'CancelledDateTime' => $TODAY,
          'CancellationReason' => $CancellationReason,
          'Status' => config('app.STATUS_CANCELLED'),
          'UpdatedByID' => $CancelledByID,
          'DateTimeUpdated' => $TODAY
        ]);

      //Save Transaction Log
      $logData['TransRefID'] = $CodeID;
      $logData['TransactedByID'] = $CancelledByID;
      $logData['ModuleType'] = "Code Generation Batch";
      $logData['TransType'] = "Cancel Code";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    }

    return $CodeID;
  }

  public function getCodeGenerationList($param)
  {

    $CenterID = $param['CenterID'];

    $Status = $param['Status'];
    $SearchText = trim($param['SearchText']);
    $Limit = $param['Limit'];
    $PageNo = $param['PageNo'];

    $query = DB::table('codegeneration as cg')
      ->join('codegenerationbatch as cgb', 'cg.BatchID', '=', 'cgb.BatchID')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'cgb.CenterID')
      ->join('package', 'cg.PackageID', '=', 'package.PackageID')
      ->leftjoin('memberentry as mbrissuedto', 'cg.IssuedToMemberEntryID', '=', 'mbrissuedto.EntryID')
      ->leftjoin('member as issuedto', 'issuedto.MemberID', '=', 'mbrissuedto.MemberID')
      ->leftjoin('memberentry as mbrentry', 'cg.CodeID', '=', 'mbrentry.CodeID')
      ->leftjoin('useraccount as iby', 'cg.IssuedByID', '=', 'iby.UserAccountID')
      ->leftjoin('member as usdby', 'usdby.MemberID', '=', 'mbrentry.MemberID')
      ->leftjoin('useraccount as cnby', 'cg.CancelledByID', '=', 'cnby.UserAccountID')
      ->selectraw("
            COALESCE(cg.CodeID,0) as CodeID,

            COALESCE(cg.BatchID,0) as BatchID,
            COALESCE(cgb.BatchNo,'') as BatchNo,
            COALESCE(cgb.DateTimeGenerated,'') as DateTimeGenerated,

            COALESCE(cgb.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(cg.PackageID,0) as PackageID,
            COALESCE(package.Package,'') as Package,
            COALESCE(package.PackagePrice,0) as PackagePrice,

            COALESCE(cg.SeriesNo,'') as SeriesNo,
            COALESCE(cg.Code,'') as Code,

            COALESCE(cg.IssuedToMemberEntryID,0) as IssuedToMemberEntryID,
            COALESCE(mbrissuedto.EntryCode,'') as IssuedToEntryCode,
            COALESCE(mbrissuedto.MemberID,0) as IssuedToMemberID,
            COALESCE(issuedto.MemberNo,'') as IssuedToMemberNo,
            CONCAT(COALESCE(issuedto.FirstName,''),' ',if(COALESCE(issuedto.MiddleName,'') != '', CONCAT(LEFT(COALESCE(issuedto.MiddleName,''),1),'. '),''),COALESCE(issuedto.LastName,'')) as IssuedToMemberName,
            cg.IssuedDateTime,
            COALESCE(cg.IssuedByID,0) as IssuedByID,
            COALESCE(iby.Fullname,'') as IssuedBy,
            COALESCE(cg.IssuedRemarks,'') as IssuedRemarks,

            COALESCE(mbrentry.EntryID,0) as EntryID,
            COALESCE(mbrentry.EntryCode,'') as EntryCode,
            COALESCE(mbrentry.MemberID,0) as UsedByMemberID,
            COALESCE(usdby.MemberNo,'') as UsedByMemberNo,
            CONCAT(COALESCE(usdby.FirstName,''),' ',if(COALESCE(usdby.MiddleName,'') != '', CONCAT(LEFT(COALESCE(usdby.MiddleName,''),1),'. '),''),COALESCE(usdby.LastName,'')) as UsedByMemberName,

            COALESCE(cgb.CancelledByID,0) as CancelledByID,
            COALESCE(cnby.Fullname,'') as CancelledBy,
            COALESCE(cgb.CancelledDateTime,'') as CancelledDateTime,
            COALESCE(cgb.CancellationReason,'') as CancellationReason,

            COALESCE(cg.IsFreeCode,0) as IsFreeCode,
            COALESCE(cg.Status,'') as Status,

            CASE
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_AVAILABLE') . "'  THEN 1
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_APPROVED') . "'  THEN 2
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_USED') . "'  THEN 3
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_CANCELLED') . "'  THEN 4
                ELSE 0
            END as SortOption

          ");

    if ($CenterID > 0) {
      $query->whereraw("COALESCE(cgb.CenterID,0) = " . $CenterID);
    }

    if ($Status != "") {
      $query->whereraw("COALESCE(cg.Status,'') = '" . $Status . "'");
    }

    if ($SearchText != '') {
      $query->whereraw(
        "COALESCE(cg.Code,'') like '%" . str_replace("'", "''", $SearchText) . "%'"
      );
    }

    if ($Limit > 0) {
      $query->limit($Limit);
      $query->offset(($PageNo - 1) * $Limit);
    }

    $query->orderByraw("(CASE
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_AVAILABLE') . "'  THEN 1
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_APPROVED') . "'  THEN 2
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_USED') . "'  THEN 3
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_CANCELLED') . "'  THEN 4
                ELSE 0
            END) ASC");
    $query->orderBy("cg.CodeID", "DESC");

    $list = $query->get();

    return $list;
  }

  public function getCodeGenerationByBatch($BatchID)
  {

    $list = DB::table('codegeneration as cg')
      ->join('codegenerationbatch as cgb', 'cg.BatchID', '=', 'cgb.BatchID')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'cgb.CenterID')
      ->join('package', 'cg.PackageID', '=', 'package.PackageID')
      ->leftjoin('memberentry as mbrissuedto', 'cg.IssuedToMemberEntryID', '=', 'mbrissuedto.EntryID')
      ->leftjoin('member as issuedto', 'issuedto.MemberID', '=', 'mbrissuedto.MemberID')
      ->leftjoin('memberentry as mbrentry', 'cg.CodeID', '=', 'mbrentry.CodeID')
      ->leftjoin('useraccount as iby', 'cg.IssuedByID', '=', 'iby.UserAccountID')
      ->leftjoin('member as usdby', 'usdby.MemberID', '=', 'mbrentry.MemberID')
      ->leftjoin('useraccount as cnby', 'cg.CancelledByID', '=', 'cnby.UserAccountID')
      ->selectraw("
            COALESCE(cg.CodeID,0) as CodeID,

            COALESCE(cg.BatchID,0) as BatchID,
            COALESCE(cgb.BatchNo,'') as BatchNo,
            COALESCE(cgb.DateTimeGenerated,'') as DateTimeGenerated,

            COALESCE(cgb.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(cg.PackageID,0) as PackageID,
            COALESCE(package.Package,'') as Package,
            COALESCE(package.PackagePrice,0) as PackagePrice,

            COALESCE(cg.SeriesNo,'') as SeriesNo,
            COALESCE(cg.Code,'') as Code,

            COALESCE(cg.IssuedToMemberEntryID,0) as IssuedToMemberEntryID,
            COALESCE(mbrissuedto.EntryCode,'') as IssuedToEntryCode,
            COALESCE(mbrissuedto.MemberID,0) as IssuedToMemberID,
            COALESCE(issuedto.MemberNo,'') as IssuedToMemberNo,
            CONCAT(COALESCE(issuedto.FirstName,''),' ',if(COALESCE(issuedto.MiddleName,'') != '', CONCAT(LEFT(COALESCE(issuedto.MiddleName,''),1),'. '),''),COALESCE(issuedto.LastName,'')) as IssuedToMemberName,
            cg.IssuedDateTime,
            COALESCE(cg.IssuedByID,0) as IssuedByID,
            COALESCE(iby.Fullname,'') as IssuedBy,
            COALESCE(cg.IssuedRemarks,'') as IssuedRemarks,

            COALESCE(mbrentry.EntryID,0) as EntryID,
            COALESCE(mbrentry.EntryCode,'') as EntryCode,
            COALESCE(mbrentry.MemberID,0) as UsedByMemberID,
            COALESCE(usdby.MemberNo,'') as UsedByMemberNo,
            CONCAT(COALESCE(usdby.FirstName,''),' ',if(COALESCE(usdby.MiddleName,'') != '', CONCAT(LEFT(COALESCE(usdby.MiddleName,''),1),'. '),''),COALESCE(usdby.LastName,'')) as UsedByMemberName,

            COALESCE(cgb.CancelledByID,0) as CancelledByID,
            COALESCE(cnby.Fullname,'') as CancelledBy,
            COALESCE(cgb.CancelledDateTime,'') as CancelledDateTime,
            COALESCE(cgb.CancellationReason,'') as CancellationReason,

            COALESCE(cg.IsFreeCode,0) as IsFreeCode,
            COALESCE(cg.Status,'') as Status,

            CASE
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_AVAILABLE') . "'  THEN 1
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_APPROVED') . "'  THEN 2
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_USED') . "'  THEN 3
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_CANCELLED') . "'  THEN 4
                ELSE 0
            END as SortOption            
        ")
      ->where('cg.BatchID', $BatchID)
      ->orderBy("cg.CodeID", "ASC")
      ->get();

    return $list;
  }

  public function getCodeGenerationInfo($CodeID)
  {

    $info = DB::table('codegeneration as cg')
      ->join('codegenerationbatch as cgb', 'cg.BatchID', '=', 'cgb.BatchID')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'cgb.CenterID')
      ->join('package', 'cg.PackageID', '=', 'package.PackageID')
      ->leftjoin('memberentry as mbrissuedto', 'cg.IssuedToMemberEntryID', '=', 'mbrissuedto.EntryID')
      ->leftjoin('member as issuedto', 'issuedto.MemberID', '=', 'mbrissuedto.MemberID')
      ->leftjoin('memberentry as mbrentry', 'cg.CodeID', '=', 'mbrentry.CodeID')
      ->leftjoin('useraccount as iby', 'cg.IssuedByID', '=', 'iby.UserAccountID')
      ->leftjoin('member as usdby', 'usdby.MemberID', '=', 'mbrentry.MemberID')
      ->leftjoin('useraccount as cnby', 'cg.CancelledByID', '=', 'cnby.UserAccountID')
      ->selectraw("
            COALESCE(cg.CodeID,0) as CodeID,

            COALESCE(cg.BatchID,0) as BatchID,
            COALESCE(cgb.BatchNo,'') as BatchNo,
            COALESCE(cgb.DateTimeGenerated,'') as DateTimeGenerated,

            COALESCE(cgb.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(cg.PackageID,0) as PackageID,
            COALESCE(package.Package,'') as Package,
            COALESCE(package.PackagePrice,0) as PackagePrice,

            COALESCE(cg.SeriesNo,'') as SeriesNo,
            COALESCE(cg.Code,'') as Code,

            COALESCE(cg.IssuedToMemberEntryID,0) as IssuedToMemberEntryID,
            COALESCE(mbrissuedto.EntryCode,'') as IssuedToEntryCode,
            COALESCE(mbrissuedto.MemberID,0) as IssuedToMemberID,
            COALESCE(issuedto.MemberNo,'') as IssuedToMemberNo,
            CONCAT(COALESCE(issuedto.FirstName,''),' ',if(COALESCE(issuedto.MiddleName,'') != '', CONCAT(LEFT(COALESCE(issuedto.MiddleName,''),1),'. '),''),COALESCE(issuedto.LastName,'')) as IssuedToMemberName,
            cg.IssuedDateTime,
            COALESCE(cg.IssuedByID,0) as IssuedByID,
            COALESCE(iby.Fullname,'') as IssuedBy,
            COALESCE(cg.IssuedRemarks,'') as IssuedRemarks,

            COALESCE(mbrentry.EntryID,0) as EntryID,
            COALESCE(mbrentry.EntryCode,'') as EntryCode,
            COALESCE(mbrentry.MemberID,0) as UsedByMemberID,
            COALESCE(usdby.MemberNo,'') as UsedByMemberNo,
            CONCAT(COALESCE(usdby.FirstName,''),' ',if(COALESCE(usdby.MiddleName,'') != '', CONCAT(LEFT(COALESCE(usdby.MiddleName,''),1),'. '),''),COALESCE(usdby.LastName,'')) as UsedByMemberName,

            COALESCE(cgb.CancelledByID,0) as CancelledByID,
            COALESCE(cnby.Fullname,'') as CancelledBy,
            COALESCE(cgb.CancelledDateTime,'') as CancelledDateTime,
            COALESCE(cgb.CancellationReason,'') as CancellationReason,

            COALESCE(cg.IsFreeCode,0) as IsFreeCode,
            COALESCE(cg.Status,'') as Status,

            CASE
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_AVAILABLE') . "'  THEN 1
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_APPROVED') . "'  THEN 2
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_USED') . "'  THEN 3
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_CANCELLED') . "'  THEN 4
                ELSE 0
            END as SortOption            
        ")
      ->where("cg.CodeID", $CodeID)
      ->first();

    return $info;
  }

  public function getCodeGenerationInfoByCode($Code)
  {

    $info = DB::table('codegeneration as cg')
      ->join('codegenerationbatch as cgb', 'cg.BatchID', '=', 'cgb.BatchID')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'cgb.CenterID')
      ->join('package', 'cg.PackageID', '=', 'package.PackageID')
      ->leftjoin('memberentry as mbrissuedto', 'cg.IssuedToMemberEntryID', '=', 'mbrissuedto.EntryID')
      ->leftjoin('member as issuedto', 'issuedto.MemberID', '=', 'mbrissuedto.MemberID')
      ->leftjoin('memberentry as mbrentry', 'cg.CodeID', '=', 'mbrentry.CodeID')
      ->leftjoin('useraccount as iby', 'cg.IssuedByID', '=', 'iby.UserAccountID')
      ->leftjoin('member as usdby', 'usdby.MemberID', '=', 'mbrentry.MemberID')
      ->leftjoin('useraccount as cnby', 'cg.CancelledByID', '=', 'cnby.UserAccountID')
      ->selectraw("
            COALESCE(cg.CodeID,0) as CodeID,

            COALESCE(cg.BatchID,0) as BatchID,
            COALESCE(cgb.BatchNo,'') as BatchNo,
            COALESCE(cgb.DateTimeGenerated,'') as DateTimeGenerated,

            COALESCE(cgb.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(cg.PackageID,0) as PackageID,
            COALESCE(package.Package,'') as Package,
            COALESCE(package.PackagePrice,0) as PackagePrice,

            COALESCE(cg.SeriesNo,'') as SeriesNo,
            COALESCE(cg.Code,'') as Code,

            COALESCE(cg.IssuedToMemberEntryID,0) as IssuedToMemberEntryID,
            COALESCE(mbrissuedto.EntryCode,'') as IssuedToEntryCode,
            COALESCE(mbrissuedto.MemberID,0) as IssuedToMemberID,
            COALESCE(issuedto.MemberNo,'') as IssuedToMemberNo,
            CONCAT(COALESCE(issuedto.FirstName,''),' ',if(COALESCE(issuedto.MiddleName,'') != '', CONCAT(LEFT(COALESCE(issuedto.MiddleName,''),1),'. '),''),COALESCE(issuedto.LastName,'')) as IssuedToMemberName,
            cg.IssuedDateTime,
            COALESCE(cg.IssuedByID,0) as IssuedByID,
            COALESCE(iby.Fullname,'') as IssuedBy,
            COALESCE(cg.IssuedRemarks,'') as IssuedRemarks,

            COALESCE(mbrentry.EntryID,0) as EntryID,
            COALESCE(mbrentry.EntryCode,'') as EntryCode,
            COALESCE(mbrentry.MemberID,0) as UsedByMemberID,
            COALESCE(usdby.MemberNo,'') as UsedByMemberNo,
            CONCAT(COALESCE(usdby.FirstName,''),' ',if(COALESCE(usdby.MiddleName,'') != '', CONCAT(LEFT(COALESCE(usdby.MiddleName,''),1),'. '),''),COALESCE(usdby.LastName,'')) as UsedByMemberName,

            COALESCE(cgb.CancelledByID,0) as CancelledByID,
            COALESCE(cnby.Fullname,'') as CancelledBy,
            COALESCE(cgb.CancelledDateTime,'') as CancelledDateTime,
            COALESCE(cgb.CancellationReason,'') as CancellationReason,

            COALESCE(cg.IsFreeCode,0) as IsFreeCode,
            COALESCE(cg.Status,'') as Status,

            CASE
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_AVAILABLE') . "'  THEN 1
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_APPROVED') . "'  THEN 2
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_USED') . "'  THEN 3
                WHEN COALESCE(cg.Status,'') = '" . config('app.STATUS_CANCELLED') . "'  THEN 4
                ELSE 0
            END as SortOption
        ")
      ->where("cg.Code", $Code)
      ->first();

    return $info;
  }

  public function doIssueCodeGeneration($data)
  {

    $Misc  = new Misc();
    $TODAY = date("Y-m-d H:i:s");

    $CodeID = $data['CodeID'];

    $IssuedToMemberEntryID = $data['IssuedToMemberEntryID'];
    $IssuedRemarks = $data['IssuedRemarks'];

    $IssuedByID = $data['IssuedByID'];

    //Save
    if ($CodeID > 0) {

      DB::table('codegeneration')
        ->where('CodeID', $CodeID)
        ->update([
          'IssuedToMemberEntryID' => $IssuedToMemberEntryID,
          'IssuedByID' => $IssuedByID,
          'IssuedDateTime' => $TODAY,
          'IssuedRemarks' => $IssuedRemarks
        ]);

      //Save Transaction Log
      $logData['TransRefID'] = $CodeID;
      $logData['TransactedByID'] = $IssuedByID;
      $logData['ModuleType'] = "Code Generation";
      $logData['TransType'] = "Issue Code";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    }

    return $CodeID;
  }

  public function IsCodeAvailableByCodeID($CodeID)
  {

    $info = DB::table('codegeneration as cg')
      ->join('codegenerationbatch as cgb', 'cg.BatchID', '=', 'cgb.BatchID')
      ->selectraw("
            COALESCE(cg.CodeID,0) as CodeID,
            COALESCE(cg.Code,'') as Code,
            COALESCE(cg.Status,'') as Status
        ")
      ->whereraw("COALESCE(cg.CodeID,0) = " . $CodeID)
      ->whereraw("COALESCE(cg.IssuedToMemberEntryID,0) > 0")
      ->first();

    $IsCodeAvailable = false;
    if (isset($info)) {
      if ($info->Status == config('app.STATUS_AVAILABLE')) {
        $IsCodeAvailable = true;
      }
    }

    return $IsCodeAvailable;
  }

  public function IsCodeAvailableByCodeNo($Code)
  {

    $info = DB::table('codegeneration as cg')
      ->join('codegenerationbatch as cgb', 'cg.BatchID', '=', 'cgb.BatchID')
      ->selectraw("
            COALESCE(cg.CodeID,0) as CodeID,
            COALESCE(cg.Code,'') as Code,
            COALESCE(cg.Status,'') as Status
        ")
      ->whereraw("COALESCE(cg.Code,'') = " . $Code)
      ->whereraw("COALESCE(cg.IssuedToMemberEntryID,0) > 0")
      ->first();

    if (isset($info)) {
      if ($info->Status == config('app.STATUS_ACTIVE')) {
        return true;
      } else {
        //Check Member Entry Used the Code
        $sinfo = DB::table('memberentry')
          //->where("EntryID",$EntryID)
          ->where("CodeID", $info->CodeID)
          ->first();

        if (isset($sinfo)) {
          return true;
        }
      }
    }

    return false;
  }

  public function doSetCodeAsUsed($data)
  {

    $TODAY = date("Y-m-d H:i:s");
    $EntryID = $data["EntryID"];
    $CodeID = $data["CodeID"];
    $UpdatedByID = $data["UpdatedByID"];

    //Save
    if ($CodeID > 0) {
      DB::table('codegeneration')
        ->where('CodeID', $CodeID)
        ->update([
          'Status' => config('app.STATUS_USED'),
          'UsedByEntryID' => $EntryID,
          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' => $TODAY
        ]);
    }

    return $CodeID;
  }
}
