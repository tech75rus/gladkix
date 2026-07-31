FROM dunglas/frankenphp:1.12-php8.4-bookworm

RUN install-php-extensions \
    intl \
    pcntl \
    redis \
    imagick \
    zip \
    pdo_pgsql 

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка рабочей директории
WORKDIR /app


RUN adduser --system --uid 1000 --group backend && \
    chown -R backend:backend /app && \
    mkdir -p /data/caddy && chown -R backend:backend /data

USER backend
