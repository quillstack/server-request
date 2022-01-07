<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Quillstack\HeaderBag\HeaderBag;
use Quillstack\ParameterBag\ParameterBag;
use Quillstack\ServerRequest\Factory\Exceptions\ServerRequestMethodNotKnownException;
use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Validators\ServerGlobalArrayValidator;
use Quillstack\Uri\Uri;

class RequestFromGlobalsFactory
{
    /**
     * @var string
     */
    public const SERVER_REQUEST_METHOD = 'REQUEST_METHOD';

    /**
     * @var string
     */
    public const SERVER_HTTP_HOST = 'HTTP_HOST';

    /**
     * @var string
     */
    public const SERVER_REQUEST_URI = 'REQUEST_URI';

    /**
     * @var string
     */
    public const SERVER_SERVER_PROTOCOL = 'SERVER_PROTOCOL';

    /**
     * @var string
     */
    private const SERVER_HTTPS = 'HTTPS';

    /**
     * @var string
     */
    private const HEADER_PREFIX = 'HTTP_';

    public ServerRequestFactory $serverRequestFactory;
    public UriFactoryInterface $uriFactory;
    public ServerGlobalArrayValidator $validator;
    private array $server;
    private array $cookie;
    private array $query;
    private array $files;
    private array $post;

    public function __construct(
        array $server = [],
        array $cookie = [],
        array $query = [],
        array $files = [],
        array $post = []
    ) {
        $this->server = !empty($server) ? $server : $_SERVER;
        $this->cookie = !empty($cookie) ? $cookie : $_COOKIE;
        $this->query = !empty($query) ? $query : $_GET;
        $this->files = !empty($files) ? $files : $_FILES;
        $this->post = !empty($post) ? $post : $_POST;
    }

    public function createServerRequest(): ServerRequestInterface
    {
        // Validate.
        $this->validator->setServer($this->server)->validate();

        // Set method, server params, and URI.
        $method = $this->getMethod();
        $serverParams = $this->getServerParams();
        $uri = $this->uriFactory->createUri(
            $this->getUriString()
        );

        return $this->serverRequestFactory->createServerRequest($method, $uri, $serverParams);
    }

    private function getMethod(): string
    {
        $method = strtoupper($this->server[self::SERVER_REQUEST_METHOD]);

        if (!in_array($method, ServerRequest::AVAILABLE_METHODS, true)) {
            throw new ServerRequestMethodNotKnownException("Method not known: {$method}");
        }

        return $method;
    }

    private function getUriString(): string
    {
        $scheme = isset($this->server[self::SERVER_HTTPS]) && $this->server[self::SERVER_HTTPS] === 'on'
            ? Uri::SCHEME_HTTPS
            : Uri::SCHEME_HTTP;

        $host = $this->server[self::SERVER_HTTP_HOST];
        $requestUri = $this->server[self::SERVER_REQUEST_URI];

        return "{$scheme}://{$host}{$requestUri}";
    }

    private function getServerParams(): array
    {
        return [
            'protocolVersion' => $this->getServerVersion(),
            'headers' => $this->getHeaders(),
            'serverParams' => new ParameterBag($this->server),
            'cookieParams' => new ParameterBag($this->cookie),
            'queryParams' => new ParameterBag($this->query),
            'uploadedFiles' => new ParameterBag($this->files),
            'parsedBody' => new ParameterBag($this->post),
        ];
    }

    private function getHeaders(): HeaderBag
    {
        $headers = [];

        foreach ($this->server as $key => $value) {
            if (substr($key, 0, 5) !== self::HEADER_PREFIX) {
                continue;
            }

            $name = str_replace(self::HEADER_PREFIX, '', $key);
            $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $name))));

            $headers[$name] = $value;
        }

        return new HeaderBag($headers);
    }

    private function getServerVersion(): string
    {
        return str_replace('HTTP/', '', $this->server['SERVER_PROTOCOL']);
    }
}
