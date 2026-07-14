FROM php:8.2-apache

WORKDIR /var/www/html

COPY . .

# =====================
# FRONTEND
# =====================

ENV API_URL=http://localhost/web_beta/admin/

# =====================
# BACKEND
# =====================

ENV DB_HOST=localhost
ENV DB_PORT=3306
ENV DB_NAME=avolicius_db
ENV DB_USER=root
ENV DB_PASS=

EXPOSE 80