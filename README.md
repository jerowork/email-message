# Email message
PHP 7.2+ value object for email messages.

## Features
- Immutable value objects
- Available parameters: subject, html/text body, from, reply to email, multiple to/cc/bcc, attachments (string based)
- Serializable (for e.g. queue usages) (implementing `JsonSerializable`)
- Message validation (minimum required valid message)
- Validation email addresses

## Installation
Install via Composer:
```
$ composer require jerowork/email-message
```

## Usage
```php
// Construct message
$message = new Message(
    Addressee::fromString('Jero Work <info@jero.work'),
    'Some subject',
    new Body('<p>Some html body</p>')
);

// Add to recipients
$message = $message
    ->withToRecipient(
        Addressee::fromString('info@jero.work'),
        Addressee::fromString('help@jero.work')
    );

// Add cc/bcc recipients
$message = $message
    ->withCcRecipient(Addressee::fromString('Somebody <info@example.com>'))
    ->withBccRecipient(new Addressee(new Email('info@foo.com'), 'Another body'));

// Add reply to email
$message = $message->withReplyToEmail(new Email('reply@jero.work'));

// Add attachments
$message = $message->withAttachment(
    '/path/to/file',
    '/path/to/file'
);

// Update body
$message = $message->withTextBody('Some text body');

// Update other parameters, e.g. sender
$message = $message->withSender(Addressee::fromString('no-reply@jero.work'));

// Verify if email is setup correctly
if ($message->isValid() === true) {
    // Do something
    // ...
    $subject      = $messsage->getSubject();
    $toRecipients = $message->getToRecipients();
}

// Serialize value object (for use in e.g. queues)
$array = $message->jsonSerialize();
```
