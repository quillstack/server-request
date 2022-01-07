<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\Factory\Exceptions;

use Quillstack\ValidatorInterface\ValidationExceptionInterface;
use RuntimeException;

class RequiredParamFromGlobalsNotFoundException extends RuntimeException implements ValidationExceptionInterface
{
    //
}
