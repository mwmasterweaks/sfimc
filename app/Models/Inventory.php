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
use App\Models\Center;

class Inventory extends Model
{

    public function getInventoryList($param){

      $TODAY = date("Y-m-d H:i:s");

      $IsWithInventoryOnly = $param['IsWithInventoryOnly'];
      $IsComplanProductsOnly = $param['IsComplanProductsOnly'];
      $CenterID = $param['CenterID'];
      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      $query = DB::table('productinventory as inv')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'inv.CenterID')
        ->join('product as prd', 'prd.ProductID', '=', 'inv.ProductID')
        ->leftjoin('useraccount as begby', 'begby.UserAccountID', '=', 'inv.BegBalanceByID')
        ->leftjoin('useraccount as minmaxby', 'minmaxby.UserAccountID', '=', 'inv.MinMaxByID')
        ->selectraw("

            COALESCE(inv.InventoryID,0) as InventoryID,

            COALESCE(inv.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(prd.ProductID,0) as ProductID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,

            COALESCE(prd.Measurement,'') as Measurement,
            COALESCE(prd.CenterPrice,0) as CenterPrice,
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

      if($IsWithInventoryOnly == 1){
        $query->whereraw("COALESCE(inv.StockOnHand,0) > 0");
      }

      if($IsComplanProductsOnly == 0){
        $query->whereraw("COALESCE(prd.IsPackageSet,0) = 0");
      }else if($IsComplanProductsOnly == 1){
        $query->whereraw("COALESCE(prd.IsPackageSet,0) = 1");
      }
      
      if($CenterID > 0){
        $query->whereraw("COALESCE(inv.CenterID,0) = ".$CenterID);
      }

      if($Status != ''){
        $query->where("prd.Status",$Status);
      }

      if(!empty($SearchText)){
        $query->whereraw(
            "CONCAT(
            COALESCE(prd.Category,''),' ',
            COALESCE(prd.ProductCode,''),' ',
            COALESCE(prd.ProductName,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderBy("inv.StockOnHand","ASC");

      $list = $query->get();

      return $list;

    }
    
    public function getInventoryInfo($InventoryID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('productinventory as inv')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'inv.CenterID')
        ->join('product as prd', 'prd.ProductID', '=', 'inv.ProductID')
        ->leftjoin('useraccount as begby', 'begby.UserAccountID', '=', 'inv.BegBalanceByID')
        ->leftjoin('useraccount as minmaxby', 'minmaxby.UserAccountID', '=', 'inv.MinMaxByID')
        ->selectraw("

            COALESCE(inv.InventoryID,0) as InventoryID,

            COALESCE(inv.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(prd.ProductID,0) as ProductID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,

            COALESCE(prd.Measurement,'') as Measurement,
            COALESCE(prd.CenterPrice,0) as CenterPrice,
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
        ->where("inv.InventoryID",$InventoryID)
        ->first();

      return $info;

    }

    public function getInventoryInfoByCenterProduct($CenterID, $ProductID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('productinventory as inv')
        ->join('centers as ctr', 'ctr.CenterID', '=', 'inv.CenterID')
        ->join('product as prd', 'prd.ProductID', '=', 'inv.ProductID')
        ->leftjoin('useraccount as begby', 'begby.UserAccountID', '=', 'inv.BegBalanceByID')
        ->leftjoin('useraccount as minmaxby', 'minmaxby.UserAccountID', '=', 'inv.MinMaxByID')
        ->selectraw("

            COALESCE(inv.InventoryID,0) as InventoryID,

            COALESCE(inv.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as TelNo,
            COALESCE(ctr.MobileNo,'') as MobileNo,
            COALESCE(ctr.EmailAddress,'') as EmailAddress,

            COALESCE(prd.ProductID,0) as ProductID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,

            COALESCE(prd.Measurement,'') as Measurement,
            COALESCE(prd.CenterPrice,0) as CenterPrice,
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
        ->where("inv.CenterID",$CenterID)
        ->where("inv.ProductID",$ProductID)
        ->first();

      return $info;

    }

    public function getInventoryLedger($param){

      $TODAY = date("Y-m-d H:i:s");

      $CenterID = trim($param['CenterID']);
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
        ->where("invledger.CenterID",$CenterID)
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

      $Center = new Center();

      $ProductID=$data['ProductID'];

      $param["Status"] = "";
      $param["SearchText"] = "";
      $param["Limit"] = 0;
      $param["PageNo"] = 0;
      $CenterList = $Center->getCenterList($param);

      if(count($CenterList) > 0){
        foreach ($CenterList as $ckey) {

          //Check If Exist 
          $CenterProductInvInfo = $this->getInventoryInfoByCenterProduct($ckey->CenterID, $ProductID);
          if(!isset($CenterProductInvInfo)){

            DB::table('productinventory')
              ->insert([
                'CenterID' => $ckey->CenterID,
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

        }
      }

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

      $InventoryID = $data['InventoryID'];
      $CenterID = $data['CenterID'];
      $ProductID = $data['ProductID'];
      $BegBalDateTime = date_format(date_create($data['BegBalDateTime']),'Y-m-d');
      $BegBalance = $data['BegBalance'];
      $BegBalRemarks = $data['BegBalRemarks'];

      $BegBalanceByID = $data['BegBalanceByID'];

      DB::table('productinventory')
        ->where('InventoryID',$InventoryID)
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
        $CenterID.",".
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

      $InventoryID = $data['InventoryID'];
      $CenterID = $data['CenterID'];
      $ProductID = $data['ProductID'];
      $MinimumLevel = $data['MinimumLevel'];
      $MaximumLevel = $data['MaximumLevel'];
      $MinMaxByID = $data['MinMaxByID'];

      DB::table('productinventory')
        ->where('InventoryID',$InventoryID)
        ->update([
          'MinimumLevel' => $MinimumLevel,
          'MaximumLevel' => $MaximumLevel,
          'MinMaxByID' => $MinMaxByID
        ]);        

    }

    public function doSaveInventoryChanges($param){
      $TODAY = date("Y-m-d H:i:s");

      $CenterID = $param['CenterID'];
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
        ->where('CenterID','=', $CenterID)
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
            WHERE CenterID = ".$CenterID.
            " AND ProductID = ".$ProductID;

        DB::statement($strSQL);

      }elseif($Type == "OUT"){

        $strSQL = "
          UPDATE productinventory SET
            TotalStockOut = TotalStockOut + ".$Qty.",
            StockOnHand = ((BegBalance + TotalStockIn) - TotalStockOut)
            WHERE CenterID = ".$CenterID.
            " AND ProductID = ".$ProductID;
        DB::statement($strSQL);

      }elseif($Type == "Remove From In"){

        //Update Inventory
        $strSQL = "
          UPDATE productinventory SET
            TotalStockIn = TotalStockIn - ".$Qty.",
            StockOnHand = ((BegBalance + TotalStockIn) - TotalStockOut)
            WHERE CenterID = ".$CenterID.
            " AND ProductID = ".$ProductID;

        DB::statement($strSQL);

      }elseif($Type == "Remove From Out"){

        //Update Inventory
        $strSQL = "
          UPDATE productinventory SET
            TotalStockOut = TotalStockOut - ".$Qty.",
            StockOnHand = ((BegBalance + TotalStockIn) - TotalStockOut)
            WHERE CenterID = ".$CenterID.
            " AND ProductID = ".$ProductID;

        DB::statement($strSQL);

      }

      //Inventory Log
      DB::statement("call spSaveInventoryLog(".
        $CenterID.",".
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
