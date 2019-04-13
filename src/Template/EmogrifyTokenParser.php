<?php
namespace Vanio\EasyMailer\Template;

use Twig_Token;
use Twig_TokenParser;

class EmogrifyTokenParser extends Twig_TokenParser
{
    public function parse(Twig_Token $token): \Twig_Node
    {
        $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

        return new EmogrifyNode($token->getLine(), $this->getTag());
    }

    public function getTag(): string
    {
        return 'emogrify';
    }
}
