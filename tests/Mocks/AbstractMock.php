<?php

declare(strict_types=1);

namespace Quillstack\Request\Tests\Mocks;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use QuillStack\DI\Container;
use Quillstack\Request\Factory\ServerRequest\RequestFromGlobalsFactory;
use QuillStack\Stream\InputStream;
use Quillstack\Uri\Factory\UriFactory;

abstract class AbstractMock
{
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/',
        'SERVER_PROTOCOL' => '1.1',
    ];

    public const COOKIE = [];
    public const QUERY = [];
    public const FILES = [];
    public const POST = [];

    protected ServerRequestInterface $request;

    public function __construct()
    {
        $container = new Container([
            StreamInterface::class => InputStream::class,
            UriFactoryInterface::class => UriFactory::class,
            RequestFromGlobalsFactory::class => [
                'server' => static::SERVER,
                'cookie' => static::COOKIE,
                'query' => static::QUERY,
                'files' => static::FILES,
                'post' => static::POST,
            ],
        ]);
        $factory = $container->get(RequestFromGlobalsFactory::class);
        $this->request = $factory->createServerRequest();
    }

    public function get()
    {
        return $this->request;
    }
}