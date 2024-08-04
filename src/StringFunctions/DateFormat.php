<?php declare(strict_types = 1);

/**
 * DateFormat.php
 *
 * @copyright      More in LICENSE.md
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
	 */
	protected Query\AST\ArithmeticExpression $dateExpression;

	/**
	 * Holds the '%format' parameter of the DATE_FORMAT DQL statement
	 */
	protected string $formatChar;

	public function getSql(Query\SqlWalker $sqlWalker): string
	{
		return 'DATE_FORMAT(' .
			$sqlWalker->walkArithmeticExpression($this->dateExpression) .
			',' .
			$sqlWalker->walkStringPrimary($this->formatChar) .
			')';
	}

	/**
	 * @throws Query\QueryException
	 */
	public function parse(Query\Parser $parser): void
	{
		$parser->match(Query\TokenType::T_IDENTIFIER);
		$parser->match(Query\TokenType::T_OPEN_PARENTHESIS);

		$this->dateExpression = $parser->ArithmeticExpression();
		$parser->match(Query\TokenType::T_COMMA);

		$this->formatChar = (string) $parser->StringPrimary();
		$parser->match(Query\TokenType::T_CLOSE_PARENTHESIS);
	}

}
