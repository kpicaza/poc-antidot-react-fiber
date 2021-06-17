Reactive Antidot Framework
=================

* PHP >=8.1.0alpha1
* [ReactPHP](https://github.com/reactphp)
* [Antidot Framework](https://github.com/antidot-framework)
* [Drift DBAL](https://github.com/driftphp/reactphp-dbal)
* [Pheature Flags](https://github.com/pheature-flags/pheature-flags)
* [PostgreSQL](https://www.postgresql.org/)

## Installation

Install a project using [composer](https://getcomposer.org/download/) package manager:

````bash
git clone git@github.com:kpicaza/poc-antidot-react-fiber.git dev
mv dev/.* dev/* ./ && rmdir dev
composer install --ignore-platform-reqs
docker-compose up --build
````

Open your browser at `http://127.0.0.1:5555`

### Routes

* Get evaluated list of feature toggles => **/features**
* Get an evaluated feature flags => **/features/{feature_id}**

## Config

### Server Config

Default config

```yaml
parameters:
    server:
      host: '0.0.0.0'
      port: '5555'
      max_concurrency: 512
      buffer_size: 256
      workers: 4

```

### Development Mode

To run it in dev mode you can run `config:development-mode` command

````bash
bin/console config:development-mode
````

Or you can do it by hand renaming from `config/services/dependencies.dev.yaml.dist` to `config/services/dependencies.dev.yaml`

````bash
mv config/services/dependencies.dev.yaml.dist config/services/dependencies.dev.yaml
````
