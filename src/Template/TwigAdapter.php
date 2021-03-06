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

        if (!$this->twig->hasExtension(TwigExtension::class)) {
            $this->twig->addExtension(new TwigExtension);
        }
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
            $this->renderBlock($template, 'title', $context),
            $this->renderBlock($template, 'subject', $context),
            $this->createMessageContent($template, $context),
            EmailAddresses::fromString(trim($this->renderBlock($template, 'to', $context))),
            EmailAddresses::fromString(trim($this->renderBlock($template, 'cc', $context))),
            EmailAddresses::fromString(trim($this->renderBlock($template, 'bcc', $context))),
            EmailAddress::fromString(trim($this->renderBlock($template, 'sender', $context))),
            EmailAddresses::fromString(trim($this->renderBlock($template, 'from', $context))),
            EmailAddresses::fromString(trim($this->renderBlock($template, 'reply_to', $context)))
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
        $mimeType = $this->renderBlock($template, 'content_type', $context);
        $content = $mimeType === 'text/html'
            ? new HtmlMessageContent
            : new GenericMessageContent($mimeType);

        $context['_content'] = $content;
        $content->modify(
            $this->renderBlock($template, 'content', $context),
            $this->renderBlock($template, 'text_content', $context) ?: null
        );

        return $content;
    }

    private function renderBlock(Twig_Template $template, string $block, array $context): string
    {
        if (!$template->hasBlock($block, $context)) {
            return '';
        }

        $obLevel = ob_get_level();

        try {
            return $template->renderBlock($block, $context);
        } catch (\Twig_Error_Runtime $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }

            throw $e;
        }
    }
}
