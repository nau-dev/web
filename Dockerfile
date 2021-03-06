FROM gitlab.toavalon.com:5000/omnisynapse/php-postgres-mysql:latest
MAINTAINER iLyK Necromancer <necromancer@toavalon.com>

COPY uploads.ini /usr/local/etc/php/conf.d/
WORKDIR /app
COPY . /app

#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN composer install

CMD php artisan config:clear &&\
    php artisan optimize &&\
    php artisan config:cache &&\
    php artisan migrate --force &&\
    nohup php artisan queue:work --sleep=3 --tries=10 --daemon &\
    php artisan serve --host=0.0.0.0 --port=8181

EXPOSE 8181
