<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockRequestUri;
use Quillstack\UnitTests\AssertEqual;

class TestRequestTarget
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual)
    {
        $this->request = (new MockRequestUri())->get();
    }

    public function testGetRequestTarget()
    {
        $this->assertEqual->equal('/test?query=value&test=1', $this->request->getRequestTarget());
    }

    public function testWithRequestTarget()
    {
        $request = $this->request->withRequestTarget('/new-target');

        $this->assertEqual->equal('/test?query=value&test=1', $this->request->getRequestTarget());
        $this->assertEqual->equal('/new-target', $request->getRequestTarget());
    }
}
