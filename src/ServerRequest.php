<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Quillstack\HeaderBag\HeaderBag;
use Quillstack\HttpRequest\HttpRequest;
use Quillstack\ParameterBag\ParameterBag;
use Quillstack\ServerRequest\Factory\Exceptions\ServerRequestMethodNotKnownException;
use Quillstack\Stream\EmptyStream;

class ServerRequest implements ServerRequestInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $attributes = [];

    public ?string $requestTarget = null;

    private ParameterBag $serverParams;
    private ParameterBag $cookieParams;
    private ParameterBag $queryParams;
    private ParameterBag $uploadedFiles;
    private ParameterBag $parsedBody;

    public function __construct(
        private string $method,
        private UriInterface $uri,
        private string $protocolVersion,
        private HeaderBag $headerBag,
        private ?StreamInterface $body = null,
        ?ParameterBag $serverParams = null,
        ?ParameterBag $cookieParams = null,
        ?ParameterBag $queryParams = null,
        ?ParameterBag $uploadedFiles = null,
        ?ParameterBag $parsedBody = null
    ) {
        // A request built without one of these was one method call away from a fatal error,
        // and PSR-7 promises an array from every one of them.
        $this->serverParams = $serverParams ?? new ParameterBag();
        $this->cookieParams = $cookieParams ?? new ParameterBag();
        $this->queryParams = $queryParams ?? new ParameterBag();
        $this->uploadedFiles = $uploadedFiles ?? new ParameterBag();
        $this->parsedBody = $parsedBody ?? new ParameterBag();
    }

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders(): array
    {
        return $this->headerBag->getHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name): bool
    {
        return $this->headerBag->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name): array
    {
        return $this->headerBag->getHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name): string
    {
        return $this->headerBag->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withHeader($name, $value);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withAddedHeader($name, $value);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name): MessageInterface
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withoutHeader($name);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getBody(): StreamInterface
    {
        return $this->body ?? new EmptyStream();
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestTarget(): string
    {
        if ($this->requestTarget) {
            return $this->requestTarget;
        }

        // The path is already an absolute one — a leading slash used to be added here to
        // make up for a URI which gave it back without one, and adding it now would say
        // `//test`, which names a host rather than a path.
        $path = $this->uri->getPath();
        $builtRequestTarget = $path === '' ? '/' : $path;
        $queryString = $this->uri->getQuery();

        if ($queryString) {
            $builtRequestTarget .= "?{$queryString}";
        }

        return $builtRequestTarget;
    }

    /**
     * {@inheritDoc}
     */
    public function withRequestTarget($requestTarget): RequestInterface
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * {@inheritDoc}
     */
    public function withMethod($method): RequestInterface
    {
        $uppercaseMethod = strtoupper($method);

        if (!in_array($uppercaseMethod, HttpRequest::AVAILABLE_METHODS, true)) {
            throw new ServerRequestMethodNotKnownException("Method not known: {$method}");
        }

        $new = clone $this;
        $new->method = $uppercaseMethod;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * {@inheritDoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;

        return $new;
    }

    /**
     * {@inheritDoc}
      *
     * @return array<string, mixed>
    */
    public function getServerParams(): array
    {
        return $this->serverParams->all();
    }

    /**
     * {@inheritDoc}
      *
     * @return array<string, mixed>
    */
    public function getCookieParams(): array
    {
        return $this->cookieParams->all();
    }

    /**
     * {@inheritDoc}
      *
     * @param array<string, mixed> $cookies
    */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $new = clone $this;
        $new->cookieParams = new ParameterBag($cookies);

        return $new;
    }

    /**
     * {@inheritDoc}
      *
     * @return array<string, mixed>
    */
    public function getQueryParams(): array
    {
        return $this->queryParams->all();
    }

    /**
     * {@inheritDoc}
      *
     * @param array<string, mixed> $query
    */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        $new = clone $this;
        $new->queryParams = new ParameterBag($query);

        return $new;
    }

    /**
     * {@inheritDoc}
      *
     * @return array<string, mixed>
    */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles->all();
    }

    /**
     * {@inheritDoc}
      *
     * @param array<string, mixed> $uploadedFiles
    */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $new = clone $this;
        $new->uploadedFiles = new ParameterBag($uploadedFiles);

        return $new;
    }

    /**
     * {@inheritDoc}
      *
     * @return array<string, mixed>
    */
    public function getParsedBody()
    {
        return $this->parsedBody->all();
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed>|object|null $data
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        $new = clone $this;
        $new->parsedBody = new ParameterBag(is_array($data) ? $data : []);

        return $new;
    }

    /**
     * {@inheritDoc}
      *
     * @return array<string, mixed>
    */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name, $default = null)
    {
        if (!isset($this->attributes[$name])) {
            return $default;
        }

        return $this->attributes[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function withAttribute($name, $value): ServerRequestInterface
    {
        $new = clone $this;
        $new->attributes[$name] = $value;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutAttribute($name): ServerRequestInterface
    {
        $new = clone $this;

        if (isset($new->attributes[$name])) {
            unset($new->attributes[$name]);
        }

        return $new;
    }
}
