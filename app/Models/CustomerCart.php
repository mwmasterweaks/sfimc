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

class CustomerCart extends Model
{
  
    public function getCustomerCartList($param){

      $TODAY = date("Y-m-d H:i:s");

      $MemberEntryID = $param['MemberEntryID'];
      $SessionID = $param['SessionID'];

      $query = DB::table('customercart as ccart')
        ->join('product as prd', 'ccart.ProductID', '=', 'prd.ProductID')
        ->selectraw("
            COALESCE(ccart.CartItemID,0) as CartItemID,
            COALESCE(ccart.MemberEntryID,0) as MemberEntryID,
            COALESCE(ccart.ProductID,0) as ProductID,
            COALESCE(ccart.SessionID,'') as SessionID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.NetWeight,0) as NetWeight,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,

            COALESCE(prd.Measurement,'') as Measurement,
            COALESCE(ccart.Qty,0) as Qty,
            COALESCE(prd.CenterPrice,0) as CenterPrice,
            COALESCE(prd.DistributorPrice,0) as DistributorPrice,
            COALESCE(prd.RetailPrice,0) as RetailPrice,
            COALESCE(prd.RebateValue,0) as RebateValue,

            COALESCE(prd.Status,'') as Status,
            COALESCE(ccart.Remarks,'') as Remarks
        ")
        ->whereraw("COALESCE(prd.Status,'') = 'Active' ")
        ->where(function ($orquery) use ($MemberEntryID, $SessionID) {
            if($MemberEntryID != 0){
              $orquery->whereraw('COALESCE(ccart.MemberEntryID,0) = '.$MemberEntryID)
                    ->orwhereraw("COALESCE(ccart.SessionID,'') = '".$SessionID."'");
            }else{
              $orquery->whereraw("COALESCE(ccart.SessionID,'') = '".$SessionID."'");
            }
        })
        ->orderByraw("COALESCE(prd.ProductName,'')","ASC");

      $list = $query->get();

      return $list;

    }

    public function getCustomerCartProductQty($param){

      $MemberEntryID = $param['MemberEntryID'];
      $SessionID = $param['SessionID'];
      $ProductID = $param['ProductID'];

      $query = DB::table('customercart as ccart')
        ->join('product as prd', 'ccart.ProductID', '=', 'prd.ProductID')
        ->where('prd.Status','Active')
        ->where('ccart.ProductID',$ProductID)
        ->where(function ($orquery) use ($MemberEntryID, $SessionID) {
            if($MemberEntryID != 0){
              $orquery->whereraw('COALESCE(ccart.MemberEntryID,0) = '.$MemberEntryID)
                    ->orwhereraw("COALESCE(ccart.SessionID,'') = '".$SessionID."'");
            }else{
              $orquery->whereraw("COALESCE(ccart.SessionID,'') = '".$SessionID."'");
            }
        });

      $info = $query->first();

      $CartQty = 0;
      if(isset($info)){
        $CartQty = $info->Qty;
      }

      return $CartQty;

    }

    public function getCustomerCartCount($param){

      $MemberEntryID = $param['MemberEntryID'];
      $SessionID = $param['SessionID'];

      $query = DB::table('customercart as ccart')
        ->join('product as prd', 'ccart.ProductID', '=', 'prd.ProductID')
        ->where('prd.Status','Active')
        ->where(function ($orquery) use ($MemberEntryID, $SessionID) {
            if($MemberEntryID != 0){
              $orquery->whereraw('COALESCE(ccart.MemberEntryID,0) = '.$MemberEntryID)
                    ->orwhereraw("COALESCE(ccart.SessionID,'') = '".$SessionID."'");
            }else{
              $orquery->whereraw("COALESCE(ccart.SessionID,'') = '".$SessionID."'");
            }
        });
      $list = $query->get();

      $CartItemCount = 0;
      if(count($list) > 0){
        foreach ($list as $cart) {
          $CartItemCount = $CartItemCount + $cart->Qty;
        }
      }

      return $CartItemCount;

    }

    public function doAddToCart($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $SessionID = $data['SessionID'];
      $MemberEntryID = $data['MemberEntryID'];
      $ProductID = $data['ProductID'];
      $Qty = $data['Qty'];
      $Remarks = $data['Remarks'];

      //Check if ProductID Exist
      $query = DB::table('customercart as ccart')
        ->selectraw("
            COALESCE(ccart.CartItemID,0) as CartItemID,
            COALESCE(ccart.Qty,0) as Qty
        ")
        ->where('ccart.ProductID',$ProductID)
        ->where(function ($orquery) use ($MemberEntryID, $SessionID) {
            if($MemberEntryID != 0){
              $orquery->whereraw('COALESCE(ccart.MemberEntryID,0) = '.$MemberEntryID)
                    ->orwhereraw("COALESCE(ccart.SessionID,'') = '".$SessionID."'");
            }else{
              $orquery->whereraw("COALESCE(ccart.SessionID,'') = '".$SessionID."'");
            }
        });
      $info = $query->first();

      $CartItemID = 0;
      if(isset($info)){
        $CartItemID = $info->CartItemID;
        $Qty = $Qty + $info->Qty;
      }

      if($CartItemID > 0){
        DB::table('customercart')
        ->where('CartItemID',$CartItemID)
        ->update([
          'SessionID' => $SessionID,
          'MemberEntryID' => $MemberEntryID,
          'ProductID' => $ProductID,
          'Qty' => $Qty,
          'Remarks' => $Remarks,
          'DateTimeUpdated' =>$TODAY
        ]);

      }else{
        $CartItemID =  DB::table('customercart')
        ->insertGetId([
          'SessionID' => $SessionID,
          'MemberEntryID' => $MemberEntryID,
          'ProductID' => $ProductID,
          'Qty' => $Qty,
          'Remarks' => $Remarks,
          'DateTimeCreated' =>$TODAY
        ]);
      }

      return $CartItemID;

    }

    public function doSaveUpdateCart($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $SessionID = $data['SessionID'];
      $MemberEntryID = $data['MemberEntryID'];
      $ProductID = $data['ProductID'];
      $Qty = $data['Qty'];
      $Remarks = $data['Remarks'];

      //Check if ProductID Exist
      $query = DB::table('customercart as ccart')
        ->selectraw("
            COALESCE(ccart.CartItemID,0) as CartItemID,
            COALESCE(ccart.Qty,0) as Qty
        ")
        ->where('ccart.ProductID',$ProductID);

      if($MemberEntryID > 0){
        $query->where('ccart.MemberEntryID',$MemberEntryID);
      }else{
        if(!empty($SessionID)){
          $query->where('ccart.SessionID',$SessionID);
        }
      }
      $info = $query->first();

      $CartItemID = 0;
      if(isset($info)){
        $CartItemID = $info->CartItemID;
      }

      if($CartItemID > 0){
        if($Qty > 0){
          DB::table('customercart')
          ->where('CartItemID',$CartItemID)
          ->update([
            'SessionID' => $SessionID,
            'MemberEntryID' => $MemberEntryID,
            'ProductID' => $ProductID,
            'Qty' => $Qty,
            'Remarks' => $Remarks,
            'DateTimeUpdated' =>$TODAY
          ]);
        }else{
          DB::table('customercart')
            ->where('CartItemID',$CartItemID)
            ->delete();
        }
      }else{
        if($Qty > 0){
          $CartItemID =  DB::table('customercart')
            ->insertGetId([
              'SessionID' => $SessionID,
              'MemberEntryID' => $MemberEntryID,
              'ProductID' => $ProductID,
              'Qty' => $Qty,
              'Remarks' => $Remarks,
              'DateTimeCreated' =>$TODAY
            ]);
        }
      }

      return $CartItemID;

    }

    public function doRemoveCartItem($data){

      $SessionID = $data['SessionID'];
      $MemberEntryID = $data['MemberEntryID'];
      $ProductID = $data['ProductID'];

      if($MemberEntryID > 0){
        DB::table('customercart')
          ->where('MemberEntryID',$MemberEntryID)
          ->where('ProductID',$ProductID)
          ->delete();
      }else if(!empty($SessionID)){
        DB::table('customercart')
          ->where('SessionID',$SessionID)
          ->where('ProductID',$ProductID)
          ->delete();
      }
            
    }

    public function doClearCart($data){

      $SessionID = $data['SessionID'];
      $MemberEntryID = $data['MemberEntryID'];

      DB::table('customercart')
        ->where('MemberEntryID', '=', $MemberEntryID)
        ->orWhere('SessionID', '=', $SessionID)
        ->delete();

    }




















}
