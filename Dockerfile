FROM php:8.2-apache

# ==================================================
# Install PHP Extensions
# ==================================================
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ==================================================
# Working Directory
# ==================================================
WORKDIR /var/www/html

# ==================================================
# Copy Project
# ==================================================
COPY . .

# ==================================================
# Frontend Environment
# ==================================================
ENV API_URL=http://localhost/web_beta/admin/

# ==================================================
# Backend Environment
# ==================================================
ENV DB_HOST=localhost
ENV DB_PORT=3306
ENV DB_NAME=avolicius_db
ENV DB_USER=root
ENV DB_PASS=

# ==================================================
# Permission
# ==================================================
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80