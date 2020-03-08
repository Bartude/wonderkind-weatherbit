## Wonderkind Test Case
Steps to test this are as follow:
1. `git clone https://github.com/Bartude/wonderkind-weatherbit`
2. `composer install`
3. `cp .env.example .env`
4. `php artisan key:generate`
5. Add Weatherbit API Key to the `WEATHERBIT_API_KEY` environment variable in `.env`
6. `php artisan serve`

