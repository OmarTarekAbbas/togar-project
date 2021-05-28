## Installation

Clone Repository

`git clone https://gitlab.com/OmarTarekAbbass/togar-project.git`

Move to the newly created directory

`cd togar-project`

Create a new .env file from .env.example

`cp .env.example .env`

Now edit your .env file and set your env parameters (Specially the database username/pass, database name)

Install dependencies

`composer install`

Generate a new key for your app

`php artisan key:generate`

Reload Database

`php artisan migrate:refresh --seed`

Done, You're ready to go

`php artisan serve`

Please test the API

`http://127.0.0.1:8000/api/inventories`

make add parameter product_name and vendor_name price sort for url

`http://127.0.0.1:8000/api/inventories?product_name=Garfield&vendor_name=Matt&price=363.96&sort=price,asc`

make unit test api for laravel 

`php artisan test`
