<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockBody;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockProtocolVersion;
use QuillStack\Stream\InputStream;
use Quillstack\UnitTests\AssertEqual;

class TestBody
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual)
    {
        $this->request = (new MockBody())->get();
    }

    public function getEmptyBody()
    {
        $request = (new MockProtocolVersion())->get();

        $this->assertEqual->equal('', $request->getBody()->getContents());
    }

    public function getNotEmptyBody()
    {
        $this->assertEqual->equal('Body test.', $this->request->getBody()->getContents());
    }

    public function withBody()
    {
        $inputStream = new InputStream('New body!');
        $request = $this->request->withBody($inputStream);

        $this->assertEqual->equal('Body test.', $this->request->getBody()->getContents());
        $this->assertEqual->equal('New body!', $request->getBody()->getContents());
    }
}
