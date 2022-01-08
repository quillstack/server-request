<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Quillstack\HeaderBag\HeaderBag;
use Quillstack\HttpRequest\HttpRequest;
use Quillstack\ServerRequest\Factory\Exceptions\ServerRequestMethodNotKnownException;
use QuillStack\ParameterBag\ParameterBag;

class ServerRequest implements ServerRequestInterface
{
    private string $method;
    private UriInterface $uri;
    private string $protocolVersion;
    private HeaderBag $headerBag;
    private ?StreamInterface $body;
    private ?ParameterBag $serverParams;
    private ?ParameterBag $cookieParams;
    private ?ParameterBag $queryParams;
    private ?ParameterBag $uploadedFiles;
    private ?ParameterBag $parsedBody;
    private array $attributes = [];
    public ?string $requestTarget = null;

    /**
     * @param string $method
     * @param UriInterface $uri
     * @param string $protocolVersion
     * @param HeaderBag $headerBag
     * @param StreamInterface|null $body
     * @param ParameterBag|null $serverParams
     * @param ParameterBag|null $cookieParams
     * @param ParameterBag|null $queryParams
     * @param ParameterBag|null $uploadedFiles
     * @param ParameterBag|null $parsedBody
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        string $protocolVersion,
        HeaderBag $headerBag,
        StreamInterface $body = null,
        ParameterBag $serverParams = null,
        ParameterBag $cookieParams = null,
        ParameterBag $queryParams = null,
        ParameterBag $uploadedFiles = null,
        ParameterBag $parsedBody = null
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->protocolVersion = $protocolVersion;
        $this->headerBag = $headerBag;
        $this->body = $body;
        $this->serverParams = $serverParams;
        $this->cookieParams = $cookieParams;
        $this->queryParams = $queryParams;
        $this->uploadedFiles = $uploadedFiles;
        $this->parsedBody = $parsedBody;
    }

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version)
    {
        $new = clone $this;
        $new->protocolVersion = $version;

        return $new;
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function getHeaders()
    {
        return $this->headerBag->getHeaders();
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function hasHeader($name)
    {
        return $this->headerBag->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function getHeader($name)
    {
        return $this->headerBag->getHeader($name);
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function getHeaderLine($name)
    {
        return $this->headerBag->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value)
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withHeader($name, $value);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value)
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withAddedHeader($name, $value);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name)
    {
        $new = clone $this;
        $new->headerBag = $this->headerBag->withoutHeader($name);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body)
    {
        $new = clone $this;
        $new->body = $body;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestTarget()
    {
        if ($this->requestTarget) {
            return $this->requestTarget;
        }

        $builtRequestTarget = '';
        $path = $this->uri->getPath();

        if ($path !== '/') {
            $builtRequestTarget = '/';
        }

        $builtRequestTarget .= $path;
        $queryString = $this->uri->getQuery();

        if ($queryString) {
            $builtRequestTarget .= "?{$queryString}";
        }

        return $builtRequestTarget;
    }

    /**
     * {@inheritDoc}
     */
    public function withRequestTarget($requestTarget)
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritDoc}
     */
    public function withMethod($method)
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
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * {@inheritDoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $new = clone $this;
        $new->uri = $uri;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getServerParams()
    {
        return $this->serverParams->all();
    }

    /**
     * {@inheritDoc}
     */
    public function getCookieParams()
    {
        return $this->cookieParams->all();
    }

    /**
     * {@inheritDoc}
     */
    public function withCookieParams(array $cookies)
    {
        $new = clone $this;
        $new->cookieParams = new ParameterBag($cookies);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryParams()
    {
        return $this->queryParams->all();
    }

    /**
     * {@inheritDoc}
     */
    public function withQueryParams(array $query)
    {
        $new = clone $this;
        $new->queryParams = new ParameterBag($query);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles->all();
    }

    /**
     * {@inheritDoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $new = clone $this;
        $new->uploadedFiles = new ParameterBag($uploadedFiles);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getParsedBody()
    {
        return $this->parsedBody->all();
    }

    /**
     * {@inheritDoc}
     */
    public function withParsedBody($data)
    {
        $new = clone $this;
        $new->parsedBody = new ParameterBag($data);

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
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
    public function withAttribute($name, $value)
    {
        $new = clone $this;
        $new->attributes[$name] = $value;

        return $new;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutAttribute($name)
    {
        $new = clone $this;

        if (isset($new->attributes[$name])) {
            unset($new->attributes[$name]);
        }

        return $new;
    }
}
