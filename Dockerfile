FROM hyperf/hyperf:8.3-alpine-v3.19-swoole

ARG timezone=America/Sao_Paulo
ARG UID=1000
ARG GID=1000

ENV TIMEZONE=${timezone} \
    APP_ENV=dev \
    SCAN_CACHEABLE=false

RUN addgroup -g $GID appgroup && \
    adduser -D -u $UID -G appgroup appuser

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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer

RUN chown -R appuser:appgroup /opt/www


RUN mkdir -p /opt/www/runtime/container/proxy \
    && chown -R appuser:appgroup /opt/www/runtime

USER appuser

RUN composer install

EXPOSE 9501

CMD ["tail", "-f", "/dev/null"]
#ENTRYPOINT ["php", "/opt/www/bin/hyperf.php", "start"]
