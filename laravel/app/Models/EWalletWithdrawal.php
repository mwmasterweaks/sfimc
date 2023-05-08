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

class EWalletWithdrawal extends Model
{

    public function getEWalletWithdrawalList($param){

      $TODAY = date("Y-m-d H:i:s");

      $MemberID = $param['MemberID'];
      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('ewalletwithdrawal as wdraw')
        ->join('memberentry as mbrentry', 'mbrentry.MemberID', '=', 'wdraw.WithdrawByMemberID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->leftjoin('useraccount as apvby', 'apvby.UserAccountID', '=', 'wdraw.ApprovedByID')
        ->selectraw("
            COALESCE(wdraw.WithdrawalID,0) as WithdrawalID,
            COALESCE(wdraw.WithdrawalNo,'') as WithdrawalNo,
            COALESCE(wdraw.WithdrawalDateTime,'') as WithdrawalDateTime,

            COALESCE(wdraw.WithdrawByMemberID,0) as WithdrawByMemberID,
            COALESCE(mbrentry.EntryCode,'') as WithdrawByMemberEntryCode,
            CONCAT(COALESCE(mbr.FirstName,''),' ',if(COALESCE(mbr.MiddleName,'') != '', CONCAT(LEFT(COALESCE(mbr.MiddleName,''),1),'. '),''),COALESCE(mbr.LastName,'')) as WithdrawBy,

            COALESCE(mbr.TelNo,'') as WithdrawByMemberTelNo,
            COALESCE(mbr.MobileNo,'') as WithdrawByMemberMobileNo,
            COALESCE(mbr.EmailAddress,'') as WithdrawByMemberEmailAddress,

            COALESCE(wdraw.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(wdraw.ApprovedDateTime,'') as ApprovedDateTime,
            COALESCE(wdraw.ApproveRemarks,'') as ApproveRemarks,

            COALESCE((SELECT COALESCE(RunningBalance,0) as RunningBalance
                        FROM ewalletledger
                        WHERE MemberID = wdraw.WithdrawByMemberID
                        ORDER BY DateTimeEarned DESC
                        LIMIT 1
                        )
                      ,0) as CurrentEWalletBalance,

            COALESCE(wdraw.EWalletBalance,0) as EWalletBalance,
            COALESCE(wdraw.RequestedAmount,0) as RequestedAmount,
            COALESCE(wdraw.ApprovedAmount,0) as ApprovedAmount,
            COALESCE(wdraw.ProcessingFee,0) as ProcessingFee,
            COALESCE(wdraw.NetAmountToReceive,0) as NetAmountToReceive,

            COALESCE(wdraw.WithdrawalOption,'') as WithdrawalOption,
            COALESCE(wdraw.SendToFirstName,'') as SendToFirstName,
            COALESCE(wdraw.SendToLastName,'') as SendToLastName,
            COALESCE(wdraw.SendToMiddleName,'') as SendToMiddleName,
            COALESCE(wdraw.SendToTelNo,'') as SendToTelNo,
            COALESCE(wdraw.SendToMobileNo,'') as SendToMobileNo,
            COALESCE(wdraw.SendToEmailAddress,'') as SendToEmailAddress,
            COALESCE(wdraw.SenderName,'') as SenderName,
            COALESCE(wdraw.SendingRefNo,'') as SendingRefNo,

            COALESCE(wdraw.Bank,'') as Bank,
            COALESCE(wdraw.BankAccountName,'') as BankAccountName,
            COALESCE(wdraw.BankAccountNo,'') as BankAccountNo,

            COALESCE(wdraw.CheckNo,'') as CheckNo,
            COALESCE(wdraw.CheckDate,'') as CheckDate,
            COALESCE(wdraw.CheckAmount,0) as CheckAmount,

            COALESCE(wdraw.Notes,'') as Notes,
            COALESCE(wdraw.Status,'') as Status,

            CASE
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 2
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(wdraw.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(wdraw.DateTimeUpdated,'') as DateTimeUpdated

        ");

      if($MemberID > 0){
        $query->where("wdraw.WithdrawByMemberID",$MemberID);
      }

      if($SearchText != ''){
        $query->whereraw(
            "CONCAT(
                COALESCE(wdraw.WithdrawalNo,''),' ',
                COALESCE(mbrentry.EntryCode,''),' ',
                COALESCE(mbr.FirstName,''),' ',
                COALESCE(mbr.MiddleName,''),' ',
                COALESCE(wdraw.SendingRefNo,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Status != ''){
        $query->where("wdraw.Status",$Status);
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("(
            CASE
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_FOR_APPROVAL')."'  THEN 2
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 3
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 4
                ELSE 0
            END) ASC");

     $query->orderByraw("COALESCE(wdraw.WithdrawalDateTime,'') DESC");

      $list = $query->get();

      return $list;

    }

    public function getEWalletWithdrawalInfo($WithdrawalID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('ewalletwithdrawal as wdraw')
        ->join('memberentry as mbrentry', 'mbrentry.MemberID', '=', 'wdraw.WithdrawByMemberID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->leftjoin('useraccount as apvby', 'apvby.UserAccountID', '=', 'wdraw.ApprovedByID')
        ->selectraw("
            COALESCE(wdraw.WithdrawalID,0) as WithdrawalID,
            COALESCE(wdraw.WithdrawalNo,'') as WithdrawalNo,
            COALESCE(wdraw.WithdrawalDateTime,'') as WithdrawalDateTime,

            COALESCE(wdraw.WithdrawByMemberID,0) as WithdrawByMemberID,
            COALESCE(mbrentry.EntryCode,'') as WithdrawByMemberEntryCode,
            CONCAT(COALESCE(mbr.FirstName,''),' ',if(COALESCE(mbr.MiddleName,'') != '', CONCAT(LEFT(COALESCE(mbr.MiddleName,''),1),'. '),''),COALESCE(mbr.LastName,'')) as WithdrawBy,

            COALESCE(mbr.TelNo,'') as WithdrawByMemberTelNo,
            COALESCE(mbr.MobileNo,'') as WithdrawByMemberMobileNo,
            COALESCE(mbr.EmailAddress,'') as WithdrawByMemberEmailAddress,

            COALESCE(wdraw.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(wdraw.ApprovedDateTime,'') as ApprovedDateTime,
            COALESCE(wdraw.ApproveRemarks,'') as ApproveRemarks,

            COALESCE((SELECT COALESCE(RunningBalance,0) as RunningBalance
                        FROM ewalletledger
                        WHERE MemberID = wdraw.WithdrawByMemberID
                        ORDER BY DateTimeEarned DESC
                        LIMIT 1
                        )
                      ,0) as CurrentEWalletBalance,

            COALESCE(wdraw.EWalletBalance,0) as EWalletBalance,
            COALESCE(wdraw.RequestedAmount,0) as RequestedAmount,
            COALESCE(wdraw.ApprovedAmount,0) as ApprovedAmount,
            COALESCE(wdraw.ProcessingFee,0) as ProcessingFee,
            COALESCE(wdraw.NetAmountToReceive,0) as NetAmountToReceive,

            COALESCE(wdraw.WithdrawalOption,'') as WithdrawalOption,
            COALESCE(wdraw.SendToFirstName,'') as SendToFirstName,
            COALESCE(wdraw.SendToLastName,'') as SendToLastName,
            COALESCE(wdraw.SendToMiddleName,'') as SendToMiddleName,
            COALESCE(wdraw.SendToTelNo,'') as SendToTelNo,
            COALESCE(wdraw.SendToMobileNo,'') as SendToMobileNo,
            COALESCE(wdraw.SendToEmailAddress,'') as SendToEmailAddress,
            COALESCE(wdraw.SenderName,'') as SenderName,
            COALESCE(wdraw.SendingRefNo,'') as SendingRefNo,

            COALESCE(wdraw.Bank,'') as Bank,
            COALESCE(wdraw.BankAccountName,'') as BankAccountName,
            COALESCE(wdraw.BankAccountNo,'') as BankAccountNo,

            COALESCE(wdraw.CheckNo,'') as CheckNo,
            COALESCE(wdraw.CheckDate,'') as CheckDate,
            COALESCE(wdraw.CheckAmount,0) as CheckAmount,

            COALESCE(wdraw.Notes,'') as Notes,
            COALESCE(wdraw.Status,'') as Status,

            CASE
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_FOR_APPROVAL')."'  THEN 2
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 3
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 4
                ELSE 0
            END as SortOption,


            COALESCE(wdraw.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(wdraw.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('wdraw.WithdrawalID',$WithdrawalID)
        ->first();

      return $info;

    }

    public function getEWalletWithdrawalInfoByWithdrawalNo($WithdrawalNo){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('ewalletwithdrawal as wdraw')
        ->join('memberentry as mbrentry', 'mbrentry.MemberID', '=', 'wdraw.WithdrawByMemberID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->leftjoin('useraccount as apvby', 'apvby.UserAccountID', '=', 'wdraw.ApprovedByID')
        ->selectraw("
            COALESCE(wdraw.WithdrawalID,0) as WithdrawalID,
            COALESCE(wdraw.WithdrawalNo,'') as WithdrawalNo,
            COALESCE(wdraw.WithdrawalDateTime,'') as WithdrawalDateTime,

            COALESCE(wdraw.WithdrawByMemberID,0) as WithdrawByMemberID,
            COALESCE(mbrentry.EntryCode,'') as WithdrawByMemberEntryCode,
            CONCAT(COALESCE(mbr.FirstName,''),' ',if(COALESCE(mbr.MiddleName,'') != '', CONCAT(LEFT(COALESCE(mbr.MiddleName,''),1),'. '),''),COALESCE(mbr.LastName,'')) as WithdrawBy,

            COALESCE(mbr.TelNo,'') as WithdrawByMemberTelNo,
            COALESCE(mbr.MobileNo,'') as WithdrawByMemberMobileNo,
            COALESCE(mbr.EmailAddress,'') as WithdrawByMemberEmailAddress,

            COALESCE(wdraw.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(wdraw.ApprovedDateTime,'') as ApprovedDateTime,
            COALESCE(wdraw.ApproveRemarks,'') as ApproveRemarks,

            COALESCE((SELECT COALESCE(RunningBalance,0) as RunningBalance
                        FROM ewalletledger
                        WHERE MemberID = wdraw.WithdrawByMemberID
                        ORDER BY DateTimeEarned DESC
                        LIMIT 1
                        )
                      ,0) as CurrentEWalletBalance,

            COALESCE(wdraw.EWalletBalance,0) as EWalletBalance,
            COALESCE(wdraw.RequestedAmount,0) as RequestedAmount,
            COALESCE(wdraw.ApprovedAmount,0) as ApprovedAmount,
            COALESCE(wdraw.ProcessingFee,0) as ProcessingFee,
            COALESCE(wdraw.NetAmountToReceive,0) as NetAmountToReceive,

            COALESCE(wdraw.WithdrawalOption,'') as WithdrawalOption,
            COALESCE(wdraw.SendToFirstName,'') as SendToFirstName,
            COALESCE(wdraw.SendToLastName,'') as SendToLastName,
            COALESCE(wdraw.SendToMiddleName,'') as SendToMiddleName,
            COALESCE(wdraw.SendToTelNo,'') as SendToTelNo,
            COALESCE(wdraw.SendToMobileNo,'') as SendToMobileNo,
            COALESCE(wdraw.SendToEmailAddress,'') as SendToEmailAddress,
            COALESCE(wdraw.SenderName,'') as SenderName,
            COALESCE(wdraw.SendingRefNo,'') as SendingRefNo,

            COALESCE(wdraw.Bank,'') as Bank,
            COALESCE(wdraw.BankAccountName,'') as BankAccountName,
            COALESCE(wdraw.BankAccountNo,'') as BankAccountNo,

            COALESCE(wdraw.CheckNo,'') as CheckNo,
            COALESCE(wdraw.CheckDate,'') as CheckDate,
            COALESCE(wdraw.CheckAmount,0) as CheckAmount,

            COALESCE(wdraw.Notes,'') as Notes,
            COALESCE(wdraw.Status,'') as Status,

            CASE
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_FOR_APPROVAL')."'  THEN 2
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 3
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 4
                ELSE 0
            END as SortOption,


            COALESCE(wdraw.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(wdraw.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('wdraw.WithdrawalNo','=',$WithdrawalNo)
        ->first();

      return $info;

    }

    public function IsHasUnApprovedWithdrawal($MemberID){

      $TODAY = date("Y-m-d H:i:s");

      ini_set('memory_limit', '999999M');

      $query = DB::table('ewalletwithdrawal as wdraw')
        ->join('memberentry as mbrentry', 'mbrentry.MemberID', '=', 'wdraw.WithdrawByMemberID')
        ->join('member as mbr', 'mbr.MemberID', '=', 'mbrentry.MemberID')
        ->leftjoin('useraccount as apvby', 'apvby.UserAccountID', '=', 'wdraw.ApprovedByID')
        ->selectraw("
            COALESCE(wdraw.WithdrawalID,0) as WithdrawalID,
            COALESCE(wdraw.WithdrawalNo,'') as WithdrawalNo,
            COALESCE(wdraw.WithdrawalDateTime,'') as WithdrawalDateTime,

            COALESCE(wdraw.WithdrawByMemberID,0) as WithdrawByMemberID,
            COALESCE(mbrentry.EntryCode,'') as WithdrawByMemberEntryCode,
            CONCAT(COALESCE(mbr.FirstName,''),' ',if(COALESCE(mbr.MiddleName,'') != '', CONCAT(LEFT(COALESCE(mbr.MiddleName,''),1),'. '),''),COALESCE(mbr.LastName,'')) as WithdrawBy,

            COALESCE(mbr.TelNo,'') as WithdrawByMemberTelNo,
            COALESCE(mbr.MobileNo,'') as WithdrawByMemberMobileNo,
            COALESCE(mbr.EmailAddress,'') as WithdrawByMemberEmailAddress,

            COALESCE(wdraw.ApprovedByID,0) as ApprovedByID,
            COALESCE(apvby.Fullname,'') as ApprovedBy,
            COALESCE(wdraw.ApprovedDateTime,'') as ApprovedDateTime,
            COALESCE(wdraw.ApproveRemarks,'') as ApproveRemarks,

            COALESCE((SELECT COALESCE(RunningBalance,0) as RunningBalance
                        FROM ewalletledger
                        WHERE MemberID = wdraw.WithdrawByMemberID
                        ORDER BY DateTimeEarned DESC
                        LIMIT 1
                        )
                      ,0) as CurrentEWalletBalance,

            COALESCE(wdraw.EWalletBalance,0) as EWalletBalance,
            COALESCE(wdraw.RequestedAmount,0) as RequestedAmount,
            COALESCE(wdraw.ApprovedAmount,0) as ApprovedAmount,
            COALESCE(wdraw.ProcessingFee,0) as ProcessingFee,
            COALESCE(wdraw.NetAmountToReceive,0) as NetAmountToReceive,

            COALESCE(wdraw.WithdrawalOption,'') as WithdrawalOption,
            COALESCE(wdraw.SendToFirstName,'') as SendToFirstName,
            COALESCE(wdraw.SendToLastName,'') as SendToLastName,
            COALESCE(wdraw.SendToMiddleName,'') as SendToMiddleName,
            COALESCE(wdraw.SendToTelNo,'') as SendToTelNo,
            COALESCE(wdraw.SendToMobileNo,'') as SendToMobileNo,
            COALESCE(wdraw.SendToEmailAddress,'') as SendToEmailAddress,
            COALESCE(wdraw.SenderName,'') as SenderName,
            COALESCE(wdraw.SendingRefNo,'') as SendingRefNo,

            COALESCE(wdraw.Bank,'') as Bank,
            COALESCE(wdraw.BankAccountName,'') as BankAccountName,
            COALESCE(wdraw.BankAccountNo,'') as BankAccountNo,

            COALESCE(wdraw.CheckNo,'') as CheckNo,
            COALESCE(wdraw.CheckDate,'') as CheckDate,
            COALESCE(wdraw.CheckAmount,0) as CheckAmount,

            COALESCE(wdraw.Notes,'') as Notes,
            COALESCE(wdraw.Status,'') as Status,

            CASE
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_PENDING')."'  THEN 1
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_FOR_APPROVAL')."'  THEN 2
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_APPROVED')."'  THEN 3
                WHEN COALESCE(wdraw.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 4
                ELSE 0
            END as SortOption,

            COALESCE(wdraw.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(wdraw.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where("wdraw.WithdrawByMemberID",$MemberID)
        ->whereraw("wdraw.Status != 'Approved'")
        ->whereraw("wdraw.Status != 'Cancelled'");

      $list = $query->get();

      if(count($list) > 0){
        return 1;
      }else{
        return 0;
      }
    }

  public function getMemberWithdrawalList($param){

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
                SUM(COALESCE(ApprovedAmount,0)) as TotalApprovedAmount
              FROM ewalletwithdrawal
              WHERE WithdrawByMemberID = mbrentry.MemberID
              AND ApprovedDateTime BETWEEN '".$DateFrom." 00:00:00' AND '".$DateTo." 23:59:59'
              )
            ,0) as TotalApprovedAmount,

            COALESCE(mbr.Status,'') as Status

      ");

    if($Limit > 0){
      $query->limit($Limit);
      $query->offset(($PageNo-1) * $Limit);
    }

    $query->orderbyraw("(COALESCE((SELECT 
                SUM(COALESCE(ApprovedAmount,0)) as TotalApprovedAmount
              FROM ewalletwithdrawal
              WHERE WithdrawByMemberID = mbrentry.MemberID
              AND ApprovedDateTime BETWEEN '".$DateFrom." 00:00:00' AND '".$DateTo." 23:59:59'
              )
            ,0)) DESC");

    $list = $query->get();

    return $list;

  }

    public function doSaveUpdateEWalletWithdrawal($data){

      $Misc  = new Misc();
      $MemberEntry  = new MemberEntry();

      $TODAY = date("Y-m-d H:i:s");

      $WithdrawalID = $data['WithdrawalID'];
      $WithdrawalNo = $data['WithdrawalNo'];  
      $WithdrawalDateTime = $data['WithdrawalDateTime'];  

      $WithdrawByMemberID = $data['WithdrawByMemberID'];  

      $ApprovedByID = $data['ApprovedByID'];  
      $ApprovedDateTime = $data['ApprovedDateTime'];  
      $ApproveRemarks = $data['ApproveRemarks'];  
      $ApprovedAmount = $data['ApprovedAmount'];  

      $EWalletBalance = $data['EWalletBalance'];
      $RequestedAmount = $data['RequestedAmount'];
      $ProcessingFee = $data['ProcessingFee'];
      $NetAmountToReceive = $data['NetAmountToReceive'];

      $WithdrawalOption = $data['WithdrawalOption'];
      $SendToFirstName = $data['SendToFirstName'];
      $SendToLastName = $data['SendToLastName'];
      $SendToMiddleName = $data['SendToMiddleName'];
      $SendToTelNo = $data['SendToTelNo'];
      $SendToMobileNo = $data['SendToMobileNo'];
      $SendToEmailAddress = $data['SendToEmailAddress'];
      $SenderName = $data['SenderName'];
      $SendingRefNo = $data['SendingRefNo'];

      $Bank = $data['Bank'];
      $BankAccountName = $data['BankAccountName'];
      $BankAccountNo = $data['BankAccountNo'];

      $CheckNo = $data['CheckNo'];
      $CheckDate = $data['CheckDate'];
      $CheckAmount = $data['CheckAmount'];

      $Notes = $data['Notes'];
      $Status = $data['Status'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      if($WithdrawalID > 0){
        if($Status == config('app.STATUS_FOR_APPROVAL') || $Status == config('app.STATUS_APPROVED')){
            DB::table('ewalletwithdrawal')
            ->where('WithdrawalID',$WithdrawalID)
            ->update([

              'WithdrawByMemberID' => $WithdrawByMemberID,

              'ApprovedByID'=> $ApprovedByID,
              'ApprovedAmount'=> $ApprovedAmount,
              'ApprovedDateTime' => $ApprovedDateTime,
              'ApproveRemarks'=> $ApproveRemarks,

              'EWalletBalance' => $EWalletBalance,
              'RequestedAmount' => $RequestedAmount,
              'ProcessingFee'=> $ProcessingFee,
              'NetAmountToReceive' => $NetAmountToReceive,

              'WithdrawalOption'=> $WithdrawalOption,
              'SendToFirstName'=> $SendToFirstName,
              'SendToLastName'=> $SendToLastName,
              'SendToMiddleName'=> $SendToMiddleName,
              'SendToTelNo'=> $SendToTelNo,
              'SendToMobileNo'=> $SendToMobileNo,
              'SendToEmailAddress'=> $SendToEmailAddress,
              'SenderName'=> $SenderName,
              'SendingRefNo'=> $SendingRefNo,

              'Bank'=> $Bank,
              'BankAccountName'=> $BankAccountName,
              'BankAccountNo'=> $BankAccountNo,

              'CheckNo'=> $CheckNo,
              'CheckDate'=> $CheckDate,
              'CheckAmount'=> $CheckAmount,

              'Notes'=> $Notes,
              'Status'=> $Status,

              'UpdatedByID'=> $UpdatedByID,
              'DateTimeUpdated' =>$TODAY
            ]);

        }else{
            DB::table('ewalletwithdrawal')
            ->where('WithdrawalID',$WithdrawalID)
            ->update([

              'WithdrawByMemberID' => $WithdrawByMemberID,

              'EWalletBalance' => $EWalletBalance,
              'RequestedAmount' => $RequestedAmount,
              'ProcessingFee'=> $ProcessingFee,
              'NetAmountToReceive' => $NetAmountToReceive,

              'WithdrawalOption'=> $WithdrawalOption,
              'SendToFirstName'=> $SendToFirstName,
              'SendToLastName'=> $SendToLastName,
              'SendToMiddleName'=> $SendToMiddleName,
              'SendToTelNo'=> $SendToTelNo,
              'SendToMobileNo'=> $SendToMobileNo,
              'SendToEmailAddress'=> $SendToEmailAddress,
              'SenderName'=> $SenderName,
              'SendingRefNo'=> $SendingRefNo,

              'Bank'=> $Bank,
              'BankAccountName'=> $BankAccountName,
              'BankAccountNo'=> $BankAccountNo,

              'CheckNo'=> $CheckNo,
              'CheckDate'=> $CheckDate,
              'CheckAmount'=> $CheckAmount,

              'Notes'=> $Notes,
              'Status'=> $Status,

              'UpdatedByID'=> $UpdatedByID,
              'DateTimeUpdated' =>$TODAY
            ]);
        }

        //Save Transaction Log
        $logData['TransRefID'] = $WithdrawalID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "E-Wallet Withdrawal";
        $logData['TransType'] = "Update E-Wallet Withdrawal";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        $WithdrawalNo = $Misc->GenerateRandomNo(6,'ewalletwithdrawal','WithdrawalNo');

        if($Status == config('app.STATUS_APPROVED')){
            $WithdrawalID =  DB::table('ewalletwithdrawal')
              ->insertGetId([
                  'WithdrawalNo' => $WithdrawalNo,
                  'WithdrawalDateTime' => $TODAY,

                  'WithdrawByMemberID' => $WithdrawByMemberID,

                  'ApprovedByID'=> $ApprovedByID,
                  'ApprovedAmount'=> $ApprovedAmount,
                  'ApprovedDateTime' => $ApprovedDateTime,
                  'ApproveRemarks'=> $ApproveRemarks,

                  'EWalletBalance' => $EWalletBalance,
                  'RequestedAmount' => $RequestedAmount,
                  'ProcessingFee'=> $ProcessingFee,
                  'NetAmountToReceive' => $NetAmountToReceive,

                  'WithdrawalOption'=> $WithdrawalOption,
                  'SendToFirstName'=> $SendToFirstName,
                  'SendToLastName'=> $SendToLastName,
                  'SendToMiddleName'=> $SendToMiddleName,
                  'SendToTelNo'=> $SendToTelNo,
                  'SendToMobileNo'=> $SendToMobileNo,
                  'SendToEmailAddress'=> $SendToEmailAddress,
                  'SenderName'=> $SenderName,
                  'SendingRefNo'=> $SendingRefNo,

                  'Bank'=> $Bank,
                  'BankAccountName'=> $BankAccountName,
                  'BankAccountNo'=> $BankAccountNo,

                  'CheckNo'=> $CheckNo,
                  'CheckDate'=> $CheckDate,
                  'CheckAmount'=> $CheckAmount,

                  'Notes'=> $Notes,
                  'Status'=> $Status,

                  'CreatedByID'=> $CreatedByID,
                  'UpdatedByID'=> $UpdatedByID,
                  'DateTimeCreated' =>$TODAY,
                  'DateTimeUpdated' =>$TODAY
              ]);
        }else{
            $WithdrawalID =  DB::table('ewalletwithdrawal')
              ->insertGetId([
                  'WithdrawalNo' => $WithdrawalNo,
                  'WithdrawalDateTime' => $TODAY,

                  'WithdrawByMemberID' => $WithdrawByMemberID,

                  'EWalletBalance' => $EWalletBalance,
                  'RequestedAmount' => $RequestedAmount,
                  'ProcessingFee'=> $ProcessingFee,
                  'NetAmountToReceive' => $NetAmountToReceive,

                  'WithdrawalOption'=> $WithdrawalOption,
                  'SendToFirstName'=> $SendToFirstName,
                  'SendToLastName'=> $SendToLastName,
                  'SendToMiddleName'=> $SendToMiddleName,
                  'SendToTelNo'=> $SendToTelNo,
                  'SendToMobileNo'=> $SendToMobileNo,
                  'SendToEmailAddress'=> $SendToEmailAddress,
                  'SenderName'=> $SenderName,
                  'SendingRefNo'=> $SendingRefNo,

                  'Bank'=> $Bank,
                  'BankAccountName'=> $BankAccountName,
                  'BankAccountNo'=> $BankAccountNo,

                  'CheckNo'=> $CheckNo,
                  'CheckDate'=> $CheckDate,
                  'CheckAmount'=> $CheckAmount,

                  'Notes'=> $Notes,
                  'Status'=> $Status,

                  'CreatedByID'=> $CreatedByID,
                  'UpdatedByID'=> $UpdatedByID,
                  'DateTimeCreated' =>$TODAY,
                  'DateTimeUpdated' =>$TODAY

              ]);
        }
        
        //Save Transaction Log
        $logData['TransRefID'] = $WithdrawalID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "E-Wallet Withdrawal";
        $logData['TransType'] = "New E-Wallet Withdrawal";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }

      //Save to ewallet Ledger
      if($Status == config('app.STATUS_APPROVED')  && $WithdrawalID > 0){
        DB::statement("call spSaveEWalletWithdrawal(".$WithdrawalID.",'".$TODAY."')");
      }

      return $WithdrawalID;

    }

    public function doCancelEWalletWithdrawal($data){

      $TODAY = date("Y-m-d H:i:s");

      $WithdrawalID =$data['WithdrawalID'];
      $CancelledByID = $data['CancelledByID'];
      $Reason = $data['Reason'];

      if($WithdrawalID > 0){
        DB::table('ewalletwithdrawal')
        ->where('WithdrawalID',$WithdrawalID)
        ->update([
          'CancelledByID' => $CancelledByID,
          'CancelReason' => $Reason,
          'Status' => config('app.STATUS_CANCELLED'),
          'DateTimeUpdated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $WithdrawalID;
        $logData['TransactedByID'] = $CancelledByID;
        $logData['ModuleType'] = "E-Wallet Withdrawal";
        $logData['TransType'] = "E-Wallet Withdrawal Cancelled";
        $logData['Remarks'] = $Reason;
        $Misc->doSaveTransactionLog($logData);

      }

      return $WithdrawalID;

    }

    
   



}
