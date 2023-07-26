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

use App\Models\Product;
use App\Models\Inventory;

class InventoryAdjustment extends Model
{

    public function getAdjustmentList($param){

      $TODAY = date("Y-m-d H:i:s");

      $CenterID = $param['CenterID'];
      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('productinvadj as adj')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'adj.CenterID')
        ->join('useraccount as cby', 'cby.UserAccountID', '=', 'adj.CreatedByID')
        ->leftjoin('useraccount as apvby', 'apvby.UserAccountID', '=', 'adj.ApprovedByID')
        ->leftjoin('useraccount as cancelledby', 'cancelledby.UserAccountID', '=', 'adj.CancelledByID')
        ->selectraw("
            COALESCE(adj.AdjustmentID,0) as AdjustmentID,
            COALESCE(adj.AdjustmentNo,'') as AdjustmentNo,
            adj.AdjustmentDateTime,

            COALESCE(adj.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(adj.Remarks,'') as Remarks,
            COALESCE(adj.Status,'') as Status,

            COALESCE(adj.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.FullName,'') as ApprovedBy,

            COALESCE(adj.CancelledByID,0) as CancelledByID,
            COALESCE(cancelledby.FullName,'') as CancelledBy,
            COALESCE(adj.CancellationDateTime,'') as CancellationDateTime,
            COALESCE(adj.CancellationReason,'') as CancellationReason,

            CASE
                WHEN COALESCE(adj.Status,'') = 'Pending'  THEN 1
                WHEN COALESCE(adj.Status,'') = 'Approved'  THEN 2
                WHEN COALESCE(adj.Status,'') = 'Cancelled' THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(adj.CreatedByID,0) as CreatedByID,
            COALESCE(cby.FullName,'') as CreatedBy,

            adj.DateTimeCreated,
            adj.DateTimeUpdated

        ");

      if($CenterID > 0){
        $query->whereraw("COALESCE(adj.CenterID,0) = ".$CenterID);
      }

      if($SearchText != ''){
        $query->whereraw(
            "CONCAT(
            COALESCE(adj.AdjustmentNo,''),' ',
            COALESCE(adj.Remarks,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Status != ''){
        $query->where("adj.Status",$Status);
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("CASE
                WHEN COALESCE(adj.Status,'') = 'Pending'  THEN 1
                WHEN COALESCE(adj.Status,'') = 'Approved'  THEN 2
                WHEN COALESCE(adj.Status,'') = 'Cancelled' THEN 3
                ELSE 0
            END","ASC");

      $query->orderBy("adj.AdjustmentDateTime","DESC");

      $list = $query->get();

      return $list;

    }
    
    public function getAdjustmentInfo($AdjustmentID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('productinvadj as adj')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'adj.CenterID')
        ->join('useraccount as cby', 'cby.UserAccountID', '=', 'adj.CreatedByID')
        ->leftjoin('useraccount as apvby', 'apvby.UserAccountID', '=', 'adj.ApprovedByID')
        ->leftjoin('useraccount as cancelledby', 'cancelledby.UserAccountID', '=', 'adj.CancelledByID')
        ->selectraw("
            COALESCE(adj.AdjustmentID,0) as AdjustmentID,
            COALESCE(adj.AdjustmentNo,'') as AdjustmentNo,
            adj.AdjustmentDateTime,

            COALESCE(adj.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(adj.Remarks,'') as Remarks,
            COALESCE(adj.Status,'') as Status,

            COALESCE(adj.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.FullName,'') as ApprovedBy,

            COALESCE(adj.CancelledByID,0) as CancelledByID,
            COALESCE(cancelledby.FullName,'') as CancelledBy,
            COALESCE(adj.CancellationDateTime,'') as CancellationDateTime,
            COALESCE(adj.CancellationReason,'') as CancellationReason,

            CASE
                WHEN COALESCE(adj.Status,'') = 'Pending'  THEN 1
                WHEN COALESCE(adj.Status,'') = 'Approved'  THEN 2
                WHEN COALESCE(adj.Status,'') = 'Cancelled' THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(adj.CreatedByID,0) as CreatedByID,
            COALESCE(cby.FullName,'') as CreatedBy,

            adj.DateTimeCreated,
            adj.DateTimeUpdated

        ")
        ->where('adj.AdjustmentID',$AdjustmentID)
        ->first();

      return $info;

    }

    public function getAdjustmentInfoByAdjustmentNo($AdjustmentNo){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('productinvadj as adj')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'adj.CenterID')
        ->join('useraccount as cby', 'cby.UserAccountID', '=', 'adj.CreatedByID')
        ->leftjoin('useraccount as apvby', 'apvby.UserAccountID', '=', 'adj.ApprovedByID')
        ->leftjoin('useraccount as cancelledby', 'cancelledby.UserAccountID', '=', 'adj.CancelledByID')
        ->selectraw("
            COALESCE(adj.AdjustmentID,0) as AdjustmentID,
            COALESCE(adj.AdjustmentNo,'') as AdjustmentNo,
            adj.AdjustmentDateTime,

            COALESCE(adj.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(adj.Remarks,'') as Remarks,
            COALESCE(adj.Status,'') as Status,

            COALESCE(adj.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.FullName,'') as ApprovedBy,

            COALESCE(adj.CancelledByID,0) as CancelledByID,
            COALESCE(cancelledby.FullName,'') as CancelledBy,
            COALESCE(adj.CancellationDateTime,'') as CancellationDateTime,
            COALESCE(adj.CancellationReason,'') as CancellationReason,

            CASE
                WHEN COALESCE(adj.Status,'') = 'Pending'  THEN 1
                WHEN COALESCE(adj.Status,'') = 'Approved'  THEN 2
                WHEN COALESCE(adj.Status,'') = 'Cancelled' THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(adj.CreatedByID,0) as CreatedByID,
            COALESCE(cby.FullName,'') as CreatedBy,

            adj.DateTimeCreated,
            adj.DateTimeUpdated

        ")
        ->where('adj.AdjustmentNo','=',$AdjustmentNo)
        ->first();

      return $info;

    }
  
    public function doSaveUpdateRecord($data){

      $Misc  = new Misc();
      $TODAY = date("Y-m-d H:i:s");

      $AdjustmentID=$data['AdjustmentID'];

      $CenterID = $data['CenterID'];

      $Remarks = $data['Remarks'];
      $Status = $data['Status'];

      $ApprovedByID = $data['ApprovedByID'];
      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      if($AdjustmentID > 0){

        if($Status == config("app.STATUS_APPROVED")){

          //Revert Inventory receive
          $this->doRevertInventoryAdjustment($AdjustmentID, "Edit");

          DB::table('productinvadj')
          ->where('AdjustmentID',$AdjustmentID)
          ->update([
            'CenterID'=> $CenterID,
            'Remarks'=> $Remarks,
            'Status' => $Status,
            'ApprovedByID'=> $ApprovedByID,
            'ApprovedDateTime'=> $TODAY,
            'UpdatedByID'=> $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
          ]);
        }else{
          DB::table('productinvadj')
          ->where('AdjustmentID',$AdjustmentID)
          ->update([
            'CenterID'=> $CenterID,
            'Remarks'=> $Remarks,
            'Status' => $Status,
            'UpdatedByID'=> $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
          ]);
        }

        //Save Transaction Log
        $logData['TransRefID'] = $AdjustmentID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Inventory Adjustment";
        $logData['TransType'] = "Update Inventory Adjustment";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        $AdjustmentNo = $Misc->GenerateRandomNo(6,'productinvadj','AdjustmentNo');

        if($Status == config("app.STATUS_APPROVED")){

          $AdjustmentID=  DB::table('productinvadj')
            ->insertGetId([
            'AdjustmentNo' => $AdjustmentNo,
            'AdjustmentDateTime' => $TODAY,
            'CenterID'=> $CenterID,
            'Remarks'=> $Remarks,
            'Status' => $Status,
            'ApprovedByID'=> $ApprovedByID,
            'ApprovedDateTime'=> $TODAY,
            'CreatedByID'=> $CreatedByID,
            'DateTimeCreated' =>$TODAY,
            'UpdatedByID'=> $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
          ]);

        }else{

         $AdjustmentID=  DB::table('productinvadj')
            ->insertGetId([
            'AdjustmentNo' => $AdjustmentNo,
            'AdjustmentDateTime' => $TODAY,
            'CenterID'=> $CenterID,
            'Remarks'=> $Remarks,
            'Status' => $Status,
            'CreatedByID'=> $CreatedByID,
            'DateTimeCreated' =>$TODAY,
            'UpdatedByID'=> $UpdatedByID,
            'DateTimeUpdated' =>$TODAY
          ]);

        }

        //Save Transaction Log
        $logData['TransRefID'] = $AdjustmentID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "Inventory Adjustment";
        $logData['TransType'] = "New Inventory Adjustment";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }

      $data['AdjustmentID'] = $AdjustmentID;
      $this->doSaveUpdateAdjustmentItems($data);

      return $AdjustmentID;

    }

    public function doSaveUpdateAdjustmentItems($data){

      $Misc  = new Misc();
      $Product = new Product();
      $Inventory = new Inventory();

      $TODAY = date("Y-m-d H:i:s");

      $AdjustmentID = $data['AdjustmentID'];
      $CenterID = $data['CenterID'];
      $InvAdjItems = $data['InvAdjItems'];
      $InvAdjItemsDeleted = $data['InvAdjItemsDeleted'];
      $Status = $data['Status'];

      if(!empty($InvAdjItemsDeleted)){
          for($x=0; $x< count($InvAdjItemsDeleted); $x++) {
            DB::table('productinvadjitem')
              ->where('AdjustmentItemID', '=',$InvAdjItemsDeleted[$x])
              ->delete();
          }
      }

      if(!empty($InvAdjItems)){

        for($x=0; $x< count($InvAdjItems); $x++) {
          
          $AdjustmentItemID = $InvAdjItems[$x]["AdjustmentItemID"];
          $ProductID = $InvAdjItems[$x]["ProductID"];
          $Type = $InvAdjItems[$x]["Type"];
          $Qty = $InvAdjItems[$x]["Qty"];
          $Remarks = $InvAdjItems[$x]["Remarks"];

          if($AdjustmentItemID > 0){

              if($ProductID > 0){
                DB::table('productinvadjitem')
                ->where('AdjustmentItemID',$AdjustmentItemID)
                ->update([
                  'AdjustmentID' => $AdjustmentID,
                  'ProductID' => $ProductID,
                  'Type' => $Type,
                  'Qty' => $Qty,
                  'Remarks' => $Remarks
                ]);
              }

          }else{
              if($ProductID > 0){
                $AdjustmentItemID=  DB::table('productinvadjitem')
                  ->insertGetId([
                      'AdjustmentID' => $AdjustmentID,
                      'ProductID' => $ProductID,
                      'Type' => $Type,
                      'Qty' => $Qty,
                      'Remarks' => $Remarks
                  ]);
              }
          }

          if($Status == config('app.STATUS_APPROVED')){
            $Inventory = new Inventory();
            $param['CenterID'] = $CenterID;
            $param['ProductID'] = $ProductID;
            $param["Type"] = ($Type == "Add To Inventory" ? "IN" : "OUT");
            $param['Qty'] = $Qty;
            $param["TransType"] = "Inventory Adjustment";
            $param['TransactionRefID'] = $AdjustmentID;
            $param['Remarks'] = "";
            $Inventory->doSaveInventoryChanges($param);
          }


        }

      }

      return "Success";

    }

    public function getAdjustmentItemList($param){

      $TODAY = date("Y-m-d H:i:s");

      $AdjustmentID = $param['AdjustmentID'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('productinvadjitem as adji')
        ->join('product', 'product.ProductID', '=', 'adji.ProductID')
        ->selectraw("
            COALESCE(adji.AdjustmentItemID,0) as AdjustmentItemID,
            COALESCE(adji.ProductID,0) as ProductID,
            COALESCE(product.ProductCode,'') as ProductNo,
            COALESCE(product.ProductName,'') as ProductName,
            COALESCE(product.Measurement,'') as Measurement,
            COALESCE(adji.Type,'') as Type,
            COALESCE(adji.Qty,0) as Qty,
            COALESCE(adji.Remarks,'') as Remarks
        ")
        ->where('AdjustmentID',$AdjustmentID);

      $query->orderBy("product.ProductName","ASC");

      $list = $query->get();

      return $list;

    }
    
    public function doCancelInventoryAdjustment($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $AdjustmentID = $data['AdjustmentID'];
      $CancelledByID = $data['CancelledByID'];
      $Reason = $data['Reason'];

      if($AdjustmentID > 0){

        //Revert Inventory receive
        $this->doRevertInventoryAdjustment($AdjustmentID, "Cancelled");

        DB::table('productinvadj')
        ->where('AdjustmentID',$AdjustmentID)
        ->update([
          'CancelledByID' => $CancelledByID,
          'CancellationReason' => $Reason,
          'Status' => config('app.STATUS_CANCELLED'),
          'DateTimeUpdated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $AdjustmentID;
        $logData['TransactedByID'] = $CancelledByID;
        $logData['ModuleType'] = "Inventory Adjustment";
        $logData['TransType'] = "Inventory Adjustment - Cancelled";
        $logData['Remarks'] = $Reason;
        $Misc->doSaveTransactionLog($logData);

      }

      return $AdjustmentID;

    }

    public function doRevertInventoryAdjustment($AdjustmentID, $Remarks){

        $Inventory  = new Inventory();

        //Revert Inventory receive
        $itemlist = DB::table('productinvadjitem as adjitem')
          ->join('productinvadj as adj', 'adj.AdjustmentID', '=', 'adjitem.AdjustmentID')
          ->selectraw("
              COALESCE(adj.CenterID,0) as CenterID,
              COALESCE(adjitem.AdjustmentItemID,0) as AdjustmentItemID,
              COALESCE(adjitem.ProductID,0) as ProductID,
              COALESCE(adjitem.Type,'') as Type,
              COALESCE(adjitem.Qty,0) as Qty,
              COALESCE(adj.Status,'') as Status
          ")
        ->where('adj.AdjustmentID',$AdjustmentID)
        ->get();
        
        if(count($itemlist) > 0){
          foreach ($itemlist as $ikey) {
              if($ikey->Status == config('app.STATUS_APPROVED')){
                $param['CenterID'] = $ikey->CenterID;
                $param['ProductID'] = $ikey->ProductID;
                $param['Type'] = ($ikey->Type == "Add To Inventory" ? "Remove From In" : "Remove From Out");
                $param['Qty'] = $ikey->Qty;
                $param['TransType'] = "Inventory Adjustment";
                $param['TransactionRefID'] = $AdjustmentID;
                $param['Remarks'] = $Remarks;

                $Inventory->doSaveInventoryChanges($param);
              }
          }
        }
        

    }



}
