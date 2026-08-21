<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit;

use Psr\Http\Message\UploadedFileInterface;
use Quillstack\ServerRequest\Exceptions\UploadedFileAlreadyMovedException;
use Quillstack\ServerRequest\Exceptions\UploadedFileNotMovedException;
use Quillstack\ServerRequest\Exceptions\UploadedFileNotUploadedException;
use Quillstack\ServerRequest\UploadedFiles\Upload;
use Quillstack\ServerRequest\UploadedFiles\UploadedFile;
use Quillstack\ServerRequest\UploadedFiles\UploadedFileFactory;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;
use Quillstack\UnitTests\Types\AssertBoolean;
use Quillstack\UnitTests\Types\AssertNull;
use Quillstack\UnitTests\Types\AssertObject;

/**
 * getUploadedFiles() handed back whatever PHP put in $_FILES, where PSR-7 asks for objects.
 */
class TestUploadedFiles
{
    private string $directory;

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertObject $assertObject,
        private AssertBoolean $assertBoolean,
        private AssertNull $assertNull,
        private AssertExceptions $assertExceptions
    ) {
        $this->directory = sys_get_temp_dir() . '/quillstack-uploads-' . getmypid();

        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0o775, true);
        }
    }

    private function temporaryFile(string $contents = 'a file'): string
    {
        $path = $this->directory . '/' . uniqid('upload', true);
        file_put_contents($path, $contents);

        return $path;
    }

    public function oneFieldWithOneFile()
    {
        $path = $this->temporaryFile();
        $files = (new UploadedFileFactory())->createFromGlobals([
            'avatar' => [
                'tmp_name' => $path,
                'name' => 'me.png',
                'type' => 'image/png',
                'size' => 6,
                'error' => UPLOAD_ERR_OK,
            ],
        ]);

        $file = $files['avatar'];

        $this->assertObject->instanceOf(UploadedFileInterface::class, $file);
        $this->assertEqual->equal('me.png', $file->getClientFilename());
        $this->assertEqual->equal('image/png', $file->getClientMediaType());
        $this->assertEqual->equal(6, $file->getSize());
        $this->assertEqual->equal(UPLOAD_ERR_OK, $file->getError());
        $this->assertEqual->equal('a file', (string) $file->getStream());
    }

    /**
     * PHP writes a field holding several files inside out, one entry per property rather
     * than one per file.
     */
    public function oneFieldWithSeveralFiles()
    {
        $first = $this->temporaryFile('first');
        $second = $this->temporaryFile('second');

        $files = (new UploadedFileFactory())->createFromGlobals([
            'documents' => [
                'tmp_name' => [$first, $second],
                'name' => ['one.pdf', 'two.pdf'],
                'type' => ['application/pdf', 'application/pdf'],
                'size' => [5, 6],
                'error' => [UPLOAD_ERR_OK, UPLOAD_ERR_OK],
            ],
        ]);

        $this->assertEqual->equal(2, count($files['documents']));
        $this->assertEqual->equal('one.pdf', $files['documents'][0]->getClientFilename());
        $this->assertEqual->equal('two.pdf', $files['documents'][1]->getClientFilename());
        $this->assertEqual->equal('second', (string) $files['documents'][1]->getStream());
    }

    public function afieldWhichIsNotAFileIsLeftOut()
    {
        $files = (new UploadedFileFactory())->createFromGlobals([
            'avatar' => 'not an upload at all',
            'broken' => ['name' => 'no tmp_name here'],
        ]);

        $this->assertEqual->equal(['broken' => []], $files);
    }

    public function afileIsMovedOnce()
    {
        $path = $this->temporaryFile('moved');
        $target = $this->directory . '/target.txt';
        $file = new UploadedFile($path, 5, UPLOAD_ERR_OK, 'a.txt', 'text/plain');

        $file->moveTo($target);

        $this->assertEqual->equal('moved', file_get_contents($target));
        $this->assertBoolean->isFalse(file_exists($path));

        $this->assertExceptions->expect(UploadedFileAlreadyMovedException::class);
        $file->moveTo($target);
    }

    public function afileWhichWasMovedHasNoStream()
    {
        $file = new UploadedFile($this->temporaryFile(), 6, UPLOAD_ERR_OK);
        $file->moveTo($this->directory . '/gone.txt');

        $this->assertExceptions->expect(UploadedFileAlreadyMovedException::class);
        $file->getStream();
    }

    public function afileWhichDidNotArriveSaysSo()
    {
        $file = new UploadedFile('', null, UPLOAD_ERR_NO_FILE);

        $this->assertEqual->equal(UPLOAD_ERR_NO_FILE, $file->getError());
        $this->assertNull->isNull($file->getSize());
        $this->assertNull->isNull($file->getClientFilename());

        $this->assertExceptions->expect(UploadedFileNotUploadedException::class);
        $file->getStream();
    }

    public function movingWithoutATargetIsRefused()
    {
        $this->assertExceptions->expect(UploadedFileNotMovedException::class);

        (new UploadedFile($this->temporaryFile()))->moveTo('');
    }

    public function movingSomewhereImpossibleIsReported()
    {
        $this->assertExceptions->expect(UploadedFileNotMovedException::class);

        (new UploadedFile($this->temporaryFile()))->moveTo('/quillstack/not/here/file.txt');
    }

    public function whatWentWrongIsSaidInWords()
    {
        $this->assertEqual->equal('no file was sent', Upload::describe(UPLOAD_ERR_NO_FILE));
        $this->assertEqual->equal('the file arrived', Upload::describe(UPLOAD_ERR_OK));
        $this->assertEqual->equal('unknown reason (99)', Upload::describe(99));
    }
}
