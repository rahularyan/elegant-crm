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
 * Class for registering shortcode.
 *
 * @since 1.0.0
 */
class Shortcode {
	/**
	 * Instance of this class.
	 *
	 * @var object Activate
	 */
	private static $instance = null;

	/**
	 * Shortcode attributes.
	 *
	 * @var array
	 */
	private $atts = [];

	/**
	 * Form fields.
	 *
	 * @var array
	 */
	public $fields = [];

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
		$this->fields = [
			'name' => [
				'label'      => __( 'Name', 'elegant-crm' ),
				'type'       => 'text',
			],
			'phone' => [
				'label'      => __( 'Phone', 'elegant-crm' ),
				'type'       => 'text',
			],
			'email'=> [
				'label'      => __( 'Email', 'elegant-crm' ),
				'type'       => 'text',
			],
			'budget' => [
				'label'      => __( 'Budget', 'elegant-crm' ),
				'type'       => 'text',
			],
			'message' => [
				'label'      => __( 'Message', 'elegant-crm' ),
				'type'       => 'textarea',
				'cols'       => 10,
				'rows'       => 10,
			],
		];

		add_shortcode( 'elegant_customer_form', [ $this, 'shortcode' ] );
	}

	/**
	 * Render shortcode.
	 */
	public function shortcode( $atts ) {
		$this->atts = wp_parse_args( $atts, [

		] );

		$formatted_fields = [];

		/**
		 * Format the fields and override defaults from attributes.
		 * For overriding field HTML attributes something like this need to be passed
		 * from shortcode attributes:
		 *
		 * `[$field_name]_label`
		 */
		foreach ( $this->fields as $field_name => $field_args ) {
			// Get custom labels.
			if ( isset( $this->atts[ $field_name . '_label' ] ) ) {
				$field_args['label'] = $this->atts[ $field_name . '_label' ];
			}

			// Get maxlength.
			if ( isset( $this->atts[ $field_name . '_maxlength' ] ) ) {
				$field_args['maxlength'] = $this->atts[ $field_name . '_maxlength' ];
			}

			// If message field check for rows and cols.
			if ( 'message' === $field_name ) {
				if ( isset( $this->atts[ $field_name . '_cols' ] ) ) {
					$field_args['cols'] = $this->atts[ $field_name . '_cols' ];
				}

				if ( isset( $this->atts[ $field_name . '_rows' ] ) ) {
					$field_args['rows'] = $this->atts[ $field_name . '_rows' ];
				}
			}

			$formatted_fields[ $field_name ] = $field_args;
		}

		// Enqueue assets.
		wp_enqueue_script( 'elegant-script' );
		wp_enqueue_style( 'elegant-style' );

		ob_start();

		// Load the template.
		elegant_crm()->include_file( 'template/form', [
			'atts'   => $this->atts,
			'fields' => $formatted_fields,
		] );

		return ob_get_clean();
	}
}
