<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage;

use Jerowork\EmailMessage\Exception\InvalidArgumentException;
use JsonSerializable;

final class Email implements JsonSerializable
{
    /**
     * @var string
     */
    private $email;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $email, bool $sanitize = true)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw InvalidArgumentException::invalidEmail($email);
        }

        $this->email = $sanitize === true ? strtolower($email) : $email;
    }

    public function __toString() : string
    {
        return $this->email;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function equals(Email $email) : bool
    {
        return $this->email === $email->email;
    }

    public function jsonSerialize() : string
    {
        return $this->email;
    }
}
