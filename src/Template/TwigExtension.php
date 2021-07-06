<?php
namespace Vanio\EasyMailer\Template;

use Twig\Extension\AbstractExtension;
use Twig\TokenParser\AbstractTokenParser;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Vanio\EasyMailer\MessageContent;

class TwigExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('attach', [$this, 'attach'], ['needs_context' => true]),
        ];
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('embed', [$this, 'embed'], ['needs_context' => true]),
        ];
    }

    /**
     * @return AbstractTokenParser[]
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
