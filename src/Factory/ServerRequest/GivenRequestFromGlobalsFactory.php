<?php

declare(strict_types=1);

namespace Quillstack\Request\Factory\ServerRequest;

use Psr\Http\Message\ServerRequestInterface;

class GivenRequestFromGlobalsFactory
{
    public RequestFromGlobalsFactory $requestFromGlobalsFactory;

    public function createGivenServerRequest(string $requestClass): ServerRequestInterface
    {
        $this->requestFromGlobalsFactory
            ->serverRequestFactory
            ->setRequestClass($requestClass);

        return $this->requestFromGlobalsFactory->createServerRequest();
    }
}
