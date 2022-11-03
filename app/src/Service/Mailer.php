<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\NamedAddress;

class Mailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendReportMail(array $data)
    {
        $email = (new TemplatedEmail())
            ->from(new NamedAddress('ourapp@ourapp.com', 'The Space Bar'))
            ->to(new NamedAddress($data['email'], 'Dear client'))
            ->subject("Company Symbol = {$data['companySymbol']} => Company’s Name = ${$data['companyName']}")
            ->htmlTemplate('email/report.html.twig')
            ->context([
                'startDate' => $data['startDate'],
                'endDate' => $data['endDate'],
            ]);

        $this->mailer->send($email);
    }
}