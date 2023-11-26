# Laravel-api setup with steps.(latest branch is 'api').
1. download project.
2. run on git bash(install git bash--install composer--install xampp) 'composer require' or 'composer dump-autoload' or 'composer update'.
3. create file '.env' with db password and username(take the one is on the demo server because has jwt options done) and add to project.
4. import to xampp(phpadmin) the database(export the one on demo server(the eml one project)) with your chosen username and password.
5. know your the exact url the project is listening, example :http://localhost:8000/carrentalApi/public .

# Project setup

The project is build on Laravel 6, and it utilizes [Homestead](https://laravel.com/docs/6.x/homestead). A basic knowledge of [Vagrant](https://www.vagrantup.com/) is required. 

After cloning the repository, you'll first have to install dependencies: 

    $ composer install 
    
and then bring up Homestead with: 
       
    $ vagrant up
    
You'll find Homestead's configuration in `./Homestead.yaml`

You will also have to update your `hosts` file to point the domain `carrental.test` to the virtual machines's IP:

    192.168.10.10 carrental.test
    
Finally, you'll need a `.env` for local configuration. Here's an example: 

    APP_NAME=CarRental
    APP_ENV=local
    APP_KEY=base64:I97BpTrCtIXTQdbnwZ0EpOH8Vh1FPWFzR3tiCInqaQM=
    APP_DEBUG=true
    APP_URL=http://carrental.test
    
    LOG_CHANNEL=stack
    
    DB_CONNECTION=mysql
    DB_HOST=192.168.10.10
    DB_PORT=3306
    DB_DATABASE=carrental
    DB_USERNAME=homestead
    DB_PASSWORD=secret
    
    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=file
    SESSION_LIFETIME=120
    
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    
    MAIL_DRIVER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    
    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=mt1
    
    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Database setup

To run the database migrations, use: 

    ./artisan migrate
    
To refresh all database migrations (which will erase all data), use:

    ./artisan migrate
    
To seed the database with demo data, use: 

    ./artisan db:seed
    
To refresh all database migrations and seed with demo data, use:
 
    ./artisan migrate:refresh --seed       
