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


class MemberController extends Controller {

    public function showMemberLogin(){

        $data['Page'] = 'member-login';
        $data['Token'] = csrf_token();

        return View::make('member/member-login')->with($data);

    }
    
    public function showMemberLogin2(){

        $data['Page'] = 'auto-login';
        $data['Token'] = csrf_token();

        return View::make('member/auto-login')->with($data);

    }

	public function doMemberLogin(Request $request){

	    $MemberEntry = new MemberEntry();
	    $Error_Msg = "";

	    $data['EntryCode'] = $request['EntryCode'];
	    $data['UserPassword'] = $request['UserPassword'];
	    $data['Status'] = config('app.STATUS_ACTIVE');

	    if (empty($data['EntryCode'])) {
	     	$Error_Msg = 'Please enter your IBO Number.';
	    }elseif (empty($data['UserPassword'])) {
	     	$Error_Msg = 'Please enter your password.';
	    }

	    if (!empty($Error_Msg)){
	        return Redirect::back()->with('Error_Msg',$Error_Msg);
	    }else{

	    	$Result = $MemberEntry->doCheckMemberLoginAccount($data);

	    	if($Result == "Failed"){
	        	return Redirect::back()->with('Error_Msg','Invalid IBO No. and password.');
	      	}else if($Result == config('app.STATUS_INACTIVE')){
	        	return Redirect::back()->with('Error_Msg','Your account has been deactivated. Please contact our main office for support.');
	      	}else if($Result == config('app.STATUS_BLOCKED')){
	        	return Redirect::back()->with('Error_Msg','Your account has been blocked. Please contact our main office for support.');
	      	}else{
	          	return Redirect::route('member-dashboard');
	      	}
	    }

	}
	
	

	

	public function doMemberLogout(){
	    
		if (Session('MEMBER_LOGGED_IN')) {
			Session::flush();
		}
		
		return view('member/member-login');

	}

	function IsMemberLoggedIn(){
		if (!Session('MEMBER_LOGGED_IN')) {
			return false;
		}
		return true;
	}

	function SetMemberInitialData($data){

		$Misc = new Misc();
        $data['MiscModel'] = $Misc;

		return $data;
	}

    public function showDashboard(){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }
       	
       	$MemberEntry = new MemberEntry();

        $data['Page'] = 'dashboard';
        $data['Token'] = csrf_token();
	    $data = $this->SetMemberInitialData($data);

	    $data['DashboardFigures'] = $MemberEntry->getDashboardFigures();

        return View::make('member/dashboard')->with($data);

    }

	public function showMemberGenealogy(Request $request){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }

		$Misc = new Misc();
		$MemberEntry = new MemberEntry();

		$MemberEntryID = $request['MemberEntryID'];
		$MaxLevel = config('app.GenealogyLevelLimit');
		if($request["MaxLevel"]){
			$MaxLevel = $request["MaxLevel"];
		}

		$data['Page'] = 'member-genealogy';
		$data['Token'] = csrf_token();
	    $data = $this->SetMemberInitialData($data);

		$data['CountryCityList']=$Misc->getCountryCityList(174);
		$data['CountryList']=$Misc->getCountryList();
		$data['TOP'] = $MemberEntry->getMemberEntryInfo($MemberEntryID);
		$data['TREE'] = $MemberEntry->getMemberGenealogy($MemberEntryID,$MaxLevel);
		$data['MaxLevel'] = $MaxLevel;

		return View::make('member/member-genealogy')->with($data);
		
	}

    public function showMemberProfile(){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }
       	
       	$MemberEntry = new MemberEntry();
       	$Misc = new Misc();

        $data['Page'] = 'member-profile';
        $data['Token'] = csrf_token();
	    $data = $this->SetMemberInitialData($data);

		$EntryID = Session("MEMBER_ENTRY_ID");
	    $data['MemberInfo'] = $MemberEntry->getMemberEntryInfo($EntryID);
		$data['CountryCityList']=$Misc->getCountryCityList(174);
		$data['CountryList']=$Misc->getCountryList();

        return View::make('member/member-profile')->with($data);

    }

    public function showMemberEWallet(){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }

        $data['Page'] = 'member-ewallet-ledger';
        $data['Token'] = csrf_token();
	    $data = $this->SetMemberInitialData($data);

        return View::make('member/member-ewallet')->with($data);

    }

    public function showEWalletWithdrawal(){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }

        $data['Page'] = 'ewallet-withdrawal';
        $data['Token'] = csrf_token();
	    $data = $this->SetMemberInitialData($data);

        return View::make('member/member-ewallet-withdrawal')->with($data);

    }

    public function showMemberUpgradeEntry(){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }

        $data['Page'] = 'member-upgrade-entry';
        $data['Token'] = csrf_token();
	    $data = $this->SetMemberInitialData($data);

	    $MemberEntry = new MemberEntry();
	    $data["MemberEntryInfo"] = $MemberEntry->getMemberEntryInfo(Session('MEMBER_ENTRY_ID'));

        return View::make('member/member-upgrade-entry')->with($data);

    }

    public function showMemberVouchers(){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }

        $data['Page'] = 'member-vouchers';
        $data['Token'] = csrf_token();
	    $data = $this->SetMemberInitialData($data);

        return View::make('member/member-vouchers')->with($data);

    }

    public function showMemberOrderHistory(){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }

        $data['Page'] = 'member-order-history';
        $data['Token'] = csrf_token();
	    $data = $this->SetMemberInitialData($data);

        return View::make('member/member-order-history')->with($data);

    }

	public function showMemberTree()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}
		$MemberEntryID = Session('MEMBER_ENTRY_ID');
		// $Admin = new Admin();
		$data['upline'] = DB::table('member_tree')->where('descendant_id',$MemberEntryID)
		->join('member', 'member.MemberID', '=', 'member_tree.ancestor_id')
		->join('memberentry', 'memberentry.MemberID', '=', 'member.MemberID')
		->select('member.*','member_tree.*','memberentry.EntryCode','memberentry.MemberID')
		->orderBy('member_tree.depth','ASC')->get()->toArray();

		$data['downline'] = DB::table('member_tree')->where('ancestor_id',$MemberEntryID)
		->join('member', 'member.MemberID', '=', 'member_tree.descendant_id')
		->join('memberentry', 'memberentry.MemberID', '=', 'member.MemberID')
		->orderBy('depth','ASC')->get()->groupBy('depth');
		
		$data['Page'] = 'member-tree';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);

		return View::make('member/member-tree')->with($data);
	}

    //Change Password ------------------------------------------------
    public function showChangePassword(){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }

        $data['Page'] = 'change-password';
        $data['Token'] = csrf_token();
	    $data = $this->SetMemberInitialData($data);

        return View::make('member/change-password')->with($data);

    }

	public function doChangePassword(Request $request){

	    if(!$this->IsMemberLoggedIn()){
	      return Redirect::route('member-logout');
	    }

	    $MemberEntry = new MemberEntry();
		$ErrorMessage = null;

		$data['MemberEntryID'] = Session('MEMBER_ENTRY_ID');
		$data['CurrentPassword'] = $request['CurrentPassword'];
		$data['NewPassword'] = $request['NewPassword'];
		$data['ConfirmNewPassword'] = $request['ConfirmNewPassword'];

		if(empty($data['CurrentPassword'])) {
			$ErrorMessage='Please enter current password.';
		}elseif (empty($data['NewPassword'])) {
			$ErrorMessage='Please enter new password.';
		}elseif (trim($data['NewPassword']) != trim($data['ConfirmNewPassword'])) {
			$ErrorMessage='Please confirm your new password.';
		}

		if (!empty($ErrorMessage)) {
			Session::flash('ERROR_MSG',$ErrorMessage);
			return redirect()->back();
       	}else{

   			$Response = $MemberEntry->doChangePassword($data);

			if($Response != "Success"){
				Session::flash('ERROR_MSG',$Response);
				return redirect()->back();
			}else{
				Session::flash('SUCCESS_MSG','Your password has been changed successfully.');
				return redirect()->back();
			}
		}

	}

	public function doUploadMemberPhoto(Request $request){

		$MemberEntry = new MemberEntry();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["MemberEntryID"] = $request['MemberEntryID'];

		if($RetVal['Response'] != "Failed"){
			$Response = $MemberEntry->doUploadProductPhoto($data);
			return Redirect::back()->with('Success_Msg','Member photo has been uploaded successfully.');
		}else{
			return Redirect::back()->with('Error_Msg','Something went wrong while uploading member photo.');
		}
	}





















































    

    







































}
