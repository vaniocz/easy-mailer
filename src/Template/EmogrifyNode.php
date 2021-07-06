<?php
namespace Vanio\EasyMailer\Template;

use Twig\Compiler;
use Twig\Node\Node;

class EmogrifyNode extends Node
{
    public function __construct(int $lineno = 0, string $tag = null)
    {
        parent::__construct([], [], $lineno, $tag);
    }

    public function compile(Compiler $compiler)
    {
        $compiler->write('
            if (!$context["_content"] instanceof \Vanio\EasyMailer\HtmlMessageContent) {
                throw new \Exception("Emogrifier can be run over HTML e-mail only.");
            }

            $context["_content"]->enableEmogrifier();
        ');
    }
}
