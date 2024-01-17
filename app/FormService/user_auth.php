<?php

namespace App\FormService;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\admin;
use App\Models\User;
use App\Models\attendance;
use App\Models\course;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;

class user_auth
{

    private $course_id;

    public function set_course_id($course_id){
        $this->course_id = $course_id;
    }





    public function register_user(Request $req)
    {




        $data = new User();
        $data->fullname = $req->full_name;
        $data->user_id = $req->user_id;
        //$data->course = $req->course;
        $data->password = Hash::make($req->password);


        if ($data->save()) {


            return true;
        }

        return false;
    }


    public function login_user(Request $req ){



        // $validator = Validator::make($req->all(), $rules);
        // if ($validator->fails()) {

        //     return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
        // } else {
            if (!Auth::attempt($req->only('user_id', 'password'))) {

                $response = [

                    'message' => "incorrect password",
                    "status_code" => 401
                ];
            } else {
                $user = User::where('user_id', $req->user_id)->first();

                //create personal access token
                $token = $user->createToken('mytoken',['user'])->plainTextToken;
                $response = [

                    "useri" => Auth::user(),
                    'token' => $token,
                    "status_code" => 200,



                ];
            }
            return response($response);
       // }
    }



    public function clockIn($name,$user_id)
    {




        $data = new attendance();
        $fullname =explode(" ", $name);
        $data->surname = $fullname[0];
        $data->firstname = $fullname[1];
        $data->user_id = Auth::user()->id;
        $data->course_id =$this->course_id;
        $data->signIn_time = date("h:i:sa");
        

        if ($data->save()) {


            return true;
        }

        return false;
    }


    public function clockOut(){

       
         $parameter = ['user_id'=>Auth::user()->id, 'course_id'=>$this->course_id];
        $data = attendance::where($parameter)->whereDay('created_at',now()->day);
        //$data->signOut_time = date("h:i:sa");
        $time =  date("h:i:sa");
        
        //update attend_no number 
        $attend_no = 0;
        $user = DB::table('users')->where('id', Auth::user()->id)->first();
        $attend_no = $user->attend_no +1;

        $data_update = DB::table('users')
            ->where('id', Auth::user()->id)
            ->update(['attend_no' => $attend_no]);

            
            $res = $data->update(['signOut_time' => $time]);

        if ($res) {
           return true;
        } 
        return false;



    }

    public function user_profile(){

            $data = User::find(Auth::user()->id);
            if ($data) {
                $response = [
                    "message" => $data,
                    "status_code" => 200
                ];
            } else {
    
                $response = [
                    "message" => "user does not exist",
                    "status_code" => 404
                ];
            }
            return $response;
        }

        public function get_course_duration(){
            $duration = course::find($this->course_id);
          return $duration;
            
        }



    



















   

    
    }


    

