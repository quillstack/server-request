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

### Requirements

- PHP 8.1 or newer

### Installation

```shell
composer require quillstack/server-request
```

### Usage

#### Building one from what arrived

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

#### Attributes

What the router matched arrives here, and anything else a middleware wants to hand along:

```php
$request = $request->withAttribute('id', '42');

$request->getAttribute('id');             // '42'
$request->getAttribute('page', '1');      // '1' — nothing under that name
```

Every change hands back a copy, so a middleware adding one does not change the request anybody
else is holding.

#### A request class of your own

A controller can be handed a request of its own type, so what an endpoint expects is a class
rather than a convention:

```php
$factory->setRequestClass(UserRequest::class);
```

The class has to exist; `UnknownServerRequestClassException` says so when it does not.

#### Uploaded files

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

### Technical documentation

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

### Unit tests

```shell
composer test
composer test:coverage
composer stan
```

### Docker

```shell
docker-compose up -d
docker exec -w /var/www/html -it quillstack_server-request sh
```

### License

MIT. See [LICENSE](LICENSE).
