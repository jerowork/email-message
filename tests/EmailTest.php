<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage\Test;

use Jerowork\EmailMessage\Email;
use Jerowork\EmailMessage\Exception\EmailMessageException;
use Jerowork\EmailMessage\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_construct() : void
    {
        $email = new Email('hello@jero.work');
        $this->assertSame('hello@jero.work', $email->getEmail());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_on_invalid_email() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(EmailMessageException::INVALID_EMAIL);

        new Email('an-invalid-email');
    }

    /**
     * @test
     */
    public function it_should_not_sanitize_on_construct() : void
    {
        $email = new Email('Hello@jero.work', false);
        $this->assertSame('Hello@jero.work', $email->getEmail());
    }

    /**
     * @test
     */
    public function it_should_check_equality() : void
    {
        $email        = new Email('hello@jero.work');
        $anotherEmail = new Email('hello@jero.work');
        $this->assertTrue($email->equals($anotherEmail));

        $email        = new Email('hello@jero.work');
        $anotherEmail = new Email('info@jero.work');
        $this->assertFalse($email->equals($anotherEmail));
    }

    /**
     * @test
     */
    public function it_should_be_json_serializable() : void
    {
        $email = new Email('hello@jero.work');
        $this->assertSame('hello@jero.work', $email->jsonSerialize());
    }
}
