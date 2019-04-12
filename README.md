# tiggmi-server
Server for Tiggmi



rest_api

Quick Start-

Requirements-

    Laravel 5.8 (and it's requirements such as PHP ver.>5.7) =>
    Composer to install various packages (composer v2.0.2 or >2) => https://getcomposer.org/download/

Steps-


    Go to "api" directory

    run "composer install" to install all the project dependencies

    rund command "copy .env.example .env" , this will make local .env file

    run "php artisan key:generate" to generate the application key.

    Configure database
    a. Open .env file and check db configs, namely 5 below. Ensure correct values for the configs

     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=<your database name>
     DB_USERNAME=<your db user name>
     DB_PASSWORD=<replace this with the correct password>

    b. Create schema "<your database name>" 
    c. Install database  run command "php artisan migrate"
    d .command to get all dummy data into table. run "php artisan db:seed" 
    e."php artisan serve" command and a local server will start at 127.0.0.1:8000

    configure :
      APP_URL = "<YOUR Server Url>"
      
      Configure : 
      MAIL_USERNAME =<Your smtp email>
      MAIL_PASSWORD = <YOUR SMTP Password>
