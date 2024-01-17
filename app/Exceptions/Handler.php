<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\VarDumper\Exception\ThrowingCasterException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof MissingAbilityException) {
            $abilities = $e->abilities();

            // if ($request->routeIs("") && $abilities[0]==="xxx") {
            //     # code...
            // }
            
           {
                return response()->json([

                    "message"=>"Unauthenticated user",
                ], 406);
            }
        }

        if($e instanceof ThrowingCasterException){

            {
            return response()->json([

                "message"=>"you can only clock in once in a day",
            ], 406);
        }
            
        }
        return parent::render($request,$e);
    }
}
