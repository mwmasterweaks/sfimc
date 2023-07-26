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

use App\Models\Order;

class Email extends Model
{

  	public function SendMemberRegistration($param){

	    $EmailAddress = $param['EmailAddress'];
	    if (filter_var($EmailAddress, FILTER_VALIDATE_EMAIL) && config('app.DebugMode') == '0'){
	      Mail::send(
	        'emails/member-registration',
	        [
	          'EntryCode'=> $param['EntryCode'],
              'FirstName'=> $param['FirstName'],
	          'Password'=> $param['Password']
	        ],
	        function($message) use ($EmailAddress){
	          $message->from(config('app.COMPANY_EMAIL'));
	          $message->to($EmailAddress);
	          $message->bcc('fransadan@gmail.com');
	          $message->subject(config('app.COMPANY_NAME').' - Member Registration');
	        }
	      );
	    }
  	}

  	public function sendRecievedSalesEmail($param){

  		$Order = new Order();
	    $OrderID = $param['OrderID'];
		$OrderInfo = $Order->getOrderInfo($OrderID);
		$OrderItem = $Order->getOrderItemList($param);

		if(isset($OrderInfo)){

			$CustomerName = $OrderInfo->CustomerName;
			$ReferenceNo = $OrderInfo->OrderNo;

			$ShippingAddress = $OrderInfo->Address.", ".$OrderInfo->City.", ".$OrderInfo->StateProvince.", ".$OrderInfo->ZipCode." ".$OrderInfo->Country;

			$MobileNo = $OrderInfo->MobileNo;
			$EmailAddress = $OrderInfo->EmailAddress;

			$GrossTotal = $OrderInfo->GrossTotal;
			$ShippingCharges = $OrderInfo->ShippingCharges;
			$TotalDiscountAmount = $OrderInfo->TotalDiscountAmount;
			$TotalAmountDue = $OrderInfo->TotalAmountDue;

			$ModeOfPayment = $OrderInfo->ModeOfPayment;
			$TotalVoucherPayment = $OrderInfo->TotalVoucherPayment;
			$TotalEWalletPayment = $OrderInfo->TotalEWalletPayment;
			$TotalCashPayment = $OrderInfo->TotalCashPayment;
			$AmountChange = $OrderInfo->AmountChange;

			$Remarks = $OrderInfo->Remarks;
			$Status = $OrderInfo->Status;

    		$mparam["EmailAddress"] = $EmailAddress;
    		$mparam["Subject"] = config('app.COMPANY_NAME').' - Order No. '.$ReferenceNo." on verification process";
    
    	    if (filter_var($EmailAddress, FILTER_VALIDATE_EMAIL) && config('app.DebugMode') == '0'){
    	      Mail::send(
    	        'emails/new-order-email',
    	        [
    	          'CustomerName'=> $CustomerName,
    	          'ReferenceNo'=> $ReferenceNo,
    	          'ShippingAddress'=> $ShippingAddress,
    	          'MobileNo'=> $MobileNo,
    	          'EmailAddress'=> $EmailAddress,
    	          'GrossTotal'=> $GrossTotal,
    	          'ShippingCharges'=> $ShippingCharges,
    	          'TotalDiscountAmount'=> $TotalDiscountAmount,
    	          'TotalAmountDue'=> $TotalAmountDue,
    	          'ModeOfPayment'=> $ModeOfPayment,
    	          'Remarks'=> $Remarks,
    	          'OrderItem'=> $OrderItem
    	        ],
    	        function($message) use ($mparam){
    	          	$message->from(config('app.COMPANY_EMAIL'));
    	          	$message->to($mparam["EmailAddress"]);
    	          	$message->bcc('fransadan@gmail.com');
    	          	$message->subject($mparam["Subject"]);
    	        }
    	      );
    	    }

		}

  	}







}
