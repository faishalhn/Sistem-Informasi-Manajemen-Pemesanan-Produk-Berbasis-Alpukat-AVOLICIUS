FROM php:8.2-apache

WORKDIR /var/www/html

COPY . .

# =========================
# APPLICATION
# =========================
ENV APP_ENV=development
ENV APP_NAME=AVOLICIUS
ENV APP_URL=http://localhost

# =========================
# FRONTEND
# =========================
ENV API_URL=http://localhost/webbeta/

# =========================
# DATABASE
# =========================
ENV DB_HOST=localhost
ENV DB_PORT=3306
ENV DB_NAME=avolicius_db
ENV DB_USER=root
ENV DB_PASS=

EXPOSE 80