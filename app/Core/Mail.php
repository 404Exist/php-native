<?php

namespace App\Core;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;

class Mail implements MailerInterface
{
    protected TransportInterface $transport;

    public function __construct(string $dsn = null)
    {
        $this->transport = Transport::fromDsn($dsn ?? (new Config())->mailer["dsn"]);
    }

    public function send(RawMessage $message, Envelope $envelope = null): void
    {
        $this->transport->send($message, $envelope);
    }
}
