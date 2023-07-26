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

class ShipperJAT extends Model
{


    public function getShipperJATList(){

      $TODAY = date("Y-m-d H:i:s");

      ini_set('memory_limit', '999999M');

      $query = DB::table('shipperjatsettings')
        ->selectraw("
            COALESCE(shipperjatsettings.Destination,0) as Destination,
            COALESCE(shipperjatsettings.SettingsID,0) as SettingsID,
            COALESCE(shipperjatsettings.WeightLimit,0) as WeightLimit,
            COALESCE(shipperjatsettings.Rates,0) as Rates,
            COALESCE(shipperjatsettings.AdditionalRatesPerKg,0) as AdditionalRatesPerKg
        ");

      $query->orderByraw("COALESCE(shipperjatsettings.WeightLimit,0)","ASC");

      $list = $query->get();

      return $list;

    }

    public function getShipperJATInfo($SettingsID){

      $TODAY = date("Y-m-d H:i:s");

      $info = DB::table('shipperjatsettings')
        ->selectraw("
            COALESCE(shipperjatsettings.Destination,0) as Destination,
            COALESCE(shipperjatsettings.SettingsID,0) as SettingsID,
            COALESCE(shipperjatsettings.WeightLimit,0) as WeightLimit,
            COALESCE(shipperjatsettings.Rates,0) as Rates,
            COALESCE(shipperjatsettings.AdditionalRatesPerKg,0) as AdditionalRatesPerKg
        ")
        ->where('shipperjatsettings.SettingsID',$SettingsID)
        ->first();

      return $info;

    }

    public function doSaveUpdateShipperJAT($data){

      $Misc  = new Misc();

      $TODAY = date("Y-m-d H:i:s");

      $SettingsID = $data['SettingsID'];
      $Destination = $data['Destination'];
      $WeightLimit = $data['WeightLimit'];
      $Rates = $data['Rates'];
      $AdditionalRatesPerKg = $data['AdditionalRatesPerKg'];

      $UpdatedByID = $data['UpdatedByID'];

      //Save Info
      if($SettingsID > 0){
        DB::table('shipperjatsettings')
        ->where('SettingsID',$SettingsID)
        ->update([ 
          'Destination' => $Destination,
          'WeightLimit' => $WeightLimit,
          'Rates' => $Rates,
          'AdditionalRatesPerKg' => $AdditionalRatesPerKg
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $SettingsID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Shipper - J&T Settings";
        $logData['TransType'] = "Update Shipper - J&T Settings";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);

      }else{

        $SettingsID =  DB::table('shipperjatsettings')
          ->insertGetId([
            'Destination' => $Destination,
            'WeightLimit' => $WeightLimit,
            'Rates' => $Rates,
            'AdditionalRatesPerKg' => $AdditionalRatesPerKg
        ]);

        //Save Transaction Log
        $logData['TransRefID'] = $SettingsID;
        $logData['TransactedByID'] = $UpdatedByID;
        $logData['ModuleType'] = "Shipper - J&T Settings";
        $logData['TransType'] = "New Shipper - J&T Settings";
        $logData['Remarks'] = "";
        $Misc->doSaveTransactionLog($logData);
      }

      return $SettingsID;

    }

    public function getJATRate($CityID, $TotalWeight){

      $CityGroup =DB::table('countrycities')
            ->where('CityID','=',$CityID)
            ->first();

      $Region = "";
      $IslandGroup = "";
      if(isset($CityGroup)){
        $IslandGroup = $CityGroup->IslandGroup;
        $Region = $CityGroup->Region;
      }

      $JATRates =DB::table('shipperjatsettings')
        ->whereraw("COALESCE(WeightLimit,0) >= ".$TotalWeight)
        ->whereraw("COALESCE(Destination,'') = '".($Region == "NCR" ? $Region : $IslandGroup)."'")
        ->orderByraw("WeightLimit","ASC")
        ->first();

      $TotalWeight = ceil($TotalWeight);
      $ExcessWeight = 0;

      $WeightLimit = 0;
      $Rates = 0;
      $AdditionalRatesPerKg = 0;
      if(isset($JATRates)){
        $WeightLimit = $JATRates->WeightLimit;
        $Rates = $JATRates->Rates;
        $AdditionalRatesPerKg = $JATRates->AdditionalRatesPerKg;

        if($TotalWeight > $WeightLimit){
          $ExcessWeight = $TotalWeight - $WeightLimit;
        }
      }

      $TotalWeight = $TotalWeight - $ExcessWeight;
      $ExcessWeight = ceil($ExcessWeight);

      $ShippingCharges = $Rates + ($ExcessWeight*$AdditionalRatesPerKg);

      return  $ShippingCharges;

    }


}
