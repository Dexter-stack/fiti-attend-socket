<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip())->response(function(){
                return response(['message'=>"you have reach your limit"]);
            });
            
        });

       //this handle bruteforce on login
        // RateLimiter::for('clock_in', function (Request $request) {

        //     $key = 'clock_in'.Auth::user()->id.$request->course_id;
        //     $max = 1; //attempt
        //     $decay = 86400; //seconds
        //     if(RateLimiter::tooManyAttempts($key, $max)){
        //         return response(['message'=>"you can only clockin once for a course perday", "status"=>400]);
    
        //     }else{
        //         RateLimiter::hit($key,$decay);
        //     }

        // });


    // RateLimiter::for('login', function (Request $request) {
    //     return Limit::perMinute(2)->by($request->email ?: $request->email)->response(function(){
    //         return response(['message'=>"you have reach your login limit"]);
    //     });
        
    // });

}
}
