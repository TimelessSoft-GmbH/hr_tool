FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libgd-dev \
    libzip-dev \
    zip \
    unzip \
    ffmpeg 

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set up gd extention for PHP
RUN docker-php-ext-configure gd \ 
    --with-freetype=/usr/include/ \ 
    --with-jpeg=/usr/include/

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Move installation script inside container
COPY run.sh /data/run.sh
RUN chown -R $user /data

# Set working directory
WORKDIR /var/www
USER $user

# Make the installation script executable for the system user
RUN chmod +x /data/run.sh

ENTRYPOINT ["/data/run.sh"]
CMD ["php-fpm"]