<?php
namespace Vanio\EasyMailer\Template;

use Twig_Extension;
use Twig_SimpleFilter;

class TwigEmbedExtension extends Twig_Extension
{
    /**
     * @return Twig_SimpleFilter[]
     */
    public function getFilters(): array
    {
        return [
            new Twig_SimpleFilter('embed', function (array $context, string $path) {
                return $context['_content']->embed($path);
            }, ['needs_context' => true]),
        ];
    }

    public function getName(): string
    {
        return __CLASS__;
    }
}
