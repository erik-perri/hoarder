<?php

namespace Tests;

use App\Http\GuzzleHttp\GuzzleRequestFactory;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use rpkamp\Mailhog\MailhogClient;
use rpkamp\Mailhog\Message\Contact;
use rpkamp\Mailhog\Message\Message;
use rpkamp\Mailhog\Specification\AndSpecification;
use rpkamp\Mailhog\Specification\RecipientSpecification;
use rpkamp\Mailhog\Specification\SubjectSpecification;

trait UsesMailhog
{
    private function createClient(): MailhogClient
    {
        return new MailhogClient(
            GuzzleAdapter::createWithConfig([
                'timeout' => 2,
            ]),
            new GuzzleRequestFactory(),
            'http://'.env('MAIL_HOST').':8025/'
        );
    }

    /**
     * @param string $email
     * @param string|null $subject
     * @return Message[]
     */
    protected function findMailhogEmail(string $email, ?string $subject): array
    {
        $client = $this->createClient();
        $recipient = new RecipientSpecification(new Contact($email));

        if ($subject === null) {
            return $client->findMessagesSatisfying($recipient);
        }

        return $client->findMessagesSatisfying(new AndSpecification(
            new SubjectSpecification($subject),
            $recipient,
        ));
    }

    /**
     * @param string $email
     * @param string $subject
     */
    protected function assertMailhogHasEmail(string $email, string $subject): void
    {
        $messages = $this->findMailhogEmail($email, $subject);

        self::assertNotEmpty(
            $messages,
            sprintf(
                'Failed asserting that Mailhog contains an email to "%s" with subject "%s"',
                $email,
                $subject
            )
        );
    }
}
