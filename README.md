Reactive Antidot Framework
=================

* PHP >=8.1.0rc1
* [ReactPHP](https://github.com/reactphp)
* [React Fiber](https://github.com/trowski/react-fiber)
* [Antidot Framework](https://github.com/antidot-framework)
* [Drift DBAL](https://github.com/driftphp/reactphp-dbal)
* [Pheature Flags](https://github.com/pheature-flags/pheature-flags)
* [PostgreSQL](https://www.postgresql.org/)

## Installation

Install a project using [composer](https://getcomposer.org/download/) package manager:

````bash
git clone git@github.com:kpicaza/poc-antidot-react-fiber.git dev
mv dev/.* dev/* ./ && rmdir dev
docker-compose run --rm composer install --ignore-platform-reqs
docker-compose up --build
````

Open your browser at `http://127.0.0.1:5555/features`

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

### Benchmark

Using WRK:

> No queries testing against "/"

```bash
$ wrk -t8 -c512 -d15s http://127.0.0.1:5555/                                                                                                                                                                                                         [063f19b]
Running 15s test @ http://127.0.0.1:5555/
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    16.50ms    9.36ms 161.59ms   80.13%
    Req/Sec     3.88k   394.54    11.57k    79.44%
  460606 requests in 15.10s, 62.82MB read
Requests/sec:  30505.35
Transfer/sec:      4.16MB
```

> Single query no "where" statement, testing against "/features"

```bash
$ wrk -t8 -c512 -d15s http://127.0.0.1:5555/features
Running 15s test @ http://127.0.0.1:5555/features
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency   269.81ms   65.74ms 486.64ms   62.69%
    Req/Sec   236.84     87.46   555.00     71.26%
  27987 requests in 15.05s, 15.11MB read
Requests/sec:   1859.60
Transfer/sec:      1.00MB
```

> Single query with "where" statement, testing against "/features/feature_1"

```bash
$ wrk -t8 -c512 -d15s http://127.0.0.1:5555/features/feature_1
Running 15s test @ http://127.0.0.1:5555/features/feature_1
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    54.47ms   16.15ms 153.80ms   74.33%
    Req/Sec     1.18k   125.00     1.61k    70.36%
  140050 requests in 15.09s, 22.57MB read
Requests/sec:   9282.03
Transfer/sec:      1.50MB
```
