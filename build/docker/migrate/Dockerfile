ARG GO_VERSION="1.15.6-alpine3.12"
ARG ALPINE_VERSION="3.12"

FROM "golang:${GO_VERSION}" AS builder
MAINTAINER Zan Baldwin "hello@zanbaldwin.com"

COPY "./build/docker/migrate/dbenv.go" "/go/src/github.com/zanbaldwin/dbenv/main.go"
RUN    apk add --no-cache --virtual .migrate-build-deps git \
    && go get -u -d \
            "github.com/golang-migrate/migrate/cli" \
            "github.com/go-sql-driver/mysql" \
            "github.com/docker/machine/libmachine/shell" \
    && go build -tags "mysql" -o "/sbin/migrate" "github.com/golang-migrate/migrate/cli" \
    && go build -o "/sbin/dbenv" "github.com/zanbaldwin/dbenv" \
    && apk del .migrate-build-deps

FROM "alpine:${ALPINE_VERSION}" AS development
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
CMD [ "bash", "/entrypoint.sh" ]
RUN apk add --no-cache mysql-client bash
COPY --from="builder" "/sbin/migrate" "/sbin/migrate"
COPY --from="builder" "/sbin/dbenv" "/sbin/dbenv"
COPY "./build/docker/migrate/entrypoint.sh" "/entrypoint.sh"

FROM development AS production
COPY "./migrations" "/migrations"
