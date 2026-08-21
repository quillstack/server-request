<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\UploadedFiles;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Quillstack\ServerRequest\Exceptions\UploadedFileAlreadyMovedException;
use Quillstack\ServerRequest\Exceptions\UploadedFileNotMovedException;
use Quillstack\ServerRequest\Exceptions\UploadedFileNotUploadedException;
use Quillstack\Stream\FileStream;

/**
 * One file which arrived with the request.
 */
class UploadedFile implements UploadedFileInterface
{
    private bool $moved = false;

    public function __construct(
        private readonly string $path,
        private readonly ?int $size = null,
        private readonly int $error = UPLOAD_ERR_OK,
        private readonly ?string $clientFilename = null,
        private readonly ?string $clientMediaType = null
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getStream(): StreamInterface
    {
        $this->guard();

        return new FileStream($this->path);
    }

    /**
     * {@inheritDoc}
     *
     * A file may be moved once: after that the one which arrived is no longer where it was.
     */
    public function moveTo(string $targetPath): void
    {
        $this->guard();

        if ($targetPath === '') {
            throw new UploadedFileNotMovedException('A target path is required');
        }

        // move_uploaded_file() refuses anything PHP did not receive as an upload, which is
        // the guard it is there for. A file put where one would be, by a test or by a
        // command line, is moved the ordinary way.
        $moved = is_uploaded_file($this->path)
            ? @move_uploaded_file($this->path, $targetPath)
            : @rename($this->path, $targetPath);

        if ($moved === false) {
            throw new UploadedFileNotMovedException("Unable to move the uploaded file to: {$targetPath}");
        }

        $this->moved = true;
    }

    /**
     * {@inheritDoc}
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * {@inheritDoc}
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * {@inheritDoc}
     */
    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    /**
     * {@inheritDoc}
     */
    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }

    /**
     * A file which did not arrive, or which has already been moved, has nothing to give.
     */
    private function guard(): void
    {
        if ($this->error !== UPLOAD_ERR_OK) {
            throw new UploadedFileNotUploadedException(
                'The file did not arrive: ' . Upload::describe($this->error)
            );
        }

        if ($this->moved) {
            throw new UploadedFileAlreadyMovedException('The uploaded file has already been moved');
        }
    }
}
