<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Customer;
use App\Models\Token;

class CustomerMiddleware
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
         if ($request->route()->named('customer.login') || $request->route()->named('mobile.verification')) {
           return $next($request);            
        }
        else if($request->route()->named('customer.verifyotp')) {
            $user = Customer::where('mobile', $request->mobile)->first();      
            if( $user && $user->password == md5($request->password))
            {
                return $next($request);
            }
            $failed['Failure']= array( 
                'return'=>false,
                'error'=> "Invalid Credentials",
                'successcode' =>401
            );
            return response()->json($failed,401);
        
            
        }
        else if($request->route()->named('customer.profile') || $request->route()->named('customer.passwordChange') || $request->route()->named('customer_profile.edit')  || $request->route()->named('customer.logout'))
        {
            $token = $request->bearerToken();

            if (!empty($token)) {
                return $next($request);
            }
            return  response()->json(['failure' => [
                'return' => false,
                'message' => 'Invalid Token access',
                'error' => 'Unauthorized Access', ],
                ], 422);
        }
        else{
            return response()->json(['Failure'=>[ 'return'=>false,'token'=>null,'user_details'=>null,'error'=>'Invalid Login Credentials']], 401);;
        }
    }
}
