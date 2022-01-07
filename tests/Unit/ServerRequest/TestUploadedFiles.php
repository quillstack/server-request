<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockUploadedFiles;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertArray;

class TestUploadedFiles
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual, private AssertArray $assertArray)
    {
        $this->request = (new MockUploadedFiles())->get();
    }

    public function getUploadedFiles()
    {
        $uploadedFiles = $this->request->getUploadedFiles();

        $this->assertArray->count(1, $uploadedFiles);
        $this->assertArray->hasKey('file', $uploadedFiles);
        $this->assertEqual->equal('file1.jpg', $uploadedFiles['file']);
    }

    public function withUploadedFiles()
    {
        $request = $this->request->withUploadedFiles(['new-file' => 'file2.jpg']);

        $this->assertEqual->equal(['file' => 'file1.jpg'], $this->request->getUploadedFiles());
        $this->assertEqual->equal(['new-file' => 'file2.jpg'], $request->getUploadedFiles());
    }
}
