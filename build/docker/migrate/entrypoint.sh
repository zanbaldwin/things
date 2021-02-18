#!/bin/bash

function retry {
    local MAX_ATTEMPTS=$1
    shift

    local COUNT=${MAX_ATTEMPTS}
    while [ ${COUNT} -gt 0 ]; do
        eval "$*" && break
        COUNT=$((${COUNT} - 1))
        sleep 5
    done

    [ ${COUNT} -eq 0 ] && {
        echo >&2 "Retry failed [${MAX_ATTEMPTS}]: $*"
        exit 1;
    }
    return 0
}

function migrate {
    if [ $? -ne 0 ]; then
        echo >&2 "Database URL is not valid."
        exit 1;
    fi
    echo "Parsing DSN: ${1}"
    DB_ENV_SCRIPT="$(dbenv "${1}" 2>/dev/null)"
    echo "Extracted the following variables:"
    echo "${DB_ENV_SCRIPT}"
    echo ""
    eval "${DB_ENV_SCRIPT}"
    # Retry every 5 seconds for a maximum of 18 times (90 seconds total). If MySQL
    # isn't up by then you should probably investigate.
    retry 18 "mysql --user=\"${DB_USER}\" --password=\"${DB_PASS}\" --host=\"${DB_HOST}\" --port=\"${DB_PORT}\" --database=\"${DB_NAME}\" --execute=\"SELECT 1\" >/dev/null" \
        && /sbin/migrate -path "/migrations" -database "mysql://${DB_USER}:${DB_PASS}@tcp(${DB_HOST}:${DB_PORT})/${DB_NAME}" up
}

[ "$DATABASE_URL" != "" ] || { echo >&2 "Database URL not specified in environment variables."; exit 1; } && migrate "${DATABASE_URL}"
