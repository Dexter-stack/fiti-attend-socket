<?php

namespace App\FormService;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\admin;
use App\Models\User;
use App\Models\user_course;

use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;

class admin_auth
{

    private $course;

    public function set_course($course)
    {
        $this->course = $course;
    }






    public function register_user(Request $req)
    {




        $data = new admin();
        $data->fullname = $req->fullname;
        $data->user_id = $req->user_id;
        $data->password = Hash::make($req->password);


        if ($data->save()) {


            return true;
        }

        return false;
    }


    // public function login_admin(Request $req, $rules)
    // {



    //     $validator = Validator::make($req->all(), $rules);
    //     if ($validator->fails()) {

    //         return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
    //     } else {
    //         if (!Auth::guard('admin')->attempt($req->only('user_id', 'password'))) {

    //             $response = [

    //                 'message' => "incorrect password",
    //                 "status_code" => 401
    //             ];
    //         } else {
    //             $user = admin::where('user_id', $req->user_id)->first();

    //             //create personal access token
    //             $token = $user->createToken('mytoken')->plainTextToken;
    //             $response = [

    //                 "useri" => Auth::guard('admin')->user(),
    //                 'token' => $token,
    //                 "status_code" => 200,



    //             ];
    //         }
    //         return response($response);
    //     }
    // }




    public function fetch_attendance()
    {
    }



    public function logout(Request $req)
    {
        auth()->user()->tokens()->delete();

        $response = [

            "useri" => "successfully loged out",
            "status_code" => 200,
        ];

        return response($response, 200);
    }

    public function course_student()
    {
        $data = user_course::where('course', $this->course)->count();
        if ($data) {
            return $data;
        }
        return false;
    }
    
}
