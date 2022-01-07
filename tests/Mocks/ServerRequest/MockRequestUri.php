<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Mocks\ServerRequest;

use Quillstack\ServerRequest\Tests\Mocks\AbstractMock;

class MockRequestUri extends AbstractMock
{
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/test?query=value&test=1',
        'SERVER_PROTOCOL' => '1.1',
    ];
}
