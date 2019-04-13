<?php
namespace Vanio\EasyMailer\Template;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_TokenParser;

class TwigExtension extends Twig_Extension
{
    /**
     * @return Twig_SimpleFilter[]
     */
    public function getFilters(): array
    {
        return [
            new Twig_SimpleFilter('embed', [$this, 'embed'], ['needs_context' => true]),
        ];
    }

    /**
     * @return Twig_TokenParser[]
     */
    public function getTokenParsers(): array
    {
        return [
            new EmogrifyTokenParser,
        ];
    }

    public function getName(): string
    {
        return __CLASS__;
    }

    public function embed(array $context, string $path): string
    {
        return $context['_content']->embed($path);
    }
}
