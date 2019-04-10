<?php
namespace App\Repositories;

use App\Models\User;
use App\Models\Customer;
use App\Models\UserAudit;

use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use Carbon;


class LoginRepository
{
    
   
    public function getUser($name,$password)
    {
       // $password = password_hash($password, PASSWORD_BCRYPT);
        $password = md5($data['password']);
        return User::Where('email', $name)->where('password',$password)->first();
    }

    public function create(array $data)
    {
        $user = new User();

      //  $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['password'] = md5($data['password']);
        $user->fill($data);
        $user->save();

        return $user;
    }
    
    public function postLogout($token){
        $user = User::where('remember_token','=' ,$token)->first();

            if( $user && $user->remember_token == $token ){
                $user->remember_token ='';
				$user->save();
                Auth::logout();
                $success['access_token'] =  $user->remember_token;
                return $success;
            }
            return null;
    }

    public function postLogin(array $data){
            //Get the user
        $user = User::where('email', $data['email'])->first();

            if( $user && $user->password == md5($data['password']) )
                {
                    
                        Auth::login($user);
                        $token_key = self::makeRandomTokenKey(40);
                        $user->remember_token = $token_key ;
                        $user->save();
                      //  $success['token'] = $token_key ;
                       // $success['user'] = $user->name;
                       $success['success']= array( 
                                            'return'=>true,
                                            'token'=>$token_key,
                                            'user_details'=>$user
                                                );
                        return $success;                                            
                       
                }  
                    
    }

    public static function makeRandomTokenKey($length = 40)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijk@@3$%^^SJKDFHGSHJGHJDHFDHFGHJDHJDFHJDFDH';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public function setLogoutAudit($id)
    {
        $userAudit = new UserAudit();
        $userAudit->setAttribute('user_id', $id);
        $userAudit->setAttribute('activity', 'Logout');
        $userAudit->setAttribute('comments', '');
        return $userAudit;
    }
    public function setLoginAudit($id)
    {
        $userAudit = new UserAudit();
        $userAudit->setAttribute('user_id', $id);
        $userAudit->setAttribute('activity', 'Login');
        $userAudit->setAttribute('comments', '');
        return $userAudit;
    }
}