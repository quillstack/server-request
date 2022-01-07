<?php

declare(strict_types=1);

namespace Quillstack\Request\Tests\Mocks\ServerRequest;

use Quillstack\Request\Tests\Mocks\AbstractMock;

class MockUploadedFiles extends AbstractMock
{
    public const FILES = [
        'file' => 'file1.jpg',
    ];
}
