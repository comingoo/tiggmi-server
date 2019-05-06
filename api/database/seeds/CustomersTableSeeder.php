<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory as Faker;



class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i =0;
        Customer::truncate();//Empty all user record
        $faker = Faker::create();
        $password = md5('123456');
        while ($i < 50) {
            $digits_needed=10;

            $random_number=''; // set up a blank string

            $count=0;

            while ( $count < $digits_needed ) {
                $random_digit = mt_rand(0, 9);
                
                $random_number .= $random_digit;
                $count++;
            }
            Customer::create([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'password' => $password,
                'mobile'=>$random_number,
                'isVerified'=>1
            ]);
            $i++;
        }
      
    }
}
