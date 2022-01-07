<?php

declare(strict_types=1);

namespace Quillstack\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use Quillstack\Request\Tests\Unit\ServerRequest;
use QuillStack\Http\Stream\InputStream;
use Quillstack\Request\Tests\Mocks\ServerRequest\MockProtocolVersion;
use Quillstack\Request\Tests\Mocks\ServerRequest\MockUri;

final class HeaderBagTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function testWithHeader()
    {
        $request = $this->request;
        $this->assertEquals([], $request->getHeader('new'));

        $request = $this->request->withHeader('new', 'header');

        $this->assertEquals([], $this->request->getHeader('new'));
        $this->assertEquals(['header'], $request->getHeader('new'));
    }

    public function testWithAddedHeader()
    {
        $request = $this->request;
        $this->assertEquals([], $request->getHeader('new'));

        $request = $this->request->withAddedHeader('new', 'header');
        $request = $request->withAddedHeader('new', 'added');

        $this->assertEquals([], $this->request->getHeader('new'));
        $this->assertEquals(['header', 'added'], $request->getHeader('new'));
    }

    public function testWithoutAddedHeader()
    {
        $request = $this->request;
        $this->assertEquals(['localhost:8000'], $request->getHeader('host'));

        $request = $this->request->withoutHeader('Host');

        $this->assertEquals(['localhost:8000'], $this->request->getHeader('host'));
        $this->assertEquals([], $request->getHeader('host'));
    }
}
