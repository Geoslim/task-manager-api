
## Task Manager API

### Prerequisites

- PHP v8.1
- Follow this [link](https://laravel.com/docs/10.x/installation#getting-started-on-macos) for basic laravel setup with sail

### Start up

To start project, perform the following steps in the order

- Clone the repository by running the command
- git clone 'https://github.com/Geoslim/task-manager-api.git'
- cd task-manager-api
- Run composer install
- Run 'cp .env.example .env'
- Fill your configuration settings in the '.env' file you created above
- Turn on Docker Desktop
- Run './vendor/bin/sail up' to start up the application
- Run 'php artisan key:generate'
- Run 'php artisan migrate --seed'

