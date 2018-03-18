<?php
/**
 * DateFormat.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineCrud!
 * @subpackage     String functions
 * @since          1.0.0
 *
 * @date           29.01.14
 */

namespace IPub\DoctrineCrud\StringFunctions;

use Doctrine\ORM\Query;

class DateFormat extends Query\AST\Functions\FunctionNode
{
	/**
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
	 * @param Query\SqlWalker $sqlWalker
	 *
	 * @return string
	 */
	public function getSql(Query\SqlWalker $sqlWalker) : string
	{
		return 'DATE_FORMAT(' .
		$sqlWalker->walkArithmeticExpression($this->dateExpression) .
		',' .
		$sqlWalker->walkStringPrimary($this->formatChar) .
		')';
	}

	/**
	 * @param Query\Parser $parser
	 *
	 * @return void
	 *
	 * @throws Query\QueryException
	 */
	public function parse(Query\Parser $parser) : void
	{
		$parser->match(Query\Lexer::T_IDENTIFIER);
		$parser->match(Query\Lexer::T_OPEN_PARENTHESIS);

		$this->dateExpression = $parser->ArithmeticExpression();
		$parser->match(Query\Lexer::T_COMMA);

		$this->formatChar = $parser->StringPrimary();
		$parser->match(Query\Lexer::T_CLOSE_PARENTHESIS);
	}
}
