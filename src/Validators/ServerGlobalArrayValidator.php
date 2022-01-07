<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Validators;

use Quillstack\ServerRequest\Factory\Exceptions\RequiredParamFromGlobalsNotFoundException;
use Quillstack\ServerRequest\Factory\ServerRequest\RequestFromGlobalsFactory;
use Quillstack\ValidatorInterface\ValidatorInterface;

final class ServerGlobalArrayValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private const REQUIRED_SERVER_PARAMS = [
        RequestFromGlobalsFactory::SERVER_REQUEST_METHOD,
        RequestFromGlobalsFactory::SERVER_HTTP_HOST,
        RequestFromGlobalsFactory::SERVER_REQUEST_URI,
        RequestFromGlobalsFactory::SERVER_SERVER_PROTOCOL,
    ];

    private array $server;

    public function setServer(array $server): self
    {
        $this->server = $server;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        foreach (self::REQUIRED_SERVER_PARAMS as $requiredServerParam) {
            if (!isset($this->server[$requiredServerParam])) {
                throw new RequiredParamFromGlobalsNotFoundException("Not found: \$_SERVER['{$requiredServerParam}']");
            }
        }

        return true;
    }
}
