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

class CronJobs extends Model
{

  //$TODAY = date("Y-m-d_H-i-s");
  //$FileName = "DbaseBackup/coachmic_sfi-server-$TODAY.sql.gz";

  //$Command = "mysqldump --databases --add-drop-database --user='" . env('DB_USERNAME', 'root') . "' --password='" . env('DB_PASSWORD', '') . "' --host='localhost' --port='" . env('DB_PORT', '3306') . "' " . env('DB_DATABASE', 'coachmic_sfi') . " | gzip > " . $FileName;
  //$Result = passthru($Command);

  public function doScheduledJobs()
  {
    //return "doScheduledJobs";

    //if (time() >= strtotime("23:00:00") || time() <= strtotime("01:00:00")) {

    //Generate Matching Commisison
    DB::statement("CALL spGenerateMatchingCommission('" . date('Y-m-d') . "')");
    $filenameDate = date("Ym");
    $myfile = fopen("logs/doScheduledJobsLOGS" . $filenameDate . ".txt", "a") or die("Unable to open file!");
    fwrite($myfile,  date("Y-m-d H:i:s") . " doScheduledJobsLOGS called\n\n");
    fclose($myfile);
    //Save Transaction Log
    $Misc = new Misc();
    $logData['TransRefID'] = 0;
    $logData['TransactedByID'] = 1;
    $logData['ModuleType'] = "Matching Commission";
    $logData['TransType'] = "Scheduled Matching Commission";
    $logData['Remarks'] = "";
    $Misc->doSaveTransactionLog($logData);
    //}
  }
}
