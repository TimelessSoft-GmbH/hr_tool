#!/bin/bash

# DB configuration key => value parings
keys=(
    "DB_CONNECTION"
    "DB_HOST"
    "DB_PORT"
    "DB_DATABASE"
    "DB_USERNAME"
    "DB_PASSWORD"
)

values=(
    "mysql"
    "tlsoft_hr_mariadb"
    "3306"
    "tlsoft_hr"
    "root"
    "password"
)

# Install Laravel (folder vendor must not exist in this case)
if ! [ -d vendor ]; then

    # Install PHP dependencies (alongside Laravel)
    composer install

    # Create .env file
    cp .env.example .env

    # Generate key
    php artisan key:generate

    # Update DB configs
    count=${#keys[@]}

    for (( i=0; i<${count}; i++ )); do

        if grep -q "^${keys[i]}=" .env; then
            # Update the variable value
            sed -i "s/^${keys[i]}=.*$/${keys[i]}=${values[i]}/" .env
        else
            # Add the variable to the .env file
            echo "${keys[i]}=${values[i]}" >> .env
        fi

    done

    # Update App url
    sed -i "s/^APP_URL=.*$/APP_URL=http:\/\/localhost:8008/" .env

    # Migrate database
    php artisan migrate

    # Run seeder
    php artisan db:seed
fi

exec "$@"