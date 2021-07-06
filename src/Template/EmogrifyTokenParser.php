<?php
namespace Vanio\EasyMailer\Template;

use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class EmogrifyTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): Node
    {
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);

        return new EmogrifyNode($token->getLine(), $this->getTag());
    }

    public function getTag(): string
    {
        return 'emogrify';
    }
}
