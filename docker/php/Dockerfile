# --- Stage 1: Build Frontend Assets ---
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# --- Stage 2: Runtime PHP ---
FROM php:8.3-fpm AS runtime

# Arguments for user management
ARG user=channelmarket
ARG uid=1001

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    libicu-dev \
    libonig-dev \
    libfcgi-bin \
    default-mysql-client \
    acl \
    netcat-traditional

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# OPcache Configuration
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Copy compiled assets from Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# Create system user
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user \
    && chown -R $user:$user /var/www

# Copy entrypoint script and fix permissions
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

USER $user

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]
