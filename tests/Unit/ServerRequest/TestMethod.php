<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\Factory\Exceptions\ServerRequestMethodNotKnownException;
use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockProtocolVersion;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;

class TestMethod
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual, private AssertExceptions $assertExceptions)
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function getMethod()
    {
        $this->assertEqual->equal('GET', $this->request->getMethod());
    }

    public function withMethod()
    {
        $request = $this->request->withMethod('POST');

        $this->assertEqual->equal('GET', $this->request->getMethod());
        $this->assertEqual->equal('POST', $request->getMethod());
    }

    public function withNonExistingMethod()
    {
        $this->assertExceptions->expect(ServerRequestMethodNotKnownException::class);

        $this->request->withMethod('SMURFS');
    }
}
