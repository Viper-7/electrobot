FROM php:7.4-cli
COPY . /opt/electrobot
WORKDIR /opt/electrobot
RUN apt update && apt install -y units libbz2-dev sqlite3 libsqlite3-dev libssl-dev libcurl4-openssl-dev libjpeg-dev
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install curl
RUN docker-php-ext-install sockets


HEALTHCHECK CMD exit $(cat /var/run/electrobot.status)

CMD [ "php", "./startBot.php" ]

