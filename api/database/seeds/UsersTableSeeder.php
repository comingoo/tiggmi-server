<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::truncate();//Empty all user record
        $faker = Faker::create();
        $password = md5('123456');
      
        User::create([
            'name' => 'Shesh',
            'email' => 'mishrakshesh14287@gmail.com',
            'password' => $password,
          //  'mobile'=>'8318198224',
            'isVerified'=>1,
            'is_admin'=>1
        ]);
    }
}
