<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestInterface;

class GivenServerRequestFromGlobalsFactory
{
    public ServerRequestFromGlobalsFactory $requestFromGlobalsFactory;

    public function createGivenServerRequest(string $requestClass): ServerRequestInterface
    {
        $this->requestFromGlobalsFactory
            ->serverRequestFactory
            ->setRequestClass($requestClass);

        return $this->requestFromGlobalsFactory->createServerRequest();
    }
}
