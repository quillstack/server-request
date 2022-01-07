<?php

declare(strict_types=1);

namespace Quillstack\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use Quillstack\Request\ServerRequest;
use QuillStack\Mocks\ServerRequest\MockProtocolVersion;

final class ProtocolVersionTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function testGetProtocolVersion()
    {
        $this->assertEquals('1.1', $this->request->getProtocolVersion());
    }

    public function testWithProtocolVersion()
    {
        $request = $this->request->withProtocolVersion('1.2');

        $this->assertEquals('1.1', $this->request->getProtocolVersion());
        $this->assertEquals('1.2', $request->getProtocolVersion());
    }
}
