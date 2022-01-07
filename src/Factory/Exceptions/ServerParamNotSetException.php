<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Factory\Exceptions;

use Quillstack\ValidatorInterface\ValidationExceptionInterface;
use RuntimeException;

class ServerParamNotSetException extends RuntimeException implements ValidationExceptionInterface
{
    //
}
