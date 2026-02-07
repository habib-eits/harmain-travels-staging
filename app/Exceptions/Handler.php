<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

// if csrf token expired it give 419 expired error. to avoid  this use this code
use Illuminate\Session\TokenMismatchException;
// end csrf token expired code
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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

    // if csrf token expired it give 419 expired error. to avoid  this use this code
    public function render($request, Throwable $exception)
    {
    if ($exception instanceof TokenMismatchException) {
        return redirect('Logout')->with('error', 'Your session expired, please try again.')->with('class', 'success');

    }else if ($exception instanceof MethodNotAllowedHttpException) {
        return redirect('/Dashboard')->with('error', 'Invalid request method.');
    }

    return parent::render($request, $exception);
   
 
    
    
    
    
    
     
        
     }
    
}