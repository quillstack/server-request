<?php

declare(strict_types=1);

namespace Quillstack\Request\Validators;

use Quillstack\Request\Factory\Exceptions\ServerParamNotSetException;
use Quillstack\ValidatorInterface\ValidatorInterface;

final class ServerParamValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private const REQUIRED_SERVER_PARAMS = [
        'protocolVersion',
        'headers',
        'serverParams',
        'cookieParams',
        'queryParams',
        'uploadedFiles',
        'parsedBody',
    ];

    private array $serverParams;

    public function setServerParams(array $serverParams): self
    {
        $this->serverParams = $serverParams;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        foreach (self::REQUIRED_SERVER_PARAMS as $requiredServerParam) {
            if (!isset($this->serverParams[$requiredServerParam])) {
                throw new ServerParamNotSetException("Server param not set: {$requiredServerParam}");
            }
        }

        return true;
    }
}