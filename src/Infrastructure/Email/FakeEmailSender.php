<?php

namespace App\Infrastructure\Email;
use Psr\Log\LoggerInterface;
final class FakeEmailSender
{
    private array $sentEmails = [];

    public function send(string $to, string $subject, string $body): void
    {
        error_log("EMAIL FAKE → $to | $subject | $body");
        // Fake: solo guarda el email en memoria para que los tests puedan verificarlo
        $this->sentEmails[] = [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
        ];
    }

    public function getSentEmails(): array
    {
        return $this->sentEmails;
    }
}
