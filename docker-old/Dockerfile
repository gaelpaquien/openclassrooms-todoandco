# Install PHP 7.1 and Apache
FROM php:7.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Modify PHP configuration (memory_limit = -1)
RUN echo "memory_limit = -1" >> /usr/local/etc/php/php.ini

# Set sys_temp_dir
RUN echo "sys_temp_dir = /tmp" >> /usr/local/etc/php/php.ini

# Create and set permissions for tmp directory
RUN mkdir -p /tmp && chmod 777 /tmp

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer version 1.10.20
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.20

# Install New Relic Agent
# RUN curl -L https://download.newrelic.com/php_agent/release/newrelic-php5-10.11.0.3-linux.tar.gz | tar -C /tmp -zx && \
#     export NR_INSTALL_USE_CP_NOT_LN=1 && \
#     export NR_INSTALL_SILENT=1 && \
#     export NR_INSTALL_KEY="YOUR_NEW_RELIC_LICENSE_KEY" && \
#     /tmp/newrelic-php5-*/newrelic-install install && \
#     rm -rf /tmp/newrelic-php5-* /tmp/nrinstall*

# Set working directory
WORKDIR /var/www/html

# Copy application source
COPY . /var/www/html

# Permissions
RUN chmod -R 777 /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Create a new apache configuration file
RUN echo '\
<Directory /var/www/html/web>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    DirectoryIndex app.php\n\
</Directory>\n\
' > /etc/apache2/conf-available/symfony.conf

# Enable Apache symfony configuration
RUN a2enconf symfony

# Change default apache document root from /var/www/html to /var/www/html/web
RUN sed -i 's#/var/www/html#/var/www/html/web#g' /etc/apache2/sites-available/000-default.conf
