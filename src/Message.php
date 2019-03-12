<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage;

use Jerowork\EmailMessage\Exception\InvalidArgumentException;
use JsonSerializable;

final class Message implements JsonSerializable
{
    /**
     * @var Addressee
     */
    private $sender;

    /**
     * @var string | null
     */
    private $subject;

    /**
     * @var Body | null
     */
    private $body;

    /**
     * @var AddresseeCollection
     */
    private $toRecipients;

    /**
     * @var AddresseeCollection
     */
    private $ccRecipients;

    /**
     * @var AddresseeCollection
     */
    private $bccRecipients;

    /**
     * @var Email | null
     */
    private $replyToEmail;

    /**
     * @var string[]
     */
    private $attachments;

    public function __construct(Addressee $sender, string $subject = null, Body $body = null)
    {
        $this->sender  = $sender;
        $this->subject = $subject;
        $this->body    = $body;

        $this->toRecipients  = new AddresseeCollection();
        $this->ccRecipients  = new AddresseeCollection();
        $this->bccRecipients = new AddresseeCollection();
        $this->attachments   = [];
    }

    public function __clone()
    {
        $this->toRecipients  = clone $this->toRecipients;
        $this->ccRecipients  = clone $this->ccRecipients;
        $this->bccRecipients = clone $this->bccRecipients;
    }

    public function withSender(Addressee $sender) : Message
    {
        $message         = clone $this;
        $message->sender = $sender;

        return $message;
    }

    public function withSubject(string $subject) : Message
    {
        $message          = clone $this;
        $message->subject = $subject;

        return $message;
    }

    public function withHtmlBody(string $body) : Message
    {
        $message       = clone $this;
        $message->body = $message->body !== null ? $message->body->withHtml($body) : new Body($body);

        return $message;
    }

    public function withTextBody(string $body) : Message
    {
        $message       = clone $this;
        $message->body = $message->body !== null ? $message->body->withText($body) : new Body(null, $body);

        return $message;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function withBody(string $body, string $type) : Message
    {
        if ($type === Body::HTML) {
            return $this->withHtmlBody($body);
        }

        if ($type === Body::TEXT) {
            return $this->withTextBody($body);
        }

        throw InvalidArgumentException::invalidBodyType($type);
    }

    public function withToRecipient(Addressee ...$recipients) : Message
    {
        $message = clone $this;
        $message->toRecipients->add(...$recipients);

        return $message;
    }

    public function withCcRecipient(Addressee ...$recipients) : Message
    {
        $message = clone $this;
        $message->ccRecipients->add(...$recipients);

        return $message;
    }

    public function withBccRecipient(Addressee ...$recipients) : Message
    {
        $message = clone $this;
        $message->bccRecipients->add(...$recipients);

        return $message;
    }

    public function withReplyToEmail(Email $replyToEmail) : Message
    {
        $message               = clone $this;
        $message->replyToEmail = $replyToEmail;

        return $message;
    }

    public function withAttachment(string ...$attachments) : Message
    {
        $message = clone $this;
        foreach ($attachments as $attachment) {
            $message->attachments[] = $attachment;
        }

        return $message;
    }

    public function getSender() : Addressee
    {
        return $this->sender;
    }

    public function getSubject() : ?string
    {
        return $this->subject;
    }

    public function getBody() : ?Body
    {
        return $this->body;
    }

    /**
     * @return Addressee[]
     */
    public function getToRecipients() : array
    {
        return $this->toRecipients->getAddressees();
    }

    /**
     * @return Addressee[]
     */
    public function getCcRecipients() : array
    {
        return $this->ccRecipients->getAddressees();
    }

    /**
     * @return Addressee[]
     */
    public function getBccRecipients() : array
    {
        return $this->bccRecipients->getAddressees();
    }

    public function getReplyToEmail() : ?Email
    {
        return $this->replyToEmail;
    }

    /**
     * @return string[]
     */
    public function getAttachments() : array
    {
        return $this->attachments;
    }

    public function isValid() : bool
    {
        if ($this->body->isValid() === false) {
            return false;
        }

        if (empty($this->subject) === true) {
            return false;
        }

        if (count($this->toRecipients) === 0) {
            return false;
        }

        return true;
    }

    public function equals(Message $message) : bool
    {
        return (
            $this->sender->equals($message->sender) === true &&
            $this->subject === $message->subject &&
            (
                ($this->body === null && $message->body === null) ||
                $this->body->equals($message->body) === true
            ) &&
            $this->toRecipients->equals($message->toRecipients) === true &&
            $this->ccRecipients->equals($message->ccRecipients) === true &&
            $this->bccRecipients->equals($message->bccRecipients) === true &&
            (
                ($this->replyToEmail === null && $message->replyToEmail === null) ||
                $this->replyToEmail->equals($message->replyToEmail) === true
            ) &&
            $this->attachments === $message->attachments
        );
    }

    public function jsonSerialize() : array
    {
        return [
            'sender'        => $this->sender->jsonSerialize(),
            'subject'       => $this->subject,
            'body'          => $this->body->jsonSerialize(),
            'toRecipients'  => $this->toRecipients->jsonSerialize(),
            'ccRecipients'  => $this->ccRecipients->jsonSerialize(),
            'bccRecipients' => $this->bccRecipients->jsonSerialize(),
            'replyToEmail'  => $this->replyToEmail !== null ? $this->replyToEmail->jsonSerialize() : null,
            'attachments'   => $this->attachments,
        ];
    }
}
