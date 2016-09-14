<?php
namespace Vanio\EasyMailer\Template;

use Twig_Environment;
use Twig_Template;
use Vanio\EasyMailer\EmailAddress;
use Vanio\EasyMailer\EmailAddresses;
use Vanio\EasyMailer\GenericMessageContent;
use Vanio\EasyMailer\HtmlMessageContent;
use Vanio\EasyMailer\Message;
use Vanio\EasyMailer\MessageContent;

/**
 * Adapter for Twig template engine.
 */
class TwigAdapter implements TemplateEngineAdapter
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Create a new e-mail message from the given template.
     *
     * @param string $templatePath A path to the template file.
     * @param mixed[] $context The template context.
     *
     * @return Message Newly created e-mail message.
     */
    public function createMessage(string $templatePath, array $context): Message
    {
        $template = $this->twig->loadTemplate($templatePath);
        $context = $this->twig->mergeGlobals($context);

        return new Message(
            $template->renderBlock('title', $context),
            $template->renderBlock('subject', $context),
            $this->createMessageContent($template, $context),
            EmailAddresses::fromString($template->renderBlock('to', $context)),
            EmailAddresses::fromString($template->renderBlock('cc', $context)),
            EmailAddresses::fromString($template->renderBlock('bcc', $context)),
            EmailAddress::fromString($template->renderBlock('sender', $context)),
            EmailAddresses::fromString($template->renderBlock('from', $context))
        );
    }

    /**
     * Create a message content from the given template and context.
     *
     * @param Twig_Template $template
     * @param mixed[] $context
     *
     * @return MessageContent
     */
    protected function createMessageContent(Twig_Template $template, array $context): MessageContent
    {
        $mimeType = $template->renderBlock('content_type', $context);
        $content = $template->renderBlock('content', $context);
        $text = $template->renderBlock('text_content', $context);

        return $mimeType === 'text/html'
            ? new HtmlMessageContent($content, $text ?: null)
            : new GenericMessageContent($mimeType, $content, $text);
    }
}
