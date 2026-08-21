<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Stream;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * The body of a request which has none. PSR-7 promises getBody() answers with a stream, so
 * a request built without one hands back an empty one rather than null.
 */
final class EmptyStream implements StreamInterface
{
    public function __toString(): string
    {
        return '';
    }

    public function close(): void
    {
        //
    }

    public function detach()
    {
        return null;
    }

    public function getSize(): int
    {
        return 0;
    }

    public function tell(): int
    {
        return 0;
    }

    public function eof(): bool
    {
        return true;
    }

    public function isSeekable(): bool
    {
        return false;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        throw new RuntimeException('An empty stream cannot be seeked');
    }

    public function rewind(): void
    {
        //
    }

    public function isWritable(): bool
    {
        return false;
    }

    public function write($string): int
    {
        throw new RuntimeException('An empty stream cannot be written to');
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read($length): string
    {
        return '';
    }

    public function getContents(): string
    {
        return '';
    }

    public function getMetadata($key = null)
    {
        return $key === null ? [] : null;
    }
}
