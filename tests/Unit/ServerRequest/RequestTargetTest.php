<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\ServerRequest;

use PHPUnit\Framework\TestCase;
use Quillstack\ServerRequest\Tests\Unit\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockRequestUri;

final class RequestTargetTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockRequestUri())->get();
    }

    public function testGetRequestTarget()
    {
        $this->assertEquals('/test?query=value&test=1', $this->request->getRequestTarget());
    }

    public function testWithRequestTarget()
    {
        $request = $this->request->withRequestTarget('/new-target');

        $this->assertEquals('/test?query=value&test=1', $this->request->getRequestTarget());
        $this->assertEquals('/new-target', $request->getRequestTarget());
    }
}
