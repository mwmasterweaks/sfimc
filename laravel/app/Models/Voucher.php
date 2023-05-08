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

class Voucher extends Model
{

    public function getMemberVoucherList($param){

      $MemberEntryID = $param['MemberEntryID'];
      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('membervoucher as mbrvoucher')
        ->join('memberentry as mbrentry', 'mbrvoucher.MemberEntryID', '=', 'mbrentry.EntryID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'mbr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'mbr.CountryID')
        ->leftjoin('order', 'order.OrderID', '=', 'mbrvoucher.UsedByOrderID')
        ->selectraw("
              COALESCE(mbrvoucher.VoucherID,0) as VoucherID,

              COALESCE(mbrvoucher.MemberEntryID,0) as MemberEntryID,
              COALESCE(mbrentry.EntryCode,'') as EntryCode,
              COALESCE(mbr.MemberNo,'') as MemberNo,
              CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
              COALESCE(mbr.EmailAddress,'') as EmailAddress,
              COALESCE(mbr.TelNo,'') as TelNo,
              COALESCE(mbr.MobileNo,'') as MobileNo,

              COALESCE(mbr.Address,'') as Address,
              COALESCE(mbr.CityID,0) as CityID,
              COALESCE(cty.City,'') as City,
              COALESCE(mbr.StateProvince,'') as StateProvince,
              COALESCE(mbr.ZipCode,'') as ZipCode,
              COALESCE(mbr.CountryID,0) as CountryID,
              COALESCE(ctry.Country,'') as Country,

              COALESCE(mbrvoucher.VoucherCode,'') as VoucherCode,
              COALESCE(mbrvoucher.VoucherAmount,0) as VoucherAmount,
              COALESCE(mbrvoucher.NthPair,0) as NthPair,
              COALESCE(mbrvoucher.Remarks,'') as Remarks,

              COALESCE(mbrvoucher.UsedByEntryID,0) as UsedByEntryID,

              COALESCE(mbrvoucher.UsedByOrderID,0) as UsedByOrderID,
              COALESCE(order.OrderNo,'') as OrderNo,
              COALESCE(order.OrderDateTime,'') as OrderDateTime,

              COALESCE(mbrvoucher.Status,'') as Status,

              CASE
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_AVAILABLE')."'  THEN 1
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_USED')."'  THEN 2
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                  ELSE 0
              END as SortOption

          ");

      if($MemberEntryID > 0){
        $query->whereraw("COALESCE(mbrvoucher.MemberEntryID,0) = '".$MemberEntryID."'");
      }

      if($Status != ""){
        $query->whereraw("COALESCE(mbrvoucher.Status,'') = '".$Status."'");
      }

      if($SearchText != ''){
        $query->whereraw(
            "CONCAT(
              COALESCE(mbrvoucher.VoucherCode,''),' ',
              COALESCE(mbrentry.EntryCode,''),' ',
              COALESCE(mbr.FirstName,''),' ',
              COALESCE(mbr.MiddleName,''), ' ', 
              COALESCE(mbr.LastName,''),' ',
              COALESCE(order.OrderNo,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("(CASE
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_AVAILABLE')."'  THEN 1
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_USED')."'  THEN 2
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                  ELSE 0
              END) ASC");
      $query->orderBy("mbrvoucher.MemberEntryID","ASC");
      $query->orderBy("mbrvoucher.VoucherID","ASC");

      $list = $query->get();

      return $list;
    }

    public function getCenterVoucherList($param){

      $CenterID = $param['CenterID'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('membervoucher as mbrvoucher')
        ->join('memberentry as mbrentry', 'mbrvoucher.MemberEntryID', '=', 'mbrentry.EntryID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'mbr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'mbr.CountryID')
        ->leftjoin('order', 'order.OrderID', '=', 'mbrvoucher.UsedByOrderID')
        ->selectraw("
              COALESCE(mbrvoucher.VoucherID,0) as VoucherID,

              COALESCE(mbrvoucher.MemberEntryID,0) as MemberEntryID,
              COALESCE(mbrentry.EntryCode,'') as EntryCode,
              COALESCE(mbr.MemberNo,'') as MemberNo,
              CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
              COALESCE(mbr.EmailAddress,'') as EmailAddress,
              COALESCE(mbr.TelNo,'') as TelNo,
              COALESCE(mbr.MobileNo,'') as MobileNo,

              COALESCE(mbr.Address,'') as Address,
              COALESCE(mbr.CityID,0) as CityID,
              COALESCE(cty.City,'') as City,
              COALESCE(mbr.StateProvince,'') as StateProvince,
              COALESCE(mbr.ZipCode,'') as ZipCode,
              COALESCE(mbr.CountryID,0) as CountryID,
              COALESCE(ctry.Country,'') as Country,

              COALESCE(mbrvoucher.VoucherCode,'') as VoucherCode,
              COALESCE(mbrvoucher.VoucherAmount,0) as VoucherAmount,
              COALESCE(mbrvoucher.NthPair,0) as NthPair,
              COALESCE(mbrvoucher.Remarks,'') as Remarks,

              COALESCE(mbrvoucher.UsedByEntryID,0) as UsedByEntryID,

              COALESCE(mbrvoucher.UsedByOrderID,0) as UsedByOrderID,
              COALESCE(order.OrderNo,'') as OrderNo,
              COALESCE(order.OrderDateTime,'') as OrderDateTime,

              COALESCE(mbrvoucher.Status,'') as Status,

              CASE
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_AVAILABLE')."'  THEN 1
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_USED')."'  THEN 2
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                  ELSE 0
              END as SortOption

          ");

      if($CenterID > 0){
        $query->whereraw("COALESCE(mbrvoucher.OwnedByCenterID,0) = ".$CenterID);
        $query->whereraw("COALESCE(mbrvoucher.UsedByCenterID,0) = 0");
      }

      if($SearchText != ''){
        $query->whereraw(
            "CONCAT(
              COALESCE(mbrvoucher.VoucherCode,''),' ',
              COALESCE(mbrentry.EntryCode,''),' ',
              COALESCE(mbr.FirstName,''),' ',
              COALESCE(mbr.MiddleName,''), ' ', 
              COALESCE(mbr.LastName,''),' ',
              COALESCE(order.OrderNo,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("(CASE
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_AVAILABLE')."'  THEN 1
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_USED')."'  THEN 2
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                  ELSE 0
              END) ASC");
      $query->orderBy("mbrvoucher.NthPair","ASC");
      $query->orderBy("mbrvoucher.VoucherID","ASC");

      $list = $query->get();

      return $list;
    }

    public function getMemberVoucherInfo($VoucherID){

      $info = DB::table('membervoucher as mbrvoucher')
        ->join('memberentry as mbrentry', 'mbrvoucher.MemberEntryID', '=', 'mbrentry.EntryID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'mbr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'mbr.CountryID')
        ->leftjoin('order', 'order.OrderID', '=', 'mbrvoucher.UsedByOrderID')
        ->selectraw("
              COALESCE(mbrvoucher.VoucherID,0) as VoucherID,

              COALESCE(mbrvoucher.MemberEntryID,0) as MemberEntryID,
              COALESCE(mbrentry.EntryCode,'') as EntryCode,
              COALESCE(mbr.MemberNo,'') as MemberNo,
              CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
              COALESCE(mbr.EmailAddress,'') as EmailAddress,
              COALESCE(mbr.TelNo,'') as TelNo,
              COALESCE(mbr.MobileNo,'') as MobileNo,

              COALESCE(mbr.Address,'') as Address,
              COALESCE(mbr.CityID,0) as CityID,
              COALESCE(cty.City,'') as City,
              COALESCE(mbr.StateProvince,'') as StateProvince,
              COALESCE(mbr.ZipCode,'') as ZipCode,
              COALESCE(mbr.CountryID,0) as CountryID,
              COALESCE(ctry.Country,'') as Country,

              COALESCE(mbrvoucher.VoucherCode,'') as VoucherCode,
              COALESCE(mbrvoucher.VoucherAmount,0) as VoucherAmount,
              COALESCE(mbrvoucher.NthPair,0) as NthPair,
              COALESCE(mbrvoucher.Remarks,'') as Remarks,

              COALESCE(mbrvoucher.UsedByEntryID,0) as UsedByEntryID,

              COALESCE(mbrvoucher.UsedByOrderID,0) as UsedByOrderID,
              COALESCE(order.OrderNo,'') as OrderNo,
              COALESCE(order.OrderDateTime,'') as OrderDateTime,

              COALESCE(mbrvoucher.Status,'') as Status,

              CASE
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_AVAILABLE')."'  THEN 1
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_USED')."'  THEN 2
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                  ELSE 0
              END as SortOption,

              COALESCE(mbrvoucher.DateTimeCreated,'') as DateTimeCreated

          ")
        ->where('mbrvoucher.VoucherID',$VoucherID)
        ->first();

      return $info;

    }

    public function getMemberVoucherInfoByVoucherCode($VoucherCode){

      $info = DB::table('membervoucher as mbrvoucher')
        ->join('memberentry as mbrentry', 'mbrvoucher.MemberEntryID', '=', 'mbrentry.EntryID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->join('countrycities as cty', 'cty.CityID', '=', 'mbr.CityID')
        ->join('country as ctry', 'ctry.CountryID', '=', 'mbr.CountryID')
        ->leftjoin('order', 'order.OrderID', '=', 'mbrvoucher.UsedByOrderID')
        ->selectraw("
              COALESCE(mbrvoucher.VoucherID,0) as VoucherID,

              COALESCE(mbrvoucher.MemberEntryID,0) as MemberEntryID,
              COALESCE(mbrentry.EntryCode,'') as EntryCode,
              COALESCE(mbr.MemberNo,'') as MemberNo,
              CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
              COALESCE(mbr.EmailAddress,'') as EmailAddress,
              COALESCE(mbr.TelNo,'') as TelNo,
              COALESCE(mbr.MobileNo,'') as MobileNo,

              COALESCE(mbr.Address,'') as Address,
              COALESCE(mbr.CityID,0) as CityID,
              COALESCE(cty.City,'') as City,
              COALESCE(mbr.StateProvince,'') as StateProvince,
              COALESCE(mbr.ZipCode,'') as ZipCode,
              COALESCE(mbr.CountryID,0) as CountryID,
              COALESCE(ctry.Country,'') as Country,

              COALESCE(mbrvoucher.VoucherCode,'') as VoucherCode,
              COALESCE(mbrvoucher.VoucherAmount,0) as VoucherAmount,
              COALESCE(mbrvoucher.NthPair,0) as NthPair,
              COALESCE(mbrvoucher.Remarks,'') as Remarks,

              COALESCE(mbrvoucher.UsedByEntryID,0) as UsedByEntryID,

              COALESCE(mbrvoucher.UsedByOrderID,0) as UsedByOrderID,
              COALESCE(order.OrderNo,'') as OrderNo,
              COALESCE(order.OrderDateTime,'') as OrderDateTime,

              COALESCE(mbrvoucher.Status,'') as Status,

              CASE
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_AVAILABLE')."'  THEN 1
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_USED')."'  THEN 2
                  WHEN COALESCE(mbrvoucher.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                  ELSE 0
              END as SortOption

          ")
        ->where('mbrvoucher.VoucherCode',$VoucherCode)
        ->first();

      return $info;
      
    }





}
