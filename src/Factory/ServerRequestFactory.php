<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Factory;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Quillstack\HeaderBag\HeaderBag;
use Quillstack\ParameterBag\ParameterBag;
use Quillstack\ServerRequest\ServerRequest;
use Quillstack\Uri\Factory\UriFactory;

/**
 * A request built from a method and an address.
 *
 * Everything else here builds a request from the globals, which is what a server does. This is
 * for everybody else: a test that wants to ask the application something, a client of this
 * package that has a URI and a verb and no `$_SERVER` to speak of. It is PSR-17, so anything
 * expecting that interface can be handed this.
 */
final class ServerRequestFactory implements ServerRequestFactoryInterface
{
    private readonly UriFactory $uriFactory;

    /**
     * The URI factory needs nothing itself, so one is made here where none was given.
     */
    public function __construct(?UriFactory $uriFactory = null)
    {
        $this->uriFactory = $uriFactory ?? new UriFactory();
    }

    /**
     * {@inheritDoc}
     *
     * @param array<string, mixed> $serverParams
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        $uri = $uri instanceof UriInterface ? $uri : $this->uriFactory->createUri((string) $uri);
        $host = $uri->getHost();
        $port = $uri->getPort();

        // A request goes out with the host it is going to, which every server needs and which
        // nobody should have to remember to set.
        $headers = $host === ''
            ? []
            : ['Host' => $port === null ? $host : "{$host}:{$port}"];

        $parsed = [];
        parse_str($uri->getQuery(), $parsed);

        // Every key is a name — everything before an `=` in a query string is one — but
        // `parse_str()` is typed as though a numeric one could reach the top level.
        $query = [];

        foreach ($parsed as $name => $value) {
            $query[(string) $name] = $value;
        }

        return new ServerRequest(
            method: $method,
            uri: $uri,
            protocolVersion: '1.1',
            headerBag: new HeaderBag($headers),
            serverParams: new ParameterBag($serverParams),
            queryParams: new ParameterBag($query),
        );
    }
}
