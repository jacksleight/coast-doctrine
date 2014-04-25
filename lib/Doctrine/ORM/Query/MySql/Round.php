<?php
/* 
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\ORM\Query\MySql;

class Round extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
	private $firstExpression = null;
	private $secondExpression = null;

	public function parse(\Doctrine\ORM\Query\Parser $parser)
	{
		$lexer = $parser->getLexer();
		$parser->match(\Doctrine\ORM\Query\Lexer::T_IDENTIFIER);
		$parser->match(\Doctrine\ORM\Query\Lexer::T_OPEN_PARENTHESIS);
		$this->firstExpression = $parser->ArithmeticExpression();

		if(\Doctrine\ORM\Query\Lexer::T_COMMA === $lexer->lookahead['type']){
			$parser->match(\Doctrine\ORM\Query\Lexer::T_COMMA);
			$this->secondExpression = $parser->ArithmeticExpression();
		}

		$parser->match(\Doctrine\ORM\Query\Lexer::T_CLOSE_PARENTHESIS);
	}

	public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
	{
		if (null !== $this->secondExpression){
			return 'ROUND(' 
				. $this->firstExpression->dispatch($sqlWalker)
				. ', '
				. $this->secondExpression->dispatch($sqlWalker)
				. ')';
		}
		return 'ROUND(' . $this->firstExpression->dispatch($sqlWalker) . ')';
	}
}