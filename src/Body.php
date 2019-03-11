<?php

declare(strict_types=1);

namespace Jerowork\EmailMessage;

use JsonSerializable;

final class Body implements JsonSerializable
{
    public const HTML = 'HTML';
    public const TEXT = 'TEXT';

    /**
     * @var string | null
     */
    private $html;

    /**
     * @var string | null
     */
    private $text;

    public function __construct(string $html = null, string $text = null)
    {
        $this->html = $html;
        $this->text = $text;
    }

    public function withHtml(string $html) : Body
    {
        $body       = clone $this;
        $body->html = $html;

        return $body;
    }

    public function withText(string $text) : Body
    {
        $body       = clone $this;
        $body->text = $text;

        return $body;
    }

    public function getHtml() : ?string
    {
        return $this->html;
    }

    public function getText() : ?string
    {
        return $this->text;
    }

    public function isValid() : bool
    {
        return empty($this->html) === false || empty($this->text) === false;
    }

    public function equals(Body $body) : bool
    {
        return $this->html === $body->html && $this->text === $body->text;
    }

    public function jsonSerialize() : array
    {
        return [
            'html' => $this->html,
            'text' => $this->text,
        ];
    }
}
