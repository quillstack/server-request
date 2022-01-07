<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Mocks\ServerRequest;

use Quillstack\ServerRequest\Tests\Mocks\AbstractMock;

class MockQuery extends AbstractMock
{
    public const QUERY = [
        'query' => 'value',
    ];
}
