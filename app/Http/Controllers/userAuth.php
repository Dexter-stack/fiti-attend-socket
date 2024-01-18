<?php

namespace App\Http\Controllers;

use App\Events\TestingSocket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\course;
use App\Models\attendance;
use App\Models\user_course;
use App\FormService\user_auth;

use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;

class userAuth extends Controller
{


    public function register(Request $req)
    {

        $rules = array(
            'full_name' => 'required|string',
            "user_id" => "required|unique:users",
            'password' => ['required']

        );

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {

            return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
        } else {


            $data =  new user_auth();
            if ($data->register_user($req, $rules) == true) {
                $response = array(
                    "message" => "User successfully Registered",
                    "status" => 201
                );
            } else {
                $response = array(
                    "message" => "Error Occured",
                    "status" => 401
                );
            }
        }
        return $response;
    }



    public function login(Request $req)
    {



        $rules = array(

            "user_id" => "required|exists:users",
            'password' => 'required|string'

        );


        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {

            return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
        } else {

            $data =  new user_auth();
            return $data->login_user($req);
        }
    }


    public function clock_in(Request $req)
    {

        $data =  new user_auth();
        $rules = array(

            "course_id" => "required|string"
        );

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {

            return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
        } else {



        try {
            $data->set_course_id($req->course_id);
            if ($data->clockIn(Auth::user()->fullname,Auth::user()->user_id) == true) {
            $response = array(
                "message" => "User successfully Signed In",
                "status" => 201
            );
        } else {
            $response = array(
                "message" => "Error Occured",
                "status" => 401
            );
        }

    } catch (\Exception $e) {
        $response = array(
            "message" => $e->getMessage(),
            "status" => 401
        );
    }
}
    return $response;

    }

            


    public function clock_out(Request $req)
    {

        $data =  new user_auth();

        $rules = array(

            "course_id" => "required|string"
        );

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {

            return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
        } else {


    
          $data->set_course_id($req->course_id);
        if ($data->clockOut() == true) {
            $response = array(
                "message" => "User successfully Signed Out",
                "status" => 201
            );
        } else {
            $response = array(
                "message" => "User did not sign in today",
                "status" => 401
            );
        }
    }
        return $response;
    }

    public function single_user(){

        $data =  new user_auth();
       return $data->user_profile();

    }

    public function logout(Request $req)
    {
        auth()->user()->tokens()->delete();

        $response = [

            "user" => "successfully loged out",
            "status_code" => 200,
        ];

        return response()->json( $response);
    }


    public function user_course(){

        $courses = user_course::where('user_id',Auth::user()->id);
        return response()->json( ['message' => ($courses->get()->isEmpty()) ? "courses are not available" : $courses->get(),
    
        "status_code"=>201
]);


    }

    public function courses(){
        $courses = course::all();


        return response()->json( ['message' => ($courses->isEmpty()) ? "courses are not available" : $courses,
    
             "status_code"=>201
    ]);


    }
    public function add_course($course_id){

       
           
            $data = new user_course();
            $get_course = course::find($course_id);
            $data->course = $get_course->name;
            $data->user_id = Auth::user()->id;
            if ($data->save()) {

                $response = [

                    "user" => "course registered successfully",
                    "status_code" => 200,
                ];
        
                
            }else{
                $response = [

                    "user" => "something went wrong",
                    "status_code" => 401,
                ];
        
            }


        return $response;


        



    }





    public function fetch_attendance($course_id){
        $attendance = attendance::where(['user_id'=>Auth::user()->id, 'course_id'=>$course_id])->get();
        return response($attendance);
        //$attendance = attendance::where(['user_id'=>Auth::user()->id, 'course_id'=>$course_id])->count();


        //return json_encode(['message' => ($attendance->get()->isEmpty()) ? "attendance is empty" : $attendance]);

    }



    public function fetch_present_attendance($course_id){
        // $attendance = attendance::where(['user_id'=>Auth::user()->id, 'course_id'=>$course_id])->whereDay('created_at',now()->day)->get();
        $attendance = attendance::where(['user_id'=>Auth::user()->id, 'course_id'=>$course_id])->whereDate('created_at',now())->get();

        return response()->json( $attendance);

        //return json_encode(['message' => ($attendance->get()->isEmpty()) ? "attendance is empty" : $attendance]);

    }


    public function get_course_duration($course_id){

        $duration = new user_auth();
        $duration->set_course_id($course_id);

        if($duration->get_course_duration()==true){


            $response = [

                "message" => $duration->get_course_duration()->duration,
                "status_code" => 200,
            ];
        }else{

            $response = [

                "message" => "something went wrong",
                "status_code" => 401,
            ];

        }

        return response()->json($response);
        



    }


    public function testSocket(Request $request){
        event(new TestingSocket($request->message,$request->type));
        dd("dexter"); 
        return response( array("status"=>200),200);
    }









}
