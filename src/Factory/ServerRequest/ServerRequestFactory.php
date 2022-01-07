<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Quillstack\ServerRequest\Factory\Exceptions\UnknownServerRequestClassException;
use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Validators\ServerParamValidator;

class ServerRequestFactory implements ServerRequestFactoryInterface
{
    /**
     * @var string
     */
    private string $requestClass = ServerRequest::class;

    /**
     * @var StreamInterface
     */
    public StreamInterface $stream;

    /**
     * @var ServerParamValidator
     */
    public ServerParamValidator $validator;

    /**
     * @param string $requestClass
     */
    public function setRequestClass(string $requestClass)
    {
        if (!class_exists($requestClass)) {
            throw new UnknownServerRequestClassException("Unknown class name: {$requestClass}");
        }

        $this->requestClass = $requestClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        $this->validator->setServerParams($serverParams)->validate();

        return new $this->requestClass(
            $method,
            $uri,
            $serverParams['protocolVersion'],
            $serverParams['headers'],
            $this->stream,
            $serverParams['serverParams'],
            $serverParams['cookieParams'],
            $serverParams['queryParams'],
            $serverParams['uploadedFiles'],
            $serverParams['parsedBody'],
        );
    }
}
