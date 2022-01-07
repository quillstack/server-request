<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockProtocolVersion;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockUri;
use Quillstack\UnitTests\AssertEqual;

class TestUri
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual)
    {
        $this->request = (new MockUri())->get();
    }

    public function getEmptyBody()
    {
        $request = (new MockProtocolVersion())->get();

        $this->assertEqual->equal('http://localhost:8000/', (string) $request->getUri());
    }

    public function getNotEmptyBody()
    {
        $this->assertEqual->equal('http://127.0.0.1/path', (string) $this->request->getUri());
    }

    public function withBody()
    {
        $request = (new MockProtocolVersion())->get();
        $request = $this->request->withUri($request->getUri());

        $this->assertEqual->equal('http://127.0.0.1/path', (string) $this->request->getUri());
        $this->assertEqual->equal('http://localhost:8000/', (string) $request->getUri());
    }
}
