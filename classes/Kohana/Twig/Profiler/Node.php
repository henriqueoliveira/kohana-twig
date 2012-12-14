<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Parses a {% profiler %} tag
 *
 * @package   Kohana/Twig/Profiler
 * @author    Marcel Beck <marcel.beck@outlook.com>
 * @copyright (c) 2012 Marcel Beck
 */
class Kohana_Twig_Profiler_Node extends Twig_Node {

	/**
	 * Compiles the tag
	 *
	 * @param \Twig_Compiler $compiler
	 *
	 * @return void
	 * @author Marcel Beck <marcel.beck@outlook.com>
	 */
	public function compile(Twig_Compiler $compiler)
	{
		// Output the profiler
		$compiler
						->write('echo View::factory(\'profiler/stats\')')
						->raw(";\n");
	}

}
