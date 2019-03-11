<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage\Test;

use Jerowork\EmailMessage\Body;
use PHPUnit\Framework\TestCase;

final class BodyTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_construct() : void
    {
        $body = new Body('<p>Some html body</p>', 'Some text body');
        $this->assertSame('<p>Some html body</p>', $body->getHtml());
        $this->assertSame('Some text body', $body->getText());
    }

    /**
     * @test
     */
    public function it_should_update_html_body_immutable() : void
    {
        $body        = new Body('<p>Some html body</p>');
        $updatedBody = $body->withHtml('<p>Updated body</p>');

        $this->assertNotSame($body, $updatedBody);
        $this->assertSame('<p>Some html body</p>', $body->getHtml());
        $this->assertSame('<p>Updated body</p>', $updatedBody->getHtml());
    }

    /**
     * @test
     */
    public function it_should_update_text_body_immutable() : void
    {
        $body        = new Body(null, '<p>Some text body</p>');
        $updatedBody = $body->withText('<p>Updated body</p>');

        $this->assertNotSame($body, $updatedBody);
        $this->assertSame('<p>Some text body</p>', $body->getText());
        $this->assertSame('<p>Updated body</p>', $updatedBody->getText());
    }

    /**
     * @test
     */
    public function it_should_check_if_body_is_valid() : void
    {
        $body = new Body();
        $this->assertFalse($body->isValid());

        $body = new Body('<p>Some html body</p>');
        $this->assertTrue($body->isValid());

        $body = new Body(null, '<p>Some text body</p>');
        $this->assertTrue($body->isValid());

        $body = new Body('<p>Some html body</p>', 'Some text body');
        $this->assertTrue($body->isValid());
    }

    /**
     * @test
     */
    public function it_should_check_equality() : void
    {
        $body        = new Body('<p>Some html body</p>');
        $anotherBody = new Body('<p>Some html body</p>');
        $this->assertTrue($body->equals($anotherBody));

        $body        = new Body('<p>Some html body</p>');
        $anotherBody = new Body('<p>Another html body</p>');
        $this->assertFalse($body->equals($anotherBody));

        $body        = new Body('<p>Some html body</p>', 'Some text body');
        $anotherBody = new Body('<p>Some html body</p>', 'Some text body');
        $this->assertTrue($body->equals($anotherBody));

        $body        = new Body('<p>Some html body</p>');
        $anotherBody = new Body('<p>Some html body</p>', 'Some text body');
        $this->assertFalse($body->equals($anotherBody));

        $body        = new Body(null, 'Some text body');
        $anotherBody = new Body(null, 'Some text body');
        $this->assertTrue($body->equals($anotherBody));

        $body        = new Body(null, 'Some text body');
        $anotherBody = new Body(null, 'Another text body');
        $this->assertFalse($body->equals($anotherBody));
    }

    /**
     * @test
     */
    public function it_should_be_json_serializable() : void
    {
        $body = new Body('<p>Some html body</p>', 'Some text body');
        $this->assertSame(
            [
                'html' => '<p>Some html body</p>',
                'text' => 'Some text body',
            ],
            $body->jsonSerialize()
        );

        $body = new Body(null, null);
        $this->assertSame(
            [
                'html' => null,
                'text' => null,
            ],
            $body->jsonSerialize()
        );
    }
}
