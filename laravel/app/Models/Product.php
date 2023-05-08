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
use App\Models\Inventory;

class Product extends Model
{

    public function getProductList($param){

      $TODAY = date("Y-m-d H:i:s");

      $Status = $param['Status'];
      $SearchText = trim($param['SearchText']);
      $Limit = $param['Limit'];
      $PageNo = $param['PageNo'];

      ini_set('memory_limit', '999999M');

      $query = DB::table('product as prd')
        ->selectraw("
            COALESCE(prd.ProductID,0) as ProductID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,
            COALESCE(prd.NetWeight,0) as NetWeight,
            COALESCE(prd.Measurement,'') as Measurement,

            COALESCE(prd.IsPackageSet,0) as IsPackageSet,

            COALESCE(prd.CenterPrice,0) as CenterPrice,
            COALESCE(prd.DistributorPrice,0) as DistributorPrice,
            COALESCE(prd.RetailPrice,0) as RetailPrice,
            COALESCE(prd.RebateValue,0) as RebateValue,

            COALESCE(prd.Status,'') as Status
        ");

      if($Status != ''){
        $query->where("prd.Status",$Status);
      }

      if($SearchText != ''){
        $query->whereraw(
            "CONCAT(
              COALESCE(prd.ProductCode,''),' ',
              COALESCE(prd.ProductName,'')
            ) like '%".str_replace("'", "''", $SearchText)."%'");
      }

      if($Limit > 0){
        $query->limit($Limit);
        $query->offset(($PageNo-1) * $Limit);
      }

      $query->orderByraw("COALESCE(prd.ProductName,'')","ASC");

      $list = $query->get();

      return $list;

    }

    public function getProductInfo($ProductID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('product as prd')
        ->selectraw("
            COALESCE(prd.ProductID,0) as ProductID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,
            COALESCE(prd.NetWeight,0) as NetWeight,
            COALESCE(prd.Measurement,'') as Measurement,

            COALESCE(prd.IsPackageSet,0) as IsPackageSet,

            COALESCE(prd.CenterPrice,0) as CenterPrice,
            COALESCE(prd.DistributorPrice,0) as DistributorPrice,
            COALESCE(prd.RetailPrice,0) as RetailPrice,
            COALESCE(prd.RebateValue,0) as RebateValue,

            COALESCE(prd.Status,'') as Status
        ")
        ->where('prd.ProductID',$ProductID)
        ->first();

      return $info;

    }

    public function getProductInfoByCode($ProductCode){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('product as prd')
        ->selectraw("
            COALESCE(prd.ProductID,0) as ProductID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,
            COALESCE(prd.NetWeight,0) as NetWeight,
            COALESCE(prd.Measurement,'') as Measurement,

            COALESCE(prd.IsPackageSet,0) as IsPackageSet,

            COALESCE(prd.CenterPrice,0) as CenterPrice,
            COALESCE(prd.DistributorPrice,0) as DistributorPrice,
            COALESCE(prd.RetailPrice,0) as RetailPrice,
            COALESCE(prd.RebateValue,0) as RebateValue,

            COALESCE(prd.Status,'') as Status
        ")
        ->whereraw("COALESCE(prd.ProductCode,'') = '".$ProductCode."'")
        ->first();

      return $info;

    }

    public function getProductInfoByName($ProductName){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('product as prd')
        ->selectraw("
            COALESCE(prd.ProductID,0) as ProductID,
            COALESCE(prd.Category,'') as Category,
            COALESCE(prd.Brand,'') as Brand,
            COALESCE(prd.ProductCode,'') as ProductCode,
            COALESCE(prd.ProductName,'') as ProductName,
            COALESCE(prd.Description,'') as Description,
            COALESCE(prd.Specification,'') as Specification,
            COALESCE(prd.NetWeight,0) as NetWeight,
            COALESCE(prd.Measurement,'') as Measurement,

            COALESCE(prd.IsPackageSet,0) as IsPackageSet,

            COALESCE(prd.CenterPrice,0) as CenterPrice,
            COALESCE(prd.DistributorPrice,0) as DistributorPrice,
            COALESCE(prd.RetailPrice,0) as RetailPrice,
            COALESCE(prd.RebateValue,0) as RebateValue,

            COALESCE(prd.Status,'') as Status
        ")
        ->whereraw("COALESCE(prd.ProductName,'') = '".$ProductName."'")
        ->first();

      return $info;

    }

    public function doSaveUpdateProduct($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $ProductID = $data['ProductID'];
      $Brand = $data['Brand'];
      $Category = $data['Category'];
      $ProductCode = $data['ProductCode'] ;
      $ProductName = $data['ProductName'] ;
      $Description = $data['Description'] ;
      $Specification = $data['Specification'] ;
      $NetWeight = $data['NetWeight'] ;
      $Measurement = $data['Measurement'];

      $IsPackageSet = $data['IsPackageSet'];

      $CenterPrice = $data['CenterPrice'];
      $DistributorPrice = $data['DistributorPrice'];
      $RetailPrice = $data['RetailPrice'];
      $RebateValue = $data['RebateValue'];

      $Status = $data['Status'];

      $CreatedByID = $data['CreatedByID'];
      $UpdatedByID = $data['UpdatedByID'];

      //Save Product Info
      if($ProductID > 0){
        DB::table('product')
        ->where('ProductID',$ProductID)
        ->update([
          'Brand' => $Brand,
          'Category' => $Category,
          'ProductCode' => $ProductCode,
          'ProductName' => ucwords(trim($ProductName)),
          'Description' => trim($Description),
          'Specification' => trim($Specification),
          'NetWeight' => trim($NetWeight),

          'IsPackageSet' => trim($IsPackageSet),

          'Measurement' => trim($Measurement),
          'CenterPrice' => $CenterPrice,
          'DistributorPrice' => $DistributorPrice,
          'RetailPrice' => $RetailPrice,
          'RebateValue' => $RebateValue,

          'Status' => $Status,

          'UpdatedByID' => $UpdatedByID,
          'DateTimeUpdated' =>$TODAY
        ]);

        //Save to Inventory
        $Inventory = new Inventory();
        $data["ProductID"] = $ProductID;
        $Inventory->doSaveUpdateInventory($data);

        //Save Transaction Log
        $logData['TransRefID'] = $ProductID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Package";
        $logData['TransType'] = "New Package";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        if(empty($ProductCode)){
          $ProductCode = $Misc->GenerateRandomNo(6,'product','ProductCode');
        }

        $ProductID=  DB::table('product')
          ->insertGetId([
            'Brand' => $Brand,
            'Category' => $Category,
            'ProductCode' => $ProductCode,
            'ProductName' => ucwords(trim($ProductName)),
            'Description' => trim($Description),
            'Specification' => trim($Specification),
            'NetWeight' => trim($NetWeight),

            'IsPackageSet' => trim($IsPackageSet),

            'Measurement' => trim($Measurement),
            'CenterPrice' => $CenterPrice,
            'DistributorPrice' => $DistributorPrice,
            'RetailPrice' => $RetailPrice,
            'RebateValue' => $RebateValue,

            'Status' => $Status,

            'CreatedByID' => $CreatedByID,
            'UpdatedByID' => $UpdatedByID,
            'DateTimeCreated' =>$TODAY
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $ProductID;
        $logData['TransactedByID'] = $CreatedByID;
        $logData['ModuleType'] = "Product";
        $logData['TransType'] = "New Product";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);
        
        //Save to Inventory
        $Inventory = new Inventory();
        $data["ProductID"] = $ProductID;
        $Inventory->doSaveUpdateInventory($data);

        $this->doSaveEmptyPhoto($ProductID);

      }

      return $ProductID;

    }

    public function doSaveEmptyPhoto($ProductID){
        //Save empty product
        $ImageDestination = "public/img/products/".$ProductID."/";
        File::makeDirectory($ImageDestination, 0777, true,true);

        copy("public/img/products/product-no-image-".config('app.Thumbnail').".jpg",$ImageDestination.$ProductID."-1-".config('app.Thumbnail').".jpg");

        copy("public/img/products/product-no-image-".config('app.Dimension').".jpg",$ImageDestination.$ProductID."-1-".config('app.Dimension').".jpg");
    }

    public function doUploadProductPhoto($data){

      $Misc  = new Misc();

      $ProductID = $data["ProductID"];

      $ImageDestination = "public/img/products/".$ProductID."/";
      File::makeDirectory($ImageDestination, 0777, true,true);

      $fieldName = 'productimage';
      $files = $_FILES;
      for($i=0; $i<count($files[$fieldName]['name']); $i++){

        if($files[$fieldName]['type'][$i] != ''){

          //300 x 300
          $FileName = $ProductID.'-'.($i + 1).'-'.config('app.Thumbnail').'.jpg';
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
          $FileName = $ProductID.'-'.($i + 1).'-'.config('app.Dimension').'.jpg';;
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
