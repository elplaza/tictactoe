FROM php:8

RUN rm /bin/sh && ln -s /bin/bash /bin/sh

# Install packages
RUN apt-get update && apt-get install -y \
	zlib1g-dev \
	libicu-dev \
	libpq-dev \
	g++ \
	zip \
	unzip \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install intl \
	&& docker-php-ext-configure pgsql \
	&& docker-php-ext-install pgsql \
	&& docker-php-ext-configure pdo \
	&& docker-php-ext-install pdo \
	&& docker-php-ext-configure pdo_pgsql \
	&& docker-php-ext-install pdo_pgsql

# Install Symfony
RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt-get update && apt-get install -y symfony-cli

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Work dir
WORKDIR /app

# Code volume
VOLUME /app

COPY entrypoint.sh /entrypoint.sh
RUN chmod a+x /entrypoint.sh
ENTRYPOINT [ "/entrypoint.sh" ]

CMD ["start"]