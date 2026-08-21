<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Mocks\ServerRequest;

use Quillstack\ServerRequest\Tests\Mocks\AbstractMock;

class MockUploadedFiles extends AbstractMock
{
    /**
     * Shaped the way PHP writes $_FILES, which is what the request is built from.
     */
    public const FILES = [
        'file' => [
            'tmp_name' => '/tmp/php-upload-test',
            'name' => 'file1.jpg',
            'type' => 'image/jpeg',
            'size' => 1024,
            'error' => UPLOAD_ERR_OK,
        ],
    ];
}
