<?php

declare(strict_types=1);

namespace Quillstack\Request\Tests\Mocks\ServerRequest;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use QuillStack\DI\Container;
use Quillstack\Request\Factory\ServerRequest\RequestFromGlobalsFactory;
use Quillstack\Request\Tests\Mocks\AbstractMock;
use QuillStack\Stream\InputStream;
use Quillstack\Uri\Factory\UriFactory;

class MockBody extends AbstractMock
{
    public function __construct()
    {
        $container = new Container([
            StreamInterface::class => InputStream::class,
            InputStream::class => [
                'contest' => 'Body test.',
            ],
            UriFactoryInterface::class => UriFactory::class,
            RequestFromGlobalsFactory::class => [
                'server' => AbstractMock::SERVER,
            ],
        ]);
        $factory = $container->get(RequestFromGlobalsFactory::class);
        $this->request = $factory->createServerRequest();
    }
}
