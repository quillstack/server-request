<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriFactoryInterface;
use Quillstack\DI\Container;
use Quillstack\ServerRequest\Factory\Exceptions\RequiredParamFromGlobalsNotFoundException;
use Quillstack\ServerRequest\Factory\Exceptions\ServerParamNotSetException;
use Quillstack\ServerRequest\Factory\Exceptions\ServerRequestMethodNotKnownException;
use Quillstack\ServerRequest\Factory\Exceptions\UnknownServerRequestClassException;
use Quillstack\ServerRequest\Factory\ServerRequest\GivenServerRequestFromGlobalsFactory;
use Quillstack\ServerRequest\Factory\ServerRequest\ServerRequestFactory;
use Quillstack\ServerRequest\Factory\ServerRequest\ServerRequestFromGlobalsFactory;
use Quillstack\ServerRequest\ServerRequest;
use Quillstack\ServerRequest\Tests\Mocks\AbstractMock;
use Quillstack\ServerRequest\Tests\Mocks\Requests\MockCustomRequest;
use Quillstack\Stream\InputStream;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\AssertExceptions;
use Quillstack\UnitTests\Types\AssertObject;
use Quillstack\Uri\Factory\UriFactory;
use Quillstack\Uri\Uri;

/**
 * What the factories do when what they are given is not what they expect, which nothing
 * used to ask about.
 */
class TestFactories
{
    public function __construct(
        private AssertEqual $assertEqual,
        private AssertObject $assertObject,
        private AssertExceptions $assertExceptions
    ) {
        //
    }

    /**
     * @param array<string, mixed> $server
     */
    private function container(array $server): Container
    {
        return new Container([
            StreamInterface::class => InputStream::class,
            UriFactoryInterface::class => UriFactory::class,
            ServerRequestFromGlobalsFactory::class => ['server' => $server],
        ]);
    }

    public function aMethodNobodyKnowsIsReported()
    {
        $this->assertExceptions->expect(ServerRequestMethodNotKnownException::class);

        $server = AbstractMock::SERVER;
        $server['REQUEST_METHOD'] = 'BREW';

        $this->container($server)->get(ServerRequestFromGlobalsFactory::class)->createServerRequest();
    }

    /**
     * A request cannot be built out of a server array missing what it is built from.
     */
    public function aMissingServerParamIsReported()
    {
        $this->assertExceptions->expect(RequiredParamFromGlobalsNotFoundException::class);

        $server = AbstractMock::SERVER;
        unset($server['REQUEST_URI']);

        $this->container($server)->get(ServerRequestFromGlobalsFactory::class)->createServerRequest();
    }

    public function httpsIsReadFromTheServerArray()
    {
        $server = AbstractMock::SERVER;
        $server['HTTPS'] = 'on';

        $request = $this->container($server)->get(ServerRequestFromGlobalsFactory::class)->createServerRequest();

        $this->assertEqual->equal('https', $request->getUri()->getScheme());
    }

    /**
     * A controller declaring a request of its own is handed one of that class.
     */
    public function aRequestIsBuiltAsTheGivenClass()
    {
        $container = $this->container(AbstractMock::SERVER);
        $factory = $container->get(GivenServerRequestFromGlobalsFactory::class);

        $request = $factory->createGivenServerRequest(MockCustomRequest::class);

        $this->assertObject->instanceOf(MockCustomRequest::class, $request);
    }

    public function aRequestClassNobodyKnowsIsReported()
    {
        $this->assertExceptions->expect(UnknownServerRequestClassException::class);

        $container = $this->container(AbstractMock::SERVER);
        $container->get(ServerRequestFactory::class)->setRequestClass('Nothing\\Here');
    }

    /**
     * The factory is handed the pieces a request is built from. Missing one is not the same
     * as a server array missing a key, and is reported separately.
     */
    public function aMissingPieceIsReported()
    {
        $this->assertExceptions->expect(ServerParamNotSetException::class);

        $container = $this->container(AbstractMock::SERVER);
        $container->get(ServerRequestFactory::class)->createServerRequest('GET', new Uri(), []);
    }

    public function withoutAClassTheRequestIsTheDefaultOne()
    {
        $request = $this->container(AbstractMock::SERVER)
            ->get(ServerRequestFromGlobalsFactory::class)
            ->createServerRequest();

        $this->assertObject->instanceOf(ServerRequest::class, $request);
    }
}
