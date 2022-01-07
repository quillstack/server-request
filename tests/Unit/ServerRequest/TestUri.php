<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use PHPUnit\Framework\TestCase;
use Quillstack\ServerRequest\Tests\Unit\ServerRequest;
use QuillStack\Http\Stream\InputStream;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockProtocolVersion;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockUri;

final class UriTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockUri())->get();
    }

    public function testGetEmptyBody()
    {
        $request = (new MockProtocolVersion())->get();

        $this->assertEquals('http://localhost:8000/', (string) $request->getUri());
    }

    public function testGetNotEmptyBody()
    {
        $this->assertEquals('http://127.0.0.1/path', (string) $this->request->getUri());
    }

    public function testWithBody()
    {
        $request = (new MockProtocolVersion())->get();
        $request = $this->request->withUri($request->getUri());

        $this->assertEquals('http://127.0.0.1/path', (string) $this->request->getUri());
        $this->assertEquals('http://localhost:8000/', (string) $request->getUri());
    }
}
