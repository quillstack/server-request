<?php

declare(strict_types=1);

namespace Quillstack\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use Quillstack\Request\ServerRequest;
use QuillStack\Http\Stream\InputStream;
use QuillStack\Mocks\ServerRequest\MockBody;
use QuillStack\Mocks\ServerRequest\MockProtocolVersion;

final class BodyTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockBody())->get();
    }

    public function testGetEmptyBody()
    {
        $request = (new MockProtocolVersion())->get();

        $this->assertEquals('', $request->getBody()->getContents());
    }

    public function testGetNotEmptyBody()
    {
        $this->assertEquals('Body test.', $this->request->getBody()->getContents());
    }

    public function testWithBody()
    {
        $inputStream = new InputStream('New body!');
        $request = $this->request->withBody($inputStream);

        $this->assertEquals('Body test.', $this->request->getBody()->getContents());
        $this->assertEquals('New body!', $request->getBody()->getContents());
    }
}
