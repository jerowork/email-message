<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage\Exception;

use Exception;

class EmailMessageException extends Exception
{
    public const INVALID_EMAIL     = 1000;
    public const INVALID_BODY_TYPE = 1010;
    public const INVALID_ADDRESSEE = 1020;
}
