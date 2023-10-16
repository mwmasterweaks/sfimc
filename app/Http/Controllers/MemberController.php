<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Mail;
use Session;
use Hash;
use View;
use Image;
use Excel;
use PDF;

use App\Models\Admin;
use App\Models\Misc;
use App\Models\MemberEntry;
use App\Models\Member;
use App\Models\MemberTree;
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
use App\Models\memberentrycutoff;
use Carbon\Doctrine\CarbonType;
use App\Models\wirecode;
use App\Models\wirecode_active;
use App\Models\member_activate_wire;

class MemberController extends Controller
{
	private $TotalAcquiredRebatableValue = 0;
	private $CutOffIDsCantMaintain = [];
	private $WirecodeController = "App\Http\Controllers\WirecodeController";
	public function showMemberLogin()
	{

		$data['Page'] = 'member-login';
		$data['Token'] = csrf_token();

		return View::make('member/member-login')->with($data);
	}
	public function showMemberLogin2()
	{

		$data['Page'] = 'auto-login';
		$data['Token'] = csrf_token();

		return View::make('member/auto-login')->with($data);
	}
	public function doMemberLogin(Request $request)
	{

		$MemberEntry = new MemberEntry();
		$Error_Msg = "";

		$data['EntryCode'] = $request['EntryCode'];
		$data['UserPassword'] = $request['UserPassword'];
		$data['Status'] = config('app.STATUS_ACTIVE');

		if (empty($data['EntryCode'])) {
			$Error_Msg = 'Please enter your IBO Number.';
		} elseif (empty($data['UserPassword'])) {
			$Error_Msg = 'Please enter your password.';
		}

		if (!empty($Error_Msg)) {
			return Redirect::back()->with('Error_Msg', $Error_Msg);
		} else {

			$Result = $MemberEntry->doCheckMemberLoginAccount($data);

			if ($Result == "Failed") {
				return Redirect::back()->with('Error_Msg', 'Invalid IBO No. and password.');
			} else if ($Result == config('app.STATUS_INACTIVE')) {
				return Redirect::back()->with('Error_Msg', 'Your account has been deactivated. Please contact our main office for support.');
			} else if ($Result == config('app.STATUS_BLOCKED')) {
				return Redirect::back()->with('Error_Msg', 'Your account has been blocked. Please contact our main office for support.');
			} else {
				return Redirect::route('member-dashboard');
			}
		}
	}
	public function doMemberLogout()
	{

		if (Session('MEMBER_LOGGED_IN')) {
			Session::flush();
		}

		return view('member/member-login');
	}
	function IsMemberLoggedIn()
	{
		if (!Session('MEMBER_LOGGED_IN')) {
			return false;
		}
		return true;
	}
	function SetMemberInitialData($data)
	{

		$Misc = new Misc();
		$data['MiscModel'] = $Misc;

		return $data;
	}
	public function showDashboard()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}

		$MemberEntry = new MemberEntry();

		$data['Page'] = 'dashboard';
		$data['Token'] = csrf_token();
		//$data['testdata'] = "testdataaa";
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();
		//dd(app($this->WirecodeController)->get_active_wire());
		$data = $this->SetMemberInitialData($data);

		$data['DashboardFigures'] = $MemberEntry->getDashboardFigures();

		return View::make('member/dashboard')->with($data);
	}
	public function showMemberGenealogy(Request $request)
	{
		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}
		$Misc = new Misc();
		$MemberEntry = new MemberEntry();
		$MemberEntryID = $request['MemberEntryID'];
		$MaxLevel = config('app.GenealogyLevelLimit');
		if ($request["MaxLevel"]) {
			$MaxLevel = $request["MaxLevel"];
		}
		$data['Page'] = 'member-genealogy';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();
		$data['TOP'] = $MemberEntry->getMemberEntryInfo($MemberEntryID);
		$data['TREE'] = $MemberEntry->getMemberGenealogy($MemberEntryID, $MaxLevel);
		$data['MaxLevel'] = $MaxLevel;
		return View::make('member/member-genealogy')->with($data);
	}
	public function showMemberProfile()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}

		$MemberEntry = new MemberEntry();
		$Misc = new Misc();

		$data['Page'] = 'member-profile';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();

		$EntryID = Session("MEMBER_ENTRY_ID");
		$data['MemberInfo'] = $MemberEntry->getMemberEntryInfo($EntryID);
		$data['CountryCityList'] = $Misc->getCountryCityList(174);
		$data['CountryList'] = $Misc->getCountryList();

		return View::make('member/member-profile')->with($data);
	}
	public function showMemberEWallet()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}

		$data['Page'] = 'member-ewallet-ledger';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();

		return View::make('member/member-ewallet')->with($data);
	}
	public function showEWalletWithdrawal()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}

		$data['Page'] = 'ewallet-withdrawal';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();

		return View::make('member/member-ewallet-withdrawal')->with($data);
	}
	public function showMemberUpgradeEntry()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}

		$data['Page'] = 'member-upgrade-entry';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();

		$MemberEntry = new MemberEntry();
		$data["MemberEntryInfo"] = $MemberEntry->getMemberEntryInfo(Session('MEMBER_ENTRY_ID'));

		return View::make('member/member-upgrade-entry')->with($data);
	}
	public function showMemberVouchers()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}

		$data['Page'] = 'member-upgrade-entry';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();

		$MemberEntry = new MemberEntry();
		$data["MemberEntryInfo"] = $MemberEntry->getMemberEntryInfo(Session('MEMBER_ENTRY_ID'));

		return View::make('member/member-vouchers')->with($data);
	}
	public function showWirecode()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}

		$data['Page'] = 'member-vouchers';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();

		return View::make('member/member-wirecode')->with($data);
	}
	public function showMemberOrderHistory()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}

		$data['Page'] = 'member-order-history';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();

		return View::make('member/member-order-history')->with($data);
	}

	public function showMemberTree()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}
		$MemberEntryID = Session('MEMBER_ENTRY_ID');
		// $Admin = new Admin();
		$data['upline'] = DB::table('member_tree')->where('descendant_id', $MemberEntryID)
			->join('member', 'member.MemberID', '=', 'member_tree.ancestor_id')
			->join('memberentry', 'memberentry.MemberID', '=', 'member.MemberID')
			->select('member.*', 'member_tree.*', 'memberentry.EntryCode', 'memberentry.MemberID')
			->orderBy('member_tree.depth', 'ASC')->get()->toArray();

		$data['downline'] = DB::table('member_tree')->where('ancestor_id', $MemberEntryID)
			->join('member', 'member.MemberID', '=', 'member_tree.descendant_id')
			->join('memberentry', 'memberentry.MemberID', '=', 'member.MemberID')
			->orderBy('depth', 'ASC')->get()->groupBy('depth');

		$data['Page'] = 'member-tree';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);

		return View::make('member/member-tree')->with($data);
	}

	//Change Password ------------------------------------------------
	public function showChangePassword()
	{

		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}

		$data['Page'] = 'change-password';
		$data['Token'] = csrf_token();
		$data = $this->SetMemberInitialData($data);
		$data['active_wire'] = app($this->WirecodeController)->get_active_wire();

		return View::make('member/change-password')->with($data);
	}
	public function doChangePassword(Request $request)
	{
		if (!$this->IsMemberLoggedIn()) {
			return Redirect::route('member-logout');
		}
		$MemberEntry = new MemberEntry();
		$ErrorMessage = null;
		$data['MemberEntryID'] = Session('MEMBER_ENTRY_ID');
		$data['CurrentPassword'] = $request['CurrentPassword'];
		$data['NewPassword'] = $request['NewPassword'];
		$data['ConfirmNewPassword'] = $request['ConfirmNewPassword'];
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

			$Response = $MemberEntry->doChangePassword($data);

			if ($Response != "Success") {
				Session::flash('ERROR_MSG', $Response);
				return redirect()->back();
			} else {
				Session::flash('SUCCESS_MSG', 'Your password has been changed successfully.');
				return redirect()->back();
			}
		}
	}
	public function doUploadMemberPhoto(Request $request)
	{

		$MemberEntry = new MemberEntry();

		$RetVal['Response'] = "Success";
		$RetVal['ResponseMessage'] = "";

		$data["MemberEntryID"] = $request['MemberEntryID'];

		if ($RetVal['Response'] != "Failed") {
			$Response = $MemberEntry->doUploadProductPhoto($data);
			return Redirect::back()->with('Success_Msg', 'Member photo has been uploaded successfully.');
		} else {
			return Redirect::back()->with('Error_Msg', 'Something went wrong while uploading member photo.');
		}
	}
	public function generateRabates($date)
	{

		//$initialMember = Member::where('MemberID', 2947)->first();
		//return $this->buildTree(1168, "2023-04-30");
		$genDate = new Carbon($date);
		$genDate = $genDate->subMonth();
		$genDate = $genDate->endOfMonth();
		// update memberentrycutoff MaintainingBalance base on package
		DB::table('memberentrycutoff as t1')
			->join('memberentry as t2', 't1.MemberEntryID', '=', 't2.EntryID')
			->join('packagerebates as t3', 't2.PackageID', '=', 't3.PackageID')
			->where('t1.EndDate', $genDate->toDateString())
			->update([
				't1.AcquiredByEntryID' => null,
				't1.IsRebatesGenerated' => 0,
				't1.TotalAcquiredRebatableValue' => 0,
				't1.MaintainingBalance' => DB::raw('t3.RebatesMaintainingBal')
			]);

		//Dynamic compression
		$cutoff = DB::table('memberentrycutoff')
			->where('EndDate', $genDate->toDateString())
			->orderBy('MemberEntryID', 'desc')
			->get();
		foreach ($cutoff as $item) {

			if ($item->TotalRebatableValue >= $item->MaintainingBalance) {
				\Log::info('CutOffID: ' . $item->CutOffID);
				\Log::info('MemberEntryID: ' . $item->MemberEntryID);
				\Log::info('TotalAcquiredRebatableValue: ' . $item->TotalAcquiredRebatableValue);

				$updateQuery = DB::table('memberentrycutoff')
					->where('CutOffID', $item->CutOffID)
					->update([
						'AcquiredByEntryID' => $item->MemberEntryID,
						'TotalAcquiredRebatableValue' => DB::raw('TotalAcquiredRebatableValue + ' . $item->TotalRebatableValue)
					]);

				\Log::info('Update Query: ' . $updateQuery);
			} else {
				$firstAncestorWithMaintainingBalance = memberentrycutoff::join('member_tree', 'memberentrycutoff.MemberEntryID', '=', 'member_tree.ancestor_id')
					->where('EndDate', $genDate->toDateString())
					->where('descendant_id', $item->MemberEntryID)
					->where('memberentrycutoff.TotalRebatableValue', '>=', 1500)
					->orderBy('member_tree.depth')
					->first();

				if ($firstAncestorWithMaintainingBalance) {
					DB::table('memberentrycutoff')
						->where('CutOffID', $firstAncestorWithMaintainingBalance->CutOffID)
						->update([
							'TotalAcquiredRebatableValue' => DB::raw('TotalAcquiredRebatableValue + ' . $item->TotalRebatableValue)
						]);
					$resultMemberID = $firstAncestorWithMaintainingBalance->MemberEntryID;
				} else {
					$resultMemberID = 1;
				}
				DB::table('memberentrycutoff')
					->where('CutOffID', $item->CutOffID)
					->update([
						'AcquiredByEntryID' => $resultMemberID,
					]);
			}
		}
		//end dynamic Compression


		//Calculating
		$cutoff1 = DB::table('memberentrycutoff as t1')
			->join('memberentry as t2', 't1.MemberEntryID', '=', 't2.EntryID')
			->join('packagerebates as t3', 't2.PackageID', '=', 't3.PackageID')
			->join('packagerank as t4', 't2.PackageID', '=', 't4.PackageID')
			->where('t1.EndDate', $genDate->toDateString())
			->where('t1.TotalAcquiredRebatableValue', ">", 0)
			->get();

		foreach ($cutoff1 as $item) {
			$maxLevel = 0;
			//get maxlevel
			$memberpurch = DB::table('memberentryorder')
				->where('HeadEntryID', $item->MemberEntryID)
				->orderBy('AccumulatedOrderID', 'desc')
				->first();
			if ($item->RankLevel1APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel1AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 1;
			if ($item->RankLevel2APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel2AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 2;
			if ($item->RankLevel3APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel3AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 3;
			if ($item->RankLevel4APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel4AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 4;
			if ($item->RankLevel5APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel5AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 5;
			if ($item->RankLevel6APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel6AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 6;
			if ($item->RankLevel7APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel7AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 7;
			if ($item->RankLevel8APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel8AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 8;
			if ($item->RankLevel9APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel9AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 9;
			// end get maxlevel

			//get decendant with maintainingbalance
			$decendantWithMaintainingBalance = memberentrycutoff::join('member_tree', 'memberentrycutoff.MemberEntryID', '=', 'member_tree.descendant_id')
				->where('EndDate', $genDate->toDateString())
				->where(
					'ancestor_id',
					$item->MemberEntryID
				)
				->where('memberentrycutoff.TotalRebatableValue', '>=', 1500)
				->orderBy('member_tree.depth')
				->take(9)
				->get();

			$count = 0; //check per level
			$dateNow = new Carbon();
			foreach ($decendantWithMaintainingBalance as $decendant) {
				if ($count <= $maxLevel) {
					$rebatePercent = 1;
					$rebate_msg = "";
					if ($count == 0) {
						$rebatePercent = $item->PersonalRebatesPercent;
						$rebate_msg = "Personal Rebates";
					};
					if ($count == 1) {
						$rebatePercent = $item->RebateLevel1Percent;
						$rebate_msg = "Rebates Level 1";
					};
					if ($count == 2) {
						$rebatePercent = $item->RebateLevel2Percent;
						$rebate_msg = "Rebates Level 2";
					};
					if ($count == 3) {
						$rebatePercent = $item->RebateLevel3Percent;
						$rebate_msg = "Rebates Level 3";
					};
					if ($count == 4) {
						$rebatePercent = $item->RebateLevel4Percent;
						$rebate_msg = "Rebates Level 4";
					};
					if ($count == 5) {
						$rebatePercent = $item->RebateLevel5Percent;
						$rebate_msg = "Rebates Level 5";
					};
					if ($count == 6) {
						$rebatePercent = $item->RebateLevel6Percent;
						$rebate_msg = "Rebates Level 6";
					};
					if ($count == 7) {
						$rebatePercent = $item->RebateLevel7Percent;
						$rebate_msg = "Rebates Level 7";
					};
					if ($count == 8) {
						$rebatePercent = $item->RebateLevel8Percent;
						$rebate_msg = "Rebates Level 8";
					};
					if ($count == 9) {
						$rebatePercent = $item->RebateLevel9Percent;
						$rebate_msg = "Rebates Level 9";
					};

					$info = DB::table('ewalletledger')
						->where('MemberID', $item->MemberEntryID)
						->orderby('LedgerID', 'DESC')
						->first();

					$EWalletBalance = 0;
					if (isset($info)) {
						$EWalletBalance = $info->RunningBalance;
					}
					$INAmount = $decendant->TotalAcquiredRebatableValue * ($rebatePercent / 100);
					DB::table('ewalletledger')->insert([
						'ComplanID' => 4, // base on complan table
						'MemberID' => $item->MemberEntryID,
						'EarnedFromMemberID' => $item->MemberEntryID,
						'LevelNo' => $count,
						'DateTimeEarned' => $dateNow,
						'EarnedMonth' => $dateNow->month,
						'EarnedYear' => $dateNow->year,
						'INAmount' => $INAmount,
						'OUTAmount' => 0,
						'OldBalance' => $EWalletBalance,
						'RunningBalance' => ($EWalletBalance + $INAmount),
						'Remarks' => $rebate_msg . ' for the month of ' . $genDate->month . '-' . $genDate->year,
						'Status' => 'Approved',
						'TransactionRefID' => $decendant->MemberEntryID,
						'DateTimeCreated' => $dateNow,
						'DateTimeUpdated' => $dateNow
					]);

					DB::table('memberentry')
						->where('MemberID', $item->MemberEntryID)
						->update(['AccumulatedRewards' => DB::raw('AccumulatedRewards + ' . $INAmount)]);
				}
				$count++;
			}
		}
		return 'ok';
	}
	public function RabatesProjection($memberID)
	{

		//$initialMember = Member::where('MemberID', 2947)->first();
		//return $this->buildTree(1168, "2023-04-30");
		$genDate = new Carbon();
		$genDate = $genDate->subMonth();
		$genDate = $genDate->endOfMonth();

		//Dynamic compression
		$cutoff = DB::table('memberentrycutoff')
			->where('EndDate', $genDate->toDateString())
			->orderBy('MemberEntryID', 'desc')
			->get();
		foreach ($cutoff as $item) {

			if ($item->TotalRebatableValue >= $item->MaintainingBalance) {
				\Log::info('CutOffID: ' . $item->CutOffID);
				\Log::info('MemberEntryID: ' . $item->MemberEntryID);
				\Log::info('TotalAcquiredRebatableValue: ' . $item->TotalAcquiredRebatableValue);

				$updateQuery = DB::table('memberentrycutoff')
					->where('CutOffID', $item->CutOffID)
					->update([
						'AcquiredByEntryID' => $item->MemberEntryID,
						'TotalAcquiredRebatableValue' => DB::raw('TotalAcquiredRebatableValue + ' . $item->TotalRebatableValue)
					]);

				\Log::info('Update Query: ' . $updateQuery);
			} else {
				$firstAncestorWithMaintainingBalance = memberentrycutoff::join('member_tree', 'memberentrycutoff.MemberEntryID', '=', 'member_tree.ancestor_id')
					->where('EndDate', $genDate->toDateString())
					->where('descendant_id', $item->MemberEntryID)
					->where('memberentrycutoff.TotalRebatableValue', '>=', 1500)
					->orderBy('member_tree.depth')
					->first();

				if ($firstAncestorWithMaintainingBalance) {
					DB::table('memberentrycutoff')
						->where('CutOffID', $firstAncestorWithMaintainingBalance->CutOffID)
						->update([
							'TotalAcquiredRebatableValue' => DB::raw('TotalAcquiredRebatableValue + ' . $item->TotalRebatableValue)
						]);
					$resultMemberID = $firstAncestorWithMaintainingBalance->MemberEntryID;
				} else {
					$resultMemberID = 1;
				}
				DB::table('memberentrycutoff')
					->where('CutOffID', $item->CutOffID)
					->update([
						'AcquiredByEntryID' => $resultMemberID,
					]);
			}
		}
		//end dynamic Compression


		//Calculating
		$cutoff1 = DB::table('memberentrycutoff as t1')
			->join('memberentry as t2', 't1.MemberEntryID', '=', 't2.EntryID')
			->join('packagerebates as t3', 't2.PackageID', '=', 't3.PackageID')
			->join('packagerank as t4', 't2.PackageID', '=', 't4.PackageID')
			->where('t1.EndDate', $genDate->toDateString())
			->where('t1.TotalAcquiredRebatableValue', ">", 0)
			->get();

		foreach ($cutoff1 as $item) {
			$maxLevel = 0;
			//get maxlevel
			$memberpurch = DB::table('memberentryorder')
				->where('HeadEntryID', $item->MemberEntryID)
				->orderBy('AccumulatedOrderID', 'desc')
				->first();
			if ($item->RankLevel1APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel1AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 1;
			if ($item->RankLevel2APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel2AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 2;
			if ($item->RankLevel3APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel3AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 3;
			if ($item->RankLevel4APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel4AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 4;
			if ($item->RankLevel5APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel5AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 5;
			if ($item->RankLevel6APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel6AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 6;
			if ($item->RankLevel7APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel7AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 7;
			if ($item->RankLevel8APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel8AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 8;
			if ($item->RankLevel9APPRV <= $memberpurch->PersonalRunningBalance && $item->RankLevel9AGPRV  <= $memberpurch->GroupRunningBalance)
				$maxLevel = 9;
			// end get maxlevel

			//get decendant with maintainingbalance
			$decendantWithMaintainingBalance = memberentrycutoff::join('member_tree', 'memberentrycutoff.MemberEntryID', '=', 'member_tree.descendant_id')
				->where('EndDate', $genDate->toDateString())
				->where(
					'ancestor_id',
					$item->MemberEntryID
				)
				->where('memberentrycutoff.TotalRebatableValue', '>=', 1500)
				->orderBy('member_tree.depth')
				->take(9)
				->get();

			$count = 0; //check per level
			$dateNow = new Carbon();
			foreach ($decendantWithMaintainingBalance as $decendant) {
				if ($count <= $maxLevel) {
					$rebatePercent = 1;
					$rebate_msg = "";
					if ($count == 0) {
						$rebatePercent = $item->PersonalRebatesPercent;
						$rebate_msg = "Personal Rebates";
					};
					if ($count == 1) {
						$rebatePercent = $item->RebateLevel1Percent;
						$rebate_msg = "Rebates Level 1";
					};
					if ($count == 2) {
						$rebatePercent = $item->RebateLevel2Percent;
						$rebate_msg = "Rebates Level 2";
					};
					if ($count == 3) {
						$rebatePercent = $item->RebateLevel3Percent;
						$rebate_msg = "Rebates Level 3";
					};
					if ($count == 4) {
						$rebatePercent = $item->RebateLevel4Percent;
						$rebate_msg = "Rebates Level 4";
					};
					if ($count == 5) {
						$rebatePercent = $item->RebateLevel5Percent;
						$rebate_msg = "Rebates Level 5";
					};
					if ($count == 6) {
						$rebatePercent = $item->RebateLevel6Percent;
						$rebate_msg = "Rebates Level 6";
					};
					if ($count == 7) {
						$rebatePercent = $item->RebateLevel7Percent;
						$rebate_msg = "Rebates Level 7";
					};
					if ($count == 8) {
						$rebatePercent = $item->RebateLevel8Percent;
						$rebate_msg = "Rebates Level 8";
					};
					if ($count == 9) {
						$rebatePercent = $item->RebateLevel9Percent;
						$rebate_msg = "Rebates Level 9";
					};

					$info = DB::table('ewalletledger')
						->where('MemberID', $item->MemberEntryID)
						->orderby('LedgerID', 'DESC')
						->first();

					$EWalletBalance = 0;
					if (isset($info)) {
						$EWalletBalance = $info->RunningBalance;
					}
					$INAmount = $decendant->TotalAcquiredRebatableValue * ($rebatePercent / 100);
					DB::table('ewalletledger')->insert([
						'ComplanID' => 4, // base on complan table
						'MemberID' => $item->MemberEntryID,
						'EarnedFromMemberID' => $item->MemberEntryID,
						'LevelNo' => $count,
						'DateTimeEarned' => $dateNow,
						'EarnedMonth' => $dateNow->month,
						'EarnedYear' => $dateNow->year,
						'INAmount' => $INAmount,
						'OUTAmount' => 0,
						'OldBalance' => $EWalletBalance,
						'RunningBalance' => ($EWalletBalance + $INAmount),
						'Remarks' => $rebate_msg . ' for the month of ' . $genDate->month . '-' . $genDate->year,
						'Status' => 'Approved',
						'TransactionRefID' => $decendant->MemberEntryID,
						'DateTimeCreated' => $dateNow,
						'DateTimeUpdated' => $dateNow
					]);

					DB::table('memberentry')
						->where('MemberID', $item->MemberEntryID)
						->update(['AccumulatedRewards' => DB::raw('AccumulatedRewards + ' . $INAmount)]);
				}
				$count++;
			}
		}
		return 'ok';
	}

	public function generateWire()
	{

		//$initialMember = Member::where('MemberID', 2947)->first();
		//return $this->buildTree(1168, "2023-04-30");
		$genDate = new Carbon();
		$genDate = $genDate->subDays(2);
		$dateNow = new Carbon();

		$wirecode_active = wirecode_active::with('wirecode')
			->where("start_date", "<=", $genDate->toDateString())
			->where("end_date", ">=", $genDate->toDateString())
			->first();
		$wirecode = $wirecode_active->wirecode;

		$member_activate_wire = member_activate_wire::with(['member'])
			->where("wirecode_active_id", $wirecode_active->id)->get();

		foreach ($member_activate_wire as $item) {
			$maxLevel = $wirecode->max_level;
			$ancestor_member_active_wire = member_activate_wire::join('member_tree', 'member_activate_wire.memberID', '=', 'member_tree.ancestor_id')
				->where("wirecode_active_id", $wirecode_active->id)
				->where(
					'descendant_id',
					$item->memberID
				)
				->orderBy('member_tree.depth')
				->take($maxLevel + 1) //na include iyaha sarili
				->get();
			$count = 1;
			//INSERT INTO `complan` (`ComplanID`, `Complan`, `Description`, `Status`, `DateTimeCreated`, `DateTimeUpdated`) 
			//VALUES (NULL, 'Wirecode', 'Wirecode', 'Active', '2023-09-26 00:00:00', '2023-09-26 00:00:00');
			$skipFirst = true;
			foreach ($ancestor_member_active_wire as $ancestor) {
				if ($skipFirst) {
					$skipFirst = false;
					continue; // Skip the first element
				}

				$info = DB::table('ewalletledger')
					->where('MemberID', $ancestor->memberID)
					->orderby('LedgerID', 'DESC')
					->first();

				$EWalletBalance = 0;
				if (isset($info)) {
					$EWalletBalance = $info->RunningBalance;
				}
				DB::table('ewalletledger')->insert([
					'ComplanID' => 7, // base on complan table
					'MemberID' => $ancestor->memberID,
					'EarnedFromMemberID' => $item->memberID,
					'LevelNo' => $count,
					'DateTimeEarned' => $dateNow,
					'EarnedMonth' => $dateNow->month,
					'EarnedYear' => $dateNow->year,
					'INAmount' => $wirecode->amount_acquired,
					'OUTAmount' => 0,
					'OldBalance' => $EWalletBalance,
					'RunningBalance' => ($EWalletBalance + $wirecode->amount_acquired),
					'Remarks' => 'Wirecode income Level ' . $count . ' for codewire',
					'Status' => 'Approved',
					'TransactionRefID' => $item->id,
					'DateTimeCreated' => $dateNow,
					'DateTimeUpdated' => $dateNow
				]);

				DB::table('memberentry')
					->where('MemberID', $item->MemberEntryID)
					->update(['AccumulatedRewards' => DB::raw('AccumulatedRewards + ' . $wirecode->amount_acquired)]);

				$count++;
			}
		}
		return 'ok';
	}

	function buildTree($member_id, $date)
	{
		//$member->load('member_entry.sponsor');
		$member = Member::with(['member_entry.sponsor', 'memberentrycutoff'])
			->where('MemberID', $member_id)
			->first();

		$cutoff = DB::table('memberentrycutoff')
			->where('EndDate', $date)
			->where('MemberEntryID', $member_id)
			->first();
		$tree = [
			'id' => $member->MemberID,
			'name' => $member->FirstName, // Change this to the member's attribute you want to display
			'children' => [],
		];
		if ($cutoff != null) {
			if ($cutoff->TotalRebatableValue >= $cutoff->MaintainingBalance)
				return $cutoff->CutOffID;
			if ($member->member_entry->SponsorEntryID == 1)
				return $tree;
			if ($member->member_entry) {
				$tree['children'][] = $this->buildTree($member->member_entry->sponsor->MemberID, $date);
			}
		} else
			$tree['children'][] = $this->buildTree($member->member_entry->sponsor->MemberID, $date);
		return $tree;
	}

	public function checkInTree($member_id, $date)
	{
		// $member->whereHas('memberentrycutoff', function ($query) use ($date) {
		// 	$query->where("EndDate", $date);
		// });
		// $member->load();

		$member = Member::with(['member_entry.sponsor'])
			->where('MemberID', $member_id)
			->first();


		// $tree = [
		// 	'id' => $member->MemberID,
		// 	'name' => $member->FirstName, // Change this to the member's attribute you want to display
		// 	'children' => [],
		// ];
		$cutoff = DB::table('memberentrycutoff')
			->where('EndDate', $date)
			->where('MemberEntryID', $member_id)
			->first();

		if ($member->MemberID == 1) {
			DB::table('memberentrycutoff')
				->whereIn('CutOffID', $this->CutOffIDsCantMaintain)
				->update(['AcquiredByEntryID' => $member->MemberID]);
			$this->CutOffIDsCantMaintain = [];
			$this->TotalAcquiredRebatableValue = 0;
			return "ok";
		}
		if ($cutoff != null) {
			array_push($this->CutOffIDsCantMaintain, $cutoff->CutOffID);
			$this->TotalAcquiredRebatableValue += $cutoff->TotalRebatableValue;
			if ($cutoff->TotalRebatableValue >= $cutoff->MaintainingBalance) {

				DB::table('memberentrycutoff')
					->whereIn('CutOffID', $this->CutOffIDsCantMaintain)
					->update(['AcquiredByEntryID' => $member->MemberID]);
				$this->CutOffIDsCantMaintain = [];

				DB::table('memberentrycutoff')
					->where('CutOffID', $cutoff->CutOffID)
					->update(['TotalAcquiredRebatableValue' => $this->TotalAcquiredRebatableValue]);
				$this->TotalAcquiredRebatableValue = 0;
				return 'ok';
			} else {
				$this->checkInTree($member->member_entry->sponsor, $date);
			}
		} else
			$this->checkInTree($member->member_entry->sponsor->MemberID, $date);

		return 'ok';
	}

	public function genMemberTree()
	{
		set_time_limit(0);
		// $this->populateMemberTreeTable(1152, 1143, 1);
		// return "ok kaau";
		$excludedIdsQuery = MemberTree::select('ancestor_id')
			->groupBy('ancestor_id')
			->get();

		$excludedIds = $excludedIdsQuery->pluck('ancestor_id')->toArray();
		//$existingMembers = MemberEntry::orderBy('SponsorEntryID', 'asc')->get();

		$existingMembers = MemberEntry::whereNotIn('MemberID', $excludedIds)
			->orderBy('SponsorEntryID', 'asc')
			->get();
		// Populate the member_tree table for each ancestor with their descendants
		foreach ($existingMembers as $ancestor) {
			// Check if the current member is an ancestor (sponsor)
			if (!$ancestor->sponsor_id) {
				// Process descendants of the current ancestor
				$this->populateMemberTreeTable($ancestor->MemberID, $ancestor->MemberID, 0);
			}
		}
		return "ok kaau";
	}
	function populateMemberTreeTable($ancestorID, $descendantID, $depth)
	{
		// Insert the relationship into the member_tree table
		// if ($depth == 5)
		// 	return "ok";
		// if ($descendantID == $ancestorID)
		// 	return 0;

		MemberTree::create([
			'ancestor_id' => $ancestorID,
			'descendant_id' => $descendantID,
			'depth' => $depth,
		]);

		// Find all descendants of the current descendantID
		$descendants = MemberEntry::where('SponsorEntryID', $descendantID)->get();

		// Recursively process each descendant
		foreach ($descendants as $descendant) {
			$this->populateMemberTreeTable($ancestorID, $descendant->MemberID, $depth + 1);
		}
	}
}
