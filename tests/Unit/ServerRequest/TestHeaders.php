<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\Tests\Mocks\AbstractMock;
use Quillstack\Stream\EmptyStream;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;
use Quillstack\UnitTests\Types\AssertObject;

/**
 * The headers of the request, read through it rather than through the bag it keeps them in.
 */
class TestHeaders extends AbstractMock
{
    public const SERVER = [
        'REQUEST_METHOD' => 'GET',
        'HTTP_HOST' => 'localhost:8000',
        'REQUEST_URI' => '/',
        'SERVER_PROTOCOL' => '1.1',
        'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
    ];

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean,
        private AssertObject $assertObject
    ) {
        parent::__construct();
    }

    public function headersAreListed()
    {
        $headers = $this->request->getHeaders();

        $this->assertEqual->equal(['localhost:8000'], $headers['Host']);
        $this->assertEqual->equal(['gzip, deflate'], $headers['Accept-Encoding']);
    }

    public function oneHeaderIsRead()
    {
        $this->assertEqual->equal(['gzip, deflate'], $this->request->getHeader('accept-encoding'));
        $this->assertEqual->equal('gzip, deflate', $this->request->getHeaderLine('Accept-Encoding'));
        $this->assertEqual->equal([], $this->request->getHeader('X-Nothing'));
        $this->assertEqual->equal('', $this->request->getHeaderLine('X-Nothing'));
    }

    public function askingWhetherAHeaderIsThere()
    {
        $this->assertBoolean->isTrue($this->request->hasHeader('Host'));
        $this->assertBoolean->isTrue($this->request->hasHeader('host'));
        $this->assertBoolean->isFalse($this->request->hasHeader('X-Nothing'));
    }

    /**
     * PSR-7 promises a stream, and a request built without a body has to answer with one.
     */
    public function aRequestWithoutABodyStillHasOne()
    {
        $request = $this->request->withBody(new EmptyStream());

        $this->assertObject->instanceOf(EmptyStream::class, $request->getBody());
    }
}
