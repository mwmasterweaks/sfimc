<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Mail\ForgotPassword;
use Mail;
use DB;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function doForgetPassword(Request $request)
    {
        $Error_Msg = "";
        $Success_Msg = "";
        $Temp_Pass = $this->generateCode(8);
        $data['EmailAddress'] = $request['EmailAddress'];
        $data['MemberNo'] = $request['MemberNo'];
        // 4e17a448e043206801b95de317e07c839770c8b8
        // briones@gmail.com
        $Member = DB::table('member')->where('EmailAddress',$data['EmailAddress'])->where('MemberNo',$data['MemberNo'])->first();
        if($Member)
        {
            $email = new ForgotPassword($Temp_Pass,$Member->FirstName);
            Mail::to($data['EmailAddress'])->send($email);

            if($email)
            {
                DB::table('member')->where('EmailAddress',$data['EmailAddress'])->where('MemberNo',$data['MemberNo'])->update(['Password' =>  sha1(trim($Temp_Pass))]);
                $Success_Msg = 'Password reset instrunction sent to your email';
            }
            
        }else{
            $Error_Msg = 'Invalid Email Address or IBO Number';
        }

        if (empty($data['EmailAddress']) && empty($data['MemberNo'])) {
            $Error_Msg = 'Please enter your Email Address and IBO Number';
        }else if(empty($data['EmailAddress'])) {
            $Error_Msg = 'Please enter your Email Address';
        }else if(empty($data['MemberNo'])) {
            $Error_Msg = 'Please enter your IBO Number';
        }

        if (!empty($Error_Msg)){
	        return Redirect::back()->with('Error_Msg',$Error_Msg);
	    }else{
            return Redirect::back()->with('Success_Msg',$Success_Msg);
        }
    }

    /**
     * generate alpha numeric code
     *
     * @param $length
     * @return \Illuminate\Http\JsonResponse
     */
    function generateCode($length)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
