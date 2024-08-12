## TCOM BOOKING

Setup steps:

- Clone source
- Rename .env.example -> .env
- Rename .env.testing.example -> .env.testing
- MySQL create 2 database: booking and booking-test
- Composer install
- Run php artisan migrate
- Run php artisan db:seed
- Run php artisan serve

In Booking database has 10 users and 10 rooms.
- Users
    - Email template: user<X>@localhost.com (X increase from 1 to 10)
        - Example: user1@localhost.com, user2@localhost.com
    - Password is "password"