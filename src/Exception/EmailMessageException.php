<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage\Exception;

use Exception;

class EmailMessageException extends Exception
{
    public const INVALID_EMAIL = 1000;
}
