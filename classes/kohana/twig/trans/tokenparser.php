<?php

/**
 * Token parser for the trans tag.
 *
 * Both block styles are allowed:
 *
 *     {% trans "String to translate" %}
 *
 *     {% trans %}
 *         String to translate
 *     {% endtrans %}
 *
 * The body of the tag will be trim()ed before being passed to __().
 *
 * @package kohana-twig
 */
class Kohana_Twig_Trans_TokenParser extends Twig_TokenParser {

	/**
	 * Parses a token and returns a node.
	 *
	 * @param Twig_Token $token A Twig_Token instance
	 *
	 * @return Twig_NodeInterface A Twig_NodeInterface instance
	 */
	public function parse(Twig_Token $token)
	{
		$lineno = $token->getLine();
		$stream = $this->parser->getStream();

		// Allow passing only an expression without an endblock
		if (! $stream->test(Twig_Token::BLOCK_END_TYPE))
		{
			$body = $this->parser->getExpressionParser()->parseExpression();
		}
		else
		{
			$stream->expect(Twig_Token::BLOCK_END_TYPE);
			$body = $this->parser->subparse(array($this, 'decideForEnd'), true);
		}

		$stream->expect(Twig_Token::BLOCK_END_TYPE);

		// Sanity check the body
		$this->check_trans_string($body, $lineno);

		// Pass it off to the compiler
		return new Kohana_Twig_Trans_Node(array('body' => $body), array(), $lineno, $this->getTag());
	}

	/**
	 * Tests for the endtrans block
	 *
	 * @param Twig_Token $token
	 *
	 * @return bool
	 */
	public function decideForEnd(Twig_Token $token)
	{
		return $token->test('endtrans');
	}

	/**
	 * Gets the tag name associated with this token parser.
	 *
	 * @return string
	 */
	public function getTag()
	{
		return 'trans';
	}

	/**
	 * Ensures only "simple" vars are in the body to be translated.
	 *
	 * @param Twig_NodeInterface $body
	 * @param string             $lineno
	 *
	 * @throws Twig_Error_Syntax
	 * @return void
	 * @author Tiger Advertising
	 */
	protected function check_trans_string(Twig_NodeInterface $body, $lineno)
	{
		foreach ($body as $node)
		{
			if ($node instanceof Twig_Node_Text or ($node instanceof Twig_Node_Print and $node->expr instanceof Twig_Node_Expression_Name))
			{
				continue;
			}

			throw new Twig_Error_Syntax(sprintf('The text to be translated with "trans" can only contain references to simple variables'), $lineno);
		}
	}

}
