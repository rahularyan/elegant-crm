<?php
/**
 * Main class of this plugin.
 *
 * @package    ElegantCRM
 * @subpackage Classes
 * @author     Rahul Aryan <rah12@live.com>
 * @since      1.0.0
 */
namespace ElegantCRM;

// You know what to do :)
defined( 'ABSPATH' ) || exit;

/**
 * Class for registering CPT.
 *
 * @since 1.0.0
 */
class CPT {
	/**
	 * Instance of this class.
	 *
	 * @var object Activate
	 */
	private static $instance = null;

	/**
	 * Return singleton instance of this class.
	 *
	 * @return object Hooks
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	private function __construct() {
		elegant_crm()->add_action( 'init', $this, 'register_cpt' );
	}

	/**
	 * Register `elegant_customer` cpt.
	 *
	 * @since 1.0.0
	 */
	public function register_cpt() {
		$labels = array(
			'name'               => _x( 'Customers', 'post type general name', 'elegant-crm' ),
			'singular_name'      => _x( 'Customer', 'post type singular name', 'elegant-crm' ),
			'menu_name'          => _x( 'Customers', 'admin menu', 'elegant-crm' ),
			'name_admin_bar'     => _x( 'Customer', 'add new on admin bar', 'elegant-crm' ),
			'add_new'            => _x( 'Add New', 'customer', 'elegant-crm' ),
			'add_new_item'       => __( 'Add New Customer', 'elegant-crm' ),
			'new_item'           => __( 'New Customer', 'elegant-crm' ),
			'edit_item'          => __( 'Edit Customer', 'elegant-crm' ),
			'view_item'          => __( 'View Customer', 'elegant-crm' ),
			'all_items'          => __( 'All Customers', 'elegant-crm' ),
			'search_items'       => __( 'Search Customers', 'elegant-crm' ),
			'parent_item_colon'  => __( 'Parent Customers:', 'elegant-crm' ),
			'not_found'          => __( 'No customers found.', 'elegant-crm' ),
			'not_found_in_trash' => __( 'No customers found in Trash.', 'elegant-crm' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Customer lead.', 'elegant-crm' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'customer' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'elegant_customer', $args );
	}
}
