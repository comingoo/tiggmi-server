<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /* Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
           // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('isVerified')->default(0) ;
            $table->integer('is_admin');         
            $table->rememberToken();            
            $table->timestamps();
        });
        */
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('isVerified')->default(0);
            $table->date('dob');
            $table->enum('gender', ['non-binary', 'male', 'female']);
            $table->integer('is_admin');         
            $table->rememberToken();  
            $table->timestamps();
            $table->string('verification_token')->unique()->nullable();
            $table->timestamp('last_login_time')->nullable();
            $table->string('last_login_ip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
