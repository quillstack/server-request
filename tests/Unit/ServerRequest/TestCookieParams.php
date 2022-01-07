<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockCookie;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertArray;

class TestCookieParams
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual, private AssertArray $assertArray)
    {
        $this->request = (new MockCookie())->get();
    }

    public function getCookieParams()
    {
        $cookieParams = $this->request->getCookieParams();

        $this->assertArray->count(1, $cookieParams);
        $this->assertArray->hasKey('cookie', $cookieParams);
        $this->assertEqual->equal('test', $cookieParams['cookie']);
    }

    public function withCookieParams()
    {
        $request = $this->request->withCookieParams(['new' => 'yes']);

        $this->assertEqual->equal(['cookie' => 'test'], $this->request->getCookieParams());
        $this->assertEqual->equal(['new' => 'yes'], $request->getCookieParams());
    }
}
