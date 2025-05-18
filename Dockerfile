FROM hyperf/hyperf:8.3-alpine-v3.19-swoole

ARG timezone=America/Sao_Paulo

ENV TIMEZONE=${timezone} \
    APP_ENV=dev \
    SCAN_CACHEABLE=false

RUN set -ex \
    && php -v \
    && php -m \
    && php --ri swoole \
    && cd /etc/php*/conf.d \
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee 99-overrides.ini \
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo "${TIMEZONE}" > /etc/timezone \
    && rm -rf /var/cache/apk/* /tmp/* /usr/share/man \
    && echo -e "\033[42;37m Build Completed :).\033[0m\n"

WORKDIR /opt/www

COPY composer.json composer.lock ./

RUN composer install --no-dev -o || true

COPY . .

RUN composer install

EXPOSE 9501

ENTRYPOINT ["php", "/opt/www/bin/hyperf.php", "start"]
