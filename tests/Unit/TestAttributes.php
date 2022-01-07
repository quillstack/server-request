<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockProtocolVersion;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertNull;

final class TestAttributes
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual, private AssertNull $assertNull)
    {
        $this->request = (new MockProtocolVersion())->get();
    }

    public function getEmptyAttributes()
    {
        $this->assertEqual->equal([], $this->request->getAttributes());
    }

    public function withAttribute()
    {
        $attributes = $this->request->withAttribute('name', 'value');

        $this->assertEqual->equal([], $this->request->getAttributes());
        $this->assertNull->isNull($this->request->getAttribute('name'));
        $this->assertEqual->equal(['name' => 'value'], $attributes->getAttributes());
        $this->assertEqual->equal('value', $attributes->getAttribute('name'));
    }

    public function withoutAttribute()
    {
        $attributes = $this->request->withAttribute('name', 'value');
        $attributes = $attributes->withoutAttribute('name');
        $attributes = $attributes->withoutAttribute('name-not-exists');

        $this->assertEqual->equal([], $attributes->getAttributes());
        $this->assertNull->isNull($attributes->getAttribute('name'));
    }
}
