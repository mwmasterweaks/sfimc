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

use App\Models\Helper;
use App\Models\Email;
use App\Models\Misc;
use App\Models\SMS;
use App\Models\CustomerCart;
use App\Models\Personnel;
use App\Models\Inventory;

class PurchaseOrder extends Model
{

    public function getPOList($param){

      $TODAY = date("Y-m-d H:i:s");

      $CenterID = $param['CenterID'];
      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];
      $IsUnProcessedOnly = $param['IsUnProcessedOnly'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('purchaseorder as po')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'po.CenterID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->leftjoin('useraccount as apvby', 'po.ApprovedByID', '=', 'apvby.UserAccountID')
        ->selectraw("
            COALESCE(po.POID,0) as POID,
            COALESCE(po.PONo,'') as PONo,
            COALESCE(po.PODateTime,'') as PODateTime,

            COALESCE(po.POType,'') as POType,

            COALESCE(po.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,

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

            COALESCE(po.GrossTotal,0) as GrossTotal,
            COALESCE(po.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(po.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(po.Remarks,'') as Remarks,
            COALESCE(po.Status,'') as Status,

            CASE
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE((SELECT ProcessID 
              FROM purchaseorderprocess
              WHERE POID = po.POID
              AND Status = '".config('app.STATUS_APPROVED')."'
              LIMIT 1)
            ,0) as ProcessID,

            COALESCE(po.IsProcessed,0) as IsProcessed,

            COALESCE(po.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(po.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(po.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(po.DateTimeUpdated,'') as DateTimeUpdated

        ");

      if($CenterID > 0){
        $query->whereraw("COALESCE(po.CenterID,0) = ".$CenterID);
      }

      if($IsUnProcessedOnly > 0){
        $query->whereraw("COALESCE(po.IsProcessed,0) = 0");
      }

      if($SearchText != ''){
        $query->whereraw(
            "CONCAT(
            COALESCE(po.PONo,''),' ',
            COALESCE(ctr.Center,''),' ',
            COALESCE(ctr.TelNo,''),' ',
            COALESCE(ctr.EmailAddress,''),' ',
            COALESCE(ctr.MobileNo,''),' ',
            COALESCE(po.Remarks,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Status != ''){
        $query->where("po.Status",$Status);
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("(CASE
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END) ASC");

     $query->orderByraw("COALESCE(po.PODateTime,'') DESC");

      $list = $query->get();

      return $list;

    }

    public function getPOInfo($POID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('purchaseorder as po')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'po.CenterID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->leftjoin('useraccount as apvby', 'po.ApprovedByID', '=', 'apvby.UserAccountID')
        ->selectraw("
            COALESCE(po.POID,0) as POID,
            COALESCE(po.PONo,'') as PONo,
            COALESCE(po.PODateTime,'') as PODateTime,

            COALESCE(po.POType,'') as POType,

            COALESCE(po.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,

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

            COALESCE(po.GrossTotal,0) as GrossTotal,
            COALESCE(po.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(po.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(po.Remarks,'') as Remarks,
            COALESCE(po.Status,'') as Status,

            CASE
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(po.IsProcessed,0) as IsProcessed,

            COALESCE(po.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(po.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(po.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(po.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('po.POID',$POID)
        ->first();

      return $info;

    }

    public function getPOInfoByPONo($PONo){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('purchaseorder as po')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'po.CenterID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->leftjoin('useraccount as apvby', 'po.ApprovedByID', '=', 'apvby.UserAccountID')
        ->selectraw("
            COALESCE(po.POID,0) as POID,
            COALESCE(po.PONo,'') as PONo,
            COALESCE(po.PODateTime,'') as PODateTime,

            COALESCE(po.POType,'') as POType,

            COALESCE(po.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,

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

            COALESCE(po.GrossTotal,0) as GrossTotal,
            COALESCE(po.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(po.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(po.Remarks,'') as Remarks,
            COALESCE(po.Status,'') as Status,

            CASE
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(po.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(po.IsProcessed,0) as IsProcessed,

            COALESCE(po.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(po.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(po.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(po.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('po.PONo','=',$PONo)
        ->first();

      return $info;

    }

    public function doSaveUpdatePO($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $POID = $data['POID'];
      $POType = $data['POType'];

      $CenterID = $data['CenterID'];

      $GrossTotal = $data['GrossTotal'];
      $TotalVoucherPayment = $data['TotalVoucherPayment'];
      $TotalAmountDue = $data['TotalAmountDue'];

      $ApprovedByID = $data['ApprovedByID'];
      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      $Remarks = $data['Remarks'];
      $Status = $data['Status'];

      if($POID > 0){

        if($Status == config('app.STATUS_APPROVED')){
          DB::table('purchaseorder')
            ->where('POID',$POID)
            ->update([

            'POType' => $POType,

            'CenterID' => $CenterID,

            'GrossTotal'=> $GrossTotal,
            'TotalVoucherPayment' => $TotalVoucherPayment,
            'TotalAmountDue' => $TotalAmountDue,

            'Remarks'=> $Remarks,
            'Status'=> $Status,

            'ApprovedByID'=> $ApprovedByID,
            'ApprovedDateTime' =>$TODAY,

            'UpdatedByID'=> $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
          ]);
        }else{
          DB::table('purchaseorder')
            ->where('POID',$POID)
            ->update([

            'POType' => $POType,

            'CenterID' => $CenterID,

            'GrossTotal'=> $GrossTotal,
            'TotalVoucherPayment' => $TotalVoucherPayment,
            'TotalAmountDue' => $TotalAmountDue,

            'Remarks'=> $Remarks,
            'Status'=> $Status,

            'UpdatedByID'=> $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
          ]);
        }

        //Save Transaction Log
        $logData['TransRefID'] = $POID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Purchase Order";
        $logData['TransType'] = "Update Order Information";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        $PONo = $Misc->GenerateRandomNo(6,'purchaseorder','PONo');

        if($Status == config('app.STATUS_APPROVED')){
          $POID =  DB::table('purchaseorder')
            ->insertGetId([

              'POType' => $POType,

              'PONo' => $PONo,
              'PODateTime' => $TODAY,

              'CenterID' => $CenterID,

              'GrossTotal'=> $GrossTotal,
              'TotalVoucherPayment' => $TotalVoucherPayment,
              'TotalAmountDue' => $TotalAmountDue,

              'Remarks'=> $Remarks,
              'Status'=> $Status,

              'ApprovedByID'=> $ApprovedByID,
              'ApprovedDateTime' =>$TODAY,

              'CreatedByID'=> $CreatedByID,
              'DateTimeCreated' =>$TODAY,

              'UpdatedByID'=> $UpdatedByID,
              'DateTimeUpdated' =>$TODAY

            ]);
        }else{
            $POID =  DB::table('purchaseorder')
                ->insertGetId([

                  'POType' => $POType,

                  'PONo' => $PONo,
                  'PODateTime' => $TODAY,

                  'CenterID' => $CenterID,

                  'GrossTotal'=> $GrossTotal,
                  'TotalVoucherPayment' => $TotalVoucherPayment,
                  'TotalAmountDue' => $TotalAmountDue,

                  'Remarks'=> $Remarks,
                  'Status'=> $Status,

                  'CreatedByID'=> $CreatedByID,
                  'DateTimeCreated' =>$TODAY,

                  'UpdatedByID'=> $UpdatedByID,
                  'DateTimeUpdated' =>$TODAY

                ]);
        }

        //Save Transaction Log
        $logData['TransRefID'] = $POID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "Purchase Order";
        $logData['TransType'] = "New Purchase Order";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }

      //Vouchers
      if($POID > 0){

        //Clear PO Voucher
        DB::statement("
            UPDATE membervoucher SET
              UsedByCenterID = NULL
            WHERE VoucherID IN (SELECT VoucherID FROM purchaseordervoucher WHERE POID = ".$POID.")
          ");

        DB::table('purchaseordervoucher')
          ->where('POID', '=',$POID)
          ->delete();

        $VoucherData = $data['VoucherData'];
        if(!empty($VoucherData) && $Status != config('app.STATUS_CANCELLED')){

          for($x=0; $x< count($VoucherData); $x++) {
            $VoucherID = $VoucherData[$x]["VoucherID"];

            $ID =  DB::table('purchaseordervoucher')
                ->insertGetId([
                  'POID' => $POID,
                  'VoucherID' => $VoucherID
                ]);

            //Update voucher Status
            DB::table('membervoucher')
              ->where('VoucherID',$VoucherID)
              ->update([
              'UsedByCenterID' => $CenterID,
            ]);

          }
        }
      }

      $data['POID'] = $POID;
      $RetValue = $this->doSaveUpdatePOItems($data);

      return $POID;

    }

    public function doSaveUpdatePOItems($data){

      $TODAY = date("Y-m-d H:i:s");

      $Product = new Product();

      $POID = $data['POID'];
      $CenterID = $data['CenterID'];
      $POItems = $data['POItems'];
      $POItemsDeleted = $data['POItemsDeleted'];

      if(!empty($POItemsDeleted)){

        //Deleted Supplier Products
        for($x=0; $x< count($POItemsDeleted); $x++) {
          DB::table('purchaseorderitem')
            ->where('POItemID', '=',$POItemsDeleted[$x])
            ->delete();
        }
      }

      if(!empty($POItems)){

        for($x=0; $x< count($POItems); $x++) {
          
          $POItemID = $POItems[$x]["POItemID"];

          $ProductID = $POItems[$x]["ProductID"];
          $Qty = $POItems[$x]["Qty"];
          $Price = $POItems[$x]["Price"];
          $SubTotal = $POItems[$x]["SubTotal"];

          if($POItemID > 0){
              if($ProductID > 0){
                DB::table('purchaseorderitem')
                ->where('POItemID',$POItemID)
                ->update([
                  'POID' => $POID,
                  'ProductID' => $ProductID,
                  'Qty' => $Qty,
                  'Price' => $Price,
                  'SubTotal' => $SubTotal,
                  'DateTimeUpdated' =>$TODAY
                ]);
              }
          }else{
              if($ProductID > 0){
                $POItemID =  DB::table('purchaseorderitem')
                  ->insertGetId([
                      'POID' => $POID,
                      'ProductID' => $ProductID,
                      'Qty' => $Qty,
                      'Price' => $Price,
                      'SubTotal' => $SubTotal,
                      'DateTimeCreated' =>$TODAY,
                      'DateTimeUpdated' =>$TODAY
                  ]);
              }
          }

        }

      }

      return "Success";

    }

    public function getPOItemList($param){

      $TODAY = date("Y-m-d H:i:s");

      $POID = $param['POID'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('purchaseorderitem as poi')
        ->join('product', 'product.ProductID', '=', 'poi.ProductID')
        ->selectraw("
            0 as ProcessItemID,
            COALESCE(poi.POItemID,0) as POItemID,
            COALESCE(poi.POID,0) as POID,
            COALESCE(poi.ProductID,0) as ProductID,
            COALESCE(product.ProductCode,'') as ProductCode,
            COALESCE(product.ProductName,'') as ProductName,
            COALESCE(poi.Qty,0) as Qty,
            COALESCE(product.Measurement,'') as Measurement,
            COALESCE(poi.Price,0) as Price,
            COALESCE(poi.SubTotal,0) as SubTotal
        ")
        ->where('poi.POID',$POID);

      $query->orderBy("product.ProductName","ASC");

      $list = $query->get();

      return $list;

    }

    public function getPOVoucherList($param){

      $TODAY = date("Y-m-d H:i:s");

      $POID = $param['POID'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('purchaseordervoucher as pov')
        ->join('membervoucher as mv', 'pov.VoucherID', '=', 'mv.VoucherID')
        ->selectraw("
            COALESCE(pov.ID,0) as ID,
            COALESCE(pov.POID,0) as POID,
            COALESCE(pov.VoucherID,0) as VoucherID,
            COALESCE(mv.VoucherCode,'') as VoucherCode,
            COALESCE(mv.NthPair,0) as NthPair,
            COALESCE(mv.VoucherAmount,'') as VoucherAmount
        ")
        ->where('pov.POID',$POID);

      $query->orderBy("mv.NthPair","ASC");

      $list = $query->get();

      return $list;

    }

    public function doCancelPO($data){

      $Misc = new Misc();
      $TODAY = date("Y-m-d H:i:s");

      $POID =$data['POID'];
      $CancelledByID = $data['CancelledByID'];
      $Reason = $data['Reason'];

      if($POID > 0){
        DB::table('purchaseorder')
        ->where('POID',$POID)
        ->update([
          'CancelledByID' => $CancelledByID,
          'CancellationReason' => $Reason,
          'Status' => config('app.STATUS_CANCELLED'),
          'DateTimeUpdated' => $TODAY
        ]);

        //Clear PO Voucher
        if($POID > 0){
          DB::statement("
              UPDATE membervoucher SET
                UsedByCenterID = NULL
              WHERE VoucherID IN (SELECT VoucherID FROM purchaseordervoucher WHERE POID = ".$POID.")
            ");
        }

        //Save Transaction Log
        $logData['TransRefID'] = $POID;
        $logData['TransactedByID'] = $CancelledByID;
        $logData['ModuleType'] = "Purchase Order";
        $logData['TransType'] = "Purchase Order Cancelled";
        $logData['Remarks'] = $Reason;
        $Misc->doSaveTransactionLog($logData);

      }

      return $POID;

    }

    public function doProcessPO($POID){

      if($POID > 0){
        DB::table('purchaseorder')
        ->where('POID',$POID)
        ->update([
          'IsProcessed' => 1
        ]);
      }

      return $POID;

    }


}
