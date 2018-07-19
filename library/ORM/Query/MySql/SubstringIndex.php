<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\ORM\Query\MySql;

class SubstringIndex extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    public $string = null;

    public $delimiter = null;

    public $count = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
        $parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);
        $this->string = $parser->ArithmeticPrimary();
        $parser->match(\Doctrine\ORM\Query\Lexer::T_COMMA);
        $this->delimiter = $parser->ArithmeticPrimary();
        $parser->match(\Doctrine\ORM\Query\Lexer::T_COMMA);
        $this->count = $parser->ArithmeticFactor();
        $parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return sprintf('SUBSTRING_INDEX(%s, %s, %s)', $this->string->dispatch($sqlWalker), $this->delimiter->dispatch($sqlWalker), $this->count->dispatch($sqlWalker));
    }
}