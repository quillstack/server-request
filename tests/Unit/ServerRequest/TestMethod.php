<?php

declare(strict_types=1);

namespace Quillstack\Request\Tests\Unit\ServerRequest;

use Quillstack\Request\Factory\Exceptions\RequestMethodNotKnownException;
use Quillstack\Request\ServerRequest;
use Quillstack\Request\Tests\Mocks\ServerRequest\MockProtocolVersion;
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
        $this->assertExceptions->expect(RequestMethodNotKnownException::class);

        $this->request->withMethod('SMURFS');
    }
}
