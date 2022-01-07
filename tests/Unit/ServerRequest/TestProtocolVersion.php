<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockProtocolVersion;
use Quillstack\UnitTests\AssertEqual;

class TestProtocolVersion
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual)
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function getProtocolVersion()
    {
        $this->assertEqual->equal('1.1', $this->request->getProtocolVersion());
    }

    public function wWithProtocolVersion()
    {
        $request = $this->request->withProtocolVersion('1.2');

        $this->assertEqual->equal('1.1', $this->request->getProtocolVersion());
        $this->assertEqual->equal('1.2', $request->getProtocolVersion());
    }
}
