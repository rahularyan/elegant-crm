<?php
/**
 * Register helper functions and template tags.
 *
 * @since      1.0.0
 * @author     Rahul Aryan <rah12@live.com>
 * @package    ElegantCRM
 * @subpackage Helpers
 */

namespace ElegantCRM;

// You know what to do :)
defined( 'ABSPATH' ) || exit;

/**
 * Default options of the category.
 *
 * @return array
 * @since 1.0.0
 */
function get_default_options() {
	$arr = array(

	);

	/**
	 * Filter default options of ElegantCRM.
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'elegant_crm_default_options', $arr );
}
