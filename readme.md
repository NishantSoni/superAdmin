## Super Admin - Panel

----

### Pre requirements : 
    Docker and docker-compose must be installed on your machine. 

----

Step-1 : git clone.

Step-2 : cd laraWebApp

Step-3 : cp .env.example .env

Step-4 : Setup your email driver for sending emails. 

Step-5 : docker-compose exec docker-server composer install

Step-6 : docker-compose exec docker-server php artisan key:generate

Step-7 : docker-compose exec docker-server php artisan storage:link

Step-8 : docker-compose up -d

Step-9 : check every container is running or not by => **docker-compose ps**

Step-10 : Database migrations by => **docker-compose exec docker-server php artisan migrate**

Step-11: docker-compose exec docker-server php artisan db:seed --class=UserTableSeeder 

----

> You can access your app at **http://localhost:8000**

> You can access your Database/phpMyAdmin at **http://localhost:8383**
 
> There is no need to run **php artisan serve** because docker container is already running. 

    
#### Following features are included:  
   
    1. Laravel's defualt login, logout, forgot password, reset password.
    2. User management : listing, create, update, delete, sorting, searching, pagination.
    3. User activity is tracked in the DB while user is updating any other's profile. 
    4. Google's 2FA
    5. Backup keys, in case user lost mobile phone.  
    6. Export users in CSV format, and activity of users will also be exported in CSV as sub-row.
    7. DB seeder for inserting 10K records in database.  
     

