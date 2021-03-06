<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Compiler for {% cache "some-key" %} Some text {% endcache %}
 *
 * @package Kohana/Twig/Cache
 * @author  Jonathan Geiger
 */
class Kohana_Twig_Cache_Node extends Twig_Node {

	/**
	 * The cache key
	 *
	 * @var object
	 */
	protected $key;

	/**
	 * The data to cache
	 *
	 * @var object
	 */
	protected $data;

	/**
	 * The cache lifetime
	 *
	 * @var object
	 */
	protected $lifetime;

	/**
	 * @param Twig_Compiler $compiler
	 *
	 * @return void
	 * @author Jonathan Geiger
	 */
	public function compile(Twig_Compiler $compiler)
	{
		$compiler
						->write('if (!fragment::load(')
						->subcompile($this->getNode('key'));

		// Lifetime will be false if it wasn't parsed
		if ($this->lifetime)
		{
			$compiler
							->write(', ')
							->subcompile($this->getNode('lifetime'))
							->write(')) {');
		}
		else
		{
			$compiler
							->write(')) {');
		}

		$compiler
						->raw("\n")
						->subcompile($this->getNode('data'))
						->raw("\n")
						->write('fragment::save();')
						->raw("\n}\n");
	}

}
