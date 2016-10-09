<?php
namespace Vanio\EasyMailer\Template;

use Twig_Environment;
use Twig_SimpleFilter;
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
        $this->twig->addFilter(new Twig_SimpleFilter('embed', function (array $context, string $path) {
            return $context['_content']->embed($path);
        }, ['needs_context' => true]));
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
        $content = $mimeType === 'text/html'
            ? new HtmlMessageContent
            : new GenericMessageContent($mimeType);

        $context['_content'] = $content;
        $content->modify(
            $template->renderBlock('content', $context),
            $template->renderBlock('text_content', $context) ?: null
        );

        $attachments = array_filter(preg_split('/\s*[,;]\s*/', trim($template->renderBlock('attachments', $context))));
        foreach ($attachments as $path) {
            $content->attach($path);
        }

        return $content;
    }
}
