<?php
namespace App\Services;

use App\Repositories\LoginRepository;

class LoginService
{
    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }

    /**
     *User Login
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
     * If user loggedin update log_status
     *@attribute(id)
     * 
     */
    public function updateLogStatus($id,$logstatus){
       return $this->loginRepository->updateLogStatus($id,$logstatus);
    }
}

