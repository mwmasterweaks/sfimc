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
use App\Models\PurchaseOrderProcess;

class PurchaseReceive extends Model
{

    public function getPurchaseReceiveList($param){

      $TODAY = date("Y-m-d H:i:s");

      $CenterID = $param['CenterID'];
      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('purchasereceive as prec')
        ->join('purchaseorderprocess as pop', 'pop.ProcessID', '=', 'prec.ProcessID')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'prec.CenterID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->leftjoin('purchaseorder as po', 'po.POID', '=', 'pop.POID')
        ->leftjoin('useraccount as apvby', 'prec.ApprovedByID', '=', 'apvby.UserAccountID')
        ->selectraw("


            COALESCE(prec.ReceiveID,0) as ReceiveID,
            COALESCE(prec.ReceiveNo,0) as ReceiveNo,
            COALESCE(prec.ReceiveDateTime,0) as ReceiveDateTime,

            COALESCE(prec.ProcessID,0) as ProcessID,
            COALESCE(pop.ProcessNo,0) as ProcessNo,

            COALESCE(prec.CenterID,0) as CenterID,
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

            COALESCE(prec.GrossTotal,0) as GrossTotal,
            COALESCE(prec.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(prec.TotalDiscountPercent,0) as TotalDiscountPercent,
            COALESCE(prec.TotalDiscount,0) as TotalDiscount,
            COALESCE(prec.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(prec.Remarks,'') as Remarks,
            COALESCE(prec.Status,'') as Status,

            CASE
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(prec.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(prec.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(prec.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(prec.DateTimeUpdated,'') as DateTimeUpdated

        ");

      if($CenterID > 0){
        $query->whereraw("COALESCE(prec.CenterID,0) = ".$CenterID);
      }

      if($SearchText != ''){
        $query->whereraw(
            "CONCAT(
            COALESCE(prec.ReceiveNo,''),' ',
            COALESCE(pop.ProcessNo,''),' ',
            COALESCE(po.PONo,''),' ',
            COALESCE(ctr.Center,''),' ',
            COALESCE(ctr.TelNo,''),' ',
            COALESCE(ctr.EmailAddress,''),' ',
            COALESCE(ctr.MobileNo,''),' ',
            COALESCE(prec.Remarks,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Status != ''){
        $query->where("prec.Status",$Status);
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("(CASE
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END) ASC");

     $query->orderByraw("COALESCE(prec.ReceiveDateTime,'') DESC");

      $list = $query->get();

      return $list;

    }

    public function getPurchaseReceiveInfo($ReceiveID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('purchasereceive as prec')
        ->join('purchaseorderprocess as pop', 'pop.ProcessID', '=', 'prec.ProcessID')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'prec.CenterID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->leftjoin('purchaseorder as po', 'po.POID', '=', 'pop.POID')
        ->leftjoin('useraccount as apvby', 'prec.ApprovedByID', '=', 'apvby.UserAccountID')
        ->selectraw("


            COALESCE(prec.ReceiveID,0) as ReceiveID,
            COALESCE(prec.ReceiveNo,0) as ReceiveNo,
            COALESCE(prec.ReceiveDateTime,0) as ReceiveDateTime,

            COALESCE(prec.ProcessID,0) as ProcessID,
            COALESCE(pop.ProcessNo,0) as ProcessNo,

            COALESCE(prec.CenterID,0) as CenterID,
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

            COALESCE(prec.GrossTotal,0) as GrossTotal,
            COALESCE(prec.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(prec.TotalDiscountPercent,0) as TotalDiscountPercent,
            COALESCE(prec.TotalDiscount,0) as TotalDiscount,
            COALESCE(prec.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(prec.Remarks,'') as Remarks,
            COALESCE(prec.Status,'') as Status,

            CASE
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(prec.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(prec.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(prec.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(prec.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('prec.ReceiveID',$ReceiveID)
        ->first();

      return $info;

    }

    public function getPurchaseReceiveInfoByReceiveNo($ReceiveNo){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('purchasereceive as prec')
        ->join('purchaseorderprocess as pop', 'pop.ProcessID', '=', 'prec.ProcessID')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'prec.CenterID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'ctr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'ctr.CountryID')
        ->leftjoin('purchaseorder as po', 'po.POID', '=', 'pop.POID')
        ->leftjoin('useraccount as apvby', 'prec.ApprovedByID', '=', 'apvby.UserAccountID')
        ->selectraw("


            COALESCE(prec.ReceiveID,0) as ReceiveID,
            COALESCE(prec.ReceiveNo,0) as ReceiveNo,
            COALESCE(prec.ReceiveDateTime,0) as ReceiveDateTime,

            COALESCE(prec.ProcessID,0) as ProcessID,
            COALESCE(pop.ProcessNo,0) as ProcessNo,

            COALESCE(prec.CenterID,0) as CenterID,
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

            COALESCE(prec.GrossTotal,0) as GrossTotal,
            COALESCE(prec.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(prec.TotalDiscountPercent,0) as TotalDiscountPercent,
            COALESCE(prec.TotalDiscount,0) as TotalDiscount,
            COALESCE(prec.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(prec.Remarks,'') as Remarks,
            COALESCE(prec.Status,'') as Status,

            CASE
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(prec.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(prec.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(prec.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(prec.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(prec.DateTimeUpdated,'') as DateTimeUpdated
        ")
        ->where('prec.ReceiveNo','=',$ReceiveNo)
        ->first();

      return $info;

    }

    public function doSaveUpdatePurchaseReceive($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $ReceiveID = $data['ReceiveID'];

      $ProcessID = $data['ProcessID'];
      $CenterID = $data['CenterID'];

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

      if($ReceiveID > 0){

        if($Status == config('app.STATUS_APPROVED')){
            DB::table('purchasereceive')
              ->where('ReceiveID',$ReceiveID)
              ->update([

              'ProcessID' => $ProcessID,

              'CenterID' => $CenterID,

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

            if($ProcessID > 0){
              $PurchaseOrderProcess = new PurchaseOrderProcess();
              $PurchaseOrderProcess->doReceivedProcessedPO($ProcessID);
            }

        }else{
            DB::table('purchasereceive')
              ->where('ReceiveID',$ReceiveID)
              ->update([

              'ProcessID' => $ProcessID,

              'CenterID' => $CenterID,

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
        $logData['TransRefID'] = $ReceiveID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Purchase Receive";
        $logData['TransType'] = "Update Purchase Receive Information";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        $ReceiveNo = $Misc->GenerateRandomNo(6,'purchasereceive','ReceiveNo');

        if($Status == config('app.STATUS_APPROVED')){
            $ReceiveID =  DB::table('purchasereceive')
                ->insertGetId([

                  'ReceiveNo' => $ReceiveNo,
                  'ReceiveDateTime' => $TODAY,

                  'ProcessID' => $ProcessID,
                  'CenterID' => $CenterID,

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

            if($ProcessID > 0){
              $PurchaseOrderProcess = new PurchaseOrderProcess();
              $PurchaseOrderProcess->doReceivedProcessedPO($ProcessID);
            }

        }else{
            $ReceiveID =  DB::table('purchasereceive')
                ->insertGetId([

                  'ReceiveNo' => $ReceiveNo,
                  'ReceiveDateTime' => $TODAY,

                  'ProcessID' => $ProcessID,
                  'CenterID' => $CenterID,

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
        $logData['TransRefID'] = $ReceiveID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "Purchase Receive";
        $logData['TransType'] = "New Purchase Receive";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }

      $data['ReceiveID'] = $ReceiveID;
      $RetValue = $this->doSaveUpdatePurchaseReceiveItems($data);

      return $ReceiveID;

    }

    public function doSaveUpdatePurchaseReceiveItems($data){

      $TODAY = date("Y-m-d H:i:s");

      $Product = new Product();

      $ReceiveID = $data['ReceiveID'];
      $CenterID = $data['CenterID'];
      $Status = $data['Status'];
      $IsRemoveAllItems = $data['IsRemoveAllItems'];
      $ReceiveItems = $data['ReceiveItems'];
      $ReceiveItemsDeleted = $data['ReceiveItemsDeleted'];

      if($IsRemoveAllItems > 0){
          DB::table('purchasereceiveitem')
            ->where('ReceiveID', '=',$ReceiveID)
            ->delete();
      }else{
        if(!empty($ReceiveItemsDeleted)){

          //Deleted Supplier Products
          for($x=0; $x< count($ReceiveItemsDeleted); $x++) {
            DB::table('purchasereceiveitem')
              ->where('ReceiveItemID', '=',$ReceiveItemsDeleted[$x])
              ->delete();
          }
        }
      }

      if(!empty($ReceiveItems)){

        for($x=0; $x< count($ReceiveItems); $x++) {
          
          $ReceiveItemID = $ReceiveItems[$x]["ReceiveItemID"];

          $ProductID = $ReceiveItems[$x]["ProductID"];
          $Qty = $ReceiveItems[$x]["Qty"];
          $ReceiveQty = $ReceiveItems[$x]["ReceiveQty"];
          $Price = $ReceiveItems[$x]["Price"];
          $SubTotal = $ReceiveItems[$x]["SubTotal"];

          if($ReceiveItemID > 0){
              if($ProductID > 0){
                DB::table('purchasereceiveitem')
                ->where('ReceiveItemID',$ReceiveItemID)
                ->update([
                  'ReceiveID' => $ReceiveID,
                  'ProductID' => $ProductID,
                  'Qty' => $Qty,
                  'ReceiveQty' => $ReceiveQty,
                  'Price' => $Price,
                  'SubTotal' => $SubTotal,
                  'DateTimeUpdated' =>$TODAY
                ]);
              }
          }else{
              if($ProductID > 0){
                $ReceiveItemID =  DB::table('purchasereceiveitem')
                  ->insertGetId([
                      'ReceiveID' => $ReceiveID,
                      'ProductID' => $ProductID,
                      'Qty' => $Qty,
                      'ReceiveQty' => $ReceiveQty,
                      'Price' => $Price,
                      'SubTotal' => $SubTotal,
                      'DateTimeCreated' =>$TODAY,
                      'DateTimeUpdated' =>$TODAY
                  ]);
              }
          }

          if($Status == config('app.STATUS_APPROVED') && $ReceiveQty > 0){
            $Inventory = new Inventory();
            $param['CenterID'] = $CenterID;
            $param['ProductID'] = $ProductID;
            $param["Type"] = "IN";
            $param['Qty'] = $ReceiveQty;
            $param["TransType"] = "Purchase Receive";
            $param['TransactionRefID'] = $ReceiveID;
            $param['Remarks'] = "";
            $Inventory->doSaveInventoryChanges($param);
          }

        }

      }

      return "Success";

    }

    public function getPurchaseReceiveItemList($param){

      $TODAY = date("Y-m-d H:i:s");

      $ReceiveID = $param['ReceiveID'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('purchasereceiveitem as preci')
        ->join('product', 'product.ProductID', '=', 'preci.ProductID')
        ->selectraw("
            COALESCE(preci.ReceiveItemID,0) as ReceiveItemID,
            COALESCE(preci.ReceiveID,0) as ReceiveID,
            COALESCE(preci.ProductID,0) as ProductID,
            COALESCE(product.ProductCode,'') as ProductCode,
            COALESCE(product.ProductName,'') as ProductName,
            COALESCE(preci.Qty,0) as Qty,
            COALESCE(preci.ReceiveQty,0) as ReceiveQty,
            COALESCE(product.Measurement,'') as Measurement,
            COALESCE(preci.Price,0) as Price,
            COALESCE(preci.SubTotal,0) as SubTotal
        ")
        ->where('preci.ReceiveID',$ReceiveID);

      $query->orderBy("product.ProductName","ASC");

      $list = $query->get();

      return $list;

    }

    public function doCancelPurchaseReceive($data){

      $Misc = new Misc();
      $TODAY = date("Y-m-d H:i:s");

      $ReceiveID =$data['ReceiveID'];
      $CancelledByID = $data['CancelledByID'];
      $Reason = $data['Reason'];

      if($ReceiveID > 0){
        DB::table('purchaseorderprocess')
        ->where('ReceiveID',$ReceiveID)
        ->update([
          'CancelledByID' => $CancelledByID,
          'CancellationReason' => $Reason,
          'Status' => config('app.STATUS_CANCELLED'),
          'DateTimeUpdated' => $TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $ReceiveID;
        $logData['TransactedByID'] = $CancelledByID;
        $logData['ModuleType'] = "Purchase Order Process";
        $logData['TransType'] = "Purchase Order Process Cancelled";
        $logData['Remarks'] = $Reason;
        $Misc->doSaveTransactionLog($logData);

      }

      return $ReceiveID;

    }




}
