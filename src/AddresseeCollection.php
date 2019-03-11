<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage;

use Countable;
use JsonSerializable;

final class AddresseeCollection implements Countable, JsonSerializable
{
    /**
     * @var Addressee[]
     */
    private $addressees;

    public function __construct()
    {
        $this->addressees = [];
    }

    public function add(Addressee ...$addressees) : self
    {
        foreach ($addressees as $addressee) {
            $this->addressees[(string) $addressee->getEmail()] = $addressee;
        }

        return $this;
    }

    public function remove(Addressee ...$addressees) : self
    {
        foreach ($addressees as $addressee) {
            if (isset($this->addressees[(string) $addressee->getEmail()]) === false) {
                continue;
            }

            unset($this->addressees[(string) $addressee->getEmail()]);
        }

        return $this;
    }

    /**
     * @return Addressee[]
     */
    public function getAddressees() : array
    {
        return array_values($this->addressees);
    }

    public function equals(AddresseeCollection $collection) : bool
    {
        if (count(array_diff_key($this->addressees, $collection->addressees)) > 0) {
            return false;
        }

        foreach ($this->addressees as $email => $recipient) {
            if (isset($collection->addressees[$email]) === false ||
                $collection->addressees[$email]->equals($recipient) === false
            ) {
                return false;
            }
        }

        return true;
    }

    public function count() : int
    {
        return count($this->addressees);
    }

    public function jsonSerialize() : array
    {
        return array_map(
            function (Addressee $addressee) : array {
                return $addressee->jsonSerialize();
            },
            array_values($this->addressees)
        );
    }
}
