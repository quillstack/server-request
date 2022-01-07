<?php

declare(strict_types=1);

namespace Quillstack\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use Quillstack\Request\Tests\Unit\ServerRequest;
use Quillstack\Request\Tests\Mocks\ServerRequest\MockRequestUri;

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
