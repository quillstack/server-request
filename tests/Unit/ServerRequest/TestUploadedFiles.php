<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Psr\Http\Message\UploadedFileInterface;
use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockUploadedFiles;
use Quillstack\ServerRequest\UploadedFiles\UploadedFile;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertArray;
use Quillstack\UnitTests\Types\AssertObject;

class TestUploadedFiles
{
    private ServerRequest $request;

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertArray $assertArray,
        private AssertObject $assertObject
    ) {
        $this->request = (new MockUploadedFiles())->get();
    }

    /**
     * PSR-7 asks for objects here. The request used to hand back whatever PHP had put in
     * $_FILES, which is an array of strings arranged inside out.
     */
    public function getUploadedFiles()
    {
        $uploadedFiles = $this->request->getUploadedFiles();

        $this->assertArray->count(1, $uploadedFiles);
        $this->assertArray->hasKey('file', $uploadedFiles);
        $this->assertObject->instanceOf(UploadedFileInterface::class, $uploadedFiles['file']);
        $this->assertEqual->equal('file1.jpg', $uploadedFiles['file']->getClientFilename());
        $this->assertEqual->equal('image/jpeg', $uploadedFiles['file']->getClientMediaType());
        $this->assertEqual->equal(1024, $uploadedFiles['file']->getSize());
    }

    public function withUploadedFiles()
    {
        $replacement = new UploadedFile('/tmp/other', 5, UPLOAD_ERR_OK, 'file2.jpg');
        $request = $this->request->withUploadedFiles(['new-file' => $replacement]);

        $this->assertArray->hasKey('file', $this->request->getUploadedFiles());
        $this->assertEqual->equal(['new-file' => $replacement], $request->getUploadedFiles());
    }
}
