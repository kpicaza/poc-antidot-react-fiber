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
→ wrk -t8 -c512 -d15s http://127.0.0.1:5555/                                                                                                                                                                                                         [defbbe7]
Running 15s test @ http://127.0.0.1:5555/
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    21.48ms    8.42ms 198.36ms   94.96%
    Req/Sec     3.03k   371.48     4.98k    82.62%
  359820 requests in 15.10s, 49.07MB read
Requests/sec:  23828.18
Transfer/sec:      3.25MB
```

> Single query no "where" statement, testing against "/features"

```bash
→ wrk -t8 -c512 -d15s http://127.0.0.1:5555/features                                                                                                                                                                                                 [defbbe7]
Running 15s test @ http://127.0.0.1:5555/features
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    68.04ms   32.17ms 312.85ms   94.46%
    Req/Sec     0.99k   179.83     1.30k    71.81%
  117312 requests in 15.07s, 16.78MB read
Requests/sec:   7784.07
Transfer/sec:      1.11MB
```

> Single query with "where" statement, testing against "/features/feature_1"

```bash
$ wrk -t8 -c512 -d15s http://127.0.0.1:5555/features/feature_1                                                                                                                                                                                       [7c7fb66]
Running 15s test @ http://127.0.0.1:5555/features/feature_1
  8 threads and 512 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    70.06ms   22.39ms 136.97ms   61.80%
    Req/Sec     0.91k   122.49     1.21k    65.25%
  109031 requests in 15.10s, 17.57MB read
Requests/sec:   7221.31
Transfer/sec:      1.16MB
```
