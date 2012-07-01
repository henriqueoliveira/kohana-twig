<?php defined('SYSPATH') or die('No direct script access.');

/**
 * This is a static class that houses code for a few filters
 * that aren't based directly off of Kohana helper methods
 *
 * @package Kohana/Twig
 * @author  Marcel Beck <marcel.beck@mbeck.org>
 */
class Kohana_Twig_Filters {

	/**
	 * Returns the time since a particular time
	 *
	 * @static
	 *
	 * @param      $i_datetime1
	 * @param null $i_datetime2
	 *
	 * @return string
	 *
	 * @author Marcel Beck <marcel.beck@mbeck.org>
	 */
	public static function time_since($i_datetime1, $i_datetime2 = null)
	{
		$datetime1 = new \DateTime($i_datetime1);
		$datetime2 = ($i_datetime2 === null) ? new \DateTime('now') : new \DateTime($i_datetime2);

		$interval = $datetime1->diff($datetime2);

		$doPlural = function($nb, $str)
		{
			return $nb > 1 ? Inflector::plural($str) : $str;
		}; // adds plurals

		$format = array();
		if ($interval->y !== 0)
		{
			$format[] = '%y ' . $doPlural($interval->y, __('year'));
		}
		if ($interval->m !== 0)
		{
			$format[] = '%m ' . $doPlural($interval->m, _('month'));
		}
		if ($interval->d !== 0)
		{
			$format[] = '%d ' . $doPlural($interval->d, _('day'));
		}
		if ($interval->h !== 0)
		{
			$format[] = '%h ' . $doPlural($interval->h, __('hour'));
		}
		if ($interval->i !== 0)
		{
			$format[] = '%i ' . $doPlural($interval->i, __('minute'));
		}
		if ($interval->s !== 0)
		{
			if (! count($format))
			{
				return __('less than a minute ago');
			}
			else
			{
				$format[] = '%s ' . $doPlural($interval->s, __('second'));
			}
		}

		// We use the two biggest parts
		if (count($format) > 1)
		{
			$format = array_shift($format) . ' ' . __('and') . ' ' . array_shift($format);
		}
		else
		{
			$format = array_pop($format);
		}

		//echo $interval->format('%y years, %m months, %d days, %h hours and %i minutes ago');
		return $interval->format($format);
	}

}