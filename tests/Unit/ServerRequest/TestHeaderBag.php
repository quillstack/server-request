<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockProtocolVersion;
use Quillstack\UnitTests\AssertEqual;

class TestHeaderBag
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual)
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function withHeader()
    {
        $request = $this->request;
        $this->assertEqual->equal([], $request->getHeader('new'));

        $request = $this->request->withHeader('new', 'header');

        $this->assertEqual->equal([], $this->request->getHeader('new'));
        $this->assertEqual->equal(['header'], $request->getHeader('new'));
    }

    public function withAddedHeader()
    {
        $request = $this->request;
        $this->assertEqual->equal([], $request->getHeader('new'));

        $request = $this->request->withAddedHeader('new', 'header');
        $request = $request->withAddedHeader('new', 'added');

        $this->assertEqual->equal([], $this->request->getHeader('new'));
        $this->assertEqual->equal(['header', 'added'], $request->getHeader('new'));
    }

    public function withoutAddedHeader()
    {
        $request = $this->request;
        $this->assertEqual->equal(['localhost:8000'], $request->getHeader('host'));

        $request = $this->request->withoutHeader('Host');

        $this->assertEqual->equal(['localhost:8000'], $this->request->getHeader('host'));
        $this->assertEqual->equal([], $request->getHeader('host'));
    }
}
