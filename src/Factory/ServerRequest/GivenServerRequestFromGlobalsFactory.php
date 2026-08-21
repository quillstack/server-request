<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestInterface;

class GivenServerRequestFromGlobalsFactory
{
    public function __construct(
        private readonly ServerRequestFromGlobalsFactory $requestFromGlobalsFactory,
        private readonly ServerRequestFactory $serverRequestFactory
    ) {
        //
    }

    /**
     * Builds the request from the globals as the given class, so a controller declaring a
     * request of its own is handed that one.
     */
    public function createGivenServerRequest(string $requestClass): ServerRequestInterface
    {
        $this->serverRequestFactory->setRequestClass($requestClass);

        return $this->requestFromGlobalsFactory->createServerRequest();
    }
}
