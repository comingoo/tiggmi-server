<?php
namespace App\Repositories;

use App\Models\Customer;


class CustomerRepository
{
    

    public function getCustomer($mobile)
    {
        return Customer::Where('mobileno', $mobile)->first();
    }
     //Edit Profile of Customer by Admin
     public function update(array $data)
     {
         $Customer = Customer::find($data['id']);
         $Customer->id = $data['id'];
         $Customer->name = $data['name'] ;
         $Customer->mobile = $data['mobile'] ;        
         $Customer->email = $data['email'] ;   
             
         if($Customer->save())  {
            $success['resposnse']  = 'Success';
            $sucess['message']= 'Profile Updated';
            $sucess['responseCode']= 200;
            return $sucess ;
        }  
        return ['resposnse' => 'Error','message'=> 'Profile Not found', 'responseCode'=> 404];
     }
     //Edit Profile of Customer by Customer Itself
    public function updateProfile($id, array $data){
        $Customer = Customer::find($id);
        $Customer->name = $data['name'] ;
        $Customer->mobile = $data['mobile'] ;        
        $Customer->email = $data['email'] ;   
       
        if($Customer->save())  {
            $success['resposnse']  = 'Success';
            $sucess['message']= 'Profile Updated';
            $sucess['responseCode']= 200;
            return $sucess ;
        }  
        return ['resposnse' => 'Error','message'=> 'Profile Not found', 'responseCode'=> 404];
          
    }
    public function create(array $data)
    {
        $Customer = new Customer();
        $Customer->fill($data);
        $Customer->save();
        return $Customer;
    }
    public function delete($id)
    {
        $Customer = Customer::find($id); 
        if($Customer->id ){
             $Customer->delete();
             return ['success'=>'Customer Account Deleted','Code'=>200] ;
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