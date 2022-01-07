<?php

declare(strict_types=1);

namespace Quillstack\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use Quillstack\Request\ServerRequest;
use QuillStack\Mocks\ServerRequest\MockProtocolVersion;

final class ServerParamsTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function testGetServerParams()
    {
        $serverParams = $this->request->getServerParams();

        $this->assertCount(4, $serverParams);
        $this->assertArrayHasKey('REQUEST_METHOD', $serverParams);
        $this->assertEquals('GET', $serverParams['REQUEST_METHOD']);
    }
}
