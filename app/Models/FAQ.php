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

class FAQ extends Model
{

    public function getFAQList($param){

      $TODAY = date("Y-m-d H:i:s");

      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('faq')
        ->selectraw("
            COALESCE(faq.FAQID,0) as FAQID,

            COALESCE(faq.SortOrder,0) as SortOrder,
            COALESCE(faq.FAQ,'') as FAQ,
            COALESCE(faq.FAQAnswer,'') as FAQAnswer,

            COALESCE(faq.Status,'') as Status,

            COALESCE(faq.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(faq.DateTimeUpdated,'') as DateTimeUpdated

        ");

      if($SearchText != ''){
        $query->whereraw(
            "COALESCE(faq.FAQ,'')
            like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Status != ''){
        $query->where("faq.Status",$Status);
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("COALESCE(faq.SortOrder,0) ASC");

      $list = $query->get();

      return $list;

    }

    public function getFAQInfo($FAQID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('faq')
        ->selectraw("
            COALESCE(faq.FAQID,0) as FAQID,

            COALESCE(faq.SortOrder,0) as SortOrder,
            COALESCE(faq.FAQ,'') as FAQ,
            COALESCE(faq.FAQAnswer,'') as FAQAnswer,

            COALESCE(faq.Status,'') as Status,

            COALESCE(faq.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(faq.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('faq.FAQID',$FAQID)
        ->first();

      return $info;

    }
    
    public function doSaveUpdateFAQ($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $FAQID = $data['FAQID'];

      $SortOrder = $data['SortOrder'];  
      $FAQ = $data['FAQ'];  
      $FAQAnswer = $data['FAQAnswer'];  

      $Status = $data['Status'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      if($FAQID > 0){
        DB::table('faq')
        ->where('FAQID',$FAQID)
        ->update([

          'SortOrder' => $SortOrder,
          'FAQ'=> $FAQ,
          'FAQAnswer'=> $FAQAnswer,

          'Status'=> $Status,

          'UpdatedByID'=> $UpdatedByID,
          'DateTimeUpdated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $FAQID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "FAQ";
        $logData['TransType'] = "Update FAQ";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        $FAQID =  DB::table('faq')
          ->insertGetId([
              'SortOrder' => $SortOrder,
              'FAQ'=> $FAQ,
              'FAQAnswer'=> $FAQAnswer,

              'Status'=> $Status,

              'CreatedByID'=> $CreatedByID,
              'UpdatedByID'=> $UpdatedByID,
              'DateTimeCreated' =>$TODAY,
              'DateTimeUpdated' =>$TODAY
          ]);

        
        //Save Transaction Log
        $logData['TransRefID'] = $FAQID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "FAQ";
        $logData['TransType'] = "New FAQ";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }

      return $FAQID;

    }

    
   



}
