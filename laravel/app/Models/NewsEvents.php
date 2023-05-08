<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use Mail;
use Session;
use Hash;
use View;
use Image;
use DB;

class NewsEvents extends Model
{

    public function getNewsEventsList($param){

      $TODAY = date("Y-m-d H:i:s");

      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('newsevents')
        ->selectraw("
            COALESCE(newsevents.RecordID,0) as RecordID,

            COALESCE(newsevents.Title,'') as Title,
            COALESCE(newsevents.SlugTitle,'') as SlugTitle,
            COALESCE(newsevents.Contents,'') as Contents,
            COALESCE(newsevents.PostedBy,'') as PostedBy,
            COALESCE(newsevents.PublishDate,'') as PublishDate,

            COALESCE(newsevents.Status,'') as Status,

            CASE
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_UNPUBLISHED')."'  THEN 1
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_PUBLISHED')."'  THEN 2
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(newsevents.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(newsevents.DateTimeUpdated,'') as DateTimeUpdated

        ");

      if($SearchText != ''){
        $query->whereraw(
            "COALESCE(newsevents.Title,'')
            like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Status != ''){
        $query->where("newsevents.Status",$Status);
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("(
            CASE
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_UNPUBLISHED')."'  THEN 1
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_PUBLISHED')."'  THEN 2
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END) ASC");

     $query->orderByraw("COALESCE(newsevents.PublishDate,'') DESC");

      $list = $query->get();

      return $list;

    }

    public function getNewsEventsInfo($RecordID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('newsevents')
        ->selectraw("
            COALESCE(newsevents.RecordID,0) as RecordID,

            COALESCE(newsevents.Title,'') as Title,
            COALESCE(newsevents.SlugTitle,'') as SlugTitle,
            COALESCE(newsevents.Contents,'') as Contents,
            COALESCE(newsevents.PostedBy,'') as PostedBy,
            COALESCE(newsevents.PublishDate,'') as PublishDate,

            COALESCE(newsevents.Status,'') as Status,

            CASE
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_UNPUBLISHED')."'  THEN 1
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_PUBLISHED')."'  THEN 2
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(newsevents.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(newsevents.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('newsevents.RecordID',$RecordID)
        ->first();

      return $info;

    }

    public function getNewsEventInfoByTitle($Title){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('newsevents')
        ->selectraw("
            COALESCE(newsevents.RecordID,0) as RecordID,

            COALESCE(newsevents.Title,'') as Title,
            COALESCE(newsevents.SlugTitle,'') as SlugTitle,
            COALESCE(newsevents.Contents,'') as Contents,
            COALESCE(newsevents.PostedBy,'') as PostedBy,
            COALESCE(newsevents.PublishDate,'') as PublishDate,

            COALESCE(newsevents.Status,'') as Status,

            CASE
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_UNPUBLISHED')."'  THEN 1
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_PUBLISHED')."'  THEN 2
                WHEN COALESCE(newsevents.Status,'') = '".config('app.STATUS_CANCELLED')."'  THEN 3
                ELSE 0
            END as SortOption,

            COALESCE(newsevents.DateTimeCreated,'') as DateTimeCreated,
            COALESCE(newsevents.DateTimeUpdated,'') as DateTimeUpdated

        ")
        ->where('newsevents.SlugTitle',$Title)
        ->first();

      return $info;

    }    
    
    public function doSaveUpdateNewsEvents($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $RecordID = $data['RecordID'];

      $Title = $data['Title'];  
      $SlugTitle = Str::slug($data['Title']);
      $Contents = $data['Contents'];  
      $PostedBy = $data['PostedBy'];  
      $PublishDate = $data['PublishDate'];  

      $Status = $data['Status'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      if($RecordID > 0){
        DB::table('newsevents')
        ->where('RecordID',$RecordID)
        ->update([

          'Title' => $Title,
          'SlugTitle' => $SlugTitle,
          'Contents'=> $Contents,
          'PostedBy'=> $PostedBy,
          'PublishDate'=> $PublishDate,

          'Status'=> $Status,

          'UpdatedByID'=> $UpdatedByID,
          'DateTimeUpdated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $RecordID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "News and Events";
        $logData['TransType'] = "Update News and Events";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        $RecordID =  DB::table('newsevents')
          ->insertGetId([
              'Title' => $Title,
              'SlugTitle' => $SlugTitle,
              'Contents'=> $Contents,
              'PostedBy'=> $PostedBy,
              'PublishDate'=> $PublishDate,

              'Status'=> $Status,

              'CreatedByID'=> $CreatedByID,
              'UpdatedByID'=> $UpdatedByID,
              'DateTimeCreated' =>$TODAY,
              'DateTimeUpdated' =>$TODAY
          ]);

        
        //Save Transaction Log
        $logData['TransRefID'] = $RecordID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "News and Events";
        $logData['TransType'] = "New News and Events";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }

      return $RecordID;

    }

    public function doUploadPhoto($data){

      $Misc  = new Misc();

      $RecordID = $data["RecordID"];

      $ImageDestination = "public/img/newsevents/";
      File::makeDirectory($ImageDestination, 0777, true,true);

      $fieldName = 'imgPhoto';
      $files = $_FILES;
      for($i=0; $i<count($files[$fieldName]['name']); $i++){

        if($files[$fieldName]['type'][$i] != ''){

          //300 x 300
          $FileName = $RecordID.'-'.($i + 1).'-'.config('app.Thumbnail').'.jpg';
          $_FILES[$fieldName]['name']= $FileName;
          $_FILES[$fieldName]['type']= $files[$fieldName]['type'][$i];
          $_FILES[$fieldName]['tmp_name']= $files[$fieldName]['tmp_name'][$i];
          $_FILES[$fieldName]['error']= $files[$fieldName]['error'][$i];
          $_FILES[$fieldName]['size']= $files[$fieldName]['size'][$i];
          $picdata["ImageUpload"] = $fieldName;
          $picdata["Path"] = $ImageDestination;
          $picdata["AutoScale"] = true;
          $picdata["PosX"] = 0;
          $picdata["PosY"] = 0;
          $picdata["Width"] = 0;
          $picdata["Height"] = 0;
          $picdata["MaxWidth"] = config('app.ThumbnailWidth');
          $picdata["MaxHeight"] = config('app.ThumbnailHeight');
          $picdata["FileName"] = $FileName;
          $Misc->ResizePhoto($picdata);

          //500 x 500
          $FileName = $RecordID.'-'.($i + 1).'-'.config('app.Dimension').'.jpg';
          $_FILES[$fieldName]['name']= $FileName;
          $_FILES[$fieldName]['type']= $files[$fieldName]['type'][$i];
          $_FILES[$fieldName]['tmp_name']= $files[$fieldName]['tmp_name'][$i];
          $_FILES[$fieldName]['error']= $files[$fieldName]['error'][$i];
          $_FILES[$fieldName]['size']= $files[$fieldName]['size'][$i];
          $picdata["ImageUpload"] = $fieldName;
          $picdata["Path"] = $ImageDestination;
          $picdata["AutoScale"] = true;
          $picdata["PosX"] = 0;
          $picdata["PosY"] = 0;
          $picdata["Width"] = 0;
          $picdata["Height"] = 0;
          $picdata["MaxWidth"] = config('app.DimensionWidth');
          $picdata["MaxHeight"] = config('app.DimensionHeight');
          $picdata["FileName"] = $FileName;
          $Misc->ResizePhoto($picdata);
        }
      }

      return true;
      
    }    
   



}
