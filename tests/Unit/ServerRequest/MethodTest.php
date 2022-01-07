<?php

declare(strict_types=1);

namespace Quillstack\Request\ServerRequest;

use PHPUnit\Framework\TestCase;
use Quillstack\Request\Factory\Exceptions\RequestMethodNotKnownException;
use Quillstack\Request\Tests\Unit\ServerRequest;
use Quillstack\Request\Tests\Mocks\ServerRequest\MockProtocolVersion;

final class MethodTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function testGetMethod()
    {
        $this->assertEquals('GET', $this->request->getMethod());
    }

    public function testWithMethod()
    {
        $request = $this->request->withMethod('POST');

        $this->assertEquals('GET', $this->request->getMethod());
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testWithNonExistingMethod()
    {
        $this->expectException(RequestMethodNotKnownException::class);

        $this->request->withMethod('SMURFS');
    }
}
