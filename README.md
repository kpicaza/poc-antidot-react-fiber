Reactive Antidot Framework
=================

[![link-packagist](https://img.shields.io/packagist/v/antidot-fw/reactive-starter.svg?style=flat-square)](https://packagist.org/packages/antidot-fw/reactive-starter)

This framework is based on concepts and components of other open source software, especially 
[Zend Expressive](https://docs.zendframework.com/zend-expressive/), 
[Zend Stratigillity](https://docs.zendframework.com/zend-stratigility/), 
[Recoil](https://github.com/recoilphp/recoil) and [React PHP](https://reactphp.org/).

## Installation

Install a project using [composer](https://getcomposer.org/download/) package manager:

````bash
git clone git@github.com:kpicaza/poc-antidot-react-fiber.git dev
mv dev/.* dev/* ./ && rmdir dev
php public/index.php
````

Open your browser on port `8080`

## Config

### Server Config

Default config

```yaml
parameters:
    server:
      host: '0.0.0.0'
      port: '8080'
      max_concurrency: 100
      buffer_size: 4194304
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

### Run server

````bash
php public/index.php
````
