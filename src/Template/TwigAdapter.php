<?php
namespace Vanio\EasyMailer\Template;

use Twig\Environment;
use Twig\Error\RuntimeError;
use Twig\Template;
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
     * @var Environment
     */
    private $twig;

    /**
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
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
        $template = $this->twig->loadTemplate($this->twig->getTemplateClass($templatePath), $templatePath);
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
     * @param Template $template
     * @param mixed[] $context
     *
     * @return MessageContent
     */
    protected function createMessageContent(Template $template, array $context): MessageContent
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

    private function renderBlock(Template $template, string $block, array $context): string
    {
        if (!$template->hasBlock($block, $context)) {
            return '';
        }

        $obLevel = ob_get_level();

        try {
            return $template->renderBlock($block, $context);
        } catch (RuntimeError $e) {
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }

            throw $e;
        }
    }
}
