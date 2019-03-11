<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage\Test;

use Jerowork\EmailMessage\Addressee;
use Jerowork\EmailMessage\AddresseeCollection;
use PHPUnit\Framework\TestCase;

final class AddresseeCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_construct() : void
    {
        $collection = new AddresseeCollection();
        $this->assertCount(0, $collection);
        $this->assertSame([], $collection->getAddressees());
    }

    /**
     * @test
     */
    public function it_should_add_addressees() : void
    {
        $addressee        = Addressee::fromString('Jero Work <hello@jero.work>');
        $anotherAddressee = Addressee::fromString('info@jero.work');

        $collection = new AddresseeCollection();
        $collection->add($addressee, $anotherAddressee);
        $this->assertCount(2, $collection);
        $this->assertSame([$addressee, $anotherAddressee], $collection->getAddressees());
    }

    /**
     * @test
     */
    public function it_should_remove_addressees() : void
    {
        $addressee        = Addressee::fromString('Jero Work <hello@jero.work>');
        $anotherAddressee = Addressee::fromString('info@jero.work');

        $collection = new AddresseeCollection();
        $collection->add($addressee, $anotherAddressee);
        $this->assertCount(2, $collection);
        $this->assertSame([$addressee, $anotherAddressee], $collection->getAddressees());

        $collection->remove($addressee, $anotherAddressee, Addressee::fromString('Somebody else <some@example.com>'));
        $this->assertCount(0, $collection);
        $this->assertSame([], $collection->getAddressees());
    }

    /**
     * @test
     */
    public function it_should_check_equality() : void
    {
        $addressee        = Addressee::fromString('Jero Work <hello@jero.work>');
        $anotherAddressee = Addressee::fromString('info@jero.work');

        $collection = new AddresseeCollection();
        $collection->add($addressee, $anotherAddressee);

        $anotherCollection = new AddresseeCollection();
        $anotherCollection->add($anotherAddressee, $addressee);

        $this->assertTrue($collection->equals($anotherCollection));

        $thirdCollection = new AddresseeCollection();
        $thirdCollection->add($anotherAddressee);

        $this->assertFalse($collection->equals($thirdCollection));

        $fourthCollection = new AddresseeCollection();
        $fourthCollection->add($addressee, Addressee::fromString('Somebody else <info@jero.work>'));

        $this->assertFalse($collection->equals($fourthCollection));
    }

    /**
     * @test
     */
    public function it_should_be_json_serializable() : void
    {
        $addressee        = Addressee::fromString('Jero Work <hello@jero.work>');
        $anotherAddressee = Addressee::fromString('info@jero.work');

        $collection = new AddresseeCollection();
        $collection->add($addressee, $anotherAddressee);

        $this->assertSame(
            [
                [
                    'email' => 'hello@jero.work',
                    'name'  => 'Jero Work',
                ],
                [
                    'email' => 'info@jero.work',
                    'name'  => null,
                ],
            ],
            $collection->jsonSerialize()
        );
    }
}
