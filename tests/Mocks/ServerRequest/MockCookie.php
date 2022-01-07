<?php

declare(strict_types=1);

namespace Quillstack\Request\Tests\Mocks\ServerRequest;

use Quillstack\Request\Tests\Mocks\AbstractMock;

class MockCookie extends AbstractMock
{
    public const COOKIE = [
        'cookie' => 'test',
    ];
}
