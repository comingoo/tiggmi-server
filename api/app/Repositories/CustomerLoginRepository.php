<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Customer;
use App\Models\Token;

use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use Carbon;


class CustomerLoginRepository
{
    
   
    public function getUser($name,$password)
    {
       // $password = password_hash($password, PASSWORD_BCRYPT);
        $password = md5($data['password']);
        return Customer::Where('email', $name)->where('password',$password)->first();
    }

    public function create(array $data)
    {
        $user = new Customer();

      //  $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['password'] = md5($data['password']);
        $user->fill($data);
        $user->save();

        return $user;
    }
    
    public function postLogout($token){
        $user = Customer::where('remember_token','=' ,$token)->first();

            if( $user && $user->remember_token == $token ){
                $user->remember_token ='';
				$user->save();
                Auth::logout();
                $success['access_token'] =  $user->remember_token;
                return $success;
            }
            return null;
    }
    /**
     * 
     * Customer Login handle
     * Send OTP to mail/mobile
     * @parameter array(mobile,password)
     * @return response
     * 
     */
    public function postLogin(array $data){
            //Get the user
            $user = Customer::where('mobile', $data['mobile'])->first();

            if( $user && $user->password == md5($data['password']) )
                {
                    Token::where('customer_id', $user->id)->delete();  
                    $token = Token::create([
                        'customer_id' => $user->id
                    ]);
                    if ($token->sendCode()) {
                        $success['success']= array( 
                                                'status'=> "code sent",
                                                'return'=>true,
                                                "token_id"=> $token->id,
                                                "customer_id"=> $user->id,
                                                'OTP'=>$token->code,
                                                'user_details'=>$user                           
                                            );
                        return $success;   
                    }
                    $token->delete();// delete token because it can't be sent
                    $success['Failure']= array( 
                         'return'=>false,
                         'OTP'=>null,
                         'user_details'=>$user,
                         'error'=> "Unable to send verification code"
                    );
                                                          
                    return $success;
                }  
                    
    }

    public static function makeRandomTokenKey($length = 40)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijk@@3$%^^SJKDFHGSHJGHJDHFDHFGHJDHJDFHJDFDH';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

     /**
     * 
     * Customer Login handle by OTP
     * Send OTP to mail/mobile
     * @parameter array(mobile,password)
     * @return response
     * 
     */
    public function VerifyOTP(array $data){
        //Get the user
        $user = Customer::where('mobile', $data['mobile'])->first();
     
        if( $user && $user->password == md5($data['password']))
            {
                $token = Token::where('customer_id' , $user->id)->where('code',$data['OTP'])->first();
                $from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',$token->created_at);
                $curr_date = Carbon\Carbon::now();
                $to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i',  $curr_date);                
                
                $diff_in_minutes = $to->diffInMinutes($from);
                if (($token->used) == 1) {
                    $success['Failure']= array( 
                        'return'=>false,
                        'error'=> "OTP already used ",
                        'successcode' =>402
                   );
                                                         
                   return $success;
                }
                elseif($diff_in_minutes > 15){
                   
                    $success['Failure']= array( 
                        'return'=>false,
                        'error'=> "OTP Expired ,Please try again",
                        'expireBefore'=>$diff_in_minutes ." minutes",
                        'successcode' =>401
                    );
                                                         
                   return $success;
                } 
                else{
                    $token->used = 1;
                    $token->save();
                    Auth::login($user);
                    $token_key = self::makeRandomTokenKey(40);
                    $user->remember_token = $token_key ;
                    $user->save();
                    $success['success']= array( 
                                                'return'=>true,
                                                'token'=>$token_key,
                                                'user_details'=>$user,
                                                'success'=> "OTP Verified",
                                                'successcode' =>200
                                            );
                    return $success;
                }
                
            }  
                
    }
}