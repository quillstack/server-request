# Quillstack Server Request

[![Tests](https://github.com/quillstack/server-request/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/server-request/actions/workflows/tests.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=quillstack_request&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=quillstack_request)
[![StyleCI](https://github.styleci.io/repos/291464420/shield?branch=main)](https://github.styleci.io/repos/291464420?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/server-request/badge)](https://www.codefactor.io/repository/github/quillstack/server-request)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=quillstack_request&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_request)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=quillstack_request&metric=ncloc)](https://sonarcloud.io/summary/new_code?id=quillstack_request)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_request&metric=coverage)](https://sonarcloud.io/summary/new_code?id=quillstack_request)

Quillstack Server Request is the server request library which implements
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
$ docker exec -w /var/www/html -it quillstack_server-request sh
```
