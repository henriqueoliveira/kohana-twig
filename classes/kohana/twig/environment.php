<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Twig Loader
 *
 * @package kohana-twig
 * @author  Jonathan Geiger
 */
class Kohana_Twig_Environment {

	/**
	 * Loads Twig_Environments based on the
	 * configuration key they represent
	 *
	 * @param string $env
	 *
	 * @throws Kohana_View_Exception
	 * @return Twig_Environment
	 * @author Jonathan Geiger
	 */
	public static function instance($env = 'default')
	{
		static $instances;

		if (! isset($instances[$env]))
		{
			$config = Kohana::$config->load('twig.' . $env);

			// Create the the loader
			$twig_loader = $config['loader']['class'];

			if (class_exists($twig_loader) === false)
			{
				throw new Kohana_View_Exception('Twig loader: :twig_loader does not exists', array(
																																													':twig_loader' => $twig_loader,
																																										 ));
			}
			$loader = new $twig_loader($config['loader']['options']);

			/**
			 * Set up the instance
			 *
			 * @var Twig_Environment $twig
			 */
			$twig = $instances[$env] = new Twig_Environment($loader, $config['environment']);

			// Load extensions
			foreach ($config['extensions'] as $extension)
			{
				$twig->addExtension(new $extension);
			}

			// The sandbox seems buggy
			// So this dummy condition is there to avoid the bug
			// The error thrown is "Twig_Sandbox_SecurityError [ 0 ]: Calling "__toString" method on a "Twig" object is not allowed."
			if (! empty($config['sandboxing']['tags'])
					and ! empty($config['sandboxing']['filters'])
							and ! empty($config['sandboxing']['methods'])
									and ! empty($config['sandboxing']['properties'])
			)
			{

				// Add the sandboxing extension.
				$policy = new Twig_Sandbox_SecurityPolicy
				(
					$config['sandboxing']['tags'],
					$config['sandboxing']['filters'],
					$config['sandboxing']['methods'],
					$config['sandboxing']['properties']
				);

				$twig->addExtension(new Twig_Extension_Sandbox($policy, $config['sandboxing']['global']));
			}
		}

		return $instances[$env];
	}

	final private function __construct()
	{
		// This is a static class
	}

}