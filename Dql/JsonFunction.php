<?php

namespace Rapsys\BlogBundle\Dql;

use \Doctrine\ORM\Query\Lexer;

class JsonFunction extends \Doctrine\ORM\Query\AST\Functions\FunctionNode {
	public $keyExpression = null;
	public $valueExpression = null;

	public function parse(\Doctrine\ORM\Query\Parser $parser) {
		$parser->match(Lexer::T_IDENTIFIER);
		$parser->match(Lexer::T_OPEN_PARENTHESIS);
		$this->keyExpression = $parser->ArithmeticPrimary();

		if (Lexer::T_COMMA === $parser->getLexer()->lookahead['type']) {
			$parser->match(Lexer::T_COMMA);
			$this->valueExpression = $parser->ArithmeticPrimary();
		}

		$parser->match(Lexer::T_CLOSE_PARENTHESIS);
	}

	public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker) {
		if (null !== $this->valueExpression) {
			return 'CONCAT(\'{\', GROUP_CONCAT(CONCAT(\'"\', REPLACE('.$this->keyExpression->dispatch($sqlWalker).', \'"\', \'\\\\"\'), \'": "\', REPLACE('.$this->valueExpression->dispatch($sqlWalker).', \'"\', \'\\\\"\'), \'"\')), \'}\')';
		}
		return 'CONCAT(\'[\', GROUP_CONCAT(REPLACE('.$this->keyExpression->dispatch($sqlWalker).', \'"\', \'\\\\"\')), \']\')';
	}
}
