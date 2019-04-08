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
        $user = Customer::where('mobile', $request->mobile)->first();
        if ($request->route()->named('customer.login')){
            return $next($request);
        }
        
        else if($request->route()->named('customer.verifyotp')) {
            if( $user && $user->password == md5($request->password))
            {
                $token = Token::where('customer_id' , $user->id)->where('code',$request->OTP)->first();
                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i',$token->created_at);
                $curr_date = \Carbon\Carbon::now();
                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $curr_date);                
                
                $diff_in_minutes = $to->diffInMinutes($from);
                if($diff_in_minutes < 15){
                    return $next($request);
                }else{
                   
                        $success['Failure']= array( 
                            'return'=>false,
                            'error'=> "OTP Expired ,Please try again",
                            'expireBefore'=>$diff_in_minutes ." minutes",
                            'successcode' =>401
                        );
                                                             
                       return response()->json($success,401);
                    
                }
                
            }
            
        }
        else if($request->route()->named('customer.profile'))
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
