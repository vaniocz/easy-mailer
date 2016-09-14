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
     * Set necessary adapters.
     *
     * @param TemplateEngineAdapter $templateEngineAdapter Template engine adapter.
     * @param MailerAdapter $mailerAdapter Mail sender adapter.
     */
    public function __construct(TemplateEngineAdapter $templateEngineAdapter, MailerAdapter $mailerAdapter)
    {
        $this->templateEngineAdapter = $templateEngineAdapter;
        $this->mailerAdapter = $mailerAdapter;
    }

    /**
     * Send a new e-mail message based on the given template.
     *
     * @param string $templatePath A path to the template file.
     * @param mixed[] $context The template context.
     * @param EmailAddress[] $to List of recipients which will be merged with those set in the template.
     * @param EmailAddress[] $cc List of recipients of the message copy which will be merged with those set in the template.
     * @param EmailAddress[] $bcc List of recipients who this message will be blind-copied to which will be merged with those set in the template.
     */
    public function send(string $templatePath, array $context = [], array $to = [], array $cc = [], array $bcc = [])
    {
        $message = $this->templateEngineAdapter->createMessage($templatePath, $context);
        $this->mailerAdapter->sendMessage($message, $to, $cc, $bcc);
    }
}
