<?php
namespace Vanio\EasyMailer\Template;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use Twig_TokenParser;
use Vanio\EasyMailer\MessageContent;

class TwigExtension extends Twig_Extension
{
    /**
     * @return Twig_SimpleFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new Twig_SimpleFunction('attach', [$this, 'attach'], ['needs_context' => true]),
        ];
    }

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

    public function attach(array $context, string $path, ?string $filename = null): void
    {
        assert($context['_content'] instanceof MessageContent);
        $context['_content']->attach($path, $filename);
    }

    public function embed(array $context, string $path, ?string $filename = null): string
    {
        assert($context['_content'] instanceof MessageContent);

        return $context['_content']->embed($path, $filename);
    }
}
