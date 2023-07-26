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
use App\Models\PurchaseOrder;

class PurchaseOrderProcess extends Model
{

    public function getPOProcessList($param){

      $TODAY = date("Y-m-d H:i:s");

      $CenterID = $param['CenterID'];
      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];
      $IsUnReceivedOnly = $param['IsUnReceivedOnly'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('purchaseorderprocess as pop')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'pop.CenterID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->leftjoin('purchaseorder as po', 'po.POID', '=', 'pop.POID')
        ->leftjoin('useraccount as apvby', 'pop.ApprovedByID', '=', 'apvby.UserAccountID')
        ->selectraw("

            COALESCE(pop.ProcessID,0) as ProcessID,
            COALESCE(pop.ProcessNo,0) as ProcessNo,
            COALESCE(pop.ProcessDateTime,0) as ProcessDateTime,
            COALESCE(pop.ProcessType,'') as ProcessType,

            COALESCE(pop.ProcessingCenterID,0) as ProcessingCenterID,

            COALESCE(pop.CenterID,0) as CenterID,
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

            COALESCE(pop.POID,0) as POID,
            COALESCE(po.PONo,'') as PONo,
            COALESCE(po.PODateTime,'') as PODateTime,

            COALESCE(pop.GrossTotal,0) as GrossTotal,
            COALESCE(pop.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(pop.TotalDiscountPercent,0) as TotalDiscountPercent,
            COALESCE(pop.TotalDiscount,0) as TotalDiscount,
            COALESCE(pop.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(pop.Remarks,'') as Remarks,
            COALESCE(pop.Status,'') as Status,

            CASE
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE((SELECT ReceiveID 
              FROM purchasereceive
              WHERE ProcessID = pop.ProcessID
              AND Status = '".config('app.STATUS_APPROVED')."'
              LIMIT 1)
            ,0) as ReceiveID,

            COALESCE(pop.IsReceived,0) as IsReceived,

            COALESCE(pop.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(pop.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(pop.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(pop.DateTimeUpdated,'') as DateTimeUpdated

        ");

      if($CenterID > 0){
        $query->whereraw("COALESCE(pop.ProcessingCenterID,0) = ".$CenterID);
      }

      if($IsUnReceivedOnly > 0){
        $query->whereraw("COALESCE(pop.IsReceived,0) = 0");
      }

      if($SearchText != ''){
        $query->whereraw(
            "CONCAT(
            COALESCE(pop.ProcessNo,''),' ',
            COALESCE(po.PONo,''),' ',
            COALESCE(ctr.Center,''),' ',
            COALESCE(ctr.TelNo,''),' ',
            COALESCE(ctr.EmailAddress,''),' ',
            COALESCE(ctr.MobileNo,''),' ',
            COALESCE(pop.Remarks,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Status != ''){
        $query->where("pop.Status",$Status);
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("(CASE
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END) ASC");

     $query->orderByraw("COALESCE(pop.ProcessDateTime,'') DESC");

      $list = $query->get();

      return $list;

    }

    public function getPOProcessInfo($ProcessID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('purchaseorderprocess as pop')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'pop.CenterID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->leftjoin('purchaseorder as po', 'po.POID', '=', 'pop.POID')
        ->leftjoin('useraccount as apvby', 'pop.ApprovedByID', '=', 'apvby.UserAccountID')
        ->selectraw("

            COALESCE(pop.ProcessID,0) as ProcessID,
            COALESCE(pop.ProcessNo,0) as ProcessNo,
            COALESCE(pop.ProcessDateTime,0) as ProcessDateTime,
            COALESCE(pop.ProcessType,'') as ProcessType,

            COALESCE(pop.ProcessingCenterID,0) as ProcessingCenterID,

            COALESCE(pop.CenterID,0) as CenterID,
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

            COALESCE(pop.POID,0) as POID,
            COALESCE(po.PONo,'') as PONo,
            COALESCE(po.PODateTime,'') as PODateTime,

            COALESCE(pop.GrossTotal,0) as GrossTotal,
            COALESCE(pop.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(pop.TotalDiscountPercent,0) as TotalDiscountPercent,
            COALESCE(pop.TotalDiscount,0) as TotalDiscount,
            COALESCE(pop.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(pop.Remarks,'') as Remarks,
            COALESCE(pop.Status,'') as Status,

            CASE
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(pop.IsReceived,0) as IsReceived,

            COALESCE(pop.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(pop.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(pop.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(pop.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('pop.ProcessID',$ProcessID)
        ->first();

      return $info;

    }

    public function getPOProcessInfoByPONo($ProcessNo){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('purchaseorderprocess as pop')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'pop.CenterID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->leftjoin('purchaseorder as po', 'po.POID', '=', 'pop.POID')
        ->leftjoin('useraccount as apvby', 'pop.ApprovedByID', '=', 'apvby.UserAccountID')
        ->selectraw("

            COALESCE(pop.ProcessID,0) as ProcessID,
            COALESCE(pop.ProcessNo,0) as ProcessNo,
            COALESCE(pop.ProcessDateTime,0) as ProcessDateTime,
            COALESCE(pop.ProcessType,'') as ProcessType,

            COALESCE(pop.ProcessingCenterID,0) as ProcessingCenterID,

            COALESCE(pop.CenterID,0) as CenterID,
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

            COALESCE(pop.POID,0) as POID,
            COALESCE(po.PONo,'') as PONo,
            COALESCE(po.PODateTime,'') as PODateTime,

            COALESCE(pop.GrossTotal,0) as GrossTotal,
            COALESCE(pop.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(pop.TotalDiscountPercent,0) as TotalDiscountPercent,
            COALESCE(pop.TotalDiscount,0) as TotalDiscount,
            COALESCE(pop.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(pop.Remarks,'') as Remarks,
            COALESCE(pop.Status,'') as Status,

            CASE
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(pop.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,
            
            COALESCE(pop.IsReceived,0) as IsReceived,

            COALESCE(pop.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(pop.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(pop.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(pop.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('po.PONo','=',$PONo)
        ->first();

      return $info;

    }

    public function doSaveUpdatePOProcess($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $ProcessID = $data['ProcessID'];

      $ProcessType = $data['ProcessType'];
      $ProcessingCenterID = $data['ProcessingCenterID'];

      $CenterID = $data['CenterID'];
      $POID = $data['POID'];

      $GrossTotal = $data['GrossTotal'];
      $TotalVoucherPayment = $data['TotalVoucherPayment'];
      $TotalDiscountPercent = $data['TotalDiscountPercent'];
      $TotalDiscount = $data['TotalDiscount'];
      $TotalAmountDue = $data['TotalAmountDue'];

      $ApprovedByID = $data['ApprovedByID'];
      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      $Remarks = $data['Remarks'];
      $Status = $data['Status'];

      if($ProcessID > 0){

        if($Status == config('app.STATUS_APPROVED')){
            DB::table('purchaseorderprocess')
              ->where('ProcessID',$ProcessID)
              ->update([

              'ProcessingCenterID' => $ProcessingCenterID,

              'ProcessType' => $ProcessType,

              'CenterID' => $CenterID,
              'POID' => $POID,

              'GrossTotal'=> $GrossTotal,
              'TotalVoucherPayment' => $TotalVoucherPayment,
              'TotalDiscountPercent' => $TotalDiscountPercent,
              'TotalDiscount' => $TotalDiscount,
              'TotalAmountDue' => $TotalAmountDue,

              'Remarks'=> $Remarks,
              'Status'=> $Status,

              'ApprovedByID'=> $ApprovedByID,
              'ApprovedDateTime' =>$TODAY,

              'UpdatedByID'=> $UpdatedByID,
              'DateTimeUpdated' =>$TODAY
            ]);

            if($POID > 0){
              $PurchaseOrder = new PurchaseOrder();
              $PurchaseOrder->doProcessPO($POID);
            }
        }else{
            DB::table('purchaseorderprocess')
              ->where('ProcessID',$ProcessID)
              ->update([

              'ProcessingCenterID' => $ProcessingCenterID,

              'ProcessType' => $ProcessType,

              'CenterID' => $CenterID,
              'POID' => $POID,

              'GrossTotal'=> $GrossTotal,
              'TotalVoucherPayment' => $TotalVoucherPayment,
              'TotalDiscountPercent' => $TotalDiscountPercent,
              'TotalDiscount' => $TotalDiscount,
              'TotalAmountDue' => $TotalAmountDue,

              'Remarks'=> $Remarks,
              'Status'=> $Status,

              'UpdatedByID'=> $UpdatedByID,
              'DateTimeUpdated' =>$TODAY
            ]);
        }

        //Save Transaction Log
        $logData['TransRefID'] = $ProcessID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Purchase Order Processing";
        $logData['TransType'] = "Update Order Processing Information";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        $ProcessNo = $Misc->GenerateRandomNo(6,'purchaseorderprocess','ProcessNo');

        if($Status == config('app.STATUS_APPROVED')){
            $ProcessID =  DB::table('purchaseorderprocess')
                ->insertGetId([

                  'ProcessNo' => $ProcessNo,
                  'ProcessDateTime' => $TODAY,

                  'ProcessType' => $ProcessType,

                  'ProcessingCenterID' => $ProcessingCenterID,
                  'CenterID' => $CenterID,
                  'POID' => $POID,

                  'GrossTotal'=> $GrossTotal,
                  'TotalVoucherPayment' => $TotalVoucherPayment,
                  'TotalDiscountPercent' => $TotalDiscountPercent,
                  'TotalDiscount' => $TotalDiscount,
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

            if($POID > 0){
              $PurchaseOrder = new PurchaseOrder();
              $PurchaseOrder->doProcessPO($POID);
            }

        }else{
            $ProcessID =  DB::table('purchaseorderprocess')
                ->insertGetId([

                  'ProcessNo' => $ProcessNo,
                  'ProcessDateTime' => $TODAY,

                  'ProcessType' => $ProcessType,

                  'ProcessingCenterID' => $ProcessingCenterID,
                  'CenterID' => $CenterID,
                  'POID' => $POID,

                  'GrossTotal'=> $GrossTotal,
                  'TotalVoucherPayment' => $TotalVoucherPayment,
                  'TotalDiscountPercent' => $TotalDiscountPercent,
                  'TotalDiscount' => $TotalDiscount,
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
        $logData['TransRefID'] = $ProcessID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "Purchase Order Processing";
        $logData['TransType'] = "New Purchase Order Processing";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }

      //Vouchers
      if($ProcessID > 0 &&  $POID > 0){

        //Clear PO Process Voucher
        DB::table('purchaseorderprocessvoucher')
            ->where('ProcessID', '=',$ProcessID)
            ->delete();

        DB::statement("
            INSERT INTO purchaseorderprocessvoucher (ProcessID, VoucherID)
            SELECT ".$ProcessID." as ProcessID
              ,VoucherID
            FROM purchaseordervoucher
            WHERE POID = ".$POID
          );
      }

      $data['ProcessID'] = $ProcessID;
      $RetValue = $this->doSaveUpdatePOProcessItems($data);

      return $ProcessID;

    }

    public function doSaveUpdatePOProcessItems($data){

      $TODAY = date("Y-m-d H:i:s");

      $Product = new Product();

      $ProcessID = $data['ProcessID'];
      $ProcessingCenterID = $data['ProcessingCenterID'];
      $Status = $data['Status'];
      $POProcessItems = $data['POProcessItems'];
      $POProcessItemsDeleted = $data['POProcessItemsDeleted'];

      if(!empty($POProcessItemsDeleted)){

        //Deleted Supplier Products
        for($x=0; $x< count($POProcessItemsDeleted); $x++) {
          DB::table('purchaseorderprocessitem')
            ->where('ProcessItemID', '=',$POProcessItemsDeleted[$x])
            ->delete();
        }
      }

      if(!empty($POProcessItems)){

        for($x=0; $x< count($POProcessItems); $x++) {
          
          $ProcessItemID = $POProcessItems[$x]["ProcessItemID"];

          $ProductID = $POProcessItems[$x]["ProductID"];
          $Qty = $POProcessItems[$x]["Qty"];
          $Price = $POProcessItems[$x]["Price"];
          $SubTotal = $POProcessItems[$x]["SubTotal"];

          if($ProcessItemID > 0){
              if($ProductID > 0){
                DB::table('purchaseorderprocessitem')
                ->where('ProcessItemID',$ProcessItemID)
                ->update([
                  'ProcessID' => $ProcessID,
                  'ProductID' => $ProductID,
                  'Qty' => $Qty,
                  'Price' => $Price,
                  'SubTotal' => $SubTotal,
                  'DateTimeUpdated' =>$TODAY
                ]);
              }
          }else{
              if($ProductID > 0){
                $ProcessItemID =  DB::table('purchaseorderprocessitem')
                  ->insertGetId([
                      'ProcessID' => $ProcessID,
                      'ProductID' => $ProductID,
                      'Qty' => $Qty,
                      'Price' => $Price,
                      'SubTotal' => $SubTotal,
                      'DateTimeCreated' =>$TODAY,
                      'DateTimeUpdated' =>$TODAY
                  ]);
              }
          }

          if($Status == config('app.STATUS_APPROVED') && $Qty > 0){
            $Inventory = new Inventory();
            $param['CenterID'] = $ProcessingCenterID;
            $param['ProductID'] = $ProductID;
            $param["Type"] = "OUT";
            $param['Qty'] = $Qty;
            $param["TransType"] = "PO PRocessing";
            $param['TransactionRefID'] = $ProcessID;
            $param['Remarks'] = "";
            $Inventory->doSaveInventoryChanges($param);
          }

        }

      }

      return "Success";

    }

    public function getPOProcessItemList($param){

      $TODAY = date("Y-m-d H:i:s");

      $ProcessID = $param['ProcessID'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('purchaseorderprocessitem as popi')
        ->join('product', 'product.ProductID', '=', 'popi.ProductID')
        ->selectraw("
            0 as ReceiveItemID, 
            COALESCE(popi.ProcessItemID,0) as ProcessItemID,
            COALESCE(popi.ProcessID,0) as ProcessID,
            COALESCE(popi.ProductID,0) as ProductID,
            COALESCE(product.ProductCode,'') as ProductCode,
            COALESCE(product.ProductName,'') as ProductName,
            COALESCE(popi.Qty,0) as Qty,
            COALESCE(product.Measurement,'') as Measurement,
            COALESCE(popi.Price,0) as Price,
            COALESCE(popi.SubTotal,0) as SubTotal
        ")
        ->where('popi.ProcessID',$ProcessID);

      $query->orderBy("product.ProductName","ASC");

      $list = $query->get();

      return $list;

    }

    public function getPOProcessVoucherList($param){

      $TODAY = date("Y-m-d H:i:s");

      $ProcessID = $param['ProcessID'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('purchaseorderprocessvoucher as popv')
        ->join('membervoucher as mv', 'popv.VoucherID', '=', 'mv.VoucherID')
        ->selectraw("
            COALESCE(popv.ID,0) as ID,
            COALESCE(popv.ProcessID,0) as ProcessID,
            COALESCE(popv.VoucherID,0) as VoucherID,
            COALESCE(mv.VoucherCode,'') as VoucherCode,
            COALESCE(mv.NthPair,0) as NthPair,
            COALESCE(mv.VoucherAmount,'') as VoucherAmount
        ")
        ->where('popv.ProcessID',$ProcessID);

      $query->orderBy("mv.NthPair","ASC");

      $list = $query->get();

      return $list;

    }

    public function doCancelPOProcess($data){

      $Misc = new Misc();
      $TODAY = date("Y-m-d H:i:s");

      $ProcessID =$data['ProcessID'];
      $CancelledByID = $data['CancelledByID'];
      $Reason = $data['Reason'];

      //Get Info
      $POProcessInfo = $this->getPOProcessInfo($ProcessID);
      if(isset($POProcessInfo)){
        if($POProcessInfo->Status == config('app.STATUS_APPROVED')){
          //Revert Items
          $data['ProcessID'] = $ProcessID;
          $POProcessItems = $this->getPOProcessItemList($data);
          if(count($POProcessItems) > 0){
            foreach ($POProcessItems as $pkey){
              if($pkey->Qty > 0){
                $Inventory = new Inventory();
                $param['CenterID'] = $POProcessInfo->ProcessingCenterID;
                $param['ProductID'] = $pkey->ProductID;
                $param["Type"] = "Remove From Out";
                $param['Qty'] = $pkey->Qty;
                $param["TransType"] = "Cancel PO Processing";
                $param['TransactionRefID'] = $ProcessID;
                $param['Remarks'] = "";
                $Inventory->doSaveInventoryChanges($param);
              }          
            }
          }
        }
      }

      if($ProcessID > 0){
        DB::table('purchaseorderprocess')
        ->where('ProcessID',$ProcessID)
        ->update([
          'CancelledByID' => $CancelledByID,
          'CancellationReason' => $Reason,
          'Status' => config('app.STATUS_CANCELLED'),
          'DateTimeUpdated' => $TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $ProcessID;
        $logData['TransactedByID'] = $CancelledByID;
        $logData['ModuleType'] = "Purchase Order Process";
        $logData['TransType'] = "Purchase Order Process Cancelled";
        $logData['Remarks'] = $Reason;
        $Misc->doSaveTransactionLog($logData);

      }

      return $ProcessID;

    }

    public function doReceivedProcessedPO($ProcessID){

      if($ProcessID > 0){
        DB::table('purchaseorderprocess')
        ->where('ProcessID',$ProcessID)
        ->update([
          'IsReceived' => 1
        ]);
      }

      return $ProcessID;

    }


}
