<?php
/**
 * This file is part of Jarves.
 *
 * (c) Marc J. Schmidt <marc@marcjschmidt.de>
 *
 *     J.A.R.V.E.S - Just A Rather Very Easy [content management] System.
 *
 *     http://jarves.io
 *
 * To get the full copyright and license information, please view the
 * LICENSE file, that was distributed with this source code.
 */

namespace Jarves\Twig\TokenParser;

use Jarves\Twig\Node\Content as NodeContent;

class Content extends \Twig_TokenParser
{
    protected $name;

    function __construct($name)
    {
        $this->name = $name;
    }


    public function getTag()
    {
        return $this->name;
    }

    public function parse(\Twig_Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $id   = $parser->getExpressionParser()->parseExpression();
        $type = null;

        $optional = false;
        $options  = null;

        while (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {

            if ($stream->test(\Twig_Token::NAME_TYPE, 'optional')) {
                $optional = true;
                $stream->next();
                continue;
            }

            if ($stream->test(\Twig_Token::NAME_TYPE, 'type')) {
                $stream->next();
                $type = $this->parser->getExpressionParser()->parseExpression();
                continue;
            }

            if ($stream->test(\Twig_Token::NAME_TYPE, 'set')) {
                $stream->next();
                $options = $this->parser->getExpressionParser()->parseExpression();
                continue;
            }

            $stream->next();
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new NodeContent($this->name, $id, $type, $optional, $options, $token->getLine(), $this->getTag());
    }
}