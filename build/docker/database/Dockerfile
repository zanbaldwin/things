ARG MYSQL_VERSION="8.0.23"

FROM "mysql:${MYSQL_VERSION}" AS development
MAINTAINER Zan Baldwin "hello@zanbaldwin.com"

RUN { \
        echo "[mysqld]"; \
        echo "# Required for PHP to connect to MySQL 8."; \
        echo "default-authentication-plugin=mysql_native_password"; \
        echo "default-time-zone='+00:00'"; \
    } > "/etc/mysql/conf.d/php.cnf"

FROM "development" AS production
