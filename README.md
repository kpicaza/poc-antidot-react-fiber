Reactive Antidot Framework
=================

* PHP >=8.1.0rc6
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
docker-compose run --rm composer install
docker-compose up --build
````

Open your browser at `http://127.0.0.1:5555/features`

### Routes

* Get evaluated list of feature toggles => **/features**
* Get an evaluated feature flags => **/features/{feature_id}**

## Config

### Server Config

Default config
<span id="parameters-config"></span>

```yaml
parameters:
    server:
      host: '0.0.0.0'
      port: '5555'
      workers: 8

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

> Tested on Docker using [8 workers](#parameters-config) on Ubuntu 21.04 Memory: 32GB, Processor: Intel® Core™ i7-10875H CPU @ 2.30GHz × 16

Using WRK 8 threads and 512 connections during 15 seconds:

> No queries testing against "/"

```bash
→ wrk -t8 -c512 -d15s http://127.0.0.1:5555/                                                                                                                                                                                                         [defbbe7]
Running 15s test @ http://127.0.0.1:5555/
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    18.15ms   29.96ms 292.38ms   94.44%
    Req/Sec     5.23k     1.13k   10.32k    71.08%
  625018 requests in 15.10s, 85.24MB read
Requests/sec:  41395.44
Transfer/sec:      5.65MB
```

> Single query with "where" statement, testing against "/features/feature_1"

```bash
$ wrk -t8 -c512 -d15s http://127.0.0.1:5555/features/feature_1                                                                                                                                                                                       [7c7fb66]
Running 15s test @ http://127.0.0.1:5555/features/feature_1
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    60.51ms   27.30ms 143.04ms   71.78%
    Req/Sec     1.06k   233.54     1.54k    66.58%
  126979 requests in 15.09s, 20.47MB read
Requests/sec:   8415.24
Transfer/sec:      1.36MB
```

> Single query no "where" statement and 10 queries with "where" statement, testing against "/features"

```bash
→ wrk -t8 -c512 -d15s http://127.0.0.1:5555/features                                                                                                                                                                                                 [defbbe7]
Running 15s test @ http://127.0.0.1:5555/features
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency   311.21ms   84.88ms 589.97ms   62.81%
    Req/Sec   210.71    102.44   585.00     68.21%
  24378 requests in 15.06s, 13.16MB read
Requests/sec:   1618.26
Transfer/sec:      0.87MB
```
