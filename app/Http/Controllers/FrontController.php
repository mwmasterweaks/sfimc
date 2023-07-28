<?php

namespace App\Http\Controllers;

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
use Excel;
use PDF;

use App\Models\Admin;
use App\Models\Misc;
use App\Models\MemberEntry;
use App\Models\Center;
use App\Models\Code;
use App\Models\Package;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\InventoryAdjustment;
use App\Models\Order;
use App\Models\EWallet;
use App\Models\EWalletWithdrawal;
use App\Models\UserAccounts;
use App\Models\CustomerCart;

use App\Models\Shipper;
use App\Models\ShipperJAT;
use App\Models\NewsEvents;

class FrontController extends Controller {

    function SetInitialData($data){

        $Misc = new Misc();
        $CustomerCart = new CustomerCart();

        $NewsEvents = new NewsEvents();
        $param["Status"] = config('app.STATUS_PUBLISHED');
        $param["SearchText"] = "";
        $param["Limit"] = config('app.ListRowLimit');
        $param["PageNo"] = 1;
        $data["NewsEventsList"] = $NewsEvents->getNewsEventsList($param);

        $data["CompanyInfo"] = $Misc->GetCompanyInfo();

        $cparam['MemberEntryID'] = 0;
        if(Session::get('MEMBER_ENTRY_ID')){
            $cparam['MemberEntryID'] = Session::get('MEMBER_ENTRY_ID');
        }
        $cparam['SessionID'] = 0;
        if(Session::get('SESSION_ID')){
            $cparam['SessionID'] = Session::get('SESSION_ID');
        }
        $data["CartItemCount"] = $CustomerCart->getCustomerCartCount($cparam);

        return $data;
    }

    public function showHome(){

        $data['Page'] = 'home';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        return View::make('front/index')->with($data);

    }

    public function showForgotPassword(){

        $data['Page'] = 'forgot-password';
        $data['Token'] = csrf_token();

        return View::make('front/forgot-password');
    }

    public function showProducts(Request $request){

        $data['Page'] = 'products';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        $Inventory = new Inventory();
        $param["IsWithInventoryOnly"] = 1;
        $param["IsComplanProductsOnly"] = 0;
	    $param['CenterID'] = 1;
	    $param['Status'] = config('app.STATUS_ACTIVE');
	    $param['SearchText'] = "";
	    if($request["SearchText"]){
		    $param['SearchText'] = $request["SearchText"];
	    }
      	$param['Limit'] = 0;
      	$param['PageNo'] = 0;
        if($request["PageNo"]){
            $param['PageNo'] = $request["PageNo"];
        }
        $AllInventoryCount = count($Inventory->getInventoryList($param));

        $param['Limit'] = 0;//config('app.FrontListRowLimit');
        $param['PageNo'] = 0;
        if($request["PageNo"]){
            $param['PageNo'] = $request["PageNo"];
        }
	    $data["InventoryList"] = $Inventory->getInventoryList($param);

        $data["TotalPages"] = ceil(($AllInventoryCount / config('app.FrontListRowLimit')));
        $data['PageNo'] = $param["PageNo"];
		$data['SearchText'] = $param['SearchText'];

        return View::make('front/products')->with($data);

    }

    public function showProductDetail(Request $request){

        $data['Page'] = 'product-detail';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        $Inventory = new Inventory();
        $CustomerCart = new CustomerCart();
        $Product = new Product();
	    $ProductName = $request["ProductName"];
	    $data["ProductInfo"] = $Product->getProductInfoByName($ProductName);

	    $data["ProductID"] = 0;
	    $data["Category"] = "";
	    $data["Brand"] = "";
	    $data["ProductCode"] = "";
	    $data["ProductName"] = "";
	    $data["Description"] = "";
	    $data["Specification"] = "";
	    $data["Measurement"] = "";
	    $data["DistributorPrice"] = 0;
	    $data["RetailPrice"] = 0;
	    $data["RebateValue"] = 0;
	    if(isset($data["ProductInfo"])){
		    $data["ProductID"] = $data["ProductInfo"]->ProductID;
		    $data["Category"] = $data["ProductInfo"]->Category;
		    $data["Brand"] = $data["ProductInfo"]->Brand;
		    $data["ProductCode"] = $data["ProductInfo"]->ProductCode;
		    $data["ProductName"] = $data["ProductInfo"]->ProductName;
		    $data["Description"] = $data["ProductInfo"]->Description;
		    $data["Specification"] = $data["ProductInfo"]->Specification;
		    $data["Measurement"] = $data["ProductInfo"]->Measurement;
		    $data["DistributorPrice"] = $data["ProductInfo"]->DistributorPrice;
		    $data["RetailPrice"] = $data["ProductInfo"]->RetailPrice;
		    $data["RebateValue"] = $data["ProductInfo"]->RebateValue;
	    }

        //Cart Details
        $param["SessionID"] = Session::get('SESSION_ID');
        if(empty($param["SessionID"])){
            Session::put('SESSION_ID',date("YmdHis"));
            $param["SessionID"] = Session::get('SESSION_ID');
        }
        $param['MemberEntryID'] = 0;
        if(Session::get('MEMBER_ENTRY_ID')){
            $param['MemberEntryID'] = Session::get('MEMBER_ENTRY_ID');
        }
        $data['Cart']= $CustomerCart->getCustomerCartList($param);

        $param["IsWithInventoryOnly"] = 1;
        $param["IsComplanProductsOnly"] = 0;
        $param['CenterID'] = 1;
        $param['Status'] = config('app.STATUS_ACTIVE');
        $param['SearchText'] = $data["Category"];
        $param['Limit'] = config('app.FrontListRowLimit');
        $param['PageNo'] = 0;
        $data["RelatedProductList"] = $Inventory->getInventoryList($param);

        return View::make('front/product-detail')->with($data);

    }

    public function showCart(){

        $CustomerCart = new CustomerCart();

        $data['Page'] = 'cart';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        //Cart Details
        $param["SessionID"] = Session::get('SESSION_ID');
        if(empty($param["SessionID"])){
            Session::put('SESSION_ID',date("YmdHis"));
            $param["SessionID"] = Session::get('SESSION_ID');
        }
        $param['MemberEntryID'] = 0;
        if(Session::get('MEMBER_ENTRY_ID')){
            $param['MemberEntryID'] = Session::get('MEMBER_ENTRY_ID');
        }
        $data['Cart']= $CustomerCart->getCustomerCartList($param);

        return View::make('front/cart')->with($data);

    }

    public function AddToCartWithQtyBackground(Request $request){

        $CustomerCart = new CustomerCart();

        $data["SessionID"] = $request['SessionID'];
        if($data["SessionID"] <= 0){
            Session::put('SESSION_ID',date("YmdHis"));
            $data["SessionID"] = Session::get('SESSION_ID');
        }

        $data["MemberEntryID"] = $request['MemberEntryID'];
        $data["ProductID"] = $request['ProductID'];
        $data["Qty"] = $request['Qty'];
        $data["Remarks"] = $request["Remarks"];

        $RetVal['Response'] = "Success";
        $RetVal['ResponseMessage'] = "";
        $RetVal['SessionID'] = $data["SessionID"];
        $RetVal['CartCount'] = 0;

        if($RetVal['Response'] != "Failed"){
            $CartItemID = $CustomerCart->doAddToCart($data);

            if($CartItemID > 0){
                $CartItemCount = $CustomerCart->getCustomerCartCount($data);
                $RetVal['Response'] = "Success";
                $RetVal['ResponseMessage'] = "";
                $RetVal['CartCount'] = $CartItemCount;
            }
        }

        return response()->json($RetVal);

    }

    public function UpdateItemCartSingle(Request $request){

        $CustomerCart = new CustomerCart();
        $Misc = new Misc();

        $data["SessionID"] = $request['SessionID'];
        if($data["SessionID"] <= 0){
            Session::put('SESSION_ID',date("YmdHis"));
            $data["SessionID"] = Session::get('SESSION_ID');
        }

        $data["MemberEntryID"] = $request['MemberEntryID'];
        $data["ProductID"] = $request['ProductID'];
        $data["Qty"] = $request['Qty'];
        $data["Remarks"] = $request['Remarks'];

        if($data["Qty"] > 0){
            $CustomerCart->doSaveUpdateCart($data);
        }else{
            $CustomerCart->doRemoveCartItem($data);
        }

        $CartItemCount = $CustomerCart->getCustomerCartCount($data);
        $RetVal['Response'] = "Success";
        $RetVal['ResponseMessage'] = "";
        $RetVal['CartCount'] = $CartItemCount;

        return response()->json($RetVal);
    }

    public function UpdateItemCart(Request $request){

        $CustomerCart = new CustomerCart();

        $data["SessionID"] = $request['SessionID'];
        if($data["SessionID"] <= 0){
            Session::put('SESSION_ID',date("YmdHis"));
            $data["SessionID"] = Session::get('SESSION_ID');
        }

        $data["MemberEntryID"] = $request['MemberEntryID'];

        $items = array();
        $items = $request['arrayProductID'];
        for ($i = 0; $i < count($items); $i++){

            $data["ProductID"] = $items[$i];
            $data["Qty"] = $request['Qty'.$items[$i]];
            $data["Remarks"] = "";
            if($request["Remarks".$items[$i]]){
                $data["Remarks"] = $request["Remarks".$items[$i]];
            }

            if($data["Qty"] > 0){
                $CustomerCart->doSaveUpdateCart($data);
            }else{
                $CustomerCart->doRemoveCartItem($data);
            }
        }

        return Redirect::back();
    }

    public function RemoveItemCart(Request $request){

        $CustomerCart = new CustomerCart();

        $data["SessionID"] = $request['SessionID'];
        if($data["SessionID"] <= 0){
            Session::put('SESSION_ID',date("YmdHis"));
            $data["SessionID"] = Session::get('SESSION_ID');
        }

        $data["MemberEntryID"] = $request['MemberEntryID'];
        $data["ProductID"] = $request['ProductID'];

        $CustomerCart->doRemoveCartItem($data);
        $RetVal["Response"]= "Success";

        return response()->json($RetVal);

    }

    public function showCheckout(){

        $Misc = new Misc();
        $CustomerCart = new CustomerCart();
        $Shipper = new Shipper();
        $ShipperJAT = new ShipperJAT();

        $data['Page'] = 'checkout';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        $cparam['MemberEntryID'] = 0;
        if(Session::get('MEMBER_ENTRY_ID')){
            $cparam['MemberEntryID'] = Session::get('MEMBER_ENTRY_ID');
        }
        $cparam['SessionID'] = 0;
        if(Session::get('SESSION_ID')){
            $cparam['SessionID'] = Session::get('SESSION_ID');
        }
        $data["Cart"] = $CustomerCart->getCustomerCartList($cparam);

        if(count($data['Cart']) <= 0){
            return Redirect::route('cart');
        }

        $data['TotalWeightKG'] = 0;
        foreach ($data['Cart'] as $ckey) {
            $data['TotalWeightKG'] = $data['TotalWeightKG'] + ($ckey->NetWeight * $ckey->Qty);
        }
        $data['TotalWeightKG'] = $data['TotalWeightKG'] / 1000;

        //Get Shipping Charges
        $data['JATShippingCharges'] = 0;
        if(Session("MEMBER_CITYID")){
            $data['JATShippingCharges'] = $ShipperJAT->getJATRate(Session("MEMBER_CITYID"), $data['TotalWeightKG']);
        }

        $data['ShipperList'] = $Shipper->getShipperList();
        $data['CountryCityList'] = $Misc->getCountryCityList(174);
        $data['CountryList'] = $Misc->getCountryList();

        return View::make('front/checkout')->with($data);

    }

    public function doCheckShippingCharges(Request $request){

        $ShipperJAT = new ShipperJAT();

        $CityID = $request['City'];
        $TotalWeightKG = $request['TotalWeightKG'];

        $ShippingCharges = 0;
        $ShippingCharges = $ShipperJAT->getJATRate($CityID, $TotalWeightKG);

        $RetVal['Response'] = "Success";
        $RetVal['ResponseMessage'] = "";
        $RetVal['ShippingCharges'] = $ShippingCharges;

        return response()->json($RetVal);

    }

    public function doCheckoutOrder(Request $request){

        $CustomerCart = new CustomerCart();
        $Order = new Order();
        $ShipperJAT = new ShipperJAT();

        $data["CenterID"] = config('app.MAIN_CENTER_ID'); 

        if(Session("MEMBER_LOGGED_IN")){
            $data["CustomerType"] = "Member";  
            $data["CustomerEntryID"] = Session("MEMBER_ENTRY_ID");  
        }else{
            $data["CustomerType"] = "Guest";  
            $data["CustomerEntryID"] = 0;  
        }

        $data["CustomerName"] = $request['CustomerName'];
        $data["EmailAddress"] = $request['EmailAddress'];
        $data["MobileNo"] = $request['MobileNo'];

        $data["Address"] = $request['Address'];
        $data["CityID"] = $request['City'];
        $data["StateProvince"] = $request['StateProvince'];
        $data["ZipCode"] = $request['ZipCode'];
        $data["CountryID"] = $request['Country'];

        $cparam['MemberEntryID'] = 0;
        if(Session::get('MEMBER_ENTRY_ID')){
            $cparam['MemberEntryID'] = Session::get('MEMBER_ENTRY_ID');
        }
        $cparam['SessionID'] = 0;
        if(Session::get('SESSION_ID')){
            $cparam['SessionID'] = Session::get('SESSION_ID');
        }
        $data["MemberEntryID"] = $cparam['MemberEntryID'];
        $data["SessionID"] = $cparam['SessionID'];
        $data["Cart"] = $CustomerCart->getCustomerCartList($cparam);

        $GrossTotal = 0;
        $TotalRebatableValue = 0;
        foreach ($data["Cart"] as $ckey){

            if(Session("MEMBER_LOGGED_IN")){
                $GrossTotal = $GrossTotal + ($ckey->Qty * $ckey->DistributorPrice);
                $TotalRebatableValue = $TotalRebatableValue + ($ckey->Qty * $ckey->RebateValue);
            }else{
                $GrossTotal = $GrossTotal + ($ckey->Qty * $ckey->RetailPrice);
            }
        }

        $data["ShipperID"] = 1;
        $data["TotalWeightKG"] = $request['TotalWeightKG'];
        $data['ShippingCharges'] = $ShipperJAT->getJATRate($data["CityID"], $data["TotalWeightKG"]);

        $data['GrossTotal'] = $GrossTotal;
        $data['TotalDiscountPercent'] = 0;
        $data['TotalDiscountAmount'] = 0;
        $data['TotalAmountDue'] = $GrossTotal + $data['ShippingCharges'] - $data['TotalDiscountAmount'];

        $data["ModeOfPayment"] = $request['ModeOfPayment'];
        $data["TotalVoucherPayment"] = 0;
        $data["TotalEWalletPayment"] = 0;
        $data["TotalCashPayment"] = 0;
        $data["AmountChange"] = 0;
        $data["TotalRebatableValue"] = $TotalRebatableValue;

        $data["Remarks"] = $request['Remarks'];
        $data["Status"] = config('app.STATUS_UNVERIFIED');

        $data["Source"] = config('app.SOURCE_WEBSITE');

        $data['ApprovedByID'] = config('app.SUPER_ADMIN_ACCOUNT');
        $data['CreatedByID'] = config('app.SUPER_ADMIN_ACCOUNT');
        $data['UpdatedByID'] = config('app.SUPER_ADMIN_ACCOUNT');

        $Response = $Order->doCheckoutOrder($data);

        if($Response["OrderID"] >= 0){
            $RetVal['Response'] = "Success";
            $RetVal['OrderNo'] = $Response["OrderNo"];
            $RetVal['ResponseMessage'] = "";
        }else{
            $RetVal['Response'] = "Failed";
            $RetVal['OrderNo'] = $Response["OrderNo"];
            $RetVal['ResponseMessage'] = "";
        }

        return response()->json($RetVal);

    }

    public function showCheckoutResponse(Request $request){

        $Order = new Order();

        $data['Page'] = 'checkout-response';
        $data['Token'] = csrf_token();

        $data = $this->SetInitialData($data);

        $data['RefNo'] = $request['RefNo'];
        $data['OrderInfo'] = $Order->getOrderInfoByOrderNo($data['RefNo']);
        $data['OrderItemInfo'] = NULL;

        if(isset($data['OrderInfo'])){

            $param["OrderID"] = $data['OrderInfo']->OrderID;
            $data['OrderItemInfo'] = $Order->getOrderItemList($param);

            Session::put('cart',null);
        }

        return View::make('front/checkout-response')->with($data);

    }

    public function showCenters(Request $request){

        $data['Page'] = 'centers';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        $Center = new Center();
	    $param['Status'] = config('app.STATUS_ACTIVE');
	    $param['SearchText'] = "";
	    if($request["SearchText"]){
		    $param['SearchText'] = $request["SearchText"];
	    }
      	$param['Limit'] = 0;
      	$param['PageNo'] = 0;
	    $data["CenterList"] = $Center->getCenterList($param);

		$data['SearchText'] = $param['SearchText'];

        return View::make('front/centers')->with($data);

    }

    public function showNewsEvents(){

        $data['Page'] = 'news-events';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        return View::make('front/news-events')->with($data);

    }

    public function showNewsEventDetail(Request $request){

        $NewsEvents = new NewsEvents();

        $data['Page'] = 'news-event-detail';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        $data['Title'] = $request["Title"];
        $data['NewsEventInfo'] = $NewsEvents->getNewsEventInfoByTitle($data['Title']);

        return View::make('front/news-event-detail')->with($data);

    }

    public function showFAQ(){

        $data['Page'] = 'faq';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        return View::make('front/index')->with($data);

    }

    public function showAboutUs(){

        $data['Page'] = 'about-us';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        return View::make('front/about-us')->with($data);

    }

    public function showContactUs(){

        $data['Page'] = 'contact-us';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        return View::make('front/contact-us')->with($data);

    }    

    public function showPrivacyPolicy(){

        $data['Page'] = 'contact-us';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        return View::make('front/privacy-policy')->with($data);

    }    

    public function showTermsAndConditions(){

        $data['Page'] = 'terms-and-conditions';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        return View::make('front/terms-and-conditions')->with($data);

    }    

    public function showShippingCancellationReturnPolicy(){

        $data['Page'] = 'shipping-cancellation-retun-policy';
        $data['Token'] = csrf_token();
        $data = $this->SetInitialData($data);

        return View::make('front/shipping-cancellation-retun-policy')->with($data);

    }    







}
