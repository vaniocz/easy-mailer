<?php
namespace Vanio\EasyMailer\Template;

use Vanio\EasyMailer\Message;

/**
 * Interface for template engines.
 */
interface TemplateEngineAdapter
{
    /**
     * Create a new e-mail message from the given template.
     *
     * @param string $templatePath A path to the template file.
     * @param mixed[] $context The template context.
     *
     * @return Message Newly created e-mail message.
     */
    function createMessage(string $templatePath, array $context): Message;
}
