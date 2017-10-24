# Docker Image claranet/spryker-demoshop

[![build status badge](https://img.shields.io/travis/claranet/spryker-demoshop/master.svg)](https://travis-ci.org/claranet/spryker-demoshop/branches)

<!-- vim-markdown-toc GFM -->
* [What?](#what)
* [Run the Demoshop](#run-the-demoshop)
* [Exposed Services](#exposed-services)
* [Start Development Environment](#start-development-environment)
* [Common Steps](#common-steps)
    * [Build Image](#build-image)
    * [Create/Destroy Setup](#createdestroy-setup)
    * [Operations While Setups is Running](#operations-while-setups-is-running)
        * [Refetch Dependencies](#refetch-dependencies)
        * [Rebuild and Recreate just Yves/Zed Container](#rebuild-and-recreate-just-yveszed-container)
    * [Interface to `docker-compose`](#interface-to-docker-compose)
    * [Debug Failed Build](#debug-failed-build)
* [Known Issues](#known-issues)
    * [Yves Links not working](#yves-links-not-working)
    * [Elasticsearch 5.0](#elasticsearch-50)
    * [Redis concurrency](#redis-concurrency)

<!-- vim-markdown-toc -->

## What?

This is a dockerized version of the official reference implementation of the
[Spryker Demoshop](https://github.com/spryker/demoshop). It is ready to run
out-of-the-box by automatically pulling all required dependencies and creating
a stack comprising PostgreSQL, Redis, Elasticsearch and Jenkins. During runtime
each of the services gets initialized.

You can use this repository either as a demonstration for a paradigmatic shop
based on Spryker Commerce Framework or as starting point for the development of
your own implementation beginning with a fork of the demoshop.

The build and init procedures along with further tooling are inherited from the
[claranet/spryker-base](https://github.com/claranet/spryker-base) image. There
you will find the technical design ideas behind the dockerization and answers
to further points like:

* Private Repositories
* Build Layer
* Environments
* Spryker Configuration

## Run the Demoshop

Requires: a recent, stable version of [docker](https://docs.docker.com/) and
[docker-compose](https://docs.docker.com/compose/) on your
[Linux](https://docs.docker.com/engine/installation/linux/ubuntu/)/[MacOS](https://docs.docker.com/docker-for-mac/install/)
box.

If requisites are met running the shop is fairly easy. Just enter these steps:

    $ git clone https://github.com/claranet/spryker-demoshop.git
    $ cd spryker-demoshop
    $ ./docker/run devel up

This pulls the docker image, create a network, create all the containers, bind
mounts your local code into the container in order to enable you to live-edit
from outside, connects the container to each other and finally exposes the
public services. One of the containers defined in the `docker-compose-deve.yml`
file will carry out the initialization which populates the data stores with
dummy data.

After the initialization has been finished, you are able to point your browser
to the following URLs:

* Yves via http://localhost:20100
* Zed via http://localhost:20200

## Exposed Services

Several services are being exposed by the docker composable stack. In order to
run stacks in parallel and prevent port collisions we need to align port
allocation.

Therefore the following scheme has been implemented: The port number is encoded
like this: **ECCDD**

* **E** - Environment
    * 1 - production
    * 2 - development
* **CC** - Component
    * 01 - yves
    * 02 - zed
    * 03 - jenkins
    * 04 - redis
    * 05 - elasticsearch
    * 06 - postgresql
    * 06 - rabbitmq
* **DD** - Domain
    * 00 - GLOBAL
    * 01 - DE
    * 02 - AT
    * 02 - CH

For example, to reach the default yves instance in the prod environment use:
http://localhost:10100/, or the jenkins instance in the development env:
http://localhost:20300/.

Please note: Only the development environment exposes data services like redis, es
and postgresql.

## Start Development Environment

If you want to start you own work based on the demoshop you will find the local
development environment interesting. This setup enables you to mount your local
code base into a running spryker setup and see changes take effect immediately.

Just run `./docker/run devel up` and there you go.

## Common Steps

Furthermore the `./docker/run` script provides you with shortcuts for common tasks:

### Build Image

Just to build the docker image use: `./docker/run build`

This applies to both environments since both are based of the very same image.

### Create/Destroy Setup

* Create devel env: `./docker/run/build devel up`
* Destroy devel env including the removal of allocated unnamed volumes: `./docker/run devel down -v`

### Operations While Setups is Running

#### Refetch Dependencies

Rerun the process which resolves the PHP and Node dependencies within the
running Yves/Zed containers: `./docker/run devel build-deps`

#### Rebuild and Recreate just Yves/Zed Container

In case you want to rebuild the shop image and just want to recreate the Yves
and Zed container while keeping all of the data containers (redis, es, psql)
running: `./docker/run devel rebuild`

If you just want to recreate those containers without rebuilding them run:
`./docker/run devel recreate`

While debugging it might be useful instead of letting `/entrypoints.sh`
initialize the container to skip this steps and check for yourself. You could
do this by changing the `command: run-zed` directive of the concerning
container to `command: sleep 1000000` in the `docker-compose-devel.yml` and
recreate the container by running `./docker/run devel recreate zed`.

### Interface to `docker-compose`

Since all this is based on `docker-composer` you might need to call it by
yourself, for example to enter a container via shell: `./docker/run devel
compose exec yves /bin/sh`

### Debug Failed Build

If the output of the build is not that telling and you are in need of a deeper
debug session, consider the following steps in order to resurrect the died
intermediate build container:

    ./docker/run build
    # assumed that the last created container is the failed intermediate build container
    docker commit $(docker ps -lq) debug
    docker run --rm --it debug /bin/sh

And here you go in investigating the cause for the build failure.


## Known Issues

If you find a bug not listed here, please [report](https://github.com/claranet/spryker-demoshop/issues) them!

### Yves Links not working

Links which might point the user to /cart, /login or /checkout are not working properly.

Still looking into this. The links aren't build correctly (just pointing to http://<domain>/).

### Elasticsearch 5.0

ES 5 introduced bootstrap checks which enforce some configuration parameter in
order to prevent misconfigured es cluster in production. Problem is, that one
of those parameters need linux kernel configuration of host system via
`sysctl(1)`. This breaks isolation principles.

So far we rely on ES 2.4 in the first place and will later proceed with newly
arrived version 5.0.

Note: [That Spryker is only supporting ES version 2.4.x](http://spryker.github.io/getting-started/system-requirements/#elasticsearch).

For further discussion see:

* https://www.elastic.co/guide/en/elasticsearch/reference/master/bootstrap-checks.html
* https://www.elastic.co/guide/en/elasticsearch/reference/master/_maximum_map_count_check.html
* https://discuss.elastic.co/t/elasticsearch-5-0-0-aplha4-wont-start-without-setting-vm-max-map-count/57471/12
* https://www.elastic.co/blog/bootstrap_checks_annoying_instead_of_devastating

### Redis concurrency

While doing a load test, a bug was found (and it points to redis).

Apparently, there is a limitation in the way [Redis](http://www.redis.io) is configured on the demoshop project in which there can only be no more than 100 requests at a time, otherwise it doesn't behave as expected.

The following log from the demoshop can be seen during the tests:

```
redis_1          | 1:M 01 Jun 14:46:19.074 * 100 changes in 300 seconds. Saving...
redis_1          | 1:M 01 Jun 14:46:19.075 * Background saving started by pid 37
redis_1          | 37:C 01 Jun 14:46:19.271 * DB saved on disk
redis_1          | 37:C 01 Jun 14:46:19.273 * RDB: 2 MB of memory used by copy-on-write
redis_1          | 1:M 01 Jun 14:46:19.276 * Background saving terminated with success
```

which makes us think that it only saves the latest 100 changes every 5 minutes.
