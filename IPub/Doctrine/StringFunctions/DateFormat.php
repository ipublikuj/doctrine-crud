<?php
/**
 * DateFormat.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Doctrine!
 * @subpackage	String functions
 * @since		5.0
 *
 * @date		29.01.14
 */

namespace IPub\Doctrine\StringFunctions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
	Doctrine\ORM\Query\Lexer;

class DateFormat extends FunctionNode
{
	/*
	 * Holds the timestamp of the DATE_FORMAT DQL statement
	 *
	 * @var mixed
	 */
	protected $dateExpression;

	/**
	 * Holds the '%format' parameter of the DATE_FORMAT DQL statement
	 *
	 * @var string
	 */
	protected $formatChar;

	/**
	 * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
	 *
	 * @return string
	 */
	public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
	{
		return 'DATE_FORMAT(' .
			$sqlWalker->walkArithmeticExpression($this->dateExpression) .
			','.
			$sqlWalker->walkStringPrimary($this->formatChar) .
		')';
	}

	/**
	 * @param \Doctrine\ORM\Query\Parser $parser
	 *
	 * @return void
	 */
	public function parse(\Doctrine\ORM\Query\Parser $parser)
	{
		$parser->match(Lexer::T_IDENTIFIER);
		$parser->match(Lexer::T_OPEN_PARENTHESIS);

		$this->dateExpression = $parser->ArithmeticExpression();
		$parser->match(Lexer::T_COMMA);

		$this->formatChar = $parser->StringPrimary();
		$parser->match(Lexer::T_CLOSE_PARENTHESIS);
	}
}