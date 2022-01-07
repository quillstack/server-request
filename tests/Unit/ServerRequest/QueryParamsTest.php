<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\ServerRequest;

use PHPUnit\Framework\TestCase;
use Quillstack\ServerRequest\Tests\Unit\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockQuery;

final class QueryParamsTest extends TestCase
{
    private ServerRequest $request;

    protected function setUp(): void
    {
        $this->request = (new MockQuery())->get();
    }

    public function testGetQueryParams()
    {
        $queryParams = $this->request->getQueryParams();

        $this->assertCount(1, $queryParams);
        $this->assertArrayHasKey('query', $queryParams);
        $this->assertEquals('value', $queryParams['query']);
    }

    public function testWithCookieParams()
    {
        $request = $this->request->withQueryParams(['more' => 'no']);

        $this->assertEquals(['query' => 'value'], $this->request->getQueryParams());
        $this->assertEquals(['more' => 'no'], $request->getQueryParams());
    }
}
