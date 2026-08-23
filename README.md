# Quillstack Server Request

[![Tests](https://github.com/quillstack/server-request/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/server-request/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/server-request.svg)](https://packagist.org/packages/quillstack/server-request)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/server-request.svg)](https://packagist.org/packages/quillstack/server-request)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/server-request)](https://packagist.org/packages/quillstack/server-request)
[![StyleCI](https://github.styleci.io/repos/291464420/shield?branch=main)](https://github.styleci.io/repos/291464420?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/server-request/badge)](https://www.codefactor.io/repository/github/quillstack/server-request)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=quillstack_server-request&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=quillstack_server-request)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_server-request&metric=coverage)](https://sonarcloud.io/summary/new_code?id=quillstack_server-request)
[![Maintainability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_server-request&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_server-request)
[![Reliability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_server-request&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_server-request)
[![Security](https://sonarcloud.io/api/project_badges/measure?project=quillstack_server-request&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_server-request)
[![License](https://img.shields.io/packagist/l/quillstack/server-request)](https://github.com/quillstack/server-request/blob/main/LICENSE)

The request object based on [PSR-7: Server Request](https://www.php-fig.org/psr/psr-7/). Full
documentation: https://quillstack.org/server-request

A request built from what PHP hands the process — `$_SERVER`, `$_GET`, `$_COOKIE`, `$_FILES`
and the body — and answered for the way PSR-7 says it should be. Every part of it is a
[parameter bag](https://github.com/quillstack/parameter-bag), so reading something which was
not sent is an answer rather than a notice.

## Why this exists

A server request has two entirely different origins, and most implementations only take one
seriously. There is the one a web server hands over — `$_SERVER`, `$_GET`, `$_FILES`, a body on
`php://input` — and the one a test or a client makes up from a verb and an address. Building the
first from globals is fiddly and easy to get subtly wrong; building the second should be one
line.

This does both: `ServerRequestFromGlobalsFactory` for what arrived, and a PSR-17
`ServerRequestFactory` for everything else. **A controller can also declare its own request
class** — `LoginRequest`, `CreateUserRequest` — and be handed that instead, which is where
validation belongs and where a framework usually makes you go looking.

## Requirements

- PHP 8.1 or newer

## Installation

```shell
composer require quillstack/server-request
```

## Usage

### Building one from a method and an address

```php
use Quillstack\ServerRequest\Factory\ServerRequestFactory;

$request = (new ServerRequestFactory())->createServerRequest('GET', 'https://api.example.org/users/42?page=2');
```

```php
$request->getMethod();              // 'GET'
$request->getRequestTarget();       // '/users/42?page=2'
$request->getHeaderLine('Host');    // 'api.example.org'
$request->getQueryParams();         // ['page' => '2']
```

It is PSR-17, so anything expecting `ServerRequestFactoryInterface` can be handed one, and it
needs nothing to be built itself. The Host header is set from the address, with the port where
that port is not the usual one for the scheme.

### Building one from what arrived

```php
$request = $factory->createServerRequest();

$request->getMethod();                    // 'GET'
$request->getUri()->getPath();            // '/users/42'
$request->getHeaderLine('accept');        // 'application/json'
$request->getRequestTarget();             // '/users/42?page=2'
$request->getQueryParams();               // ['page' => '2']
$request->getCookieParams();
$request->getParsedBody();
```

The query parameters come from `$_GET` rather than from the URI, which is what PSR-7 allows
and what everything else in PHP does — so a rewritten URL and the parameters PHP parsed cannot
disagree.

### Attributes

What the router matched arrives here, and anything else a middleware wants to hand along:

```php
$request = $request->withAttribute('id', '42');

$request->getAttribute('id');             // '42'
$request->getAttribute('page', '1');      // '1' — nothing under that name
```

Every change hands back a copy, so a middleware adding one does not change the request anybody
else is holding.

### A request class of your own

A controller can be handed a request of its own type, so what an endpoint expects is a class
rather than a convention:

```php
$factory->setRequestClass(UserRequest::class);
```

The class has to exist; `UnknownServerRequestClassException` says so when it does not.

### Uploaded files

```php
foreach ($request->getUploadedFiles() as $name => $file) {
    $file->getClientFilename();     // 'photo.jpg'
    $file->getClientMediaType();    // 'image/jpeg'
    $file->getSize();               // 20481
    $file->getError();              // UPLOAD_ERR_OK

    $file->moveTo('/var/www/uploads/photo.jpg');
}
```

A file can only be moved once — moving it again throws `UploadedFileAlreadyMovedException`,
because the second call would otherwise fail in a way nobody expects. `getStream()` after a
move throws for the same reason.

## Technical documentation

| Class | What it is |
| --- | --- |
| `ServerRequest` | the request, implementing `Psr\Http\Message\ServerRequestInterface` |
| `Factory\ServerRequest\ServerRequestFactory` | builds one from given parameters |
| `Factory\ServerRequest\ServerRequestFromGlobalsFactory` | builds one from what PHP was given |
| `Factory\ServerRequest\GivenServerRequestFromGlobalsFactory` | the same, as a named class |
| `UploadedFiles\UploadedFile` | one uploaded file, implementing `UploadedFileInterface` |
| `UploadedFiles\UploadedFileFactory` | turns `$_FILES` into those |

| Exception | Thrown when |
| --- | --- |
| `RequiredParamFromGlobalsNotFoundException` | `$_SERVER` is missing something a request needs |
| `ServerRequestMethodNotKnownException` | the method is not one HTTP has |
| `UnknownServerRequestClassException` | the request class named does not exist |
| `ServerParamNotSetException` | a parameter the factory was told to use is not there |
| `UploadedFileNotUploadedException` | the file did not arrive as an upload |
| `UploadedFileAlreadyMovedException` | it has been moved once already |
| `UploadedFileNotMovedException` | moving it failed |

`getRequestTarget()` is the path and the query string, taken from the URI — the path is
already an absolute one, so nothing is added to the front of it.

## Benchmark

Measured with [quillstack/benchmark](https://github.com/quillstack/benchmark) on one request
built from a method and an address with a query string, a thousand times. All four produce the
same method, target and Host header. Runs are interleaved, each figure is the median of five,
and PHP is 8.5.7.

| | Version |
| --- | --- |
| quillstack/server-request | v0.8.0 |
| nyholm/psr7 | 1.8.2 |
| laminas/laminas-diactoros | 3.8.0 |
| guzzlehttp/psr7 | 2.13.0 |

| | Per request | Relative |
| --- | --- | --- |
| nyholm/psr7 | 6.2 µs | 0.46× |
| **quillstack/server-request** | **13.4 µs** | — |
| laminas/laminas-diactoros | 14.4 µs | 1.07× |
| guzzlehttp/psr7 | 15.0 µs | 1.12× |

**`nyholm/psr7` builds one in less than half the time**, and it is worth saying plainly rather
than burying: it is the leanest PSR-7 there is, and if constructing requests in a loop is what
your application does, that is the one to use.

Where this one spends the difference is on the parts that make it a *server* request rather than
a message: server parameters, query parameters, cookies and uploaded files each arrive in a
[parameter bag](https://github.com/quillstack/parameter-bag) rather than a bare array, and the
URI goes through a [conformance-tested parser](https://github.com/quillstack/uri). At thirteen
microseconds, a request that builds one of these spends thirteen microseconds on it.

## Tests

```shell
composer test
composer test:coverage
composer stan
```

## The rest of Quillstack

This is one component of [Quillstack](https://github.com/quillstack), a PHP framework which is
as simple to use as it is strict about what it does.

- [quillstack/router](https://github.com/quillstack/router) — what matches one to a controller
- [quillstack/uri](https://github.com/quillstack/uri) — what takes the address apart
- [quillstack/parameter-bag](https://github.com/quillstack/parameter-bag) — what holds the parameters
- [quillstack/response](https://github.com/quillstack/response) — what goes back

## License

MIT. See [LICENSE](LICENSE).
