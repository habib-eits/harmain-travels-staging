<?php

namespace App\Http\Middleware;

use Closure;
use Session;
 
use URL;
use DB;
class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
             if(session::get('UserID')==null)
         {
         // session::flash('error', 'Invalid Rollno or Password. Try again');
        return redirect('/')->with('error', 'Session expired')->with('class','danger');
         }
         else
         {
            return $next($request);
         }



       
    }
}


/*   if(session::get('UserType')!='Admin')
         {
         // session::flash('error', 'Invalid Rollno or Password. Try again');
        return redirect()->back()->with('error', 'Access denied!!!')->with('class','danger');
         }
         else
         {
            return $next($request);
         }


         */




         /*

         orginal


          public function handle($request, Closure $next)
    {
        
             return $next($request);



      
}

      */