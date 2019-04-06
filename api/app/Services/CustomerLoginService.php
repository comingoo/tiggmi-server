<?php
namespace App\Services;

use App\Repositories\CustomerLoginRepository as LoginRepository;

class CustomerLoginService
{
    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    /**
     *Customer Login
     *Check if user is registered or not
     */

    public function findUser($name,$password)
    {
       
        $userExists = $this->loginRepository->getUser($name,$password);

        if ($userExists) {
            return $userExists;
        }

       return null ;
    }
    public function postLogin(array $array){
       return $this->loginRepository->postLogin($array);
    }
    
    public function postLogout($token){
        return $this->loginRepository->postLogout($token);
    }
    /**
     * 
     * VerifyOTP Function
     * @parameter mobile ,password,OTP
     * @return response
     */
    public function VerifyOTP(array $array){
        return $this->loginRepository->VerifyOTP($array);
    }
    /**
     * 
     * If user loggedin update log_status
     *@attribute(id)
     * 
     */
    public function updateLogStatus($id,$logstatus){
       return $this->loginRepository->updateLogStatus($id,$logstatus);
    }
}

