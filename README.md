## TCOM BOOKING

Setup steps:

- Clone source
- Rename .env.example -> .env
- Rename .env.testing.example -> .env.testing
- MySQL create 2 database: booking and booking-test
- Composer install
- Run php artisan migrate
- Run php artisan db:seed
