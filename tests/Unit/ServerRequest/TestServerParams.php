<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockProtocolVersion;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertArray;

class TestServerParams
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual, private AssertArray $assertArray)
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function getServerParams()
    {
        $serverParams = $this->request->getServerParams();

        $this->assertArray->count(4, $serverParams);
        $this->assertArray->hasKey('REQUEST_METHOD', $serverParams);
        $this->assertEqual->equal('GET', $serverParams['REQUEST_METHOD']);
    }
}
