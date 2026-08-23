<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockBody;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockProtocolVersion;
use Quillstack\Stream\InputStream;
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

    /**
     * `getContents()` is what is left of the stream, so reading it twice gives it once —
     * which is what PSR-7 says of it. `__toString()` is the one that always gives the whole
     * thing, and is what to reach for when a body is read more than once.
     */
    public function theBodyReadTwiceIsReadOnce()
    {
        $request = (new MockBody())->get();

        $this->assertEqual->equal('Body test.', $request->getBody()->getContents());
        $this->assertEqual->equal('', $request->getBody()->getContents());
        $this->assertEqual->equal('Body test.', (string) $request->getBody());
    }

    public function withBody()
    {
        $request = (new MockBody())->get();
        $withNew = $request->withBody(new InputStream('New body!'));

        $this->assertEqual->equal('Body test.', (string) $request->getBody());
        $this->assertEqual->equal('New body!', (string) $withNew->getBody());
    }
}
