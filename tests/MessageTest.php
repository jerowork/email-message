<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage\Test;

use Jerowork\EmailMessage\Addressee;
use Jerowork\EmailMessage\Body;
use Jerowork\EmailMessage\Email;
use Jerowork\EmailMessage\Message;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_construct() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $this->assertSame($sender, $message->getSender());
        $this->assertSame('Subject', $message->getSubject());
        $this->assertSame($body, $message->getBody());

        $this->assertCount(0, $message->getToRecipients());
        $this->assertCount(0, $message->getCcRecipients());
        $this->assertCount(0, $message->getBccRecipients());
        $this->assertCount(0, $message->getAttachments());
        $this->assertNull($message->getReplyToEmail());
    }

    /**
     * @test
     */
    public function it_should_update_sender_immutable() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message->withSender(Addressee::fromString('info@jero.work'));

        $this->assertNotSame($message, $anotherMessage);
        $this->assertNotSame($message->getSender(), $anotherMessage->getSender());
        $this->assertSame('Jero Work <hello@jero.work>', (string) $message->getSender());
        $this->assertSame('info@jero.work', (string) $anotherMessage->getSender());
    }

    /**
     * @test
     */
    public function it_should_update_subject_immutable() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message->withSubject('Another subject');

        $this->assertNotSame($message, $anotherMessage);
        $this->assertSame('Subject', (string) $message->getSubject());
        $this->assertSame('Another subject', (string) $anotherMessage->getSubject());
    }

    /**
     * @test
     */
    public function it_should_update_html_body_immutable() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body('<p>Some html body</p>');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message->withHtmlBody('<p>Another html body</p>');

        $this->assertNotSame($message, $anotherMessage);
        $this->assertNotSame($message->getBody(), $anotherMessage->getBody());
        $this->assertSame('<p>Some html body</p>', $message->getBody()->getHtml());
        $this->assertSame('<p>Another html body</p>', $anotherMessage->getBody()->getHtml());
    }

    /**
     * @test
     */
    public function it_should_update_text_body_immutable() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message->withTextBody('Another text body');

        $this->assertNotSame($message, $anotherMessage);
        $this->assertNotSame($message->getBody(), $anotherMessage->getBody());
        $this->assertSame('Some text body', $message->getBody()->getText());
        $this->assertSame('Another text body', $anotherMessage->getBody()->getText());
    }

    /**
     * @test
     */
    public function it_should_update_body_immutable_with_generic_method() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body('<p>Some html body</p>', 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message
            ->withBody('<p>Another html body</p>', Body::HTML)
            ->withBody('Another text body', Body::TEXT);

        $this->assertNotSame($message, $anotherMessage);
        $this->assertNotSame($message->getBody(), $anotherMessage->getBody());
        $this->assertSame('<p>Some html body</p>', $message->getBody()->getHtml());
        $this->assertSame('<p>Another html body</p>', $anotherMessage->getBody()->getHtml());
        $this->assertSame('Some text body', $message->getBody()->getText());
        $this->assertSame('Another text body', $anotherMessage->getBody()->getText());
    }

    /**
     * @test
     */
    public function it_should_add_to_recipient_immutable() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message->withToRecipient(Addressee::fromString('info@jero.work'));

        $this->assertNotSame($message, $anotherMessage);
        $this->assertNotSame($message->getToRecipients(), $anotherMessage->getToRecipients());
        $this->assertCount(0, $message->getToRecipients());
        $this->assertCount(1, $anotherMessage->getToRecipients());
        $this->assertSame('info@jero.work', (string) $anotherMessage->getToRecipients()[0]);

        $thirdMessage = $anotherMessage->withToRecipient(Addressee::fromString('some@example.com'));

        $this->assertNotSame($anotherMessage, $thirdMessage);
        $this->assertNotSame($anotherMessage->getToRecipients(), $thirdMessage->getToRecipients());
        $this->assertCount(0, $message->getToRecipients());
        $this->assertCount(1, $anotherMessage->getToRecipients());
        $this->assertCount(2, $thirdMessage->getToRecipients());
        $this->assertSame('info@jero.work', (string) $thirdMessage->getToRecipients()[0]);
        $this->assertSame('some@example.com', (string) $thirdMessage->getToRecipients()[1]);
    }

    /**
     * @test
     */
    public function it_should_add_cc_recipient_immutable() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message->withCcRecipient(Addressee::fromString('info@jero.work'));

        $this->assertNotSame($message, $anotherMessage);
        $this->assertNotSame($message->getCcRecipients(), $anotherMessage->getCcRecipients());
        $this->assertCount(0, $message->getCcRecipients());
        $this->assertCount(1, $anotherMessage->getCcRecipients());
        $this->assertSame('info@jero.work', (string) $anotherMessage->getCcRecipients()[0]);

        $thirdMessage = $anotherMessage->withCcRecipient(Addressee::fromString('some@example.com'));

        $this->assertNotSame($anotherMessage, $thirdMessage);
        $this->assertNotSame($anotherMessage->getCcRecipients(), $thirdMessage->getCcRecipients());
        $this->assertCount(0, $message->getCcRecipients());
        $this->assertCount(1, $anotherMessage->getCcRecipients());
        $this->assertCount(2, $thirdMessage->getCcRecipients());
        $this->assertSame('info@jero.work', (string) $thirdMessage->getCcRecipients()[0]);
        $this->assertSame('some@example.com', (string) $thirdMessage->getCcRecipients()[1]);
    }

    /**
     * @test
     */
    public function it_should_add_bcc_recipient_immutable() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message->withBccRecipient(Addressee::fromString('info@jero.work'));

        $this->assertNotSame($message, $anotherMessage);
        $this->assertNotSame($message->getBccRecipients(), $anotherMessage->getBccRecipients());
        $this->assertCount(0, $message->getBccRecipients());
        $this->assertCount(1, $anotherMessage->getBccRecipients());
        $this->assertSame('info@jero.work', (string) $anotherMessage->getBccRecipients()[0]);

        $thirdMessage = $anotherMessage->withBccRecipient(Addressee::fromString('some@example.com'));

        $this->assertNotSame($anotherMessage, $thirdMessage);
        $this->assertNotSame($anotherMessage->getBccRecipients(), $thirdMessage->getBccRecipients());
        $this->assertCount(0, $message->getBccRecipients());
        $this->assertCount(1, $anotherMessage->getBccRecipients());
        $this->assertCount(2, $thirdMessage->getBccRecipients());
        $this->assertSame('info@jero.work', (string) $thirdMessage->getBccRecipients()[0]);
        $this->assertSame('some@example.com', (string) $thirdMessage->getBccRecipients()[1]);
    }

    /**
     * @test
     */
    public function it_should_add_reply_to_email_immutable() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message->withReplyToEmail(new Email('info@jero.work'));

        $this->assertNotSame($message, $anotherMessage);
        $this->assertNotSame($message->getReplyToEmail(), $anotherMessage->getReplyToEmail());
        $this->assertSame(null, $message->getReplyToEmail());
        $this->assertSame('info@jero.work', (string) $anotherMessage->getReplyToEmail());
    }

    /**
     * @test
     */
    public function it_should_add_attachment_immutable() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $anotherMessage = $message->withAttachment('some-attachment-file');

        $this->assertNotSame($message, $anotherMessage);
        $this->assertCount(1, $anotherMessage->getAttachments());
        $this->assertSame('some-attachment-file', (string) $anotherMessage->getAttachments()[0]);

        $thirdMessage = $anotherMessage->withAttachment('another-attachment-file');

        $this->assertNotSame($anotherMessage, $thirdMessage);
        $this->assertCount(2, $thirdMessage->getAttachments());
        $this->assertSame('some-attachment-file', (string) $thirdMessage->getAttachments()[0]);
        $this->assertSame('another-attachment-file', (string) $thirdMessage->getAttachments()[1]);
    }

    /**
     * @test
     */
    public function it_should_check_if_message_is_invalid_by_empty_body() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, null);
        $message = new Message($sender, 'Subject', $body);
        $message = $message->withToRecipient(Addressee::fromString('info@jero.work'));

        $this->assertFalse($message->isValid());
    }

    /**
     * @test
     */
    public function it_should_check_if_message_is_invalid_by_empty_subject() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, '', $body);
        $message = $message->withToRecipient(Addressee::fromString('info@jero.work'));

        $this->assertFalse($message->isValid());
    }

    /**
     * @test
     */
    public function it_should_check_if_message_is_invalid_by_missing_to_recipient() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);
        $message = $message->withToRecipient(Addressee::fromString('info@jero.work'));

        $this->assertTrue($message->isValid());
    }

    /**
     * @test
     */
    public function it_should_check_equality_with_minimum_setup() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $sender         = Addressee::fromString('Jero Work <hello@jero.work>');
        $body           = new Body(null, 'Some text body');
        $anotherMessage = new Message($sender, 'Subject', $body);

        $this->assertTrue($message->equals($anotherMessage));
    }

    /**
     * @test
     */
    public function it_should_check_equality_with_full_setup() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        $sender         = Addressee::fromString('Jero Work <hello@jero.work>');
        $body           = new Body(null, 'Some text body');
        $anotherMessage = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        $this->assertTrue($message->equals($anotherMessage));
    }

    /**
     * @test
     */
    public function it_should_check_invalid_equality() : void
    {
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        // With another sender
        $sender         = Addressee::fromString('hello@jero.work');
        $body           = new Body(null, 'Some text body');
        $anotherMessage = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        // With another body
        $sender       = Addressee::fromString('Jero Work <hello@jero.work>');
        $body         = new Body(null, 'Another text body');
        $thirdMessage = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        // With another subject
        $sender        = Addressee::fromString('Jero Work <hello@jero.work>');
        $body          = new Body(null, 'Some text body');
        $fourthMessage = (new Message($sender, 'Another subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        // With less to recipients
        $sender      = Addressee::fromString('Jero Work <hello@jero.work>');
        $body        = new Body(null, 'Some text body');
        $fithMessage = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        // With no cc recipients
        $sender       = Addressee::fromString('Jero Work <hello@jero.work>');
        $body         = new Body(null, 'Some text body');
        $sixthMessage = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        // With another bcc recipient
        $sender         = Addressee::fromString('Jero Work <hello@jero.work>');
        $body           = new Body(null, 'Some text body');
        $seventhMessage = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('another-bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        // With another reply to email
        $sender       = Addressee::fromString('Jero Work <hello@jero.work>');
        $body         = new Body(null, 'Some text body');
        $eightMessage = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('another-info@example.com'))
            ->withAttachment('some-file');

        // With more attachments
        $sender       = Addressee::fromString('Jero Work <hello@jero.work>');
        $body         = new Body(null, 'Some text body');
        $ninthMessage = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file')
            ->withAttachment('another-file');

        $this->assertFalse($message->equals($anotherMessage));
        $this->assertFalse($message->equals($thirdMessage));
        $this->assertFalse($message->equals($fourthMessage));
        $this->assertFalse($message->equals($fithMessage));
        $this->assertFalse($message->equals($sixthMessage));
        $this->assertFalse($message->equals($seventhMessage));
        $this->assertFalse($message->equals($eightMessage));
        $this->assertFalse($message->equals($ninthMessage));
    }

    /**
     * @test
     */
    public function it_should_be_json_serializable() : void
    {
        // Minimum setup
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body(null, 'Some text body');
        $message = new Message($sender, 'Subject', $body);

        $this->assertSame(
            [
                'sender'        => [
                    'email' => 'hello@jero.work',
                    'name'  => 'Jero Work',
                ],
                'subject'       => 'Subject',
                'body'          => [
                    'html' => null,
                    'text' => 'Some text body',
                ],
                'toRecipients'  => [],
                'ccRecipients'  => [],
                'bccRecipients' => [],
                'replyToEmail'  => null,
                'attachments'   => [],
            ],
            $message->jsonSerialize()
        );

        // Full setup
        $sender  = Addressee::fromString('Jero Work <hello@jero.work>');
        $body    = new Body('<p>Some html body</p>', 'Some text body');
        $message = (new Message($sender, 'Subject', $body))
            ->withToRecipient(Addressee::fromString('info@jero.work'))
            ->withToRecipient(Addressee::fromString('Somebody else <some@example.com>'))
            ->withCcRecipient(Addressee::fromString('cc@jero.work'))
            ->withBccRecipient(Addressee::fromString('bcc@jero.work'))
            ->withReplyToEmail(new Email('info@example.com'))
            ->withAttachment('some-file');

        $this->assertSame(
            [
                'sender'        => [
                    'email' => 'hello@jero.work',
                    'name'  => 'Jero Work',
                ],
                'subject'       => 'Subject',
                'body'          => [
                    'html' => '<p>Some html body</p>',
                    'text' => 'Some text body',
                ],
                'toRecipients'  => [
                    [
                        'email' => 'info@jero.work',
                        'name' => null,
                    ],
                    [
                        'email' => 'some@example.com',
                        'name' => 'Somebody else',
                    ]
                ],
                'ccRecipients'  => [
                    [
                        'email' => 'cc@jero.work',
                        'name' => null,
                    ]
                ],
                'bccRecipients' => [
                    [
                        'email' => 'bcc@jero.work',
                        'name' => null,
                    ]
                ],
                'replyToEmail'  => 'info@example.com',
                'attachments'   => [
                    'some-file',
                ],
            ],
            $message->jsonSerialize()
        );
    }
}
