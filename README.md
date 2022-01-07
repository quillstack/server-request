# QuillStack Request

[![Build Status](https://travis-ci.org/quillstack/request.svg?branch=master)](https://travis-ci.org/quillstack/request)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=quillstack_request&metric=alert_status)](https://sonarcloud.io/dashboard?id=quillstack_request)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/request.svg)](https://packagist.org/packages/quillstack/request)
[![StyleCI](https://github.styleci.io/repos/291464420/shield?branch=master)](https://github.styleci.io/repos/291464420?branch=master)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/request/badge)](https://www.codefactor.io/repository/github/quillstack/request)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=quillstack_request&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=quillstack_request)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=quillstack_request&metric=ncloc)](https://sonarcloud.io/dashboard?id=quillstack_request)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_request&metric=coverage)](https://sonarcloud.io/dashboard?id=quillstack_request)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/quillstack/request)
![Packagist License](https://img.shields.io/packagist/l/quillstack/request)

Quillstack Request is the request library which implements
_PSR-7: HTTP message interfaces_ and is based on
_PSR-17: HTTP Factories_.
You can find the full documentation on the website: \
https://quillstack.com/request

### Unit tests

Run tests using a command:

```
phpdbg -qrr ./vendor/bin/unit-tests
```

## Docker

```shell
$ docker-compose up -d
$ docker exec -w /var/www/html -it quillstack_request sh
```
