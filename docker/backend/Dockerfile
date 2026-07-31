FROM dunglas/frankenphp:1.12-php8.3-bookworm

RUN install-php-extensions \
    zip \
    pdo_pgsql 

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка рабочей директории
WORKDIR /app


RUN adduser --system --uid 1000 --group backend && \
    chown -R backend:backend /app && \
    mkdir -p /data/caddy && chown -R backend:backend /data

USER backend


# RUN install-php-extensions \
#     zip \
#     pdo_pgsql \
#     intl \
#     opcache \
#     curl \
#     fileinfo \
#     pcntl \
#     mbstring \
#     xml \
#     redis \
#     imagick \
#     sodium
