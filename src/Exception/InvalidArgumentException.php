<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage\Exception;

final class InvalidArgumentException extends EmailMessageException
{
    public static function invalidEmail(string $email) : InvalidArgumentException
    {
        return new self(sprintf('Invalid email %s', $email), static::INVALID_EMAIL);
    }

    public static function invalidBodyType(string $type) : InvalidArgumentException
    {
        return new self(sprintf('Invalid body type %s', $type), static::INVALID_BODY_TYPE);
    }

    public static function invalidAddressee(string $addressee) : InvalidArgumentException
    {
        return new self(sprintf('Invalid addressee %s', $addressee), static::INVALID_ADDRESSEE);
    }
}
