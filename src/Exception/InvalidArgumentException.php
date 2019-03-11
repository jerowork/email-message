<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage\Exception;

final class InvalidArgumentException extends EmailMessageException
{
    public static function invalidEmail(string $email) : InvalidArgumentException
    {
        return new self(sprintf('Invalid email %s', $email), static::INVALID_EMAIL);
    }
}
