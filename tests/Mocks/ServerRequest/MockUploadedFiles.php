<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Mocks\ServerRequest;

use Quillstack\ServerRequest\Tests\Mocks\AbstractMock;

class MockUploadedFiles extends AbstractMock
{
    public const FILES = [
        'file' => 'file1.jpg',
    ];
}
