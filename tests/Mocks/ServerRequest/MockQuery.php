<?php

declare(strict_types=1);

namespace Quillstack\Request\Tests\Mocks\ServerRequest;

use Quillstack\Request\Tests\Mocks\AbstractMock;

class MockQuery extends AbstractMock
{
    public const QUERY = [
        'query' => 'value',
    ];
}
