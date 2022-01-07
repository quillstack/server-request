<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit\ServerRequest;

use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\ServerRequest\MockParsedBody;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertArray;

class TestParsedBodyParams
{
    private ServerRequest $request;

    public function __construct(private AssertEqual $assertEqual, private AssertArray $assertArray)
    {
        $this->request = (new MockParsedBody())->get();
    }

    public function getParsedBody()
    {
        $parsedBody = $this->request->getParsedBody();

        $this->assertArray->count(1, $parsedBody);
        $this->assertArray->hasKey('form', $parsedBody);
        $this->assertEqual->equal('input', $parsedBody['form']);
    }

    public function withUploadedFiles()
    {
        $request = $this->request->withParsedBody(['new-form' => 'select']);

        $this->assertEqual->equal(['form' => 'input'], $this->request->getParsedBody());
        $this->assertEqual->equal(['new-form' => 'select'], $request->getParsedBody());
    }
}
