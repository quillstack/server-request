<?php

declare(strict_types=1);

namespace Quillstack\Request\Factory\Exceptions;

use Quillstack\ValidatorInterface\ValidationExceptionInterface;
use RuntimeException;

class RequiredParamFromGlobalsNotFoundException extends RuntimeException implements ValidationExceptionInterface
{
    //
}
