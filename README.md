# Things

A web application clone of the mac/iOS app [Things](https://culturedcode.com/things/),
by Cultured Code.

## Setup

You will need Git, [Docker](https://docs.docker.com/engine/install/ubuntu/) and
[Docker Compose](https://docs.docker.com/compose/install/) (that supports Compose
configuration versions of `3.8` and above).

1. `git clone <repo-url> <path-to-project>`
2. `cd <path-to-project>`

### Development

> It is recommended that you have a development environment that includes a PHP
> version greater than or equal to `7.2.5`. This is so you can run Composer and
> Symfony commands on your host machine.

1. Make sure you have [Composer](https://getcomposer.org) installed globally.
   If you don't want to install it globally or simply don't have the permissions
   to, the step mentioning [`bin/install-composer`](./bin/install-composer) will
   show you how to install it locally to the project.

#### Setup

> The [`bin/env`](./bin/env) script is provided to run commands within the PHP container.

1. `docker-compose build`
2. `bin/env composer install`
5. `bin/env bin/console cache:clear` (should automatically run `cache:warmup` too).
3. `docker-compose up -d`
4. Visit [`localhost:<port-number>`](http://localhost:8083) (where port number
   is defined by `WEB_PORT` in `.env`).

### Production

The following assumes you have already cloned the repository for development.

> The flag `-f docker-compose.yaml` instructs Compose to _only_ use that file
> and ignore `docker-compose.override.yaml` (which is for development only).

1. `docker-compose -f docker-compose.yaml build`
