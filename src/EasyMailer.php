<?php
namespace Vanio\EasyMailer;

use Vanio\EasyMailer\Mailer\MailerAdapter;
use Vanio\EasyMailer\Template\TemplateEngineAdapter;

/**
 * Makes e-mail sending really easy.
 */
class EasyMailer
{
    /**
     * @var TemplateEngineAdapter
     */
    private $templateEngineAdapter;

    /**
     * @var MailerAdapter
     */
    private $mailerAdapter;

    /**
     * @var int[]
     */
    private $sendRetry;

    /**
     * @var bool
     */
    private $isDeliveryEnabled = true;

    /**
     * Set necessary adapters.
     *
     * @param TemplateEngineAdapter $templateEngineAdapter Template engine adapter.
     * @param MailerAdapter $mailerAdapter Mail sender adapter.
     * @param int[] $sendRetry Intervals in which to retry sending failed e-mail (in seconds).
     */
    public function __construct(TemplateEngineAdapter $templateEngineAdapter, MailerAdapter $mailerAdapter, array $sendRetry = [])
    {
        $this->templateEngineAdapter = $templateEngineAdapter;
        $this->mailerAdapter = $mailerAdapter;
        $this->sendRetry = $sendRetry;
    }

    /**
     * Send a new e-mail message based on the given template.
     *
     * @param string $templatePath A path to the template file.
     * @param mixed[] $context The template context.
     * @param string[] $to List of recipients which will be merged with those set in the template.
     * @param string[] $cc List of recipients of the message copy which will be merged with those set in the template.
     * @param string[] $bcc List of recipients who this message will be blind-copied to which will be merged with those set in the template.
     */
    public function send(string $templatePath, array $context = [], array $to = [], array $cc = [], array $bcc = [])
    {
        if (!$this->isDeliveryEnabled) {
            return;
        }

        $message = $this->templateEngineAdapter->createMessage($templatePath, $context);
        $isSent = false;
        $attempts = 0;

        while (!$isSent) {
            try {
                $this->mailerAdapter->sendMessage(
                    $message,
                    array_map([EmailAddress::class, 'fromString'], $to),
                    array_map([EmailAddress::class, 'fromString'], $cc),
                    array_map([EmailAddress::class, 'fromString'], $bcc)
                );

                $isSent = true;
            } catch (\Throwable $exception) {
                if (count($this->sendRetry) > $attempts) {
                    sleep($this->sendRetry[$attempts]);
                    ++$attempts;
                } else {
                    throw $exception;
                }
            }
        }
    }

    /**
     * Enable delivery of e-mails
     */
    public function enableDelivery()
    {
        $this->isDeliveryEnabled = true;
    }

    /**
     * Disable delivery of e-mails
     */
    public function disableDelivery()
    {
        $this->isDeliveryEnabled = false;
    }
}
