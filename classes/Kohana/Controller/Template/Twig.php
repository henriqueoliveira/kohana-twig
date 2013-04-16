<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Twig template controller
 *
 * @package    Kohana/Twig/Controller
 * @author     John Heathco <jheathco@gmail.com>
 */
abstract class Kohana_Controller_Template_Twig extends Controller {

	/**
	 * @var Twig_Environment
	 */
	public $environment = 'default';

	/**
	 * @var boolean  Auto-render template after controller method returns
	 */
	public $auto_render = true;

	/**
	 * @var Twig
	 */
	public $template;

	public $folder = '';

	/**
	 * Setup view
	 *
	 * @return void
	 */
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);

		if (empty($this->template))
		{
			// Generate a template name if one wasn't set.
			$this->template = str_replace('_', DIRECTORY_SEPARATOR, $this->request->controller()) . DIRECTORY_SEPARATOR . $this->request->action();

			$directory = $this->request->directory();

			if (! empty($directory))
			{
				$this->template = $this->request->directory() . DIRECTORY_SEPARATOR . $this->template;
			}
		}

		$this->template = $this->folder.$this->template;
		if ($this->auto_render)
		{
			// Load the twig template.
			$this->template = Twig::factory($this->template, $this->environment);

			// Return the twig environment
			$this->environment = $this->template->environment();
		}
	}

	/**
	 * Renders the template if necessary
	 *
	 * @return void
	 */
	public function after()
	{
		if ($this->auto_render)
		{
			// Auto-render the template
			$this->response->body($this->template);
		}

		parent::after();
	}

} // End Controller_Twig
