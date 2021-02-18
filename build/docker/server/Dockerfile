ARG NGINX_VERSION="1.19.7-alpine"
FROM "nginx:${NGINX_VERSION}" AS development
MAINTAINER Zan Baldwin "hello@zanbaldwin.com"

# Compiles a statically-linked version of the init (PID 1) process bundled with Docker since v1.13 (API v1.25).
# Alpine uses musl instead of glibc, so it's best not to use the dynamically-linked version.
# See https://github.com/krallin/tini/issues/8#issuecomment-146135930
RUN    apk add --no-cache --virtual .tini-build-deps cmake gcc git libc-dev make \
    && git clone 'git://github.com/krallin/tini.git' '/tmp/tini' \
    && ( \
        cd '/tmp/tini'; \
        git checkout "$(git describe --tags)"; \
        export CFLAGS='-DPR_SET_CHILD_SUBREAPER=36 -DPR_GET_CHILD_SUBREAPER=37'; \
        cmake . && make; \
    ) \
    && mv '/tmp/tini/tini-static' '/tini' && chmod +x '/tini' \
    && rm -rf '/tmp/tini' \
    && apk del '.tini-build-deps'
ENTRYPOINT [ "/tini", "--" ]
CMD [ "nginx", "-g", "daemon off;" ]
WORKDIR "/srv/public"
EXPOSE 80

RUN    rm -rf "/etc/nginx/conf.d" \
    && mkdir -p "/etc/nginx/conf.d" \
    && mkdir -p "/srv/public" \
    && [ -e "/srv/public/index.php" ] || touch "/srv/public/index.php"

COPY "./build/docker/server/server.conf" "/etc/nginx/conf.d/server.conf"

FROM "development" AS production
COPY "./public" "/srv/public"
