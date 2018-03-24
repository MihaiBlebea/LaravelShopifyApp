## Install app
- Clone repo
- Run ``` composer install ``` to install dependencies to vendor
- Run ``` cp .env.example .env ``` to create .env file from .env.example
- Run ``` php artisan key:generate ``` to generate a new Laravel app key and save it into env file
- Run ``` php artisan storage:link ``` to link storage folder to the public folder and access files
- Manually add database credentials to .env file
- Manually change the APP_URL in the .env file
- Run ``` php artisan migrate ``` to migrate tables
- Run ``` php artisan db:seed ``` to generate dummy content for the db

#### OR

just use these 2 commands:
- Run ``` composer setup-env ```
- Manually add database credentials to .env file
- Manually change the APP_URL in the .env file
- Run ``` composer refresh ```
