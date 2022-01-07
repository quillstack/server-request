<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockQuery;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertArray;

class TestQueryParams
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual, private AssertArray $assertArray)
    {
        $this->request = (new MockQuery())->get();
    }

    public function getQueryParams()
    {
        $queryParams = $this->request->getQueryParams();

        $this->assertArray->count(1, $queryParams);
        $this->assertArray->hasKey('query', $queryParams);
        $this->assertEqual->equal('value', $queryParams['query']);
    }

    public function withCookieParams()
    {
        $request = $this->request->withQueryParams(['more' => 'no']);

        $this->assertEqual->equal(['query' => 'value'], $this->request->getQueryParams());
        $this->assertEqual->equal(['more' => 'no'], $request->getQueryParams());
    }
}
