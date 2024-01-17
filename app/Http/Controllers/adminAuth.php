<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\admin;
use App\Models\User;
use App\FormService\admin_auth;
use App\Models\attendance;
use App\Models\user_course;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use Exception;

class adminAuth extends Controller
{




    public function register_admin(Request $req)
    {

        $rules = array(
            'fullname' => 'required|string',
            "user_id" => "required|unique:admins",
            'password' => ['required']
            // 'password' => ['required', 'confirmed', Password::min(6)
            //     ->mixedCase()
            //     ->Letters()
            //     ->numbers()
            //     ->symbols()
            //     ->uncompromised()]

        );

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {

            return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
        } else {


            $data =  new admin_auth();
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



    public function login1(Request $req)
    {



        $rules = array(

            "user_id" => "required|exists:admins",
            'password' => 'required|string'

        );


        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {

            return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
        } else {
            if (!Auth::guard('admin')->attempt($req->only('user_id', 'password'))) {

                $response = [

                    'message' => "incorrect password",
                    "status_code" => 401
                ];
            } else {
                $user = admin::where('user_id', $req->user_id)->first();

                //create personal access token
                $token = $user->createToken('mytoken', ['admin'])->plainTextToken;
                $response = [

                    "useri" =>Auth::guard('admin')->user(),
                    'token' => $token,
                    "status_code" => 200,



                ];
            }
            return response()->json($response);
        }
    }


    public function login(Request $req)
    {

        $rules = array(

            "user_id" => "required|exists:admins",
            'password' => 'required|string'

        );

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {

            return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
        } else {

            if (!Auth::guard('admin')->attempt($req->only('user_id', 'password'))) {

                $response = [

                    'message' => "incorrect password",
                    "status_code" => 401
                ];
            } else {
                $user = admin::where('user_id', $req->user_id)->first();

                //create personal access token
                $token = $user->createToken('mytoken',['admin'])->plainTextToken;
                $response = [

                    "useri" =>Auth::guard('admin')->user(),
                    'token' => $token,
                    "status_code" => 200,



                ];
            }
            return response($response);


                }


        
    }


    public function fetch_attendance(Request $req)

    {


        $rules = array(

            "from_d" => "required|string",
            'to_d' => 'required|string'

        );
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {

            return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
        } else {

            $attendance = attendance::where('created_at','>=', $req->from_d)->where('created_at','<=', $req->to_d)->get();

        return response($attendance);


        }

        
        //return json_encode(['message' => ($attendance->get()->isEmpty()) ? "attendance is empty" : $attendance]);

    }

    public function logout(Request $req)
    {
        auth()->user()->tokens()->delete();

        $response = [

            "useri" => "successfully loged out",
            "status_code" => 200,
        ];

        return response()->json($response);
    }

    public function get_users()
    {
        $data = User::all();
        return response()->json($data);
    }

    public function course_student($course)
    {

        $data =  new admin_auth();
        $data->set_course($course);
        if ($data->course_student()) {

            $response = [

                "user" => $data->course_student(),
                "status_code" => 200,
            ];
        } else {
            $response = [

                "useri" => "No Student available for this course",
                "status_code" => 200,
            ];
        }

        return $response;
    }
    public function student_course($student_id)
    {

        $courses = user_course::where('user_id', $student_id);
        return response()->json([
            'message' => ($courses->get()->isEmpty()) ? "courses are not available" : $courses->get(),

            "status_code" => 201
        ]);
    }



}
