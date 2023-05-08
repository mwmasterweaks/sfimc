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

class PackageInventory extends Model
{

    public function getPackageInventoryList($param){

      $TODAY = date("Y-m-d H:i:s");

      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      $query = DB::table('productinventory as inv')
        ->join('product as prd', 'prd.ProductID', '=', 'inv.ProductID')
        ->leftjoin('useraccount as begby', 'begby.UserAccountID', '=', 'inv.BegBalanceByID')
        ->leftjoin('useraccount as minmaxby', 'minmaxby.UserAccountID', '=', 'inv.MinMaxByID')
        ->selectraw("
            COALESCE(prd.ProductID,0) as ProductID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,

            COALESCE(prd.Measurement,'') as Measurement,
            COALESCE(prd.DistributorPrice,0) as DistributorPrice,
            COALESCE(prd.RetailPrice,0) as RetailPrice,
            COALESCE(prd.RebateValue,0) as RebateValue,

            COALESCE(inv.BegBalance,0) as BegBalance,
            COALESCE(inv.BegBalanceByID,0) as BegBalanceByID,
            COALESCE(begby.Fullname,'') as BegBalanceBy,
            inv.BegBalDateTime,
            COALESCE(inv.BegBalRemarks,'') as BegBalRemarks,

            COALESCE(inv.TotalStockIn,0) as TotalStockIn,
            COALESCE(inv.TotalStockOut,0) as TotalStockOut,
            COALESCE(inv.StockOnHand,0) as StockOnHand,

            COALESCE(inv.MinimumLevel,0) as MinimumLevel,
            COALESCE(inv.MaximumLevel,0) as MaximumLevel,
            COALESCE(inv.MinMaxByID,0) as MinMaxByID,
            COALESCE(minmaxby.Fullname,'') as MinMaxBy,

            COALESCE(prd.Status,'') as Status
        ");

      if($Status != ''){
        $query->where("prd.Status",$Status);
      }

      if(!empty($SearchText)){
        $query->whereraw(
            "CONCAT(
            COALESCE(prd.ProductCode,''),' ',
            COALESCE(prd.ProductName,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderBy("prd.ProductName","ASC");

      $list = $query->get();

      return $list;

    }
    
    public function getPackageInventoryInfo($ProductID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('productinventory as inv')
        ->join('product as prd', 'prd.ProductID', '=', 'inv.ProductID')
        ->leftjoin('useraccount as begby', 'begby.UserAccountID', '=', 'inv.BegBalanceByID')
        ->leftjoin('useraccount as minmaxby', 'minmaxby.UserAccountID', '=', 'inv.MinMaxByID')
        ->selectraw("
            COALESCE(prd.ProductID,0) as ProductID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,

            COALESCE(prd.Measurement,'') as Measurement,
            COALESCE(prd.DistributorPrice,0) as DistributorPrice,
            COALESCE(prd.RetailPrice,0) as RetailPrice,
            COALESCE(prd.RebateValue,0) as RebateValue,

            COALESCE(inv.BegBalance,0) as BegBalance,
            COALESCE(inv.BegBalanceByID,0) as BegBalanceByID,
            COALESCE(begby.Fullname,'') as BegBalanceBy,
            inv.BegBalDateTime,
            COALESCE(inv.BegBalRemarks,'') as BegBalRemarks,

            COALESCE(inv.TotalStockIn,0) as TotalStockIn,
            COALESCE(inv.TotalStockOut,0) as TotalStockOut,
            COALESCE(inv.StockOnHand,0) as StockOnHand,

            COALESCE(inv.MinimumLevel,0) as MinimumLevel,
            COALESCE(inv.MaximumLevel,0) as MaximumLevel,
            COALESCE(inv.MinMaxByID,0) as MinMaxByID,
            COALESCE(minmaxby.Fullname,'') as MinMaxBy,

            COALESCE(prd.Status,'') as Status
        ")
        ->where("inv.ProductID",$ProductID)
        ->first();

      return $info;

    }

    public function getPackageInventoryLedger($param){

      $TODAY = date("Y-m-d H:i:s");

      $ProductID = trim($param['ProductID']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      $query = DB::table('productinvledger as invledger')
        ->selectraw("
            invledger.DateTimeCreated as TransDateTime,
            COALESCE(invledger.ProductID,0) as ProductID,
            (CASE 
              WHEN invledger.TransType = 'Order' THEN
                COALESCE(
                  (SELECT CONCAT('Order No. ',OrderNo)
                  FROM `order`
                  WHERE OrderID = invledger.TransactionRefID
                  LIMIT 1
                  )
                ,'')

              WHEN invledger.TransType = 'Inventory Adjustment' THEN
                COALESCE(
                  (SELECT CONCAT('Adjustment No. ',AdjustmentNo)
                  FROM productinvadj
                  WHERE AdjustmentID = invledger.TransactionRefID
                  LIMIT 1
                  )
                ,'')

              WHEN invledger.TransType = 'Beginning Balance' THEN
                COALESCE(invledger.TransType,'')

              ELSE
                ''
            END) AS TransactionType,

            COALESCE(invledger.OldStockOnhand,0) as OldStockOnhand,
            COALESCE(invledger.QtyIn,0) as QtyIn,
            COALESCE(invledger.QtyOut,0) as QtyOut,
            COALESCE(invledger.NewStockOnhand,0) as NewStockOnhand,
            COALESCE(invledger.Remarks,'') as Remarks
        ")
        ->where("invledger.ProductID",$ProductID);

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderBy("invledger.DateTimeCreated","DESC");

      $list = $query->get();

      return $list;

    }
    
    public function doSaveUpdateInventory($data){

      $TODAY = date("Y-m-d H:i:s");

      $ProductID=$data['ProductID'];

      DB::table('productinventory')
        ->insert([
          'ProductID' => $ProductID,
          'BegBalance' => 0,
          'BegBalRemarks' => '',
          'TotalStockIn' => 0,
          'TotalStockOut' => 0,
          'StockOnHand' => 0,
          'MinimumLevel' => 0,
          'MaximumLevel' => 0
        ]);
    }

    public function doSaveProductInventory($data){

        //Insert Product to Center
        $strSQL = "INSERT INTO productinventory(
                      CenterID, 
                      ProductID, 
                      BegBalance, 
                      BegBalRemarks, 
                      TotalStockIn, 
                      TotalStockOut, 
                      StockOnHand, 
                      MinimumLevel, 
                      MaximumLevel)
                  SELECT 
                    ".$data['CenterID'].",
                    prd.ProductID, 
                    0,
                    '',
                    0,
                    0,
                    0,
                    0,
                    0
                  FROM product as prd
                  LEFT JOIN productinventory as pinv ON (pinv.ProductID = prd.ProductID AND pinv.CenterID = ".$data['CenterID'].")
                  WHERE pinv.ProductID IS NULL
                  ORDER BY prd.ProductID ASC";

        DB::statement($strSQL);

    }

    public function doSaveBegBal($data){

      $Misc  = new Misc();
      $TODAY = date("Y-m-d H:i:s");

      $ProductID = $data['ProductID'];
      $BegBalDateTime = date_format(date_create($data['BegBalDateTime']),'Y-m-d');
      $BegBalance = $data['BegBalance'];
      $BegBalRemarks = $data['BegBalRemarks'];

      $BegBalanceByID = $data['BegBalanceByID'];

      DB::table('productinventory')
        ->where('ProductID',$ProductID)
        ->update([
          'BegBalanceByID' => $BegBalanceByID,
          'BegBalDateTime' => $BegBalDateTime,
          'BegBalance' => $BegBalance,
          'BegBalRemarks' => $BegBalRemarks,
          'TotalStockIn' => 0,
          'TotalStockOut' => 0,
          'StockOnHand' => $BegBalance
        ]);        

      //Inventory Log
      DB::statement("call spSaveInventoryLog(".
        $ProductID.",".
        $BegBalance.",".
        "'Reset',".
        $BegBalance.",".
        "'Beginning Balance', ".
        "0, ".
        "'".$TODAY."',".
        "''".
      ")");

    }

    public function doSaveMinMax($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $ProductID = $data['ProductID'];
      $MinimumLevel = $data['MinimumLevel'];
      $MaximumLevel = $data['MaximumLevel'];
      $MinMaxByID = $data['MinMaxByID'];

      DB::table('productinventory')
        ->where('ProductID',$ProductID)
        ->update([
          'MinimumLevel' => $MinimumLevel,
          'MaximumLevel' => $MaximumLevel,
          'MinMaxByID' => $MinMaxByID
        ]);        

    }

    public function doSaveInventoryChanges($param){
      $TODAY = date("Y-m-d H:i:s");

      $ProductID = $param['ProductID'];
      $Type = $param['Type'];
      $Qty = $param['Qty'];

      $TransType = $param['TransType'];
      $TransactionRefID = $param['TransactionRefID'];

      $Remarks = "";
      if($param['Remarks']){
        $Remarks = $param['Remarks'];
      }

      $info = DB::table('productinventory')
        ->selectraw("
            ((BegBalance + TotalStockIn) - TotalStockOut) as StockOnHand
        ")
        ->where('ProductID','=', $ProductID)
        ->first();

      $StockOnHand = 0;
      if(isset($info)){
        $StockOnHand = $info->StockOnHand;
      }

      //Update Inventory
      if($Type == "IN"){

        $strSQL = "
          UPDATE productinventory SET
            TotalStockIn = TotalStockIn + ".$Qty.",
            StockOnHand = ((BegBalance + TotalStockIn) - TotalStockOut)
            WHERE ProductID = ".$ProductID;

        DB::statement($strSQL);

      }elseif($Type == "OUT"){

        $strSQL = "
          UPDATE productinventory SET
            TotalStockOut = TotalStockOut + ".$Qty.",
            StockOnHand = ((BegBalance + TotalStockIn) - TotalStockOut)
            WHERE ProductID = ".$ProductID;
        DB::statement($strSQL);

      }elseif($Type == "Remove From In"){

        //Update Inventory
        $strSQL = "
          UPDATE productinventory SET
            TotalStockIn = TotalStockIn - ".$Qty.",
            StockOnHand = ((BegBalance + TotalStockIn) - TotalStockOut)
            WHERE ProductID = ".$ProductID;

        DB::statement($strSQL);

      }elseif($Type == "Remove From Out"){

        //Update Inventory
        $strSQL = "
          UPDATE productinventory SET
            TotalStockOut = TotalStockOut - ".$Qty.",
            StockOnHand = ((BegBalance + TotalStockIn) - TotalStockOut)
            WHERE ProductID = ".$ProductID;

        DB::statement($strSQL);

      }

      //Inventory Log
      DB::statement("call spSaveInventoryLog(".
        $ProductID.",".
        $Qty.",".
        "'".$Type."',".
        $StockOnHand.",".
        "'".$TransType."', ".
        $TransactionRefID.", ".
        "'".$TODAY."',".
        "'".$Remarks."'".
      ")");

  }




}
