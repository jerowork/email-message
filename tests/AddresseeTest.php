<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage\Test;

use Jerowork\EmailMessage\Addressee;
use Jerowork\EmailMessage\Email;
use Jerowork\EmailMessage\Exception\EmailMessageException;
use Jerowork\EmailMessage\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class AddresseeTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_construct() : void
    {
        $email     = new Email('hello@jero.work');
        $addressee = new Addressee($email, 'Jero Work');

        $this->assertSame($email, $addressee->getEmail());
        $this->assertSame('Jero Work', $addressee->getName());
    }

    /**
     * @test
     */
    public function it_should_create_from_string() : void
    {
        $addressee = Addressee::fromString('Jero Work <hello@jero.work>');
        $this->assertSame('hello@jero.work', (string) $addressee->getEmail());
        $this->assertSame('Jero Work', $addressee->getName());

        $addressee = Addressee::fromString('hello@jero.work');
        $this->assertSame('hello@jero.work', (string) $addressee->getEmail());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_on_invalid_create_from_string() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(EmailMessageException::INVALID_ADDRESSEE);

        Addressee::fromString('');
    }

    /**
     * @test
     */
    public function it_should_return_to_string() : void
    {
        $addressee = new Addressee(new Email('hello@jero.work'), 'Jero Work');
        $this->assertSame('Jero Work <hello@jero.work>', (string) $addressee);

        $addressee = new Addressee(new Email('hello@jero.work'));
        $this->assertSame('hello@jero.work', (string) $addressee);
    }

    /**
     * @test
     */
    public function it_should_update_email_immutable() : void
    {
        $addressee        = new Addressee(new Email('hello@jero.work'));
        $anotherAddressee = $addressee->withEmail(new Email('info@jero.work'));

        $this->assertNotSame($addressee, $anotherAddressee);
        $this->assertSame('hello@jero.work', (string) $addressee->getEmail());
        $this->assertSame('info@jero.work', (string) $anotherAddressee->getEmail());
    }

    /**
     * @test
     */
    public function it_should_update_name_immutable() : void
    {
        $addressee        = new Addressee(new Email('hello@jero.work'), 'Jero Work');
        $anotherAddressee = $addressee->withName('Somebody else');

        $this->assertNotSame($addressee, $anotherAddressee);
        $this->assertSame('Jero Work', $addressee->getName());
        $this->assertSame('Somebody else', $anotherAddressee->getName());
    }

    /**
     * @test
     */
    public function it_should_check_equality() : void
    {
        $addressee        = Addressee::fromString('Jero Work <hello@jero.work>');
        $anotherAddressee = Addressee::fromString('Jero Work <hello@jero.work>');
        $this->assertTrue($addressee->equals($anotherAddressee));

        $addressee        = Addressee::fromString('Jero Work <hello@jero.work>');
        $anotherAddressee = Addressee::fromString('hello@jero.work');
        $this->assertFalse($addressee->equals($anotherAddressee));

        $addressee        = Addressee::fromString('Jero Work <hello@jero.work>');
        $anotherAddressee = Addressee::fromString('Jero Work <info@jero.work>');
        $this->assertFalse($addressee->equals($anotherAddressee));

        $addressee        = Addressee::fromString('Jero Work <hello@jero.work>');
        $anotherAddressee = Addressee::fromString('Somebody else <hello@jero.work>');
        $this->assertFalse($addressee->equals($anotherAddressee));

        $addressee        = Addressee::fromString('hello@jero.work');
        $anotherAddressee = Addressee::fromString('hello@jero.work');
        $this->assertTrue($addressee->equals($anotherAddressee));
    }

    /**
     * @test
     */
    public function it_should_be_json_serializable() : void
    {
        $addressee = Addressee::fromString('Jero Work <hello@jero.work>');
        $this->assertSame(
            [
                'email' => 'hello@jero.work',
                'name'  => 'Jero Work',
            ],
            $addressee->jsonSerialize()
        );

        $addressee = Addressee::fromString('hello@jero.work');
        $this->assertSame(
            [
                'email' => 'hello@jero.work',
                'name'  => null,
            ],
            $addressee->jsonSerialize()
        );
    }
}
