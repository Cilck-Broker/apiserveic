# === PHP Stage ===
FROM php:8.2-apache-buster
# FROM php:7.3-apache

ARG env
ENV ACCEPT_EULA=Y

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    gnupg \
    libxml2-dev \
    wget \
    supervisor
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get upgrade -y

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN CFLAGS="-I/usr/src/php"
RUN docker-php-ext-install gd xml iconv simplexml
RUN docker-php-ext-enable xml iconv simplexml gd

# Install necessary extensions and libraries
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install MSSQL PDO
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - && \
    curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list
RUN apt-get update && apt-get -y --no-install-recommends install -y msodbcsql17 unixodbc-dev
RUN pecl install sqlsrv pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv
RUN docker-php-ext-install zip exif
RUN docker-php-ext-enable exif

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
EXPOSE 8080

# === Run Stage ===
WORKDIR /var/www
COPY . /var/www/
RUN touch /var/www/.env

# Add the following two lines to set DocumentRoot and ServerName
RUN echo "DocumentRoot /var/www/public" >> /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN echo "<Directory /var/www/>" >> /etc/apache2/apache2.conf && \
    echo "Options Indexes FollowSymLinks" >> /etc/apache2/apache2.conf && \
    echo "AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "Require all granted" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf

RUN chmod 777 -R /var/www/storage/ && \
  echo "Listen 8080">>/etc/apache2/ports.conf && \
  chown -R www-data:www-data /var/www/ && \
  a2enmod rewrite

RUN composer install
RUN composer update && composer dumpautoload
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan cache:clear
RUN php artisan optimize:clear
#RUN php artisan test --env=testing

ADD conf/supervisord.conf /etc/supervisord.conf
ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]