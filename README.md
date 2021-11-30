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
      max_concurrency: 512
      buffer_size: 256
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
$ wrk -t8 -c512 -d15s http://127.0.0.1:5555/                                                                                                                                                                                                         [7c7fb66]
Running 15s test @ http://127.0.0.1:5555/
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    19.76ms   11.68ms 174.07ms   96.59%
    Req/Sec     3.39k   329.48     4.03k    76.24%
  402837 requests in 15.06s, 54.94MB read
Requests/sec:  26746.67
Transfer/sec:      3.65MB
```

> Single query no "where" statement, testing against "/features"

```bash
$ wrk -t8 -c512 -d15s http://127.0.0.1:5555/features                                                                                                                                                                                                 [7c7fb66]
Running 15s test @ http://127.0.0.1:5555/features
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    67.05ms   30.21ms 275.67ms   85.32%
    Req/Sec     0.98k   232.62     1.36k    81.27%
  116995 requests in 15.08s, 16.74MB read
Requests/sec:   7758.05
Transfer/sec:      1.11MB
```

> Single query with "where" statement, testing against "/features/feature_1"

```bash
$ wrk -t8 -c512 -d15s http://127.0.0.1:5555/features/feature_1                                                                                                                                                                                       [7c7fb66]
Running 15s test @ http://127.0.0.1:5555/features/feature_1
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    63.61ms   32.91ms 275.78ms   93.75%
    Req/Sec     1.07k   246.84     1.31k    91.31%
  126963 requests in 15.05s, 20.46MB read
Requests/sec:   8433.58
Transfer/sec:      1.36MB
```
