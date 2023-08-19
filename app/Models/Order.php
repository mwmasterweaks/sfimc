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
use App\Models\GiftCertificate;

class Order extends Model
{

  public function getOrderList($param)
  {

    $TODAY = date("Y-m-d H:i:s");

    $EntryID = $param['EntryID'];

    $CenterID = $param['CenterID'];
    $CustomerEntryID = $param['CustomerEntryID'];
    $Status = $param['Status'];
    $SearchText = trim($param['SearchText']);
    $Limit = $param['Limit'];
    $PageNo = $param['PageNo'];

    ini_set('memory_limit', '999999M');

    $query = DB::table('order')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'order.CenterID')
      ->join('countrycities as cty', 'cty.CityID', '=', 'order.CityID')
      ->join('country as ctry', 'ctry.CountryID', '=', 'order.CountryID')
      ->join('useraccount as apvby', 'order.ApprovedByID', '=', 'apvby.UserAccountID')
      ->leftjoin('shipper', 'shipper.ShipperID', '=', 'order.ShipperID')
      ->selectraw("
            COALESCE(order.OrderID,0) as OrderID,
            COALESCE(order.OrderNo,'') as OrderNo,
            COALESCE(order.OrderDateTime,'') as OrderDateTime,
            COALESCE(order.CustomerType,'') as CustomerType,

            COALESCE(order.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,

            COALESCE(order.CustomerEntryID,0) as CustomerEntryID,
            COALESCE(order.CustomerName,'') as CustomerName,
            COALESCE(order.EmailAddress,'') as EmailAddress,
            COALESCE(order.MobileNo,'') as MobileNo,

            COALESCE(order.Address,'') as Address,
            COALESCE(order.CityID,0) as CityID,
            COALESCE(cty.City,'') as City,
            COALESCE(order.ZipCode,'') as ZipCode,
            COALESCE(order.StateProvince,'') as StateProvince,
            COALESCE(order.CountryID,0) as CountryID,
            COALESCE(ctry.Country,'') as Country,

            COALESCE(order.GrossTotal,0) as GrossTotal,
            COALESCE(order.ShippingCharges,0) as ShippingCharges,
            COALESCE(order.TotalDiscountPercent,0) as TotalDiscountPercent,
            COALESCE(order.TotalDiscountAmount,0) as TotalDiscountAmount,
            COALESCE(order.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(order.ShipperID,'') as ShipperID,
            COALESCE(shipper.ShipperName,'') as ShipperName,
            COALESCE(order.ShipperTrackingNo,'') as ShipperTrackingNo,

            COALESCE(order.ModeOfPayment,'') as ModeOfPayment,
            COALESCE(order.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(order.TotalEWalletPayment,0) as TotalEWalletPayment,
            COALESCE(order.TotalCashPayment,0) as TotalCashPayment,
            COALESCE(order.AmountChange,0) as AmountChange,
            COALESCE(order.TotalRebatableValue,0) as TotalRebatableValue,

            COALESCE(order.Remarks,'') as Remarks,
            COALESCE(order.Status,'') as Status,

            COALESCE(order.Source,'') as Source,

            COALESCE(order.IsPaid,0) as IsPaid,
            COALESCE(order.SetPaidByID,0) as SetPaidByID,
            COALESCE(order.SetPaidDateTime,'') as SetPaidDateTime,

            CASE
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_PENDING') . "'  THEN 1
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_UNVERIFIED') . "'  THEN 2
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_VERIFIED') . "'  THEN 3
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_PACKED') . "'  THEN 4
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_SHIPPED') . "'  THEN 5
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_DELIVERED') . "' THEN 6
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_RETURNED') . "' THEN 7
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_CANCELLED') . "' THEN 8
                ELSE 0
            END as SortOption,

            COALESCE(order.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(order.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(order.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(order.DateTimeUpdated,'') as DateTimeUpdated

        ");

    if ($EntryID > 0) {
      $query->whereraw("COALESCE(order.CustomerEntryID,0) = " . $EntryID);
    }

    if ($CenterID > 0) {
      $query->whereraw("COALESCE(order.CenterID,0) = " . $CenterID);
    }

    if ($CustomerEntryID > 0) {
      $query->whereraw("COALESCE(order.CustomerEntryID,0) = " . $CustomerEntryID);
    }

    if ($SearchText != '') {
      $query->whereraw(
        "CONCAT(
            COALESCE(order.OrderNo,''),' ',
            COALESCE(order.CustomerName,''),' ',
            COALESCE(order.EmailAddress,''),' ',
            COALESCE(order.MobileNo,''),' ',
            COALESCE(order.Remarks,'')
            ) like '%" . str_replace("'", "''", $SearchText) . "%'"
      );
    }

    if ($Status != '') {
      if ($Status == config('app.STATUS_COLLECTED')) {
        $query->whereraw("COALESCE(order.Status,'') != '" . config('app.STATUS_CANCELLED') . "'");
        $query->whereraw("COALESCE(order.Status,'') != '" . config('app.STATUS_RETURNED') . "'");
        $query->whereraw("COALESCE(order.IsPaid,0) = 1");
      } elseif ($Status == config('app.STATUS_UNCOLLECTED')) {
        $query->whereraw("COALESCE(order.Status,'') != '" . config('app.STATUS_CANCELLED') . "'");
        $query->whereraw("COALESCE(order.Status,'') != '" . config('app.STATUS_RETURNED') . "'");
        $query->whereraw("COALESCE(order.IsPaid,0) = 0");
      } else {
        $query->where("order.Status", $Status);
      }
    }

    if ($Limit > 0) {
      $query->limit($Limit);
      $query->offset(($PageNo - 1) * $Limit);
    }

    $query->orderByraw("(CASE
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_PENDING') . "'  THEN 1
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_UNVERIFIED') . "'  THEN 2
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_VERIFIED') . "'  THEN 3
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_PACKED') . "'  THEN 4
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_SHIPPED') . "'  THEN 5
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_DELIVERED') . "' THEN 6
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_RETURNED') . "' THEN 7
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_CANCELLED') . "' THEN 8
                ELSE 0
            END) ASC");

    $query->orderByraw("COALESCE(order.OrderDateTime,'') DESC");

    $list = $query->get();

    return $list;
  }

  public function getOrderInfo($OrderID)
  {

    $TODAY = date("Y-m-d H:i:s");

    $info = DB::table('order')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'order.CenterID')
      ->join('countrycities as cty', 'cty.CityID', '=', 'order.CityID')
      ->join('country as ctry', 'ctry.CountryID', '=', 'order.CountryID')
      ->join('useraccount as apvby', 'order.ApprovedByID', '=', 'apvby.UserAccountID')
      ->leftjoin('shipper', 'shipper.ShipperID', '=', 'order.ShipperID')
      ->selectraw("
            COALESCE(order.OrderID,0) as OrderID,
            COALESCE(order.OrderNo,'') as OrderNo,
            COALESCE(order.OrderDateTime,'') as OrderDateTime,
            COALESCE(order.CustomerType,'') as CustomerType,

            COALESCE(order.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            COALESCE(ctr.TelNo,'') as CenterTelNo,
            COALESCE(ctr.MobileNo,'') as CenterMobileNo,
            COALESCE(ctr.EmailAddress,'') as CenterEmailAddress,

            COALESCE(order.CustomerEntryID,0) as CustomerEntryID,
            COALESCE(order.CustomerName,'') as CustomerName,
            COALESCE(order.EmailAddress,'') as EmailAddress,
            COALESCE(order.MobileNo,'') as MobileNo,

            COALESCE(order.Address,'') as Address,
            COALESCE(order.CityID,0) as CityID,
            COALESCE(cty.City,'') as City,
            COALESCE(order.ZipCode,'') as ZipCode,
            COALESCE(order.StateProvince,'') as StateProvince,
            COALESCE(order.CountryID,0) as CountryID,
            COALESCE(ctry.Country,'') as Country,

            COALESCE(order.GrossTotal,0) as GrossTotal,
            COALESCE(order.ShippingCharges,0) as ShippingCharges,
            COALESCE(order.TotalDiscountPercent,0) as TotalDiscountPercent,
            COALESCE(order.TotalDiscountAmount,0) as TotalDiscountAmount,
            COALESCE(order.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(order.ShipperID,'') as ShipperID,
            COALESCE(shipper.ShipperName,'') as ShipperName,
            COALESCE(order.ShipperTrackingNo,'') as ShipperTrackingNo,

            COALESCE(order.ModeOfPayment,'') as ModeOfPayment,
            COALESCE(order.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(order.TotalEWalletPayment,0) as TotalEWalletPayment,
            COALESCE(order.TotalCashPayment,0) as TotalCashPayment,
            COALESCE(order.AmountChange,0) as AmountChange,
            COALESCE(order.TotalRebatableValue,0) as TotalRebatableValue,

            COALESCE(order.Remarks,'') as Remarks,
            COALESCE(order.Status,'') as Status,

            COALESCE(order.Source,'') as Source,

            COALESCE(order.IsPaid,0) as IsPaid,
            COALESCE(order.SetPaidByID,0) as SetPaidByID,
            COALESCE(order.SetPaidDateTime,'') as SetPaidDateTime,

            CASE
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_PENDING') . "'  THEN 1
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_UNVERIFIED') . "'  THEN 2
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_VERIFIED') . "'  THEN 3
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_PACKED') . "'  THEN 4
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_SHIPPED') . "'  THEN 5
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_DELIVERED') . "' THEN 6
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_RETURNED') . "' THEN 7
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_CANCELLED') . "' THEN 8
                ELSE 0
            END as SortOption,

            COALESCE(order.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(order.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(order.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(order.DateTimeUpdated,'') as DateTimeUpdated

        ")
      ->where('order.OrderID', $OrderID)
      ->first();

    return $info;
  }

  public function getOrderInfoByOrderNo($OrderNo)
  {

    $TODAY = date("Y-m-d H:i:s");

    $info = DB::table('order')
      ->join('centers as ctr', 'ctr.CenterID', '=', 'order.CenterID')
      ->join('countrycities as cty', 'cty.CityID', '=', 'order.CityID')
      ->join('country as ctry', 'ctry.CountryID', '=', 'order.CountryID')
      ->join('useraccount as apvby', 'order.ApprovedByID', '=', 'apvby.UserAccountID')
      ->leftjoin('shipper', 'shipper.ShipperID', '=', 'order.ShipperID')
      ->selectraw("
            COALESCE(order.OrderID,0) as OrderID,
            COALESCE(order.OrderNo,'') as OrderNo,
            COALESCE(order.OrderDateTime,'') as OrderDateTime,
            COALESCE(order.CustomerType,'') as CustomerType,

            COALESCE(order.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,

            COALESCE(order.CustomerEntryID,0) as CustomerEntryID,
            COALESCE(order.CustomerName,'') as CustomerName,
            COALESCE(order.EmailAddress,'') as EmailAddress,
            COALESCE(order.MobileNo,'') as MobileNo,

            COALESCE(order.Address,'') as Address,
            COALESCE(order.CityID,0) as CityID,
            COALESCE(cty.City,'') as City,
            COALESCE(order.ZipCode,'') as ZipCode,
            COALESCE(order.StateProvince,'') as StateProvince,
            COALESCE(order.CountryID,0) as CountryID,
            COALESCE(ctry.Country,'') as Country,

            COALESCE(order.GrossTotal,0) as GrossTotal,
            COALESCE(order.ShippingCharges,0) as ShippingCharges,
            COALESCE(order.TotalDiscountPercent,0) as TotalDiscountPercent,
            COALESCE(order.TotalDiscountAmount,0) as TotalDiscountAmount,
            COALESCE(order.TotalAmountDue,0) as TotalAmountDue,

            COALESCE(order.ShipperID,'') as ShipperID,
            COALESCE(shipper.ShipperName,'') as ShipperName,
            COALESCE(order.ShipperTrackingNo,'') as ShipperTrackingNo,

            COALESCE(order.ModeOfPayment,'') as ModeOfPayment,
            COALESCE(order.TotalVoucherPayment,0) as TotalVoucherPayment,
            COALESCE(order.TotalEWalletPayment,0) as TotalEWalletPayment,
            COALESCE(order.TotalCashPayment,0) as TotalCashPayment,
            COALESCE(order.AmountChange,0) as AmountChange,
            COALESCE(order.TotalRebatableValue,0) as TotalRebatableValue,

            COALESCE(order.Remarks,'') as Remarks,
            COALESCE(order.Status,'') as Status,

            COALESCE(order.Source,'') as Source,

            COALESCE(order.IsPaid,0) as IsPaid,
            COALESCE(order.SetPaidByID,0) as SetPaidByID,
            COALESCE(order.SetPaidDateTime,'') as SetPaidDateTime,

            CASE
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_PENDING') . "'  THEN 1
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_UNVERIFIED') . "'  THEN 2
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_VERIFIED') . "'  THEN 3
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_PACKED') . "'  THEN 4
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_SHIPPED') . "'  THEN 5
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_DELIVERED') . "' THEN 6
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_RETURNED') . "' THEN 7
                WHEN COALESCE(order.Status,'') = '" . config('app.STATUS_CANCELLED') . "' THEN 8
                ELSE 0
            END as SortOption,

            COALESCE(order.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(order.ApprovedDateTime,'') as ApprovedDateTime,

            COALESCE(order.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(order.DateTimeUpdated,'') as DateTimeUpdated

        ")
      ->where('order.OrderNo', '=', $OrderNo)
      ->first();

    return $info;
  }

  public function doSaveUpdateOrder($data)
  {

    $Misc  = new Misc();
    $MemberEntry  = new MemberEntry();

    $TODAY = date("Y-m-d H:i:s");

    $CenterID = $data['CenterID'];
    $OrderID = $data['OrderID'];

    $CustomerType = $data['CustomerType'];
    $CustomerEntryID = $data['CustomerEntryID'];
    $CustomerName = $data['CustomerName'];
    $EmailAddress = $data['EmailAddress'];
    $MobileNo = $data['MobileNo'];

    $Address = $data['Address'];
    $CityID = $data['CityID'];
    $StateProvince = $data['StateProvince'];
    $ZipCode = $data['ZipCode'];
    $CountryID = $data['CountryID'];

    if ($CustomerEntryID > 0) {
      $MemberEntryInfo = $MemberEntry->getMemberEntryInfo($CustomerEntryID);
      if (isset($MemberEntryInfo)) {
        $CustomerName = $MemberEntryInfo->MemberName;
        $EmailAddress = (empty($EmailAddress) ? $MemberEntryInfo->EmailAddress : $EmailAddress);
        $MobileNo = (empty($MobileNo) ? $MemberEntryInfo->MobileNo : $MobileNo);
      }
    }

    $GrossTotal = $data['GrossTotal'];
    $ShippingCharges = $data['ShippingCharges'];
    $TotalDiscountPercent = $data['TotalDiscountPercent'];
    $TotalDiscountAmount = $data['TotalDiscountAmount'];
    $TotalAmountDue = $data['TotalAmountDue'];

    $ShipperID = $data['ShipperID'];

    $ModeOfPayment = $data['ModeOfPayment'];
    $TotalVoucherPayment = $data['TotalVoucherPayment'];
    $TotalEWalletPayment = $data['TotalEWalletPayment'];
    $TotalCashPayment = $data['TotalCashPayment'];
    $AmountChange = $data['AmountChange'];
    $TotalRebatableValue = $data['TotalRebatableValue'];

    $ApprovedByID = $data['ApprovedByID'];
    $CreatedByID = $data['CreatedByID'];
    $UpdatedByID = $data['UpdatedByID'];

    $Remarks = $data['Remarks'];
    $Status = $data['Status'];

    if ($OrderID > 0) {
      //Revert Order Items
      //$this->doRevertOrderItems($OrderID, "Edit");

      DB::table('order')
        ->where('OrderID', $OrderID)
        ->update([

          'CenterID' => $CenterID,

          'CustomerType' => $CustomerType,
          'CustomerEntryID' => $CustomerEntryID,

          'CustomerName' => $CustomerName,
          'EmailAddress' => $EmailAddress,
          'MobileNo' => $MobileNo,

          'Address' => $Address,
          'CityID' => $CityID,
          'StateProvince' => $StateProvince,
          'ZipCode' => $ZipCode,
          'CountryID' => $CountryID,

          'GrossTotal' => $GrossTotal,
          'ShippingCharges' => $ShippingCharges,
          'TotalDiscountPercent' => $TotalDiscountPercent,
          'TotalDiscountAmount' => $TotalDiscountAmount,
          'TotalAmountDue' => $TotalAmountDue,

          'ShipperID' => $ShipperID,

          'ModeOfPayment' => $ModeOfPayment,
          'TotalVoucherPayment' => $TotalVoucherPayment,
          'TotalEWalletPayment' => $TotalEWalletPayment,
          'TotalCashPayment' => $TotalCashPayment,
          'AmountChange' => $AmountChange,
          'TotalRebatableValue' => $TotalRebatableValue,

          'Remarks' => $Remarks,

          'ApprovedByID' => $ApprovedByID,
          'ApprovedDateTime' => $TODAY,

          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' => $TODAY
        ]);

      //Save Transaction Log
      $logData['TransRefID'] = $OrderID;
      $logData['TransactedByID'] = $UpdatedByID;
      $logData['ModuleType'] = "Order";
      $logData['TransType'] = "Update Order Information";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    } else {

      $OrderNo = $Misc->GenerateRandomNo(6, 'order', 'OrderNo');

      $Status = config('app.STATUS_UNVERIFIED');
      $Source = config('app.SOURCE_ADMIN');

      $OrderID =  DB::table('order')
        ->insertGetId([

          'CenterID' => $CenterID,

          'OrderNo' => $OrderNo,
          'OrderDateTime' => $TODAY,

          'CustomerType' => $CustomerType,
          'CustomerEntryID' => $CustomerEntryID,

          'CustomerName' => $CustomerName,
          'EmailAddress' => $EmailAddress,
          'MobileNo' => $MobileNo,

          'Address' => $Address,
          'CityID' => $CityID,
          'StateProvince' => $StateProvince,
          'ZipCode' => $ZipCode,
          'CountryID' => $CountryID,

          'GrossTotal' => $GrossTotal,
          'ShippingCharges' => $ShippingCharges,
          'TotalDiscountPercent' => $TotalDiscountPercent,
          'TotalDiscountAmount' => $TotalDiscountAmount,
          'TotalAmountDue' => $TotalAmountDue,

          'ShipperID' => $ShipperID,

          'ModeOfPayment' => $ModeOfPayment,
          'TotalVoucherPayment' => $TotalVoucherPayment,
          'TotalEWalletPayment' => $TotalEWalletPayment,
          'TotalCashPayment' => $TotalCashPayment,
          'AmountChange' => $AmountChange,
          'TotalRebatableValue' => $TotalRebatableValue,

          'Remarks' => $Remarks,
          'Status' => $Status,

          'Source' => $Source,

          'ApprovedByID' => $ApprovedByID,
          'ApprovedDateTime' => $TODAY,

          'CreatedByID' => $CreatedByID,
          'DateTimeCreated' => $TODAY,

          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' => $TODAY

        ]);

      //Vouchers
      if ($CustomerEntryID > 0) {
        $VoucherData = $data['VoucherData'];
        if (!empty($VoucherData)) {

          for ($x = 0; $x < count($VoucherData); $x++) {
            $VoucherID = $VoucherData[$x]["VoucherID"];

            $ID =  DB::table('ordervoucher')
              ->insertGetId([
                'OrderID' => $OrderID,
                'VoucherID' => $VoucherID
              ]);

            //Update voucher Status
            DB::table('membervoucher')
              ->where('VoucherID', $VoucherID)
              ->update([
                'Status' => config('app.STATUS_USED'),
                'UsedByEntryID' => $CustomerEntryID,
                'UsedByOrderID' => $OrderID,
                'OwnedByCenterID' => $CenterID
              ]);
          }
        }
      }

      //Save Transaction Log
      $logData['TransRefID'] = $OrderID;
      $logData['TransactedByID'] = $CreatedByID;
      $logData['ModuleType'] = "Order";
      $logData['TransType'] = "New Order";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);

      $data['OrderID'] = $OrderID;
    }

    $Result = $this->doSaveUpdateOrderItems($data);

    return $OrderID;
  }

  public function doSaveUpdateOrderItems($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $Product = new Product();

    $OrderID = $data['OrderID'];
    $CenterID = $data['CenterID'];
    $OrderItems = $data['OrderItems'];
    $OrderItemsDeleted = $data['OrderItemsDeleted'];

    if (!empty($OrderItemsDeleted)) {

      //Deleted Supplier Products
      for ($x = 0; $x < count($OrderItemsDeleted); $x++) {
        DB::table('orderitem')
          ->where('OrderItemID', '=', $OrderItemsDeleted[$x])
          ->delete();
      }
    }

    if (!empty($OrderItems)) {

      for ($x = 0; $x < count($OrderItems); $x++) {

        $OrderItemID = $OrderItems[$x]["OrderItemID"];

        $ProductID = $OrderItems[$x]["ProductID"];
        $Qty = $OrderItems[$x]["Qty"];
        $Price = $OrderItems[$x]["Price"];
        $SubTotal = $OrderItems[$x]["SubTotal"];
        $RebatableValue = $OrderItems[$x]["RebatableValue"];

        if ($OrderItemID > 0) {
          if ($ProductID > 0) {
            DB::table('orderitem')
              ->where('OrderItemID', $OrderItemID)
              ->update([
                'OrderID' => $OrderID,
                'ProductID' => $ProductID,
                'Qty' => $Qty,
                'Price' => $Price,
                'SubTotal' => $SubTotal,
                'RebatableValue' => $RebatableValue,
                'DateTimeUpdated' => $TODAY
              ]);
          }
        } else {
          if ($ProductID > 0) {
            $OrderItemID =  DB::table('orderitem')
              ->insertGetId([
                'OrderID' => $OrderID,
                'ProductID' => $ProductID,
                'Qty' => $Qty,
                'Price' => $Price,
                'SubTotal' => $SubTotal,
                'RebatableValue' => $RebatableValue,
                'DateTimeCreated' => $TODAY,
                'DateTimeUpdated' => $TODAY
              ]);
          }
        }
      }
    }

    return "Success";
  }

  public function getOrderItemList($param)
  {

    $TODAY = date("Y-m-d H:i:s");

    $OrderID = $param['OrderID'];

    ini_set('memory_limit', '999999M');

    $query = DB::table('orderitem as oi')
      ->join('order', 'order.OrderID', '=', 'oi.OrderID')
      ->join('product', 'product.ProductID', '=', 'oi.ProductID')
      ->join("productinventory as inv", function ($join) {
        $join->on('inv.CenterID', '=', 'order.CenterID')
          ->on('inv.ProductID', '=', 'oi.ProductID');
      })
      ->selectraw("
            COALESCE(oi.OrderItemID,0) as OrderItemID,
            COALESCE(oi.OrderID,0) as OrderID,
            COALESCE(oi.ProductID,0) as ProductID,
            COALESCE(product.ProductCode,'') as ProductCode,
            COALESCE(product.ProductName,'') as ProductName,
            COALESCE(product.Category,'') as Category,
            COALESCE(product.Brand,'') as Brand,
            COALESCE(oi.Qty,0) as Qty,
            COALESCE(oi.RebatableValue,0) as RebatableValue,
            COALESCE(inv.StockOnHand,0) as StockOnHand,
            COALESCE(product.Measurement,'') as Measurement,
            COALESCE(oi.Price,0) as Price,
            COALESCE(oi.SubTotal,0) as SubTotal
        ")
      ->where('oi.OrderID', $OrderID);

    $query->orderBy("product.ProductName", "ASC");

    $list = $query->get();

    return $list;
  }

  public function getOrderVoucherList($param)
  {

    $TODAY = date("Y-m-d H:i:s");

    $OrderID = $param['OrderID'];

    ini_set('memory_limit', '999999M');

    $query = DB::table('ordervoucher as ov')
      ->join('membervoucher as mv', 'ov.VoucherID', '=', 'mv.VoucherID')
      ->selectraw("
            COALESCE(ov.ID,0) as ID,
            COALESCE(ov.OrderID,0) as OrderID,
            COALESCE(ov.VoucherID,0) as VoucherID,
            COALESCE(mv.VoucherCode,'') as VoucherCode,
            COALESCE(mv.NthPair,0) as NthPair,
            COALESCE(mv.VoucherAmount,'') as VoucherAmount
        ")
      ->where('ov.OrderID', $OrderID);

    $query->orderBy("mv.NthPair", "ASC");

    $list = $query->get();

    return $list;
  }

  public function doPaidOrder($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $Misc = new Misc();

    $OrderID = $data['OrderID'];
    $SetPaidByID = $data['SetPaidByID'];

    if ($OrderID > 0) {
      DB::table('order')
        ->where('OrderID', $OrderID)
        ->update([
          'IsPaid' => 1,
          'SetPaidByID' => $SetPaidByID,
          'SetPaidDateTime' => $TODAY
        ]);

      $data['OrderID'] = $OrderID;

      //Distribute For Personal And Group Purchases
      DB::statement("call spSetAccumulatedOrder(" . $OrderID . ",'" . $TODAY . "')");

      //Save Transaction Log
      $logData['TransRefID'] = $OrderID;
      $logData['TransactedByID'] = $SetPaidByID;
      $logData['ModuleType'] = "Order";
      $logData['TransType'] = "Order Set As Paid";
      $logData['Remarks'] = "";
      $Misc->doSaveTransactionLog($logData);
    }

    return $OrderID;
  }

  public function doCancelOrder($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $OrderID = $data['OrderID'];
    $CancelledByID = $data['CancelledByID'];
    $Reason = $data['Reason'];

    if ($OrderID > 0) {
      DB::table('order')
        ->where('OrderID', $OrderID)
        ->update([
          'CancelledByID' => $CancelledByID,
          'CancellationReason' => $Reason,
          'Status' => config('app.STATUS_CANCELLED'),
          'DateTimeUpdated' => $TODAY
        ]);

      //Save Transaction Log
      $Misc = new Misc();
      $logData['TransRefID'] = $OrderID;
      $logData['TransactedByID'] = $CancelledByID;
      $logData['ModuleType'] = "Order";
      $logData['TransType'] = "Order Cancelled";
      $logData['Remarks'] = $Reason;
      $Misc->doSaveTransactionLog($logData);
    }

    return $OrderID;
  }

  public function doVerifyOrder($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $Misc = new Misc();

    $OrderID = $data['OrderID'];
    $VerifiedByID = $data['VerifiedByID'];

    if ($OrderID > 0) {

      $OrderInfo = $this->getOrderInfo($OrderID);

      if (isset($OrderInfo)) {

        DB::table('order')
          ->where('OrderID', $OrderID)
          ->update([
            'Status' => config('app.STATUS_VERIFIED'),
            'VerifiedByID' => $VerifiedByID,
            'VerifiedDateTime' => $TODAY
          ]);

        //Save Transaction Log
        $logData['TransRefID'] = $OrderID;
        $logData['TransactedByID'] = $VerifiedByID;
        $logData['ModuleType'] = "Order";
        $logData['TransType'] = "Order Verified";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);
      }
    }

    return $OrderID;
  }

  public function doPackedOrder($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $Misc = new Misc();

    $OrderID = $data['OrderID'];
    $PackedByID = $data['PackedByID'];

    if ($OrderID > 0) {

      $OrderInfo = $this->getOrderInfo($OrderID);

      if (isset($OrderInfo)) {

        DB::table('order')
          ->where('OrderID', $OrderID)
          ->update([
            'Status' => config('app.STATUS_PACKED'),
            'SetAsPackedByID' => $PackedByID,
            'SetAsPackedDateTime' => $TODAY
          ]);

        //Save Transaction Log
        $logData['TransRefID'] = $OrderID;
        $logData['TransactedByID'] = $PackedByID;
        $logData['ModuleType'] = "Order";
        $logData['TransType'] = "Order Packed";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

        //Get Order Item
        $param["OrderID"] = $OrderID;
        $OrderItem = $this->getOrderItemList($param);

        if (count($OrderItem) > 0) {
          foreach ($OrderItem as $oi) {
            $Inventory = new Inventory();
            $param['CenterID'] = $OrderInfo->CenterID;
            $param['ProductID'] = $oi->ProductID;
            $param["Type"] = "OUT";
            $param['Qty'] = $oi->Qty;
            $param["TransType"] = "Order";
            $param['TransactionRefID'] = $OrderID;
            $param['Remarks'] = "";
            $Inventory->doSaveInventoryChanges($param);
          }
        }
      }
    }

    return $OrderID;
  }

  public function doShippedOrder($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $Misc = new Misc();

    $OrderID = $data['OrderID'];
    $ShipperTrackingNo = $data['ShipperTrackingNo'];
    $SetAsShippedByID = $data['SetAsShippedByID'];

    if ($OrderID > 0) {

      $OrderInfo = $this->getOrderInfo($OrderID);

      if (isset($OrderInfo)) {

        DB::table('order')
          ->where('OrderID', $OrderID)
          ->update([
            'Status' => config('app.STATUS_SHIPPED'),
            'ShipperTrackingNo' => $ShipperTrackingNo,
            'SetAsShippedByID' => $SetAsShippedByID,
            'SetAsShippedDateTime' => $TODAY
          ]);

        //Save Transaction Log
        $logData['TransRefID'] = $OrderID;
        $logData['TransactedByID'] = $SetAsShippedByID;
        $logData['ModuleType'] = "Order";
        $logData['TransType'] = "Order Shipped";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);
      }
    }

    return $OrderID;
  }

  public function doSetAsDeliveredOrder($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    $Misc = new Misc();

    $OrderID = $data['OrderID'];
    $SetAsDeliveredByID = $data['SetAsDeliveredByID'];

    if ($OrderID > 0) {

      $OrderInfo = $this->getOrderInfo($OrderID);

      if (isset($OrderInfo)) {

        DB::table('order')
          ->where('OrderID', $OrderID)
          ->update([
            'Status' => config('app.STATUS_DELIVERED'),
            'SetAsDeliveredByID' => $SetAsDeliveredByID,
            'SetAsDeliveredDateTime' => $TODAY
          ]);

        //Save Transaction Log
        $logData['TransRefID'] = $OrderID;
        $logData['TransactedByID'] = $SetAsDeliveredByID;
        $logData['ModuleType'] = "Order";
        $logData['TransType'] = "Order Delivered";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);
      }
    }

    return $OrderID;
  }

  public function doRevertOrderItems($OrderID, $Remarks)
  {

    $Inventory  = new Inventory();

    //Revert Inventory receive
    $itemlist = DB::table('orderitem as oi')
      ->join('order', 'order.OrderID', '=', 'oi.OrderID')
      ->selectraw("
              COALESCE(order.CenterID,0) as CenterID,
              COALESCE(oi.OrderID,0) as OrderID,
              COALESCE(oi.OrderItemID,0) as OrderItemID,
              COALESCE(oi.ProductID,0) as ProductID,
              COALESCE(oi.Qty,0) as Qty
          ")
      ->where('oi.OrderID', $OrderID)
      ->get();

    if (count($itemlist) > 0) {
      foreach ($itemlist as $ikey) {
        $param['CenterID'] = $ikey->CenterID;
        $param['ProductID'] = $ikey->ProductID;
        $param['Type'] = "Remove From Out";
        $param['Qty'] = $ikey->Qty;
        $param['TransType'] = "Order";
        $param['TransactionRefID'] = $OrderID;
        $param['Remarks'] = $Remarks;

        $Inventory->doSaveInventoryChanges($param);
      }
    }
  }

  public function doCheckoutOrder($data)
  {

    $Misc  = new Misc();
    $MemberEntry  = new MemberEntry();
    $CustomerCart = new CustomerCart();
    $Email = new Email();

    $TODAY = date("Y-m-d H:i:s");

    $CenterID = $data['CenterID'];

    $CustomerType = $data['CustomerType'];
    $CustomerEntryID = $data['CustomerEntryID'];

    $CustomerName = $data['CustomerName'];
    $EmailAddress = $data['EmailAddress'];
    $MobileNo = $data['MobileNo'];

    $Address = $data['Address'];
    $CityID = $data['CityID'];
    $StateProvince = $data['StateProvince'];
    $ZipCode = $data['ZipCode'];
    $CountryID = $data['CountryID'];

    $GrossTotal = $data['GrossTotal'];
    $ShippingCharges = $data['ShippingCharges'];
    $TotalDiscountPercent = $data['TotalDiscountPercent'];
    $TotalDiscountAmount = $data['TotalDiscountAmount'];
    $TotalAmountDue = $data['TotalAmountDue'];

    $ShipperID = $data['ShipperID'];
    $ModeOfPayment = $data['ModeOfPayment'];
    $TotalVoucherPayment = $data['TotalVoucherPayment'];
    $TotalEWalletPayment = $data['TotalEWalletPayment'];
    $TotalCashPayment = $data['TotalCashPayment'];
    $AmountChange = $data['AmountChange'];
    $TotalRebatableValue = $data['TotalRebatableValue'];

    $Remarks = $data['Remarks'];
    $Status = $data['Status'];

    $Source = $data['Source'];

    $ApprovedByID = $data['ApprovedByID'];
    $CreatedByID = $data['CreatedByID'];
    $UpdatedByID = $data['UpdatedByID'];

    $Cart = $data["Cart"];

    $OrderNo = $Misc->GenerateRandomNo(6, 'order', 'OrderNo');

    $OrderID =  DB::table('order')
      ->insertGetId([

        'CenterID' => $CenterID,

        'OrderNo' => $OrderNo,
        'OrderDateTime' => $TODAY,

        'CustomerType' => $CustomerType,
        'CustomerEntryID' => $CustomerEntryID,

        'CustomerName' => $CustomerName,
        'EmailAddress' => $EmailAddress,
        'MobileNo' => $MobileNo,

        'Address' => $Address,
        'CityID' => $CityID,
        'StateProvince' => $StateProvince,
        'ZipCode' => $ZipCode,
        'CountryID' => $CountryID,

        'GrossTotal' => $GrossTotal,
        'ShippingCharges' => $ShippingCharges,
        'TotalDiscountPercent' => $TotalDiscountPercent,
        'TotalDiscountAmount' => $TotalDiscountAmount,
        'TotalAmountDue' => $TotalAmountDue,

        'ShipperID' => $ShipperID,
        'ModeOfPayment' => $ModeOfPayment,
        'TotalVoucherPayment' => $TotalVoucherPayment,
        'TotalEWalletPayment' => $TotalEWalletPayment,
        'TotalCashPayment' => $TotalCashPayment,
        'AmountChange' => $AmountChange,
        'TotalRebatableValue' => $TotalRebatableValue,

        'Remarks' => $Remarks,
        'Status' => $Status,

        'Source' => $Source,

        'ApprovedByID' => $ApprovedByID,
        'ApprovedDateTime' => $TODAY,

        'CreatedByID' => $CreatedByID,
        'DateTimeCreated' => $TODAY,

        'UpdatedByID' => $UpdatedByID,
        'DateTimeUpdated' => $TODAY

      ]);

    //Save Transaction Log
    $logData['TransRefID'] = $OrderID;
    $logData['TransactedByID'] = $CreatedByID;
    $logData['ModuleType'] = "Order";
    $logData['TransType'] = "New Order";
    $logData['Remarks'] = "";
    $Misc->doSaveTransactionLog($logData);

    //SaveItems
    if (!empty($Cart)) {
      foreach ($Cart as $ckey) {

        $ProductID = $ckey->ProductID;
        $Qty = $ckey->Qty;

        $Price = 0;
        $SubTotal = 0;
        $RebatableValue = 0;
        if (Session("MEMBER_LOGGED_IN")) {
          $Price = $ckey->DistributorPrice;
          $SubTotal = $ckey->Qty * $ckey->DistributorPrice;
          $RebatableValue = $ckey->Qty * $ckey->RebateValue;
        } else {
          $Price = $ckey->RetailPrice;
          $SubTotal = $ckey->Qty * $ckey->RetailPrice;
        }

        if ($ProductID > 0) {
          $OrderItemID =  DB::table('orderitem')
            ->insertGetId([
              'OrderID' => $OrderID,
              'ProductID' => $ProductID,
              'Qty' => $Qty,
              'Price' => $Price,
              'SubTotal' => $SubTotal,
              'RebatableValue' => $RebatableValue,
              'DateTimeCreated' => $TODAY,
              'DateTimeUpdated' => $TODAY
            ]);
        }
      }
    }

    //Clear Customer Cart
    $cparam["MemberEntryID"] = $data['MemberEntryID'];
    $cparam["SessionID"] = $data['SessionID'];
    $CustomerCart->doClearCart($cparam);

    //Send Order Email 
    $eparam["OrderID"] =  $OrderID;
    $Email->sendRecievedSalesEmail($eparam);

    $RetVal['OrderID'] = $OrderID;
    $RetVal['OrderNo'] = $OrderNo;

    return $RetVal;
  }

  public function getAllCenterSalesList($param)
  {

    $DateFrom = $param['DateFrom'];
    $DateTo = $param['DateTo'];

    ini_set('memory_limit', '999999M');

    $query = DB::table('centers as ctr')
      ->selectraw("

            COALESCE(ctr.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,

            COALESCE((SELECT 
                SUM(COALESCE(odr.TotalAmountDue,0))
              FROM `order` as odr
              WHERE odr.CenterID = ctr.CenterID
              AND COALESCE(odr.Status,'') != '" . config('app.STATUS_CANCELLED') . "'
              AND COALESCE(odr.Status,'') != '" . config('app.STATUS_RETURNED') . "'
              AND COALESCE(odr.IsPaid,0) = 1
              AND COALESCE(odr.OrderDateTime,'') BETWEEN '" . $DateFrom . "' AND '" . $DateTo . " 23:59:59'
            ),0) as TotalSales

        ")
      ->orderByraw("TotalSales DESC");

    $list = $query->get();

    return $list;
  }

  public function getCenterSalesList($param)
  {

    $CenterID = $param['CenterID'];
    $DateFrom = $param['DateFrom'];
    $DateTo = $param['DateTo'];

    ini_set('memory_limit', '999999M');

    $query = DB::table('centers as ctr')
      ->leftjoin('order as odr', 'ctr.CenterID', '=', 'odr.CenterID')
      ->selectraw("
            COALESCE(ctr.CenterID,0) as CenterID,
            COALESCE(ctr.CenterNo,'') as CenterNo,
            COALESCE(ctr.Center,'') as Center,
            DATE(odr.OrderDateTime) as SalesDate,
            SUM(COALESCE(odr.TotalAmountDue,0)) as TotalSales
        ")
      ->groupBy('ctr.CenterID', 'ctr.CenterNo', 'ctr.Center', 'SalesDate')
      ->whereraw("ctr.CenterID = " . $CenterID)
      ->whereraw("COALESCE(odr.Status,'') != '" . config('app.STATUS_CANCELLED') . "'")
      ->whereraw("COALESCE(odr.Status,'') != '" . config('app.STATUS_RETURNED') . "'")
      ->whereraw("COALESCE(odr.IsPaid,0) = 1")
      ->whereraw("COALESCE(odr.OrderDateTime,'') BETWEEN '" . $DateFrom . "' AND '" . $DateTo . " 23:59:59'")
      ->orderByraw("SalesDate ASC");

    $list = $query->get();

    return $list;
  }

  public function getDirectSellingReport($param)
  {

    $DateFrom = $param['DateFrom'];
    $DateTo = $param['DateTo'];
    $Limit = $param['Limit'];
    $PageNo = $param['PageNo'];

    $query = DB::table('memberentry as mbrentry')
      ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
      ->selectraw("
            COALESCE(mbrentry.EntryID,0) as EntryID,
            COALESCE(mbrentry.EntryCode,'') as EntryCode,
            mbrentry.EntryDateTime,

            COALESCE(mbrentry.MemberID,0) as MemberID,
            COALESCE(mbr.MemberNo,'') as MemberNo,
            CONCAT(COALESCE(mbr.FirstName,''),' ',COALESCE(mbr.LastName,'')) as MemberName,
            COALESCE(mbr.FirstName,'') as FirstName,
            COALESCE(mbr.LastName,'') as LastName,
            COALESCE(mbr.MiddleName,'') as MiddleName,
            COALESCE(mbr.EmailAddress,'') as EmailAddress,
            COALESCE(mbr.TelNo,'') as TelNo,
            COALESCE(mbr.MobileNo,'') as MobileNo,

            COALESCE((SELECT 
                SUM(COALESCE(TotalAmountDue,0)) as TotalDirectSales
              FROM `order`
              WHERE CustomerEntryID = mbrentry.EntryID
              AND OrderDateTime BETWEEN '" . $DateFrom . " 00:00:00' AND '" . $DateTo . " 23:59:59'
              AND IsPaid = 1
              )
            ,0) as TotalDirectSales,

             COALESCE((SELECT 
                SUM(COALESCE(TotalRebatableValue,0)) as TotalRebatableValue
              FROM `order`
              WHERE CustomerEntryID = mbrentry.EntryID
              AND OrderDateTime BETWEEN '" . $DateFrom . " 00:00:00' AND '" . $DateTo . " 23:59:59'
              AND IsPaid = 1
              )
            ,0) as TotalRebatableValue,

            COALESCE(mbr.Status,'') as Status

      ");

    if ($Limit > 0) {
      $query->limit($Limit);
      $query->offset(($PageNo - 1) * $Limit);
    }

    $query->orderbyraw("(COALESCE((SELECT 
                SUM(COALESCE(TotalAmountDue,0)) as TotalDirectSales
              FROM `order`
              WHERE CustomerEntryID = mbrentry.EntryID
              AND OrderDateTime BETWEEN '" . $DateFrom . " 00:00:00' AND '" . $DateTo . " 23:59:59'
              AND IsPaid = 1
              )
            ,0)) DESC");

    $list = $query->get();

    return $list;
  }
}
