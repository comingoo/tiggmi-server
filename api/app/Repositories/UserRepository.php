<?php
namespace App\Repositories;

use App\Models\User;


class UserRepository
{
    

    public function getUser($email,$mobile)
    {
        return User::Where('email_id', $email)->orWhere('mobileno', $mobile)->first();
    }
     //Edit Profile of user by Admin
     public function update(array $data)
     {
         $user = User::find($data['id']);
         $user->id = $data['id'];
         $user->name = $data['name'] ;
         $user->password = md5($data['password']);
         $user->enabled = $data['status'] ;
         $user->dob = $data['dob'] ;
         $user->gender = $data['gender'];         
         $user->mobileno = $data['mobileno'] ;        
         $user->roleId = $data['roleId'] ;   
         if(isset($data['avatar'])){
            $user->avatar = $data['avatar']; 
        }     
         if($user->save())  {
            $success['resposnse']  = 'Success';
            $sucess['message']= 'Profile Updated';
            $sucess['responseCode']= 200;
            return $sucess ;
        }  
        return ['resposnse' => 'Error','message'=> 'Profile Not found', 'responseCode'=> 404];
     }
     //Edit Profile of Admin by Admin Itself
    public function updateProfile($id, array $data){
        $user = User::find($id);
        $user->name = $data['name'] ;
       // $user->mobile = $data['mobile'] ;
        $user->email = $data['email']; 
        
        if($user->save())  {
            $success['resposnse']  = 'Success';
            $sucess['message']= 'Profile Updated';
            $sucess['responseCode']= 200;
            return $sucess ;
        }  
        return ['resposnse' => 'Error','message'=> 'Profile Not found', 'responseCode'=> 404];
          
    }
    public function create(array $data)
    {
        $user = new User();
        $user->fill($data);
        $user->save();
        return $user;
    }
    public function delete($id)
    {
        $user = User::find($id); 
        if($user->id ){
             $user->delete();
             return ['success'=>'User Account Deleted','Code'=>200] ;
        }
        else{
            return ['error'=>'You are not allowed to delete account! ','Code'=>401];
        }
       
       
    }
    public function updatePassword(array $data)
    {
        //dd($data);     
         return $this->update($data);
    }
    

}