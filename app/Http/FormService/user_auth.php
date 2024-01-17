<?php
namespace App\Http\Controllers\FormService;
use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Models\User;
    use App\Models\admin;
    use Illuminate\Validation\Rules\Password;
    use Illuminate\Support\Facades\Hash;
    use Validator;
    use Auth;

class user_auth{
    

    
    



public function register_user($req,$rules){


    $validator = Validator::make($req->all(), $rules);
    if ($validator->fails()) {

        return response(array("message" => implode(",", $validator->errors()->all()), "status_code" => 422));
    } else {


        $data = new User;
        $data->first_name = $req->first_name;
        $data->last_name = $req->last_name;
        $data->email = $req->email;
        $data->membership = $req->membership;
        $data->password = Hash::make($req->password);

        
        if ($data->save()) {

        return true;
           
        }

        return false;
        



    }



}






}
















?>