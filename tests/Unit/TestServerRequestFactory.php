<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Tests\Unit;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Quillstack\ServerRequest\Factory\ServerRequestFactory;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Types\AssertBoolean;
use Quillstack\Uri\Factory\UriFactory;

/**
 * A request built from a method and an address, rather than from the globals.
 *
 * Everything else in this package builds what a server handed over. There was no way to make one
 * from a verb and a URI, which is what a test wants and what PSR-17 describes — so a test had to
 * fill in `$_SERVER` and hope, or call the constructor with six arguments.
 */
class TestServerRequestFactory
{
    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        //
    }

    private function factory(): ServerRequestFactory
    {
        return new ServerRequestFactory();
    }

    public function itNeedsNothingToBeBuilt()
    {
        $this->assertBoolean->isTrue(new ServerRequestFactory() instanceof ServerRequestFactoryInterface);
    }

    public function aMethodAndAnAddressAreEnough()
    {
        $request = $this->factory()->createServerRequest('GET', 'https://api.example.org/users/42');

        $this->assertEqual->equal('GET', $request->getMethod());
        $this->assertEqual->equal('/users/42', $request->getRequestTarget());
        $this->assertEqual->equal('api.example.org', $request->getUri()->getHost());
    }

    /**
     * A request goes out with the host it is going to, which every server needs and nobody
     * should have to remember to set.
     */
    public function theHostHeaderIsSetFromTheAddress()
    {
        $this->assertEqual->equal(
            'api.example.org',
            $this->factory()->createServerRequest('GET', 'https://api.example.org/')->getHeaderLine('Host')
        );
    }

    /**
     * A port which is not the usual one for the scheme belongs in the header, and one which is
     * says nothing the scheme did not.
     */
    public function andCarriesAPortWhereThereIsOneWorthCarrying()
    {
        $this->assertEqual->equal(
            'api.example.org:8443',
            $this->factory()->createServerRequest('GET', 'https://api.example.org:8443/')->getHeaderLine('Host')
        );
        $this->assertEqual->equal(
            'api.example.org',
            $this->factory()->createServerRequest('GET', 'https://api.example.org:443/')->getHeaderLine('Host')
        );
    }

    public function theQueryStringIsReadIntoParameters()
    {
        $request = $this->factory()->createServerRequest('GET', 'https://api.example.org/users?page=2&sort=name');

        $this->assertEqual->equal(['page' => '2', 'sort' => 'name'], $request->getQueryParams());
    }

    public function serverParametersAreWhateverWasGiven()
    {
        $request = $this->factory()->createServerRequest('GET', 'https://api.example.org/', ['REMOTE_ADDR' => '10.0.0.1']);

        $this->assertEqual->equal(['REMOTE_ADDR' => '10.0.0.1'], $request->getServerParams());
    }

    public function anAlreadyBuiltUriIsTakenAsItIs()
    {
        $uri = (new UriFactory())->createUri('https://api.example.org/orders');
        $request = $this->factory()->createServerRequest('POST', $uri);

        $this->assertBoolean->isTrue($request->getUri() === $uri);
        $this->assertEqual->equal('POST', $request->getMethod());
    }
}
