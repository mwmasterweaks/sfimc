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
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProcess;
use App\Models\PurchaseReceive;
use App\Models\Order;
use App\Models\EWallet;
use App\Models\EWalletWithdrawal;
use App\Models\UserAccounts;
use App\Models\Voucher;
use App\Models\CronJobs;
use App\Models\Shipper;
use App\Models\ShipperJAT;
use App\Models\NewsEvents;
use App\Models\FAQ;
use App\Models\Email;


class AdminController extends Controller
{

	public function showAdminLogin()
	{

		$data['Page'] = 'admin-login';
		$data['Token'] = csrf_token();

		return View::make('admin/admin-login')->with($data);
	}

	public function doAdminLogin(Request $request)
	{

		$UserAccounts = new UserAccounts();
		$Error_Msg = "";

		$data['Username'] = $request['Username'];
		$data['UserPassword'] = $request['UserPassword'];
		$data['Status'] = config('app.STATUS_ACTIVE');

		if (empty($data['Username'])) {
			$Error_Msg = 'Please enter your username.';
		} elseif (empty($data['UserPassword'])) {
			$Error_Msg = 'Please enter your password.';
		}

		if (!empty($Error_Msg)) {
			return Redirect::back()->with('Error_Msg', $Error_Msg);
		} else {

			$IsAdminLoggedIn = $UserAccounts->doCheckAdminLoginAccount($data);

			if (!$IsAdminLoggedIn) {
				return Redirect::back()->with('Error_Msg', 'Invalid username and password.');
			} else {
				return Redirect::route('admin-dashboard');
			}
		}
	}

	public function doAdminLogout()
	{

		Session::flush();
		return view('admin/admin-login');
	}

	function IsAdminLoggedIn()
	{
		if (!Session('ADMIN_LOGGED_IN')) {
			return false;
		}
		return true;
	}

	function SetAdminInitialData($data)
	{

		$Admin = new Admin();
		$Misc = new Misc();
		$UserAccounts = new UserAccounts();

		$data['UserAccountModel'] = $UserAccounts;
		$data['MiscModel'] = $Misc;
		$data["AlertLabels"] = $Admin->getAlertLabels();

		return $data;
	}

	public function showDashboard()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Admin = new Admin();

		$data['Page'] = 'dashboard';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data['DashboardFigures'] = $Admin->getDashboardFigures();

		return View::make('admin/dashboard')->with($data);
	}

	//CENTER ------------------------------------------------
	public function showCenterManagement()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();
		$UserAccounts = new UserAccounts();

		$data['Page'] = 'center-management';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data['UserAccountList'] = $UserAccounts->getUserAccountList($param);

		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/center-management')->with($data);
	}

	public function getCenterList(Request $request)
	{

		$Center = new Center();

		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CenterList"] = $Center->getCenterList($param);

		return response()->json($RetVal);
	}

	public function getCenterInfo(Request $request)
	{

		$Center = new Center();

		$CenterID = $request["CenterID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CenterInfo"] = $Center->getCenterInfo($CenterID);

		return response()->json($RetVal);
	}

	public function doSaveCenter(Request $request)
	{

		$Center = new Center();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CenterInfo"] = null;

		$data["CenterID"] = $request['CenterID'];

		$data["CenterNo"] = $request['CenterNo'];
		$data["Center"] = $request['Center'];

		$data["InchargeID"] = $request['InchargeID'];
		$data["TelNo"] = $request['TelNo'];
		$data["MobileNo"] = $request['MobileNo'];
		$data["EmailAddress"] = $request['EmailAddress'];

		$data["Address"] = $request['Address'];
		$data["CityID"] = $request['CityID'];
		$data["StateProvince"] = $request['StateProvince'];
		$data["ZipCode"] = $request['ZipCode'];
		$data["CountryID"] = $request['CountryID'];

		$data["Status"] = $request['Status'];

		$data["CreatedByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["UpdatedByID"] = Session('ADMIN_ACCOUNT_ID');

		if (empty($data["Center"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter center name.";
			return response()->json($RetVal);
		} else if (empty($data["InchargeID"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select incharge.";
			return response()->json($RetVal);
		} else if ($data["InchargeID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select incharge.";
			return response()->json($RetVal);
		} else if (empty($data["TelNo"]) && empty($data["MobileNo"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter enter telephone or mobile number.";
			return response()->json($RetVal);
		} else if (empty($data["Address"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter center address.";
		} else if ($data["CityID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select center city address.";
		} else if ($data["CountryID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select center country address.";
		}

		if ($RetVal['Response'] != "Failed") {
			$data["CenterID"] = $Center->doSaveUpdateCenter($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Center has been saved successfully.";
			$RetVal["CenterInfo"] = $Center->getCenterInfo($data["CenterID"]);
		}

		return response()->json($RetVal);
	}

	//Code Generation ------------------------------------------------
	public function showCodeGeneration()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Package = new Package();
		$Center = new Center();

		$data['Page'] = 'code-generation';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$Package = new Package();
		$param["Status"] = "";
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["PackageList"] = $Package->getPackageList($param);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		return View::make('admin/code-generation')->with($data);
	}

	public function getCodeGenerationBatchList(Request $request)
	{

		$Code = new Code();

		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];
		$param["Status"] = $request["Status"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationBatchList"] = $Code->getCodeGenerationBatchList($param);

		return response()->json($RetVal);
	}

	public function getCodeGenerationBatchInfo(Request $request)
	{

		$Code = new Code();

		$BatchID = $request["BatchID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationBatchInfo"] = $Code->getCodeGenerationBatchInfo($BatchID);

		return response()->json($RetVal);
	}

	public function getCodeGenerationByBatch(Request $request)
	{

		$Code = new Code();

		$BatchID = $request["BatchID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationByBatch"] = $Code->getCodeGenerationByBatch($BatchID);

		return response()->json($RetVal);
	}

	public function doSaveCodeGenerationBatch(Request $request)
	{

		$Code = new Code();
		$Package = new Package();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationBatchInfo"] = null;

		$data["BatchID"] = $request['BatchID'];
		$data["BatchNo"] = $request['BatchNo'];
		$data["DateTimeGenerated"] = $request['DateTimeGenerated'];
		$data["Status"] = $request['Status'];

		$data["CenterID"] = $request['CenterID'];
		$data["IsFreeCode"] = $request['IsFreeCode'];

		$param["Status"] = "";
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["PackageList"] = $Package->getPackageList($param);

		$TotalCodeCount = 0;
		foreach ($data["PackageList"] as $package) {

			$data["Package" . $package->PackageID . "Count"] = $request["Package" . $package->PackageID . "Count"];
			$data["Package" . $package->PackageID . "Price"] = $request["Package" . $package->PackageID . "Price"];
			$data["Package" . $package->PackageID . "ProductWorth"] = $request["Package" . $package->PackageID . "ProductWorth"];

			$TotalCodeCount = $TotalCodeCount + $request["Package" . $package->PackageID . "Count"];
		}

		$data["TotalGrossAmount"] = str_replace(",", "", $request['TotalGrossAmount']);
		$data["TotalDiscount"] = str_replace(",", "", $request['TotalDiscount']);
		$data["TotalAmountDue"] = str_replace(",", "", $request['TotalAmountDue']);
		$data["AmountPaid"] = str_replace(",", "", $request['AmountPaid']);
		$data["AmountChange"] = str_replace(",", "", $request['AmountChange']);

		$data["Remarks"] = $request['Remarks'];

		$data["CreatedByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["UpdatedByID"] = Session('ADMIN_ACCOUNT_ID');

		if (empty($data["CenterID"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select center.";
			return response()->json($RetVal);
		} else if ($TotalCodeCount <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter how many code(s) you want to generate.";
			return response()->json($RetVal);
		} else if ($data["TotalAmountDue"] > $data["AmountPaid"]) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please pay total amount due.";
			return response()->json($RetVal);
		} else {
			$data["BatchID"] = $Code->doSaveCodeGenerationBatch($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Codes has been generated successfully.";
			$RetVal["CodeGenerationBatchInfo"] = $Code->getCodeGenerationBatchInfo($data["BatchID"]);
		}

		return response()->json($RetVal);
	}

	public function doApproveCodeGenerationBatch(Request $request)
	{

		$Code = new Code();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationBatchInfo"] = null;

		$data["BatchID"] = $request['BatchID'];
		$data["CreatedByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["UpdatedByID"] = Session('ADMIN_ACCOUNT_ID');

		$data['BronzeCount'] = 0;
		$data['SilverCount'] = 0;
		$data['GoldCount'] = 0;
		$data['IsFreeCode'] = 0;
		$CodeGenerationBatchInfo = $Code->getCodeGenerationBatchInfo($data["BatchID"]);
		if (isset($CodeGenerationBatchInfo)) {
			$data['BronzeCount'] = $CodeGenerationBatchInfo->BronzeCount;
			$data['SilverCount'] = $CodeGenerationBatchInfo->SilverCount;
			$data['GoldCount'] = $CodeGenerationBatchInfo->GoldCount;
			$data['IsFreeCode'] = $CodeGenerationBatchInfo->IsFreeCode;
		}

		$data["BatchID"] = $Code->doApproveCodeGenerationBatch($data);

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "Codes batch generation has been approved successfully.";
		$RetVal["CodeGenerationBatchInfo"] = $Code->getCodeGenerationBatchInfo($data["BatchID"]);

		return response()->json($RetVal);
	}

	public function doCancelCodeGenerationBatch(Request $request)
	{

		$Code = new Code();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationByBatch"] = null;

		$data["BatchID"] = $request['BatchID'];
		$data["CancelledByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["CancellationReason"] = $request['CancellationReason'];

		if (empty($data["CancellationReason"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter the reason why you want to cancel this batch of code(s).";
			return response()->json($RetVal);
		}

		if ($RetVal['Response'] != "Failed") {
			$data["BatchID"] = $Code->doCancelCodeGenerationBatch($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Code(s) has been cancelled successfully.";
			$RetVal["CodeGenerationByBatch"] = $Code->getCodeGenerationByBatch($data["BatchID"]);
		}

		return response()->json($RetVal);
	}

	public function doCancelCode(Request $request)
	{

		$Code = new Code();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationInfo"] = null;

		$data["CodeID"] = $request['CodeID'];
		$data["CancelledByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["CancellationReason"] = $request['CancellationReason'];

		if (empty($data["CancellationReason"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter the reason why you want to cancel this code.";
			return response()->json($RetVal);
		}

		if ($RetVal['Response'] != "Failed") {
			$data["CodeID"] = $Code->doCancelCode($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Code(s) has been cancelled successfully.";
			$RetVal["CodeGenerationInfo"] = $Code->getCodeGenerationInfo($data["CodeID"]);
		}

		return response()->json($RetVal);
	}

	public function doIssueCodeGeneration(Request $request)
	{

		$Code = new Code();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationInfo"] = null;

		$data["CodeID"] = $request['CodeID'];
		$data["IssuedToMemberEntryID"] = $request['IssuedToMemberEntryID'];
		$data["IssuedRemarks"] = $request['IssuedRemarks'];

		$data["IssuedByID"] = Session('ADMIN_ACCOUNT_ID');

		if (empty($data["IssuedToMemberEntryID"]) || $data["IssuedToMemberEntryID"] == 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select to whom you want to issue the code.";
			return response()->json($RetVal);
		}

		if ($RetVal['Response'] != "Failed") {

			$data["CodeID"] = $Code->doIssueCodeGeneration($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Codes has been issued successfully.";
			$RetVal["CodeGenerationInfo"] = $Code->getCodeGenerationInfo($data["CodeID"]);
		}

		return response()->json($RetVal);
	}

	public function getCodeGenerationList(Request $request)
	{

		$Code = new Code();
		$RetVal = array();

		$param["CenterID"] = 0;
		if ($request["CenterID"]) {
			$param["CenterID"] = $request["CenterID"];
		}
		$param["Status"] = "";
		if ($request["Status"]) {
			$param["Status"] = $request["Status"];
		}
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationList"] = $Code->getCodeGenerationList($param);

		return json_encode($RetVal);
	}

	public function getCodeGenerationInfo(Request $request)
	{

		$Code = new Code();

		$CodeID = $request["CodeID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CodeGenerationInfo"] = $Code->getCodeGenerationInfo($CodeID);

		return response()->json($RetVal);
	}

	public function getCodeGenerationSearchList(Request $request)
	{

		$Code = new Code();
		$RetVal = array();

		$param["CenterID"] = 0;
		if ($request["CenterID"]) {
			$param["CenterID"] = $request["CenterID"];
		}

		$param["Status"] = "";
		if ($request["Status"]) {
			$param["Status"] = $request["Status"];
		}
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = 0;
		$param["PageNo"] = 0;

		$List = $Code->getCodeGenerationList($param);
		foreach ($List as $row) {
			$data = $row->CodeID . '|' .
				$row->Code . '|' .
				$row->PackageID . '|' .
				$row->Package . '|' .
				$row->OwnerMemberID . '|' .
				$row->OwnerMemberNo . '|' .
				$row->OwnerMemberName . '|' .
				$row->IsFreeCode;
			array_push($RetVal, $data);
		}

		return json_encode($RetVal);
	}

	public function PrintCodes(Request $request)
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Code = new Code();

		$data['Page'] = 'admin-print-codes';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data["BatchID"] = $request["BatchID"];
		$data["CodeGenerationBatchInfo"] = $Code->getCodeGenerationBatchInfo($data["BatchID"]);
		$data["CodeGenerationByBatch"] = $Code->getCodeGenerationByBatch($data["BatchID"]);

		return view('admin/code-print')->with($data);
	}

	public function showCodeDistribution()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Package = new Package();
		$Center = new Center();

		$data['Page'] = 'code-distribution';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		return View::make('admin/code-distribution')->with($data);
	}

	//MEMBER ------------------------------------------------
	public function showMemberManagement()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();

		$data['Page'] = 'member-management';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/member-management')->with($data);
	}

	public function showMemberGenealogy(Request $request)
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();
		$MemberEntry = new MemberEntry();

		$MemberEntryID = $request["MemberEntryID"];
		$MaxLevel = config('app.GenealogyLevelLimit');
		if ($request["MaxLevel"]) {
			$MaxLevel = $request["MaxLevel"];
		}

		$data['Page'] = 'admin-member-genealogy';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$Package = new Package();
		$param["Status"] = "";
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["PackageList"] = $Package->getPackageList($param);

		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();
		$data['TOP'] = $MemberEntry->getMemberEntryInfo($MemberEntryID);
		$data['TREE'] = $MemberEntry->getMemberGenealogy($MemberEntryID, $MaxLevel);
		$data['MaxLevel'] = $MaxLevel;

		return View::make('admin/member-genealogy')->with($data);
	}

	public function getMemberList(Request $request)
	{

		$MemberEntry = new MemberEntry();

		$param["PackageID"] = 0;
		if ($request["PackageID"]) {
			$param["PackageID"] = $request["PackageID"];
		}
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];
		$param["Status"] = $request["Status"];
		$param["IsWithEwallet"] = $request["IsWithEwallet"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["MemberList"] = $MemberEntry->getMemberEntryList($param);

		return response()->json($RetVal);
	}

	public function getMemberSearchList(Request $request)
	{

		$MemberEntry = new MemberEntry();

		$param["PackageID"] = 0;
		if ($request["PackageID"]) {
			$param["PackageID"] = $request["PackageID"];
		}
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];
		$param["Status"] = $request["Status"];
		$param["IsWithEwallet"] = $request["IsWithEwallet"];
		$MemberList = $MemberEntry->getMemberEntryList($param);

		$RetVal = array();
		foreach ($MemberList as $row) {
			if ($param["IsWithEwallet"] == 1) {
				$data = $row->EntryID . '|' . $row->EntryCode . '|' . $row->MemberName . '|' . $row->TelNo . '|' . $row->MobileNo . '|' . $row->EmailAddress . '|' . $row->LeftEntryID . '|' . $row->RightEntryID . '|' . $row->EWalletBalance . '|' . $row->FirstName . '|' . $row->LastName . '|' . $row->MiddleName;
			} else {
				$data = $row->EntryID . '|' . $row->EntryCode . '|' . $row->MemberName . '|' . $row->TelNo . '|' . $row->MobileNo . '|' . $row->EmailAddress . '|' . $row->LeftEntryID . '|' . $row->RightEntryID;
			}
			array_push($RetVal, $data);
		}

		return json_encode($RetVal);
	}

	public function getMemberInfo(Request $request)
	{

		$MemberEntry = new MemberEntry();

		$EntryID = $request["EntryID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["MemberInfo"] = $MemberEntry->getMemberEntryInfo($EntryID);

		return response()->json($RetVal);
	}

	public function getMemberMatchingEntries(Request $request)
	{

		$MemberEntry = new MemberEntry();

		$MemberEntryID = $request["MemberEntryID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["MemberMatchingEntries"] = $MemberEntry->getMemberMatchingEntries($MemberEntryID);

		return response()->json($RetVal);
	}

	public function getMemberAccumulatedPurchases(Request $request)
	{

		$MemberEntry = new MemberEntry();

		$MemberEntryID = $request["MemberEntryID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["MemberAccumulatedPurchases"] = $MemberEntry->getMemberAccumulatedPurchases($MemberEntryID);

		return response()->json($RetVal);
	}

	public function getMemberTempPassword(Request $request)
	{

		$Misc = new Misc();
		$TempPassword = $Misc->GenerateRandomNo(8, 'member', 'MemberNo');

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal['TempPassword'] = $TempPassword;

		return response()->json($RetVal);
	}

	public function doSaveMemberEntry(Request $request)
	{

		$MemberEntry = new MemberEntry();
		$Code = new Code();
		$Misc = new Misc();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["MemberInfo"] = null;

		$data["EntryID"] = $request['EntryID'];

		$data["MemberID"] = $request['MemberID'];

		$data["FirstName"] = $request['FirstName'];
		$data["LastName"] = $request['LastName'];
		$data["MiddleName"] = $request['MiddleName'];

		$data["TelNo"] = $request['TelNo'];
		$data["MobileNo"] = str_replace(" ", "", $request['MobileNo']);

		$data["EmailAddress"] = $request['EmailAddress'];
		$data["Password"] = $request['Password'];

		$data["Address"] = $request['Address'];
		$data["CityID"] = $request['City'];
		$data["StateProvince"] = $request['StateProvince'];
		$data["ZipCode"] = $request['ZipCode'];
		$data["CountryID"] = $request['Country'];

		$data["Status"] = $request['Status'];

		//Code Information
		$data["CodeID"] = 0;
		$data["Code"] = trim($request['Code']);
		$data["PackageID"] = 0;
		$data["IsFreeCode"] = 0;
		$CodeInfo = $Code->getCodeGenerationInfoByCode($data["Code"]);
		if (isset($CodeInfo)) {
			if ($CodeInfo->IssuedToMemberEntryID > 0 && $CodeInfo->Status == config('app.STATUS_AVAILABLE')) {
				$data["CodeID"] = $CodeInfo->CodeID;
				$data["PackageID"] = $CodeInfo->PackageID;
				$data["IsFreeCode"] = $CodeInfo->IsFreeCode;
			}
		}

		//Sposnor Information
		$data["SponsorEntryID"] = $request['SponsorEntryID'];

		//Parent Information
		$data["ParentEntryID"] = $request['ParentEntryID'];
		$data["ParentPosition"] = $request['ParentPosition'];

		if (Session('ADMIN_ACCOUNT_ID')) {
			$data["EncodedByID"] = Session('ADMIN_ACCOUNT_ID');
			$data["CreatedByID"] = Session('ADMIN_ACCOUNT_ID');
			$data["UpdatedByID"] = Session('ADMIN_ACCOUNT_ID');
		} else {
			$data["EncodedByID"] = config('app.SUPER_ADMIN_ACCOUNT');
			$data["CreatedByID"] = config('app.SUPER_ADMIN_ACCOUNT');
			$data["UpdatedByID"] = config('app.SUPER_ADMIN_ACCOUNT');
		}

		if (empty($data["FirstName"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter member first name.";
		} else if (empty($data["LastName"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter member last name.";
		} else if (empty($data["MobileNo"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter member mobile number.";
		} else if (!$Misc->CheckValidEmail($data["EmailAddress"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Invalid Email Address format.";
		} else if (empty($data["Address"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter member address.";
		} else if ($data["CityID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select member city address.";
		} else if ($data["CountryID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select member country address.";
		} else if ($data["EntryID"] == "0" && empty($data["Code"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter entry code.";
		} else if ($data["EntryID"] == "0" && $data["CodeID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Unable to verify entry code.";
		} else if ($data["EntryID"] == "0" && !$Code->IsCodeAvailableByCodeID($data["CodeID"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Sorry. Code has been used already.";
		} else if ($data["EntryID"] == "0" && $data["SponsorEntryID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select member entry sposnor.";
		} else if ($data["EntryID"] == "0" && $data["ParentEntryID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select member entry upline.";
		} else if ($data["EntryID"] == "0" && empty($data["ParentPosition"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select member entry position.";
		} else if ($data["EntryID"] == "0" && !$MemberEntry->IsPositionAvailableByEntryID($data["ParentEntryID"], $data["ParentPosition"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Sorry. Position is not available.";
		}

		if ($RetVal['Response'] != "Failed") {

			if ($data["MemberID"] > 0) {
				//Save Member Info
				$data["MemberID"] = $MemberEntry->doSaveUpdateMember($data);
			} else {
				//Save Member Info
				$data["MemberID"] = $MemberEntry->doSaveUpdateMember($data);

				//Save Member Entry
				$data["EntryID"] = $MemberEntry->doSaveMemberEntry($data);
			}

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Member entry has been saved successfully.";
			$RetVal["MemberInfo"] = $MemberEntry->getMemberEntryInfo($data["EntryID"]);
		}

		return response()->json($RetVal);
	}

	public function doTransferMemberPosition(Request $request)
	{

		$Misc = new Misc();
		$MemberEntry = new MemberEntry();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["MemberInfo"] = null;

		$data["Source"] = $request['Source'];
		$data["EntryID"] = $request['EntryID'];
		$data["ParentEntryID"] = $request['ParentEntryID'];
		$data["Position"] = $request['Position'];

		$data["EncodedByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["CreatedByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["UpdatedByID"] = Session('ADMIN_ACCOUNT_ID');

		if ($data["EntryID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Unable to verify member entry.";
		} else if ($data["ParentEntryID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Unable to verify upline.";
		} else if (empty($data["Position"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please specify preferred position.";
		}

		//Check Position
		$MemberInfo = $MemberEntry->getMemberEntryInfo($data["ParentEntryID"]);
		if (isset($MemberInfo)) {
			if ($data["Position"] == 'L' && $MemberInfo->LeftEntryID > 0) {
				$RetVal['Response'] = "Failed";
				$RetVal['ResponseMessage'] = "Left position is not available.";
			}

			if ($data["Position"] == 'R' && $MemberInfo->RightEntryID > 0) {
				$RetVal['Response'] = "Failed";
				$RetVal['ResponseMessage'] = "Right position is not available.";
			}
		}

		if ($RetVal['Response'] != "Failed") {

			//Save Member Entry
			$data["EntryID"] = $MemberEntry->doTransferMemberPosition($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Member position has been transferred successfully.";
			$RetVal["MemberInfo"] = $MemberEntry->getMemberEntryInfo($data["EntryID"]);
		}

		return response()->json($RetVal);
	}

	public function doUpgradeMemberEntry(Request $request)
	{

		$MemberEntry = new MemberEntry();
		$Package = new Package();
		$Code = new Code();
		$Misc = new Misc();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["MemberInfo"] = null;

		$data["Source"] = $request['Source'];
		$data["EntryID"] = $request['EntryID'];

		//Package Information
		$data["CurrentPackageID"] = $request['CurrentPackageID'];
		$data["CurrentPackagePrice"] = 0;
		$CurrentPackageInfo = $Package->getPackageInfo($data["CurrentPackageID"]);
		if (isset($CurrentPackageInfo)) {
			$data["CurrentPackagePrice"] = $CurrentPackageInfo->PackagePrice;
		}

		//Code Information
		$data["CodeID"] = 0;
		$data["Code"] = trim($request['Code']);
		$data["PackageID"] = 0;
		$data["Package"] = "";
		$data["PackagePrice"] = 0;
		$data["IsFreeCode"] = 0;
		$CodeInfo = $Code->getCodeGenerationInfoByCode($data["Code"]);
		if (isset($CodeInfo)) {
			if ($CodeInfo->IssuedToMemberEntryID > 0 && $CodeInfo->Status == config('app.STATUS_AVAILABLE')) {
				$data["CodeID"] = $CodeInfo->CodeID;
				$data["PackageID"] = $CodeInfo->PackageID;
				$data["Package"] = $CodeInfo->Package;
				$data["PackagePrice"] = $CodeInfo->PackagePrice;
				$data["IsFreeCode"] = $CodeInfo->IsFreeCode;
			}
		}
		$data["SponsorEntryID"] = $request['SponsorEntryID'];

		if (Session('ADMIN_ACCOUNT_ID')) {
			$data["EncodedByID"] = Session('ADMIN_ACCOUNT_ID');
			$data["CreatedByID"] = Session('ADMIN_ACCOUNT_ID');
			$data["UpdatedByID"] = Session('ADMIN_ACCOUNT_ID');
		} else {
			$data["EncodedByID"] = 1;
			$data["CreatedByID"] = 1;
			$data["UpdatedByID"] = 1;
		}

		if ($data["CodeID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Unable to verify entry code.";
		} else if (!$Code->IsCodeAvailableByCodeID($data["CodeID"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Sorry. Code has been used already.";
		} else if ($data["CurrentPackagePrice"] >= $data["PackagePrice"]) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please use a code that is higher than your current package.";
		}

		if ($RetVal['Response'] != "Failed") {

			//Save Member Entry
			$data["EntryID"] = $MemberEntry->doUpgradeMemberEntry($data);

			if ($data["Source"] == "Member") {
				Session::put('MEMBER_PACKAGE_ID', $data["PackageID"]);
				Session::put('MEMBER_PACKAGE', $data["Package"]);
			}

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Member entry has been upgraded successfully.";
			$RetVal["MemberInfo"] = $MemberEntry->getMemberEntryInfo($data["EntryID"]);
		}

		return response()->json($RetVal);
	}

	//MEMBER VOUCHER ------------------------------------------------
	public function showMemberVoucher()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();

		$data['Page'] = 'member-voucher';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		return View::make('admin/member-voucher')->with($data);
	}

	public function getMemberVoucherList(Request $request)
	{

		$Voucher = new Voucher();

		$param["MemberEntryID"] = $request["MemberEntryID"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];
		$param["Status"] = $request["Status"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["MemberVoucherList"] = $Voucher->getMemberVoucherList($param);

		return response()->json($RetVal);
	}

	public function getCenterVoucherList(Request $request)
	{

		$Voucher = new Voucher();

		$param["CenterID"] = $request["CenterID"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CenterVoucherList"] = $Voucher->getCenterVoucherList($param);

		return response()->json($RetVal);
	}

	public function getMemberVoucherInfo(Request $request)
	{

		$Voucher = new Voucher();

		$VoucherID = $request["VoucherID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["MemberVoucherInfo"] = $Voucher->getMemberVoucherInfo($VoucherID);

		return response()->json($RetVal);
	}

	//PACKAGE ------------------------------------------------
	public function showPackageManagement()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();
		$Inventory = new Inventory();

		$data['Page'] = 'package-management';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 1;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		return View::make('admin/package-management')->with($data);
	}

	public function getPackageList(Request $request)
	{

		$Package = new Package();

		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["PackageList"] = $Package->getPackageList($param);

		return response()->json($RetVal);
	}

	public function getPackageSearchList(Request $request)
	{

		$Package = new Package();

		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];
		$PackageList = $Package->getPackageList($param);

		$RetVal = array();
		foreach ($PackageList as $row) {
			$data = $row->PackageID . '|' . $row->Package . '|' . $row->PackagePrice . '|' . $row->ProductWorth . '|' . $row->SponsorCommission;
			array_push($RetVal, $data);
		}

		return json_encode($RetVal);
	}

	public function getPackageInfo(Request $request)
	{

		$Package = new Package();

		$PackageID = $request["PackageID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["PackageInfo"] = $Package->getPackageInfo($PackageID);

		return response()->json($RetVal);
	}

	public function doSavePackage(Request $request)
	{

		$Package = new Package();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["PackageInfo"] = null;

		$data["PackageID"] = $request['PackageID'];

		$data["Package"] = $request['Package'];
		$data["Description"] = $request['Description'];
		$data["Status"] = $request['Status'];

		$data["PackagePrice"] = $request['PackagePrice'];
		$data["ProductWorth"] = $request['ProductWorth'];
		$data["SponsorCommission"] = $request['SponsorCommission'];

		$data["PackageColor"] = $request['PackageColor'];
		$data["ProductID"] = $request['ProductID'];

		//Package Entry Settings
		$data["NoOfEntryShare"] = $request['NoOfEntryShare'];
		$data["EntryShareAmount"] = $request['EntryShareAmount'];
		$data["MaxShareAmount"] = $request['MaxShareAmount'];

		//Package Matching Commission Settings
		$data["RequiredBPV"] = $request['RequiredBPV'];
		$data["PairingAmount"] = $request['PairingAmount'];
		$data["MaxMatchPerDay"] = $request['MaxMatchPerDay'];
		$data["VoucherOnNthPair"] = $request['VoucherOnNthPair'];

		//Package Rebates Settings
		$data["RebatesMaintainingBal"] = $request['RebatesMaintainingBal'];
		$data["PersonalRebatesPercent"] = $request['PersonalRebatesPercent'];
		$data["RebateLevel1Percent"] = $request['RebateLevel1Percent'];
		$data["RebateLevel2Percent"] = $request['RebateLevel2Percent'];
		$data["RebateLevel3Percent"] = $request['RebateLevel3Percent'];
		$data["RebateLevel4Percent"] = $request['RebateLevel4Percent'];
		$data["RebateLevel5Percent"] = $request['RebateLevel5Percent'];
		$data["RebateLevel6Percent"] = $request['RebateLevel6Percent'];
		$data["RebateLevel7Percent"] = $request['RebateLevel7Percent'];
		$data["RebateLevel8Percent"] = $request['RebateLevel8Percent'];
		$data["RebateLevel9Percent"] = $request['RebateLevel9Percent'];

		//Package Rank Settings
		$data["RankLevel1"] = (empty($request['RankLevel1']) ? "" : $request['RankLevel1']);
		$data["RankLevel1APPRV"] = $request['RankLevel1APPRV'];
		$data["RankLevel1AGPRV"] = $request['RankLevel1AGPRV'];
		$data["RankLevel1Percent"] = $request['RankLevel1Percent'];

		$data["RankLevel2"] = (empty($request['RankLevel2']) ? "" : $request['RankLevel2']);
		$data["RankLevel2APPRV"] = $request['RankLevel2APPRV'];
		$data["RankLevel2AGPRV"] = $request['RankLevel2AGPRV'];
		$data["RankLevel2Percent"] = $request['RankLevel2Percent'];

		$data["RankLevel3"] = (empty($request['RankLevel3']) ? "" : $request['RankLevel3']);;
		$data["RankLevel3APPRV"] = $request['RankLevel3APPRV'];
		$data["RankLevel3AGPRV"] = $request['RankLevel3AGPRV'];
		$data["RankLevel3Percent"] = $request['RankLevel3Percent'];

		$data["RankLevel4"] = (empty($request['RankLevel4']) ? "" : $request['RankLevel4']);
		$data["RankLevel4APPRV"] = $request['RankLevel4APPRV'];
		$data["RankLevel4AGPRV"] = $request['RankLevel4AGPRV'];
		$data["RankLevel4Percent"] = $request['RankLevel4Percent'];

		$data["RankLevel5"] = (empty($request['RankLevel5']) ? "" : $request['RankLevel5']);
		$data["RankLevel5APPRV"] = $request['RankLevel5APPRV'];
		$data["RankLevel5AGPRV"] = $request['RankLevel5AGPRV'];
		$data["RankLevel5Percent"] = $request['RankLevel5Percent'];

		$data["RankLevel6"] = (empty($request['RankLevel6']) ? "" : $request['RankLevel6']);
		$data["RankLevel6APPRV"] = $request['RankLevel6APPRV'];
		$data["RankLevel6AGPRV"] = $request['RankLevel6AGPRV'];
		$data["RankLevel6Percent"] = $request['RankLevel6Percent'];

		$data["RankLevel7"] = (empty($request['RankLevel7']) ? "" : $request['RankLevel7']);
		$data["RankLevel7APPRV"] = $request['RankLevel7APPRV'];
		$data["RankLevel7AGPRV"] = $request['RankLevel7AGPRV'];
		$data["RankLevel7Percent"] = $request['RankLevel7Percent'];

		$data["RankLevel8"] = (empty($request['RankLevel8']) ? "" : $request['RankLevel8']);
		$data["RankLevel8APPRV"] = $request['RankLevel8APPRV'];
		$data["RankLevel8AGPRV"] = $request['RankLevel8AGPRV'];
		$data["RankLevel8Percent"] = $request['RankLevel8Percent'];

		$data["RankLevel9"] = (empty($request['RankLevel9']) ? "" : $request['RankLevel9']);
		$data["RankLevel9APPRV"] = $request['RankLevel9APPRV'];
		$data["RankLevel9AGPRV"] = $request['RankLevel9AGPRV'];
		$data["RankLevel9Percent"] = $request['RankLevel9Percent'];

		$data["ApprovedByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["CreatedByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["UpdatedByID"] = Session('ADMIN_ACCOUNT_ID');

		if (empty($data["Package"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter package name.";
		} else if (empty($data["PackagePrice"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter package price.";
		} else if ($data["PackagePrice"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter package price.";
		} else if (empty($data["ProductWorth"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter package product worth.";
		} else if (empty($data["SponsorCommission"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter package sponsor commission.";
		} else if (empty($data["PackageColor"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set package color.";
		}

		if ($RetVal['Response'] != "Failed") {

			//Save Package Info
			$data["PackageID"] = $Package->doSaveUpdatePackage($data);

			//Save Package Entry Settings
			$Package->doSaveUpdatePackageEntry($data);

			//Save Package Matching Commission Settings
			$Package->doSaveUpdatePackageMatchingComm($data);

			//Save Package Rebates Settings
			$Package->doSaveUpdatePackageRebates($data);

			//Save Package Rank Settings
			$Package->doSaveUpdatePackageRanks($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Package settings has been saved successfully.";
			$RetVal["PackageInfo"] = $Package->getPackageInfo($data["PackageID"]);
		}

		return response()->json($RetVal);
	}

	//PRODUCT ------------------------------------------------
	public function showProductManagement()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();
		$Product = new Product();

		$data['Page'] = 'product-management';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		return View::make('admin/product-management')->with($data);
	}

	public function getProductList(Request $request)
	{

		$Product = new Product();

		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["ProductList"] = $Product->getProductList($param);

		return response()->json($RetVal);
	}

	public function getProductSearchList(Request $request)
	{

		$Product = new Product();
		$RetVal = array();

		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = 0;
		$param["PageNo"] = 0;

		$List = $Product->getProductList($param);
		foreach ($List as $row) {
			$data = $row->ProductID . '|' . $row->Brand . '|' . $row->Category . '|' . $row->ProductCode . '|' . $row->ProductName . '|' . $row->Measurement . '|' . $row->DistributorPrice . '|' . $row->RetailPrice . '|' . $row->RebateValue;
			array_push($RetVal, $data);
		}

		return json_encode($RetVal);
	}

	public function getProductInfo(Request $request)
	{

		$Product = new Product();

		$ProductID = $request["ProductID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["ProductInfo"] = $Product->getProductInfo($ProductID);

		return response()->json($RetVal);
	}

	public function doSaveProduct(Request $request)
	{

		$Product = new Product();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["ProductInfo"] = null;

		$data["ProductID"] = $request['ProductID'];

		$data["Brand"] = $request['Brand'];
		$data["Category"] = $request['Category'];
		$data["ProductCode"] = $request['ProductCode'];
		$data["ProductName"] = $request['ProductName'];
		$data["Description"] = $request['Description'];
		$data["Specification"] = $request['Specification'];
		$data["NetWeight"] = $request['NetWeight'];
		$data["Measurement"] = $request['Measurement'];

		$data["IsPackageSet"] = $request['IsPackageSet'];

		$data["CenterPrice"] = 0;
		$data["DistributorPrice"] = $request['DistributorPrice'];
		$data["RetailPrice"] = $request['RetailPrice'];
		$data["RebateValue"] = $request['RebateValue'];

		$data["Status"] = $request['Status'];

		$data["CreatedByID"] = Session('ADMIN_ACCOUNT_ID');
		$data["UpdatedByID"] = Session('ADMIN_ACCOUNT_ID');

		if (empty($data["Brand"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter product brand.";
			return response()->json($RetVal);
		}

		if (empty($data["Category"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select product category.";
			return response()->json($RetVal);
		}

		if (empty($data["ProductName"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter product name.";
			return response()->json($RetVal);
		}

		if (empty($data["NetWeight"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set net weight of the product.";
			return response()->json($RetVal);
		}
		if ($data["NetWeight"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set net weight of the product.";
			return response()->json($RetVal);
		}

		if (empty($data["Measurement"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select product measurement.";
			return response()->json($RetVal);
		}

		if (empty($data["DistributorPrice"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter distributor price.";
			return response()->json($RetVal);
		}
		if ($data["DistributorPrice"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter distributor price.";
			return response()->json($RetVal);
		}

		if (empty($data["DistributorPrice"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter distributor price.";
			return response()->json($RetVal);
		}
		if ($data["DistributorPrice"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter distributor price.";
			return response()->json($RetVal);
		}

		if (empty($data["RetailPrice"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter retail price.";
			return response()->json($RetVal);
		}
		if ($data["RetailPrice"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter retail price.";
			return response()->json($RetVal);
		}

		if ($RetVal['Response'] != "Failed") {
			$data["ProductID"] = $Product->doSaveUpdateProduct($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Product has been saved successfully.";
			$RetVal["ProductInfo"] = $Product->getProductInfo($data["ProductID"]);

			$RetVal["data"] = $data;
		}

		return response()->json($RetVal);
	}

	public function doUploadProductPhoto(Request $request)
	{

		$Product = new Product();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["ProductID"] = $request['ProductID'];

		if ($RetVal['Response'] != "Failed") {
			$Response = $Product->doUploadProductPhoto($data);
			return Redirect::back()->with('Success_Msg', 'Product photo has been uploaded successfully.');
		} else {
			return Redirect::back()->with('Error_Msg', 'Something went wrong while uploading station product photo.');
		}
	}

	//INVENTORY ------------------------------------------------
	public function showInventoryList()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();

		$data['Page'] = 'admin-inventory';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		return View::make('admin/inventory-list')->with($data);
	}

	public function getInventoryList(Request $request)
	{

		$Inventory = new Inventory();

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 2;
		$param["CenterID"] = $request["CenterID"];
		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["InventoryList"] = $Inventory->getInventoryList($param);

		return response()->json($RetVal);
	}

	public function getInventoryLedger(Request $request)
	{

		$Inventory = new Inventory();

		$param["CenterID"] = $request["CenterID"];
		$param["ProductID"] = $request["ProductID"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["InventoryLedger"] = $Inventory->getInventoryLedger($param);

		return response()->json($RetVal);
	}

	public function setInventoryBegBal(Request $request)
	{

		$Inventory = new Inventory();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["InventoryInfo"] = null;

		$data["InventoryID"] = $request["InventoryID"];
		$data["CenterID"] = $request["CenterID"];
		$data["ProductID"] = $request["ProductID"];
		$data["BegBalDateTime"] = $request["BegBalDateTime"];
		$data["BegBalance"] = (empty($request["BegBalance"]) ? 0 : $request["BegBalance"]);
		$data["BegBalRemarks"] = $request["BegBalRemarks"];

		$data["BegBalanceByID"] = Session('ADMIN_ACCOUNT_ID');

		if (empty($data["InventoryID"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Unable to verify the product.";
			return response()->json($RetVal);
		}

		if (empty($data["BegBalDateTime"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set the beginning balance date.";
			return response()->json($RetVal);
		}

		if (empty($data["BegBalance"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set the beginning balance.";
			return response()->json($RetVal);
		}

		if ($RetVal['Response'] != "Failed") {
			$Inventory->doSaveBegBal($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Beginning balance has been set successfully.";
			$RetVal["InventoryInfo"] = $Inventory->getInventoryInfo($data["InventoryID"]);
		}

		return response()->json($RetVal);
	}

	public function setInventoryMinMax(Request $request)
	{

		$Inventory = new Inventory();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["InventoryInfo"] = null;

		$data["InventoryID"] = $request["InventoryID"];
		$data["CenterID"] = $request["CenterID"];
		$data["ProductID"] = $request["ProductID"];
		$data["MinimumLevel"] = $request["MinimumLevel"];
		$data["MaximumLevel"] = $request["MaximumLevel"];
		$data["MinMaxByID"] = Session('ADMIN_ACCOUNT_ID');

		if (empty($data["ProductID"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Unable to verify the product.";
			return response()->json($RetVal);
		}

		if (empty($data["MinimumLevel"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set the minimum level of this product.";
			return response()->json($RetVal);
		}

		if (empty($data["MaximumLevel"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set the maximum level of this product.";
			return response()->json($RetVal);
		}

		if ($data["MinimumLevel"] >= $data["MaximumLevel"]) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "The minimum level should not be greater than or equal the maximum level.";
			return response()->json($RetVal);
		}

		if ($RetVal['Response'] != "Failed") {
			$Inventory->doSaveMinMax($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Minimum/Maximum level has been set successfully.";
			$RetVal["InventoryInfo"] = $Inventory->getInventoryInfo($data["InventoryID"]);
		}

		return response()->json($RetVal);
	}

	//INVENTORY ADJUSTMENT------------------------------------------------
	public function showInventoryAdjustment()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();
		$Center = new Center();
		$Inventory = new Inventory();

		$data['Page'] = 'admin-inventory-adjustment';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 2;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		return View::make('admin/inventory-adjustment-list')->with($data);
	}

	public function getInventoryAdjustmentList(Request $request)
	{

		$InventoryAdjustment = new InventoryAdjustment();

		$param["CenterID"] = $request["CenterID"];
		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["InventoryAdjustmentList"] = $InventoryAdjustment->getAdjustmentList($param);

		return response()->json($RetVal);
	}

	public function getInventoryAdjustmentInfo(Request $request)
	{

		$InventoryAdjustment = new InventoryAdjustment();

		$AdjustmentID = $request["AdjustmentID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["InventoryAdjustmentInfo"] = $InventoryAdjustment->getAdjustmentInfo($AdjustmentID);

		return response()->json($RetVal);
	}

	public function getInventoryAdjustmentItemList(Request $request)
	{

		$InventoryAdjustment = new InventoryAdjustment();

		$param["AdjustmentID"] = $request['AdjustmentID'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["InventoryAdjustmentItemList"] = $InventoryAdjustment->getAdjustmentItemList($param);

		return response()->json($RetVal);
	}

	public function doSaveInventoryAdjustment(Request $request)
	{

		$InventoryAdjustment = new InventoryAdjustment();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["InventoryAdjustmentInfo"] = null;

		$data["AdjustmentID"] = $request['AdjustmentID'];
		$data["CenterID"] = $request['CenterID'];
		$data["Remarks"] = $request['Remarks'];
		$data["Status"] = $request['Status'];

		$data['InvAdjItems'] = $request["InvAdjItems"];
		$data['InvAdjItemsDeleted'] = $request["InvAdjItemsDeleted"];

		$data["ApprovedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($RetVal['Response'] != "Failed") {
			$data["AdjustmentID"] = $InventoryAdjustment->doSaveUpdateRecord($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Inventory adjustment has been saved successfully.";
			$RetVal["InventoryAdjustmentInfo"] = $InventoryAdjustment->getAdjustmentInfo($data["AdjustmentID"]);
		}

		return response()->json($RetVal);
	}

	public function doCancelInventoryAdjustment(Request $request)
	{

		$InventoryAdjustment = new InventoryAdjustment();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["AdjustmentID"] = $request['AdjustmentID'];
		$data["CancelledByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["Reason"] = $request['CancellationReason'];

		if ($RetVal['Response'] != "Failed") {
			$data["AdjustmentID"] = $InventoryAdjustment->doCancelInventoryAdjustment($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Inventory adjustment has been cancelled successfully.";
			$RetVal['AdjustmentID'] = $data["AdjustmentID"];
			$RetVal["InventoryAdjustmentInfo"] = $InventoryAdjustment->getAdjustmentInfo($data["AdjustmentID"]);
		}

		return response()->json($RetVal);
	}

	//PURCHASE ORDER ------------------------------------------------
	public function showPurchaseOrder()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();
		$Center = new Center();
		$PurchaseOrder = new PurchaseOrder();
		$Inventory = new Inventory();

		$data['Page'] = 'admin-purchase-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 2;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		return View::make('admin/purchase-order')->with($data);
	}

	public function getPOList(Request $request)
	{

		$PurchaseOrder = new PurchaseOrder();

		$param["CenterID"] = $request["CenterID"];
		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];
		$param["IsUnProcessedOnly"] = 0;

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POList"] = $PurchaseOrder->getPOList($param);

		return response()->json($RetVal);
	}

	public function getPOSearchList(Request $request)
	{

		$PurchaseOrder = new PurchaseOrder();
		$RetVal = array();

		$param["CenterID"] = 0;
		if ($request["CenterID"]) {
			$param["CenterID"] = $request["CenterID"];
		}

		$param["Status"] = "";
		if ($request["Status"]) {
			$param["Status"] = $request["Status"];
		}
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$param["IsUnProcessedOnly"] = $request["IsUnProcessedOnly"];

		$List = $PurchaseOrder->getPOList($param);
		foreach ($List as $row) {
			$data = $row->POID . '|' .
				$row->PONo . '|' .
				$row->CenterID . '|' .
				$row->POType;
			array_push($RetVal, $data);
		}

		return json_encode($RetVal);
	}

	public function getPOInfo(Request $request)
	{

		$PurchaseOrder = new PurchaseOrder();

		$POID = $request["POID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POInfo"] = $PurchaseOrder->getPOInfo($POID);

		return response()->json($RetVal);
	}

	public function getPOItemList(Request $request)
	{

		$PurchaseOrder = new PurchaseOrder();

		$param["POID"] = $request['POID'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POItemList"] = $PurchaseOrder->getPOItemList($param);

		return response()->json($RetVal);
	}

	public function getPOVoucherList(Request $request)
	{

		$PurchaseOrder = new PurchaseOrder();

		$param["POID"] = $request['POID'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POVoucherList"] = $PurchaseOrder->getPOVoucherList($param);

		return response()->json($RetVal);
	}

	public function doSavePO(Request $request)
	{

		$PurchaseOrder = new PurchaseOrder();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POInfo"] = null;

		$data["POID"] = $request['POID'];

		$data["POType"] = $request['POType'];

		$data["CenterID"] = $request['CenterID'];

		$data["GrossTotal"] = $request['GrossAmount'];
		$data["TotalVoucherPayment"] = $request['TotalVoucherPayment'];
		$data["TotalAmountDue"] = $request['TotalAmountDue'];

		$data["Remarks"] = $request['Remarks'];
		$data["Status"] = $request['Status'];

		$data['VoucherData'] = $request["VoucherData"];

		$data['POItems'] = $request["POItems"];
		$data['POItemsDeleted'] = $request["POItemsDeleted"];

		$data["ApprovedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($data["CenterID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select center.";
		} elseif (count($data['POItems']) <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter PO items.";
		}

		if ($RetVal['Response'] != "Failed") {
			$data["POID"] = $PurchaseOrder->doSaveUpdatePO($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Purchase order has been saved successfully.";
			$RetVal["POInfo"] = $PurchaseOrder->getPOInfo($data["POID"]);
		}

		return response()->json($RetVal);
	}

	public function doCancelPO(Request $request)
	{

		$PurchaseOrder = new PurchaseOrder();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["POID"] = $request['POID'];
		$data["CancelledByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["Reason"] = $request['CancellationReason'];

		if ($RetVal['Response'] != "Failed") {
			$data["POID"] = $PurchaseOrder->doCancelPO($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Purchase order has been cancelled successfully.";
			$RetVal['POID'] = $data["POID"];
			$RetVal["POInfo"] = $PurchaseOrder->getPOInfo($data["POID"]);
		}

		return response()->json($RetVal);
	}

	//PURCHASE ORDER PROCESSING ------------------------------------------------
	public function showPOProcessing()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();
		$Center = new Center();
		$Inventory = new Inventory();

		$data['Page'] = 'admin-purchase-order-process';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 2;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		return View::make('admin/purchase-order-processing')->with($data);
	}

	public function getPOProcessingList(Request $request)
	{

		$PurchaseOrderProcess = new PurchaseOrderProcess();

		$param["CenterID"] = $request["CenterID"];
		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];
		$param["IsUnReceivedOnly"] = 0;

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POProcessList"] = $PurchaseOrderProcess->getPOProcessList($param);

		return response()->json($RetVal);
	}

	public function getPOProcessingSearchList(Request $request)
	{

		$PurchaseOrderProcess = new PurchaseOrderProcess();
		$RetVal = array();

		$param["CenterID"] = 0;
		if ($request["CenterID"]) {
			$param["CenterID"] = $request["CenterID"];
		}

		$param["Status"] = "";
		if ($request["Status"]) {
			$param["Status"] = $request["Status"];
		}
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$param["IsUnReceivedOnly"] = $request["IsUnReceivedOnly"];

		$List = $PurchaseOrderProcess->getPOProcessList($param);
		foreach ($List as $row) {
			$data = $row->ProcessID . '|' .
				$row->ProcessNo . '|' .
				$row->POID . '|' .
				$row->PONo . '|' .
				$row->CenterID . '|' .
				$row->CenterNo . '|' .
				$row->Center . '|' .
				$row->TelNo . '|' .
				$row->MobileNo . '|' .
				$row->EmailAddress . '|' .
				$row->Address . '|' .
				$row->City . '|' .
				$row->StateProvince . '|' .
				$row->ZipCode . '|' .
				$row->Country . '|' .
				$row->GrossTotal . '|' .
				$row->TotalVoucherPayment . '|' .
				$row->TotalDiscountPercent . '|' .
				$row->TotalDiscount . '|' .
				$row->TotalAmountDue;

			array_push($RetVal, $data);
		}

		return json_encode($RetVal);
	}

	public function getPOProcessingInfo(Request $request)
	{

		$PurchaseOrderProcess = new PurchaseOrderProcess();

		$POID = $request["POID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POProcessInfo"] = $PurchaseOrderProcess->getPOProcessInfo($POID);

		return response()->json($RetVal);
	}

	public function getPOProcessingItemList(Request $request)
	{

		$PurchaseOrderProcess = new PurchaseOrderProcess();

		$param["ProcessID"] = $request['ProcessID'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POProcessItemList"] = $PurchaseOrderProcess->getPOProcessItemList($param);

		return response()->json($RetVal);
	}

	public function getPOProcessingVoucherList(Request $request)
	{

		$PurchaseOrderProcess = new PurchaseOrderProcess();

		$param["ProcessID"] = $request['ProcessID'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POProcessVoucherList"] = $PurchaseOrderProcess->getPOProcessVoucherList($param);

		return response()->json($RetVal);
	}

	public function doSavePOProcessing(Request $request)
	{

		$Inventory = new Inventory();
		$PurchaseOrderProcess = new PurchaseOrderProcess();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POProcessInfo"] = null;

		$data["ProcessID"] = $request['ProcessID'];

		$data["ProcessType"] = $request['ProcessType'];

		$data["ProcessingCenterID"] = Session('ADMIN_CENTER_ID');

		$data["CenterID"] = $request['CenterID'];
		$data["POID"] = $request['POID'];

		$data["GrossTotal"] = $request['GrossAmount'];
		$data["TotalVoucherPayment"] = $request['TotalVoucherPayment'];
		$data["TotalDiscountPercent"] = $request['TotalDiscountPercent'];
		$data["TotalDiscount"] = $request['TotalDiscount'];
		$data["TotalAmountDue"] = $request['TotalAmountDue'];

		$data["Remarks"] = $request['Remarks'];
		$data["Status"] = $request['Status'];

		$data['VoucherData'] = $request["VoucherData"];

		$data['POProcessItems'] = $request["POProcessItems"];
		$data['POProcessItemsDeleted'] = $request["POProcessItemsDeleted"];

		$data["ApprovedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($data["CenterID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select center.";
		} elseif (count($data['POProcessItems']) <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter items.";
		}

		if ($RetVal['Response'] != "Failed") {

			//Check Product Inventory
			$InsufficientProducts = "";
			$POProcessItems = $data['POProcessItems'];
			if (count($POProcessItems) > 0) {
				//Main Loop
				for ($x = 0; $x < count($POProcessItems); $x++) {

					$ProductID = $POProcessItems[$x]["ProductID"];
					$Qty = $POProcessItems[$x]["Qty"];

					//Check All
					for ($y = 0; $y < count($POProcessItems); $y++) {
						if (
							$ProductID == $POProcessItems[$y]["ProductID"] &&
							$x != $y
						) {
							$Qty = $Qty + $POProcessItems[$y]["Qty"];
						}
					}

					$InventoryInfo = $Inventory->getInventoryInfoByCenterProduct($data["ProcessingCenterID"], $ProductID);
					if (isset($InventoryInfo)) {
						if ($InventoryInfo->StockOnHand < $Qty) {
							$InsufficientProducts = $InsufficientProducts . (empty($InsufficientProducts) ? "" : ", ") . $InventoryInfo->ProductName;
						}
					}
				}
			}

			if ($InsufficientProducts != "") {
				$RetVal['Response'] = "Failed";
				$RetVal['ResponseMessage'] = "Sorry. You have insufficient " . $InsufficientProducts . ".";
			} else {

				$data["ProcessID"] = $PurchaseOrderProcess->doSaveUpdatePOProcess($data);

				$RetVal['Response'] = "Success";
				$RetVal['ResponseMessage'] = "Purchase order has been saved successfully.";
				$RetVal["POProcessInfo"] = $PurchaseOrderProcess->getPOProcessInfo($data["ProcessID"]);
			}
		}

		return response()->json($RetVal);
	}

	public function doCancelPOProcessing(Request $request)
	{

		$PurchaseOrderProcess = new PurchaseOrderProcess();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["ProcessID"] = $request['ProcessID'];
		$data["CancelledByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["Reason"] = $request['CancellationReason'];

		if ($RetVal['Response'] != "Failed") {
			$data["ProcessID"] = $PurchaseOrderProcess->doCancelPOProcess($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "PO processing has been cancelled successfully.";
			$RetVal['ProcessID'] = $data["ProcessID"];
			$RetVal["POProcessInfo"] = $PurchaseOrderProcess->getPOProcessInfo($data["ProcessID"]);
		}

		return response()->json($RetVal);
	}

	//PURCHASE RECEIVE ------------------------------------------------
	public function showPurchaseReceive()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();
		$Center = new Center();
		$Inventory = new Inventory();

		$data['Page'] = 'admin-purchase-receive';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 2;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		return View::make('admin/purchase-receive')->with($data);
	}

	public function getPurchaseReceiveList(Request $request)
	{

		$PurchaseReceive = new PurchaseReceive();

		$param["CenterID"] = $request["CenterID"];
		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["PurchaseReceiveList"] = $PurchaseReceive->getPurchaseReceiveList($param);

		return response()->json($RetVal);
	}

	public function getPurchaseReceiveInfo(Request $request)
	{

		$PurchaseReceive = new PurchaseReceive();

		$ReceiveID = $request["ReceiveID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["PurchaseReceiveInfo"] = $PurchaseReceive->getPurchaseReceiveInfo($ReceiveID);

		return response()->json($RetVal);
	}

	public function getPurchaseReceiveItemList(Request $request)
	{

		$PurchaseReceive = new PurchaseReceive();

		$param["ReceiveID"] = $request['ReceiveID'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["PurchaseReceiveItemList"] = $PurchaseReceive->getPurchaseReceiveItemList($param);

		return response()->json($RetVal);
	}

	public function doSavePurchaseReceive(Request $request)
	{

		$PurchaseReceive = new PurchaseReceive();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["POProcessInfo"] = null;

		$data["ReceiveID"] = $request['ReceiveID'];
		$data["ReceiveNo"] = $request['ReceiveNo'];

		$data["ProcessID"] = $request['ProcessID'];
		$data["CenterID"] = $request['CenterID'];

		$data["GrossTotal"] = $request['GrossAmount'];
		$data["TotalVoucherPayment"] = $request['TotalVoucherPayment'];
		$data["TotalDiscountPercent"] = $request['TotalDiscountPercent'];
		$data["TotalDiscount"] = $request['TotalDiscount'];
		$data["TotalAmountDue"] = $request['TotalAmountDue'];

		$data["Remarks"] = $request['Remarks'];
		$data["Status"] = $request['Status'];

		$data['IsRemoveAllItems'] = $request["IsRemoveAllItems"];
		$data['ReceiveItems'] = $request["ReceiveItems"];
		$data['ReceiveItemsDeleted'] = $request["ReceiveItemsDeleted"];

		$data["ApprovedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($data["ProcessID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select center.";
		} elseif (count($data['ReceiveItems']) <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter received items.";
		}

		if ($RetVal['Response'] != "Failed") {
			$data["ReceiveID"] = $PurchaseReceive->doSaveUpdatePurchaseReceive($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Purchase receive has been saved successfully.";
			$RetVal["PurchaseReceiveInfo"] = $PurchaseReceive->getPurchaseReceiveInfo($data["ReceiveID"]);
		}

		return response()->json($RetVal);
	}

	public function doCancelPurchaseReceive(Request $request)
	{

		$PurchaseReceive = new PurchaseReceive();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["ReceiveID"] = $request['ReceiveID'];
		$data["CancelledByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["Reason"] = $request['CancellationReason'];

		if ($RetVal['Response'] != "Failed") {
			$data["ReceiveID"] = $PurchaseReceive->doCancelPurchaseReceive($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "PO processing has been cancelled successfully.";
			$RetVal['ReceiveID'] = $data["ReceiveID"];
			$RetVal["PurchaseReceiveInfo"] = $PurchaseReceive->getPurchaseReceiveInfo($data["ReceiveID"]);
		}

		return response()->json($RetVal);
	}

	//ORDER HISTORY ------------------------------------------------
	public function showOrderHistory()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'sales';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/order-history')->with($data);
	}

	public function showOrderUnverified()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'unverified-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/unverified-order')->with($data);
	}

	public function showOrderVerified()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'verified-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/verified-order')->with($data);
	}

	public function showOrderPacked()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'packed-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/packed-order')->with($data);
	}

	public function showOrderShipped()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'shipped-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/shipped-order')->with($data);
	}

	public function showOrderDelivered()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'delivered-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/delivered-order')->with($data);
	}

	public function showOrderReturned()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'returned-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/returned-order')->with($data);
	}

	public function showOrderCancelled()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'cancelled-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/cancelled-order')->with($data);
	}

	public function showOrderUnCollected()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'uncollected-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/uncollected-order')->with($data);
	}

	public function showOrderCollected()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Inventory = new Inventory();
		$Misc = new Misc();
		$Shipper = new Shipper();

		$data['Page'] = 'collected-order';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$param["IsWithInventoryOnly"] = 0;
		$param["IsComplanProductsOnly"] = 0;
		$param["CenterID"] = Session("ADMIN_CENTER_ID");
		$param["Status"] = '';
		$param["SearchText"] = '';
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["ProductList"] = $Inventory->getInventoryList($param);

		$data['ShipperList'] = $Shipper->getShipperList();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('admin/collected-order')->with($data);
	}

	public function getOrderList(Request $request)
	{

		$Order = new Order();

		$param["EntryID"] = 0;
		if ($request["EntryID"]) {
			$param["EntryID"] = $request["EntryID"];
		}
		$param["CenterID"] = $request["CenterID"];
		$param["CustomerEntryID"] = 0;
		if (isset($request["CustomerEntryID"])) {
			$param["CustomerEntryID"] = $request["CustomerEntryID"];
		}
		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["OrderList"] = $Order->getOrderList($param);

		return response()->json($RetVal);
	}

	public function getOrderInfo(Request $request)
	{

		$Order = new Order();

		$OrderID = $request["OrderID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["OrderInfo"] = $Order->getOrderInfo($OrderID);

		return response()->json($RetVal);
	}

	public function getOrderItemList(Request $request)
	{

		$Order = new Order();

		$param["OrderID"] = $request['OrderID'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["OrderItemList"] = $Order->getOrderItemList($param);

		return response()->json($RetVal);
	}

	public function getOrdervoucherList(Request $request)
	{

		$Order = new Order();

		$param["OrderID"] = $request['OrderID'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["OrderVoucherList"] = $Order->getOrderVoucherList($param);

		return response()->json($RetVal);
	}

	public function doSaveOrder(Request $request)
	{

		$Order = new Order();
		$Inventory = new Inventory();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["OrderInfo"] = null;

		$data["CenterID"] = $request['CenterID'];

		$data["OrderID"] = $request['OrderID'];

		$data["CustomerType"] = $request['CustomerType'];
		$data["CustomerEntryID"] = $request['CustomerEntryID'];

		$data["CustomerName"] = $request['CustomerName'];
		$data["EmailAddress"] = $request['EmailAddress'];
		$data["MobileNo"] = $request['MobileNo'];

		$data["Address"] = $request['Address'];
		$data["CityID"] = $request['City'];
		$data["StateProvince"] = $request['StateProvince'];
		$data["ZipCode"] = $request['ZipCode'];
		$data["CountryID"] = $request['Country'];

		$data["GrossTotal"] = $request['GrossAmount'];
		$data["ShippingCharges"] = 0;
		$data["TotalDiscountPercent"] = $request['TotalDiscountPercent'];
		$data["TotalDiscountAmount"] = $request['TotalDiscount'];
		$data["TotalAmountDue"] = $request['TotalAmountDue'];

		$data["ModeOfPayment"] = $request['ModeOfPayment'];

		$data["ShipperID"] = (empty($request['Shipper']) ? NULL : $request['Shipper']);

		$data["TotalVoucherPayment"] = $request['TotalVoucherPayment'];
		$data["TotalEWalletPayment"] = $request['TotalEWalletPayment'];
		$data["TotalCashPayment"] = $request['TotalCashPayment'];
		$data["AmountChange"] = $request['AmountChange'];
		$data["TotalRebatableValue"] = $request['TotalRebatableValue'];

		$data["Remarks"] = $request['Remarks'];
		$data["Status"] = $request['Status'];

		$data['VoucherData'] = $request["VoucherData"];

		$data['OrderItems'] = $request["OrderItems"];
		$data['OrderItemsDeleted'] = $request["OrderItemsDeleted"];

		$data["ApprovedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if (empty($data["CustomerType"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select customer type.";
		} else if ($data["CustomerType"] == "Member" && $data["CustomerEntryID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select customer type.";
		} else if ($data["CustomerType"] == "Member" && $data["CustomerEntryID"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select customer type.";
		} else if ($data["MobileNo"] == "") {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter mobile number.";
		} else if ($data["EmailAddress"] == "") {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter email address.";
		} else if ($data["Address"] == "") {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter address.";
		} else if ($data["CityID"] == "") {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select city address.";
		} else if ($data["StateProvince"] == "") {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter state/provincial address.";
		} else if ($data["CountryID"] == "") {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select country address.";
		} else if ($data["ModeOfPayment"] == "Cash" && $data["TotalAmountDue"] > ($data["TotalCashPayment"] + $data["TotalVoucherPayment"] + $data["TotalEWalletPayment"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please pay total amount due.";
		} elseif (count($data['OrderItems']) <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter ordered items.";
		}

		if ($RetVal['Response'] != "Failed") {

			//Check Product Inventory
			$InsufficientProducts = "";
			$OrderItems = $data['OrderItems'];
			if (count($OrderItems) > 0) {
				//Main Loop
				for ($x = 0; $x < count($OrderItems); $x++) {

					$ProductID = $OrderItems[$x]["ProductID"];
					$Qty = $OrderItems[$x]["Qty"];

					//Check All
					for ($y = 0; $y < count($OrderItems); $y++) {
						if (
							$ProductID == $OrderItems[$y]["ProductID"] &&
							$x != $y
						) {
							$Qty = $Qty + $OrderItems[$y]["Qty"];
						}
					}

					$InventoryInfo = $Inventory->getInventoryInfoByCenterProduct($data["CenterID"], $ProductID);
					if (isset($InventoryInfo)) {
						if ($InventoryInfo->StockOnHand < $Qty) {
							$InsufficientProducts = $InsufficientProducts . (empty($InsufficientProducts) ? "" : ", ") . $InventoryInfo->ProductName;
						}
					}
				}
			}

			if ($InsufficientProducts != "") {
				$RetVal['Response'] = "Failed";
				$RetVal['ResponseMessage'] = "Sorry. You have insufficient " . $InsufficientProducts . ".";
			} else {
				$data["OrderID"] = $Order->doSaveUpdateOrder($data);

				$RetVal['Response'] = "Success";
				$RetVal['ResponseMessage'] = "Order information has been saved successfully.";
				$RetVal["OrderInfo"] = $Order->getOrderInfo($data["OrderID"]);
			}
		}

		return response()->json($RetVal);
	}

	public function doPaidOrder(Request $request)
	{

		$Order = new Order();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["OrderInfo"] = null;

		$data["OrderID"] = $request['OrderID'];
		$data["SetPaidByID"] = Session("ADMIN_ACCOUNT_ID");

		$data["OrderID"] = $Order->doPaidOrder($data);

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "Order has been cancelled successfully.";
		$RetVal["OrderInfo"] = $Order->getOrderInfo($data["OrderID"]);

		return response()->json($RetVal);
	}

	public function doCancelOrder(Request $request)
	{

		$Order = new Order();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["OrderInfo"] = null;

		$data["OrderID"] = $request['OrderID'];
		$data["CancelledByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["Reason"] = $request['Reason'];

		if (empty($data["Reason"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter the reason why you want to cancel this order.";
			return response()->json($RetVal);
		} else {

			$data["OrderID"] = $Order->doCancelOrder($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Order has been cancelled successfully.";
			$RetVal["OrderInfo"] = $Order->getOrderInfo($data["OrderID"]);
		}

		return response()->json($RetVal);
	}

	public function doVerifyOrder(Request $request)
	{

		$Order = new Order();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["OrderID"] = $request['OrderID'];
		$data["VerifiedByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($RetVal['Response'] != "Failed") {
			$data["OrderID"] = $Order->doVerifyOrder($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Order has been verified successfully.";
			$RetVal['OrderID'] = $data["OrderID"];
		}

		return response()->json($RetVal);
	}

	public function doPackedOrder(Request $request)
	{

		$Order = new Order();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["OrderID"] = $request['OrderID'];
		$data["PackedByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($RetVal['Response'] != "Failed") {
			$data["OrderID"] = $Order->doPackedOrder($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Order has been set as packed successfully.";
			$RetVal['OrderID'] = $data["OrderID"];
		}

		return response()->json($RetVal);
	}

	public function doShippedOrder(Request $request)
	{

		$Order = new Order();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["OrderID"] = $request['OrderID'];
		$data["ShipperTrackingNo"] = $request['ShipperTrackingNo'];
		$data["SetAsShippedByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($RetVal['Response'] != "Failed") {
			$data["OrderID"] = $Order->doShippedOrder($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Order has been set as shipped successfully.";
			$RetVal['OrderID'] = $data["OrderID"];
		}

		return response()->json($RetVal);
	}

	public function doSetAsDeliveredOrder(Request $request)
	{

		$Order = new Order();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["OrderID"] = $request['OrderID'];
		$data["SetAsDeliveredByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($RetVal['Response'] != "Failed") {
			$data["OrderID"] = $Order->doSetAsDeliveredOrder($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Order has been set as delivered successfully.";
			$RetVal['OrderID'] = $data["OrderID"];
		}

		return response()->json($RetVal);
	}

	public function showOrderPrint(Request $request)
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Order = new Order();

		$data['Page'] = 'admin-order-print';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data["OrderID"] = $request["OrderID"];
		$data["OrderInfo"] = $Order->getOrderInfo($data["OrderID"]);
		$data["OrderItemList"] = $Order->getOrderItemList($data);

		return view('admin/order-print')->with($data);
	}

	//SHIPPER - J&T ------------------------------------------------
	public function showShipperJAT()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'shipper-jat-management';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		return View::make('admin/shipper-jat-management')->with($data);
	}

	public function getShipperJATBracketList(Request $request)
	{

		$ShipperJAT = new ShipperJAT();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["ShipperJATList"] = $ShipperJAT->getShipperJATList();

		return response()->json($RetVal);
	}

	public function getShipperJATBracketInfo(Request $request)
	{

		$ShipperJAT = new ShipperJAT();

		$SettingsID = $request["SettingsID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["ShipperJATInfo"] = $ShipperJAT->getShipperJATInfo($SettingsID);

		return response()->json($RetVal);
	}

	public function doSaveShipperJATBracket(Request $request)
	{

		$ShipperJAT = new ShipperJAT();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["ShipperJATInfo"] = null;

		$data["SettingsID"] = $request['SettingsID'];

		$data["Destination"] = $request['Destination'];
		$data["WeightLimit"] = $request['WeightLimit'];
		$data["Rates"] = $request['Rates'];
		$data["AdditionalRatesPerKg"] = $request['AdditionalRatesPerKg'];

		$data["UpdatedByID"] = Session('ADMIN_ACCOUNT_ID');

		if (empty($data["WeightLimit"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter weight limit.";
			return response()->json($RetVal);
		} else if ($data["WeightLimit"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter weight limit.";
			return response()->json($RetVal);
		} else if (empty($data["Destination"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select destination.";
			return response()->json($RetVal);
		}

		if ($RetVal['Response'] != "Failed") {
			$data["SettingsID"] = $ShipperJAT->doSaveUpdateShipperJAT($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "J&T settings has been saved successfully.";
			$RetVal["ShipperJATInfo"] = $ShipperJAT->getShipperJATInfo($data["SettingsID"]);
		}

		return response()->json($RetVal);
	}

	//E-Wallet ------------------------------------------------
	public function showMemberEWallet()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();

		$data['Page'] = 'member-ewallet';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		return View::make('admin/member-ewallet')->with($data);
	}

	public function getMemberEwalletLedger(Request $request)
	{

		$EWallet = new EWallet();
		$param["MemberID"] = $request['MemberID'];
		$param["EntryID"] = $request['EntryID'];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request['PageNo'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["EwalletLedger"] = $EWallet->getMemberEwalletLedger($param);

		return response()->json($RetVal);
	}

	public function getMemberEwalletBalance(Request $request)
	{

		$EWallet = new EWallet();
		$MemberID = $request['MemberID'];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["EwalletBalance"] = $EWallet->getMemberEWalletBalance($MemberID);

		return response()->json($RetVal);
	}

	//E-Wallet Withdrawal ------------------------------------------------
	public function showEWalletWithdrawal()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'sales';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		return View::make('admin/ewallet-withdrawal')->with($data);
	}

	public function getEWalletWithdrawalList(Request $request)
	{

		$EWalletWithdrawal = new EWalletWithdrawal();

		$param["MemberID"] = $request["MemberID"];
		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["EWalletWithdrawalList"] = $EWalletWithdrawal->getEWalletWithdrawalList($param);

		return response()->json($RetVal);
	}

	public function getEWalletWithdrawalInfo(Request $request)
	{

		$EWalletWithdrawal = new EWalletWithdrawal();

		$WithdrawalID = $request["WithdrawalID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["EWalletWithdrawalInfo"] = $EWalletWithdrawal->getEWalletWithdrawalInfo($WithdrawalID);

		return response()->json($RetVal);
	}

	public function doSaveEWalletWithdrawal(Request $request)
	{
		$TODAY = date("Y-m-d H:i:s");
		$EWallet = new EWallet();
		$EWalletWithdrawal = new EWalletWithdrawal();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["EWalletWithdrawalInfo"] = null;

		$data["WithdrawalID"] = $request['WithdrawalID'];
		$data["WithdrawalNo"] = $request['WithdrawalNo'];
		$data["WithdrawalDateTime"] = $request['WithdrawalDateTime'];
		$data["Status"] = $request['Status'];

		$data["WithdrawByMemberID"] = $request['WithdrawByMemberID'];

		$data["ApprovedByID"] = 0;
		$data["ApprovedDateTime"] = null;
		if ($data["Status"] == config('app.STATUS_APPROVED')) {
			$data["ApprovedByID"] = Session("ADMIN_ACCOUNT_ID");
			$data["ApprovedDateTime"] = $TODAY;
		}
		$data["ApproveRemarks"] = $request['ApproveRemarks'];
		$data["ApprovedAmount"] = $request['ApprovedAmount'];

		$data["EWalletBalance"] = $EWallet->getMemberEWalletBalance($data["WithdrawByMemberID"]);
		$data["RequestedAmount"] = $request['RequestedAmount'];
		$data["ProcessingFee"] = $request['ProcessingFee'];
		$data["NetAmountToReceive"] = $request['NetAmountToReceive'];

		$data["WithdrawalOption"] = $request['WithdrawalOption'];

		$data["SendToFirstName"] = "";
		$data["SendToLastName"] = "";
		$data["SendToMiddleName"] = "";
		$data["SendToTelNo"] = "";
		$data["SendToMobileNo"] = "";
		$data["SendToEmailAddress"] = "";
		$data["SenderName"] = "";
		$data["SendingRefNo"] = "";

		$data["Bank"] = "";
		$data["BankAccountName"] = "";
		$data["BankAccountNo"] = "";

		$data["CheckNo"] = "";
		$data["CheckDate"] = NULL;
		$data["CheckAmount"] = 0;

		if ($data["WithdrawalOption"] == "Check") {
			$data["CheckNo"] = $request['CheckNo'];
			$data['CheckDate'] = date("Y-m-d", strtotime(str_replace("-", "/", $request['CheckDate'])));
			$data["CheckAmount"] = $request['CheckAmount'];
		} else if ($data["WithdrawalOption"] == "Bank Transfer") {
			$data["Bank"] = $request['Bank'];
			$data["BankAccountName"] = $request['BankAccountName'];
			$data["BankAccountNo"] = $request['BankAccountNo'];
		} else {
			$data["SendToFirstName"] = $request['SendToFirstName'];
			$data["SendToLastName"] = $request['SendToLastName'];
			$data["SendToMiddleName"] = $request['SendToMiddleName'];
			$data["SendToTelNo"] = $request['SendToTelNo'];
			$data["SendToMobileNo"] = $request['SendToMobileNo'];
			$data["SendToEmailAddress"] = $request['SendToEmailAddress'];
			$data["SenderName"] = $request['SenderName'];
			$data["SendingRefNo"] = $request['SendingRefNo'];
		}

		$data["Notes"] = $request['Notes'];

		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($data["RequestedAmount"] < config('app.MinimumWithdrawalAmount')) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Minimum withdrawal amount is Php " . config('app.MinimumWithdrawalAmount');
		} else if ($data["EWalletBalance"] < $data["RequestedAmount"]) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Insufficient E-Wallet balance.";
		} else if ($data["Status"] == config('app.STATUS_FOR_APPROVAL') && $data["ApprovedAmount"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter approved amount.";
		} else if ($data["Status"] == config('app.STATUS_APPROVED') && $data["ApprovedAmount"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter approved amount.";
		} else if (($data["Status"] == config('app.STATUS_FOR_APPROVAL') || $data["Status"] == config('app.STATUS_APPROVED')) && $data["EWalletBalance"] < $data["ApprovedAmount"]) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Insufficient E-Wallet balance.";
		} else if (empty($data["WithdrawalOption"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select withdrawal option.";
		} else if ($data["WithdrawalOption"] == "Check" && ($data["Status"] == config('app.STATUS_FOR_APPROVAL') || $data["Status"] == config('app.STATUS_APPROVED')) && empty($data["CheckNo"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter check number.";
		} else if ($data["WithdrawalOption"] == "Check" && ($data["Status"] == config('app.STATUS_FOR_APPROVAL') || $data["Status"] == config('app.STATUS_APPROVED')) && empty($data["CheckDate"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter check date.";
		} else if ($data["WithdrawalOption"] == "Check" && ($data["Status"] == config('app.STATUS_FOR_APPROVAL') || $data["Status"] == config('app.STATUS_APPROVED')) && empty($data["CheckAmount"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set approved amount.";
		} else if ($data["WithdrawalOption"] == "Check" && ($data["Status"] == config('app.STATUS_FOR_APPROVAL') || $data["Status"] == config('app.STATUS_APPROVED')) && $data["CheckAmount"] <= 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set approved amount.";
		} else if ($data["WithdrawalOption"] == "Bank Transfer" && empty($data["Bank"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please select a bank.";
		} else if ($data["WithdrawalOption"] == "Bank Transfer" && empty($data["BankAccountName"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter bank account name.";
		} else if ($data["WithdrawalOption"] == "Bank Transfer" && empty($data["BankAccountNo"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter bank account number.";
		} else if (($data["WithdrawalOption"] == "Palawan Pera Padala" ||
			$data["WithdrawalOption"] == "MLhuillier" ||
			$data["WithdrawalOption"] == "Cebuana Lhuillier" ||
			$data["WithdrawalOption"] == "Western Union" ||
			$data["WithdrawalOption"] == "RD Pawnshop") && empty($data["SendToFirstName"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter receiver first name.";
		} else if (($data["WithdrawalOption"] == "Palawan Pera Padala" ||
			$data["WithdrawalOption"] == "MLhuillier" ||
			$data["WithdrawalOption"] == "Cebuana Lhuillier" ||
			$data["WithdrawalOption"] == "Western Union" ||
			$data["WithdrawalOption"] == "RD Pawnshop") && empty($data["SendToLastName"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter receiver last name.";
		} else if (($data["WithdrawalOption"] == "Palawan Pera Padala" ||
			$data["WithdrawalOption"] == "MLhuillier" ||
			$data["WithdrawalOption"] == "Cebuana Lhuillier" ||
			$data["WithdrawalOption"] == "Western Union" ||
			$data["WithdrawalOption"] == "RD Pawnshop") && empty($data["SendToMiddleName"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter receiver middle name.";
		} else if (($data["WithdrawalOption"] == "Palawan Pera Padala" ||
			$data["WithdrawalOption"] == "MLhuillier" ||
			$data["WithdrawalOption"] == "Cebuana Lhuillier" ||
			$data["WithdrawalOption"] == "Western Union" ||
			$data["WithdrawalOption"] == "RD Pawnshop") && empty($data["SendToMobileNo"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter receiver mobile number.";
		} else if ($data["Status"] == config('app.STATUS_APPROVED') && ($data["WithdrawalOption"] == "Palawan Pera Padala" ||
			$data["WithdrawalOption"] == "MLhuillier" ||
			$data["WithdrawalOption"] == "Cebuana Lhuillier" ||
			$data["WithdrawalOption"] == "Western Union" ||
			$data["WithdrawalOption"] == "RD Pawnshop") && empty($data["SenderName"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter sender full name.";
		} else if ($data["Status"] == config('app.STATUS_APPROVED') && ($data["WithdrawalOption"] == "Palawan Pera Padala" ||
			$data["WithdrawalOption"] == "MLhuillier" ||
			$data["WithdrawalOption"] == "Cebuana Lhuillier" ||
			$data["WithdrawalOption"] == "Western Union" ||
			$data["WithdrawalOption"] == "RD Pawnshop") && empty($data["SendingRefNo"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter control number or reference number.";
		}

		//Check if has existing withdrawal
		if ($data["WithdrawalID"] == 0 && $EWalletWithdrawal->IsHasUnApprovedWithdrawal($data["WithdrawByMemberID"]) > 0) {

			$RetVal['Response'] = "Failed";
			if (Session('ADMIN_LOGGED_IN')) {
				$RetVal['ResponseMessage'] = "Member has still unsettled withdrawal request. Please process the unsettled before creating another one.";
			} else {
				$RetVal['ResponseMessage'] = "Please give us time to process your unsettled request before creating another one.";
			}
		}

		if ($RetVal['Response'] != "Failed") {
			$data["WithdrawalID"] = $EWalletWithdrawal->doSaveUpdateEWalletWithdrawal($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "E-Wallet withdrawal has been saved successfully.";
			$RetVal["EWalletWithdrawalInfo"] = $EWalletWithdrawal->getEWalletWithdrawalInfo($data["WithdrawalID"]);
		}

		return response()->json($RetVal);
	}

	public function doCancelEWalletWithdrawal(Request $request)
	{

		$EWalletWithdrawal = new EWalletWithdrawal();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["OrderInfo"] = null;

		$data["WithdrawalID"] = $request['WithdrawalID'];
		$data["CancelledByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["Reason"] = $request['CancellationReason'];

		if (empty($data["Reason"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter the reason why you want to cancel this record.";
			return response()->json($RetVal);
		} else {

			$data["WithdrawalID"] = $EWalletWithdrawal->doCancelEWalletWithdrawal($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "E-Wallet Withdrawal has been cancelled successfully.";
			$RetVal["EWalletWithdrawalInfo"] = $EWalletWithdrawal->getEWalletWithdrawalInfo($data["WithdrawalID"]);
		}

		return response()->json($RetVal);
	}

	//Reports ------------------------------------------------
	public function showSalesReport(Request $request)
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'sales-report';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$Center = new Center();
		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		$CenterID = 0;
		if ($request['Center']) {
			$CenterID = $request['Center'];
		}
		$data['CenterID'] = $CenterID;

		$data['DateFrom'] = date("Y-m-d");
		if ($request['DateFrom']) {
			$data['DateFrom'] = $request['DateFrom'];
		}

		$data['DateTo'] = date("Y-m-d");
		if ($request['DateTo']) {
			$data['DateTo'] = $request['DateTo'];
		}

		return View::make('admin/sales-report')->with($data);
	}

	public function getCenterSalesReport(Request $request)
	{

		$Order = new Order();

		$param["CenterID"] = $request["Center"];
		$param["DateFrom"] = (empty($request["DateFrom"]) ? null : $request["DateFrom"]);
		$param["DateTo"] = (empty($request["DateTo"]) ? null : $request["DateTo"]);

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		if ($param["CenterID"] > 0) {
			$RetVal["CenterSalesList"] = $Order->getCenterSalesList($param);
		} else {
			$RetVal["AllCenterSalesList"] = $Order->getAllCenterSalesList($param);
		}

		return response()->json($RetVal);
	}

	public function getCommissionReport(Request $request)
	{

		$EWallet = new EWallet();

		$param["DateFrom"] = (empty($request["DateFrom"]) ? null : $request["DateFrom"]);
		$param["DateTo"] = (empty($request["DateTo"]) ? null : $request["DateTo"]);
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["CommissionList"] = $EWallet->getMemberEwalletList($param);

		return response()->json($RetVal);
	}

	public function showCommissionReport(Request $request)
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'commission-report';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data['DateFrom'] = date("Y-m-d");
		if ($request['DateFrom']) {
			$data['DateFrom'] = $request['DateFrom'];
		}

		$data['DateTo'] = date("Y-m-d");
		if ($request['DateTo']) {
			$data['DateTo'] = $request['DateTo'];
		}

		return View::make('admin/commission-report')->with($data);
	}

	public function getWithdrawalReport(Request $request)
	{

		$EWalletWithdrawal = new EWalletWithdrawal();

		$param["DateFrom"] = (empty($request["DateFrom"]) ? null : $request["DateFrom"]);
		$param["DateTo"] = (empty($request["DateTo"]) ? null : $request["DateTo"]);
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["WithdrawalList"] = $EWalletWithdrawal->getMemberWithdrawalList($param);

		return response()->json($RetVal);
	}

	public function showWithdrawalReport(Request $request)
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'withdrawal-report';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data['DateFrom'] = date("Y-m-d");
		if ($request['DateFrom']) {
			$data['DateFrom'] = $request['DateFrom'];
		}

		$data['DateTo'] = date("Y-m-d");
		if ($request['DateTo']) {
			$data['DateTo'] = $request['DateTo'];
		}

		return View::make('admin/withdrawal-report')->with($data);
	}

	public function showTopSponsorshipReport(Request $request)
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'top-sponsorship-report';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data['DateFrom'] = date("Y-m-d");
		if ($request['DateFrom']) {
			$data['DateFrom'] = $request['DateFrom'];
		}

		$data['DateTo'] = date("Y-m-d");
		if ($request['DateTo']) {
			$data['DateTo'] = $request['DateTo'];
		}

		return View::make('admin/top-sponsorship-report')->with($data);
	}

	public function getSponsorshipReport(Request $request)
	{

		$MemberEntry = new MemberEntry();

		$param["DateFrom"] = (empty($request["DateFrom"]) ? null : $request["DateFrom"]);
		$param["DateTo"] = (empty($request["DateTo"]) ? null : $request["DateTo"]);
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["SponsorshipList"] = $MemberEntry->getSponsorshipReport($param);

		return response()->json($RetVal);
	}

	public function showTopDirectSellingReport(Request $request)
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'top-direct-selling-report';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data['DateFrom'] = date("Y-m-d");
		if ($request['DateFrom']) {
			$data['DateFrom'] = $request['DateFrom'];
		}

		$data['DateTo'] = date("Y-m-d");
		if ($request['DateTo']) {
			$data['DateTo'] = $request['DateTo'];
		}

		return View::make('admin/top-direct-selling-report')->with($data);
	}

	public function getDirectSellingReport(Request $request)
	{

		$Order = new Order();

		$param["DateFrom"] = (empty($request["DateFrom"]) ? null : $request["DateFrom"]);
		$param["DateTo"] = (empty($request["DateTo"]) ? null : $request["DateTo"]);
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["DirectSellingList"] = $Order->getDirectSellingReport($param);

		return response()->json($RetVal);
	}

	public function showCenterSalesReport(Request $request)
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'top-center-sales-report';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data['DateFrom'] = date("Y-m-d");
		if ($request['DateFrom']) {
			$data['DateFrom'] = $request['DateFrom'];
		}

		$data['DateTo'] = date("Y-m-d");
		if ($request['DateTo']) {
			$data['DateTo'] = $request['DateTo'];
		}

		return View::make('admin/top-center-sales-report')->with($data);
	}

	//USER ACCOUNT ------------------------------------------------
	public function showUserAccounts()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Center = new Center();
		$Misc = new Misc();

		$data['Page'] = 'user-accounts';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$param["Status"] = config('app.STATUS_ACTIVE');
		$param["SearchText"] = "";
		$param["Limit"] = 0;
		$param["PageNo"] = 0;
		$data["CenterList"] = $Center->getCenterList($param);

		return View::make('admin/user-accounts')->with($data);
	}

	public function getUserAccountsList(Request $request)
	{

		$UserAccounts = new UserAccounts();

		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["UserAccountList"] = $UserAccounts->getUserAccountList($param);

		return response()->json($RetVal);
	}

	public function getUserAccountInfo(Request $request)
	{

		$UserAccounts = new UserAccounts();

		$UserAccountID = $request["UserAccountID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["UserAccountInfo"] = $UserAccounts->getUserAccountInfo($UserAccountID);
		$RetVal["UserAccountModules"] = $UserAccounts->getUserAccountInfo($UserAccountID);
		$RetVal["UserAccountModuleList"] = $UserAccounts->getUserAccountModuleList($UserAccountID);

		return response()->json($RetVal);
	}

	public function doSaveUserAccount(Request $request)
	{

		$TODAY = date("Y-m-d H:i:s");
		$UserAccounts = new UserAccounts();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["UserAccountInfo"] = null;

		$data["UserAccountID"] = $request['UserAccountID'];

		$data["CenterID"] = $request['Center'];

		$data["Fullname"] = $request['Fullname'];
		$data["Username"] = $request['Username'];
		$data["UserPassword"] = $request['UserPassword'];

		$data["IsSuperAdmin"] = $request['IsSuperAdmin'];
		$data["Status"] = $request['Status'];

		$data["UserAccess1"] = $request['UserAccess1'];
		$data["UserAccess2"] = $request['UserAccess2'];
		$data["UserAccess3"] = $request['UserAccess3'];
		$data["UserAccess4"] = $request['UserAccess4'];
		$data["UserAccess5"] = $request['UserAccess5'];
		$data["UserAccess6"] = $request['UserAccess6'];
		$data["UserAccess7"] = $request['UserAccess7'];
		$data["UserAccess8"] = $request['UserAccess8'];
		$data["UserAccess9"] = $request['UserAccess9'];
		$data["UserAccess10"] = $request['UserAccess10'];

		$data["UserAccess12"] = $request['UserAccess12'];
		$data["UserAccess13"] = $request['UserAccess13'];
		$data["UserAccess14"] = $request['UserAccess14'];
		$data["UserAccess15"] = $request['UserAccess15'];
		$data["UserAccess16"] = $request['UserAccess16'];
		$data["UserAccess17"] = $request['UserAccess17'];
		$data["UserAccess18"] = $request['UserAccess18'];
		$data["UserAccess19"] = $request['UserAccess19'];

		$data["UserAccess21"] = $request['UserAccess21'];
		$data["UserAccess22"] = $request['UserAccess22'];
		$data["UserAccess23"] = $request['UserAccess23'];
		$data["UserAccess24"] = $request['UserAccess24'];
		$data["UserAccess25"] = $request['UserAccess25'];
		$data["UserAccess26"] = $request['UserAccess26'];
		$data["UserAccess27"] = $request['UserAccess27'];
		$data["UserAccess28"] = $request['UserAccess28'];
		$data["UserAccess29"] = $request['UserAccess29'];
		$data["UserAccess30"] = $request['UserAccess30'];
		$data["UserAccess31"] = $request['UserAccess31'];

		$data["UserAccess35"] = $request['UserAccess35'];
		$data["UserAccess36"] = $request['UserAccess36'];
		$data["UserAccess37"] = $request['UserAccess37'];

		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if (empty($data["Fullname"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter user full name..";
		} else if (empty($data["Username"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter username.";
		} else if ($data["UserAccountID"] == "0" && empty($data["UserPassword"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter password.";
		}

		if ($RetVal['Response'] != "Failed") {
			$data["UserAccountID"] = $UserAccounts->doSaveUserAccount($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "User account has been saved successfully.";
			$RetVal["UserAccountInfo"] = $UserAccounts->getUserAccountInfo($data["UserAccountID"]);
		}

		return response()->json($RetVal);
	}

	//COMPANY INFORMATION ------------------------------------------------
	public function showCompanyInfo()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$Misc = new Misc();

		$data['Page'] = 'company-info';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		$data["CompanyInfo"] = $Misc->GetCompanyInfo();

		return View::make('admin/company-info')->with($data);
	}

	public function doSaveCompanyInfo(Request $request)
	{

		$TODAY = date("Y-m-d H:i:s");
		$Misc = new Misc();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["CompanyName"] = $request['CompanyName'];

		$data["CompanyAddress"] = $request['CompanyAddress'];
		$data["TelNo"] = $request['TelNo'];
		$data["MobileNo"] = $request['MobileNo'];
		$data["EmailAddress"] = $request['EmailAddress'];

		$data["AboutCompany"] = $request['AboutCompany'];
		$data["Mission"] = $request['Mission'];
		$data["Vision"] = $request['Vision'];

		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if (empty($data["CompanyName"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter company name.";
		} else if (empty($data["AboutCompany"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please tell more about the company.";
		} else if (empty($data["CompanyAddress"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter complete address.";
		} else if (empty($data["TelNo"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter telephone number.";
		} else if (empty($data["MobileNo"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter active mobile number.";
		} else if (empty($data["EmailAddress"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter email address.";
		} else if (empty($data["Mission"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter company mission.";
		} else if (empty($data["Vision"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter company vision.";
		}

		if ($RetVal['Response'] != "Failed") {
			$data["UserAccountID"] = $Misc->doSaveCompanyInfo($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "Company information has been saved successfully.";
		}

		return response()->json($RetVal);
	}

	//News and Events ------------------------------------------------
	public function showNewsEvents()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'sales';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		return View::make('admin/news-events')->with($data);
	}

	public function getNewsEventsList(Request $request)
	{

		$NewsEvents = new NewsEvents();

		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["NewsEventsList"] = $NewsEvents->getNewsEventsList($param);

		return response()->json($RetVal);
	}

	public function getNewsEventsInfo(Request $request)
	{

		$NewsEvents = new NewsEvents();

		$RecordID = $request["RecordID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["NewsEventsInfo"] = $NewsEvents->getNewsEventsInfo($RecordID);

		return response()->json($RetVal);
	}

	public function doSaveNewsEvents(Request $request)
	{

		$TODAY = date("Y-m-d H:i:s");
		$NewsEvents = new NewsEvents();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["NewsEventsInfo"] = null;

		$data["RecordID"] = $request['RecordID'];

		$data["Title"] = $request['Title'];
		$data["Contents"] = $request['Contents'];
		$data["PostedBy"] = $request['PostedBy'];
		$data["PublishDate"] = $request['PublishDate'];

		$data["Status"] = $request['Status'];

		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if (empty($data["Title"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter title.";
		} else if (empty($data["Contents"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter contents.";
		}

		if ($RetVal['Response'] != "Failed") {
			$data["RecordID"] = $NewsEvents->doSaveUpdateNewsEvents($data);

			//Upload Photo
			$Response = $NewsEvents->doUploadPhoto($data);

			return Redirect::back()->with('Success_Msg', 'News/Events has been saved successfully.');
		} else {
			return Redirect::back()->with('Error_Msg', 'Something went wrong while saving news/event article.');
		}

		return response()->json($RetVal);
	}

	//FAQ ------------------------------------------------
	public function showFAQ()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'sales';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		return View::make('admin/faq')->with($data);
	}

	public function getFAQList(Request $request)
	{

		$FAQ = new FAQ();

		$param["Status"] = $request["Status"];
		$param["SearchText"] = $request["SearchText"];
		$param["Limit"] = config('app.ListRowLimit');
		$param["PageNo"] = $request["PageNo"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["FAQList"] = $FAQ->getFAQList($param);

		return response()->json($RetVal);
	}

	public function getFAQInfo(Request $request)
	{

		$FAQ = new FAQ();

		$FAQID = $request["FAQID"];

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["FAQInfo"] = $FAQ->getFAQInfo($FAQID);

		return response()->json($RetVal);
	}

	public function doSaveFAQ(Request $request)
	{

		$TODAY = date("Y-m-d H:i:s");
		$FAQ = new FAQ();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";
		$RetVal["FAQInfo"] = null;

		$data["FAQID"] = $request['FAQID'];

		$data["SortOrder"] = $request['SortOrder'];
		$data["FAQ"] = $request['FAQ'];
		$data["FAQAnswer"] = $request['FAQAnswer'];

		$data["Status"] = $request['Status'];

		$data["CreatedByID"] = Session("ADMIN_ACCOUNT_ID");
		$data["UpdatedByID"] = Session("ADMIN_ACCOUNT_ID");

		if ($data["SortOrder"] == 0) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please set sort order of this FAQ.";
		} else if (empty($data["FAQ"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter FAQ content.";
		} else if (empty($data["FAQAnswer"])) {
			$RetVal['Response'] = "Failed";
			$RetVal['ResponseMessage'] = "Please enter FAQ answer.";
		}

		if ($RetVal['Response'] != "Failed") {
			$data["FAQID"] = $FAQ->doSaveUpdateFAQ($data);

			$RetVal['Response'] = "Success";
			$RetVal['ResponseMessage'] = "FAQ information has been saved successfully.";
			$RetVal["FAQInfo"] = $FAQ->getFAQInfo($data["FAQID"]);
		}

		return response()->json($RetVal);
	}

	//Change Password ------------------------------------------------
	public function showChangePassword()
	{

		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$data['Page'] = 'change-password';
		$data['Token'] = csrf_token();
		$data = $this->SetAdminInitialData($data);

		return View::make('admin/change-password')->with($data);
	}

	public function doChangePassword(Request $request)
	{

		//Check Active Session
		if (!$this->IsAdminLoggedIn()) {
			return Redirect::route('admin-logout');
		}

		$UserAccounts = new UserAccounts();
		$ErrorMessage = null;

		$data['UserAccountID'] = Session('ADMIN_ACCOUNT_ID');
		$data['CurrentPassword'] = $request['CurrentPassword'];
		$data['NewPassword'] = $request['NewPassword'];
		$data['ConfirmNewPassword'] = $request['ConfirmNewPassword'];
		$data['UpdatedByID'] = Session('ADMIN_ACCOUNT_ID');

		if (empty($data['CurrentPassword'])) {
			$ErrorMessage = 'Please enter current password.';
		} elseif (empty($data['NewPassword'])) {
			$ErrorMessage = 'Please enter new password.';
		} elseif (trim($data['NewPassword']) != trim($data['ConfirmNewPassword'])) {
			$ErrorMessage = 'Please confirm your new password.';
		}

		if (!empty($ErrorMessage)) {
			Session::flash('ERROR_MSG', $ErrorMessage);
			return redirect()->back();
		} else {

			$Response = $UserAccounts->doChangePassword($data);

			if ($Response != "Success") {
				Session::flash('ERROR_MSG', $Response);
				return redirect()->back();
			} else {
				Session::flash('SUCCESS_MSG', 'Your password has been changed successfully.');
				return redirect()->back();
			}
		}
	}

	function doScheduledJob(Request $request)
	{
		$ServiceKey = $request['ServiceKey'];
		$CronJobs = new CronJobs();
		$CronJobs->doScheduledJobs();
	}
}
