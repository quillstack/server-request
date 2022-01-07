<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Mocks\ServerRequest;

use Quillstack\ServerRequest\Tests\Mocks\AbstractMock;

class MockParsedBody extends AbstractMock
{
    public const POST = [
        'form' => 'input',
    ];
}
