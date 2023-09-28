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

class Misc extends Model
{

  public function CheckValidEmail($Email)
  {

    if (config('app.DebugMode') == '1') {
      return true;
    } else if (filter_var($Email, FILTER_VALIDATE_EMAIL) && config('app.DebugMode') == '0') {
      return true;
    }

    return false;
  }

  public function GetCompanyInfo()
  {

    $info = DB::table('companyinfo')
      ->where('CompanyID', '=', 1)
      ->first();

    return $info;
  }

  public function doSaveCompanyInfo($data)
  {

    $TODAY = date("Y-m-d H:i:s");

    DB::table('companyinfo')
      ->where('CompanyID', 1)
      ->update([
        'CompanyName' => $data["CompanyName"],

        'CompanyAddress' => $data["CompanyAddress"],
        'TelNo' => $data["TelNo"],
        'MobileNo' => $data["MobileNo"],
        'EmailAddress' => $data["EmailAddress"],

        'AboutCompany' => $data["AboutCompany"],
        'Mission' => $data["Mission"],
        'Vision' => $data["Vision"],

        'CreatedByID' => $data["CreatedByID"],
        'UpdatedByID' => $data["UpdatedByID"],

        'DateTimeCreated' => $TODAY,
        'DateTimeUpdated' => $TODAY
      ]);
  }

  public function doSaveTransactionLog($data)
  {

    $TODAY = date("Y-m-d H:i:s");
    $TransRefID = $data['TransRefID'];
    $TransactedByID = $data['TransactedByID'];
    $ModuleType = $data['ModuleType'];
    $TransType = $data['TransType'];
    $Remarks = $data['Remarks'];

    DB::table('translog')
      ->insert([
        'TransRefID' => $TransRefID,
        'TransactedByID' => $TransactedByID,
        'TransactionDate' => $TODAY,
        'ModuleType' => $ModuleType,
        'TransType' => $TransType,
        'client_ip' => $this->getIp(),
        'Remarks' => $Remarks,
        'DateTimeCreated' => $TODAY
      ]);
  }
  public function getIp()
  {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
      if (array_key_exists($key, $_SERVER) === true) {
        foreach (explode(',', $_SERVER[$key]) as $ip) {
          $ip = trim($ip); // just to be safe
          if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
            return $ip;
          }
        }
      }
    }
    return request()->ip(); // it will return server ip when no client ip found
  }

  public function GetSettingsNextBatchNo()
  {

    $info = DB::table('systemsettings')
      ->selectraw("
        CAST(SettingValue as SIGNED) as CurrentNo
      ")
      ->where('SettingsID', '=', 1)
      ->first();

    if (isset($info)) {
      $CurrentNo = $info->CurrentNo + 1;
      $CurrentNo = str_pad($CurrentNo, 5, "0", STR_PAD_LEFT);
      return $CurrentNo;
    }

    return 0;
  }

  public function SetSettingsNextBatchNo($CurrentNo)
  {
    $TODAY = date("Y-m-d H:i:s");

    DB::table('systemsettings')
      ->where('SettingsID', 1)
      ->update([
        'SettingValue' => $CurrentNo,
        'DateTimeUpdated' => $TODAY
      ]);

    return true;
  }

  public function ResizePhoto($data)
  {
    $image_uploaded = $data["ImageUpload"];
    $path = $data["Path"];
    $autoscale = $data["AutoScale"];
    $posx = $data["PosX"];
    $posy = $data["PosY"];
    $width = $data["Width"];
    $height = $data["Height"];
    $max_width = $data["MaxWidth"];
    $max_height = $data["MaxHeight"];
    $filename = $data["FileName"];

    $IsResizeImage = false;
    if (isset($data["IsResizeImage"])) {
      $IsResizeImage = $data["IsResizeImage"];
    }
    switch ($_FILES[$image_uploaded]['type']) {
      case 'image/jpeg':
        $image = imagecreatefromjpeg($_FILES[$image_uploaded]['tmp_name']);
        break;
      case 'image/png':
        $image = imagecreatefrompng($_FILES[$image_uploaded]['tmp_name']);
        break;
      case 'image/gif':
        $image = imagecreatefromgif($_FILES[$image_uploaded]['tmp_name']);
        break;
      default:
        exit('Unsupported type: ' . $_FILES[$image_uploaded]['type']);
    }

    // Get current dimensions
    $old_width  = imagesx($image);
    $old_height = imagesy($image);
    if ($IsResizeImage && $old_width > $max_width && ($posx > 0 || $posy > 0)) {

      // Calculate the scaling we need to do to fit the image inside our frame
      $scale = $max_width / $old_width;

      // Get the new dimensions
      $new_width  = ceil($scale * $old_width);
      $new_height = ceil($scale * $old_height);

      //            $posx = ceil($posx * $scale);
      //            $posy = ceil($posy * $scale);
      //            $width = ceil($width * $scale);
      //            $height = ceil($height * $scale);

      // Create new empty image
      $new = imagecreatetruecolor($new_width, $new_height);

      //allow transparency for pngs
      imagealphablending($new, false);
      imagesavealpha($new, true);

      $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
      imagefilledrectangle($new, 0, 0, $new_width, $new_height, $transparent);

      // Resize old image into new
      imagecopyresampled(
        $new,
        $image,
        0,
        0,
        0,
        0,
        $new_width,
        $new_height,
        $old_width,
        $old_height
      );

      $image = $new;
    }

    if ($posx == 0 && $posy == 0) {
      $autoscale = true;
    }

    //Actual resizing
    if ($autoscale) {

      // Get current dimensions
      $old_width  = imagesx($image);
      $old_height = imagesy($image);

      // Calculate the scaling we need to do to fit the image inside our frame
      if ($max_width == 0) {
        $scale = $max_height / $old_height;
      } elseif ($max_height == 0) {
        $scale = $max_width / $old_width;
      } else {
        $scale = min($max_width / $old_width, $max_height / $old_height);
      }

      // Get the new dimensions
      if ($IsResizeImage && $posx == 0 && $posy == 0) {
        $new_width  = ceil($width * $scale);
        $new_height = ceil($height * $scale);
      } else {
        $new_width  = ceil($scale * $old_width);
        $new_height = ceil($scale * $old_height);
      }
    } else {

      $old_width  = ceil($width);
      $old_height = ceil($height);
      $new_width  = ceil($width);
      $new_height = ceil($height);
    }

    // Create new empty image
    $new = imagecreatetruecolor($new_width, $new_height);

    //allow transparency for pngs
    imagealphablending($new, false);
    imagesavealpha($new, true);

    $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
    imagefilledrectangle($new, 0, 0, $new_width, $new_height, $transparent);

    // Resize old image into new
    imagecopyresampled(
      $new,
      $image,
      0,
      0,
      $posx,
      $posy,
      $new_width,
      $new_height,
      $old_width,
      $old_height
    );


    $newfilename = $path . $filename;
    #create folder if not exist
    if (!file_exists($path)) {
      mkdir($path, 0777, TRUE);
    }

    //Delete File if exist
    if (is_file($newfilename)) {
      unlink($newfilename);
    }

    $file_parts = pathinfo($newfilename);
    switch ($file_parts['extension']) {
      case "jpg":
        imagejpeg($new, $newfilename);
        break;
      case "png":
        imagepng($new, $newfilename);
        break;
    }

    // Destroy resources
    imagedestroy($image);
    imagedestroy($new);

    return true;
  }

  // COUNTRY ==========================================================
  public static function getCountryList()
  {

    $list = DB::table('country')
      ->select('country.*')
      ->orderBy('Country', 'asc')
      ->get();

    return $list;
  }

  // COUNTRY CITIES ==========================================================
  public static function getCountryCityList($CountryID)
  {

    $list = DB::table('countrycities')
      ->select('countrycities.*')
      ->where('CountryID', '=', $CountryID)
      ->orderBy('countrycities.City', 'asc')
      ->get();

    return $list;
  }

  public function GenerateRandomNo($Length, $TableName, $FieldName)
  {

    $MinNo = "1";
    $MaxNo = "9";
    for ($i = 1; $i < $Length; $i++) {
      $MinNo = $MinNo . '0';
      $MaxNo = $MaxNo . '9';
    }

    $MinNo = $MinNo + 0;
    $MaxNo = $MaxNo + 0;

    $GeneratedNo  = mt_rand($MinNo, $MaxNo);
    $GeneratedNo = str_pad(str_replace("-", "", $GeneratedNo), $Length, "0", STR_PAD_LEFT);

    if ($TableName != '') {
      $check = DB::table($TableName)
        ->select($FieldName)
        ->where($FieldName, $GeneratedNo)
        ->first();

      if (!isset($check)) {
        return $GeneratedNo;
      } else {
        $this->GenerateRandomNo($Length, $TableName, $FieldName);
      }
    } else {
      return $GeneratedNo;
    }
  }

  public function IsValidMobileNo($MobileNo)
  {
    $Response = False;
    $MobileNo = str_replace(" ", "", $MobileNo); //Remove Spaces
    $MobileNo = str_replace("+", "", $MobileNo); //Remove Others
    $MobileNo = str_replace("-", "", $MobileNo); //Remove Others

    $MobileNoCount = strlen($MobileNo);
    $MobileNoFirst2Char = substr($MobileNo, 0, 2);
    $MobileNoFirst3Char = substr($MobileNo, 0, 3);

    if (!is_numeric($MobileNo)) {
      $Response = False;
    } else if ($MobileNoFirst2Char == "63") {
      if ($MobileNoCount != 12) {
        $Response = False;
      } else if ($MobileNoFirst3Char != "639") {
        $Response = False;
      } else {
        $Response = True;
      }
    } else if ($MobileNoFirst2Char == "09") {
      if ($MobileNoCount != 11) {
        $Response = False;
      } else {
        $Response = True;
      }
    } else {
      $Response = False;
    }

    return $Response;
  }

  public function getEntryCounter($List, $EntryID)
  {

    $Cntr = 0;

    foreach ($List as $child) {
      if ($child->EntryID == $EntryID) {
        $Cntr = $child->Cntr;
      }
    }

    return $Cntr;
  }
}
