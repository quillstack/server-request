<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockRootPath;
use Quillstack\UnitTests\AssertEqual;

class TestRootRequestTarget
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual)
    {
        $this->request = (new MockRootPath())->get();
    }

    public function getRequestTarget()
    {
        $this->assertEqual->equal('/', $this->request->getRequestTarget());
    }

    public function withRequestTarget()
    {
        $request = $this->request->withRequestTarget('/new-target');

        $this->assertEqual->equal('/', $this->request->getRequestTarget());
        $this->assertEqual->equal('/new-target', $request->getRequestTarget());
    }
}
