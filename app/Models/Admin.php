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

class Admin extends Model
{

    public function getDashboardFigures(){
		$TODAY = date("Y-m-d H:i:s");
		$list = DB::select("call spGetDashboardFigures('".$TODAY."')");

		return $list;
	}

	public function getAlertLabels(){

		$list = DB::select("CALL spGetAdminAlertLabels()");

		return $list;
	}

}
