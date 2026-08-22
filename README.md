# Quillstack Server Request

[![Tests](https://github.com/quillstack/server-request/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/server-request/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/server-request.svg)](https://packagist.org/packages/quillstack/server-request)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/server-request.svg)](https://packagist.org/packages/quillstack/server-request)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/server-request)](https://packagist.org/packages/quillstack/server-request)
[![StyleCI](https://github.styleci.io/repos/291464420/shield?branch=main)](https://github.styleci.io/repos/291464420?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/server-request/badge)](https://www.codefactor.io/repository/github/quillstack/server-request)
[![License](https://img.shields.io/packagist/l/quillstack/server-request)](https://github.com/quillstack/server-request/blob/main/LICENSE)

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
