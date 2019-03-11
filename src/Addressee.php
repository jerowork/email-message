<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage;

use Jerowork\EmailMessage\Exception\InvalidArgumentException;
use JsonSerializable;

final class Addressee implements JsonSerializable
{
    private const VALID_FROM_STRING_PATTERN = '^(.+?)\s<(.+?)>|(.+)$';

    /**
     * @var Email
     */
    private $email;

    /**
     * @var string | null
     */
    private $name;

    public function __construct(Email $email, string $name = null)
    {
        $this->email = $email;
        $this->name  = $name;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromString(string $addressee) : Addressee
    {
        $pregMatch = preg_match(
            sprintf('/%s/', self::VALID_FROM_STRING_PATTERN),
            $addressee,
            $matches,
            PREG_UNMATCHED_AS_NULL
        );

        if ($pregMatch !== 1) {
            throw InvalidArgumentException::invalidAddressee($addressee);
        }

        if (isset($matches[1], $matches[2]) === true) {
            return new self(new Email($matches[2]), $matches[1]);
        }

        if (isset($matches[3]) === true) {
            return new self(new Email($matches[3]));
        }

        throw InvalidArgumentException::invalidAddressee($addressee);
    }

    public function __toString() : string
    {
        if ($this->name === null) {
            return (string) $this->email;
        }

        return sprintf('%s <%s>', $this->name, (string) $this->email);
    }

    public function withEmail(Email $email) : Addressee
    {
        $addressee        = clone $this;
        $addressee->email = $email;

        return $addressee;
    }

    public function withName(string $name) : Addressee
    {
        $addressee       = clone $this;
        $addressee->name = $name;

        return $addressee;
    }

    public function getEmail() : Email
    {
        return $this->email;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function equals(Addressee $addressee) : bool
    {
        return $this->name === $addressee->name && $this->email->equals($addressee->email) === true;
    }

    public function jsonSerialize() : array
    {
        return [
            'email' => $this->email->jsonSerialize(),
            'name'  => $this->name,
        ];
    }
}
