<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminAuth;
use App\Http\Controllers\userAuth;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});





Route::group(['prefix' => 'user'], function () {
    Route::post('login', [userAuth::class, 'login']);
    Route::post('test', [userAuth::class, 'testSocket']);
    Route::post('register', [userAuth::class, 'register']);
    Route::get('course_duration/{course_id}',[userAuth::class,'get_course_duration']);
    
    Route::group(['middleware' => ['auth:sanctum','ability:user']], function () {
       
        Route::post('clock_in', [userAuth::class, 'clock_in'])->middleware("throttle:clock_in");
        Route::put('clock_out', [userAuth::class, 'clock_out']);
        Route::get('profile', [userAuth::class, 'single_user']);
       // Route::get('user_course', [userAuth::class, 'user_courses']);
        Route::post('logout', [userAuth::class, 'logout']);
        Route::get('user_course',[userAuth::class,'user_course']);
        Route::get('courses',[userAuth::class,'courses']);
        Route::get('fetch_attendance/{course_id}',[userAuth::class,'fetch_attendance']);
        Route::get('fetch_present_attendance/{course_id}',[userAuth::class,'fetch_present_attendance']);

        Route::post('add_course/{course_id}',[userAuth::class,'add_course']);


    });
});

Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [adminAuth::class, 'login']);
    Route::post('register', [adminAuth::class, 'register_admin']);
    Route::post('fetch_attendance', [adminAuth::class, 'fetch_attendance']);
    Route::get('course_student/{course}', [adminAuth::class, 'course_student']);
    Route::get('get_users', [adminAuth::class, 'get_users']);
    Route::get('student_course/{student_id}', [adminAuth::class, 'student_course']);

    


    

//Route::group(['middleware' => ['auth:sanctum','ability:admin']], function () {
    


    

       
    });
//});
