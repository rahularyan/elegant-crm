<?php
/**
 * Register actions and filters of the plugin.
 *
 * @package    ElegantCRM
 * @subpackage Classes
 * @since      1.0.0
 * @author     Rahul Aryan <rah12@live.com>
 */

namespace ElegantCRM;

// You know what to do :)
defined( 'ABSPATH' ) || exit;

/**
 * Register hooks of the plugin.
 *
 * @since 1.0.0
 */
class Hooks {
	/**
	 * Instance of this class.
	 *
	 * @var object Hooks
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
		$this->filters();
		$this->actions();
	}

	/**
	 * Register all filters.
	 *
	 * @return void
	 */
	private function filters() {
		$plugin = elegant_crm();

		//$plugin->apply_filters( '', $value);
	}

	/**
	 * Register all actions.
	 *
	 * @return void
	 */
	private function actions() {
		$plugin = elegant_crm();

		$plugin->add_action( 'wp_enqueue_scripts', $this, 'register_scripts' );
		$plugin->add_action( 'wp_ajax_elegant_crm_form', $this, 'submit_form' );
		$plugin->add_action( 'wp_ajax_nopriv_elegant_crm_form', $this, 'submit_form' );
		$plugin->add_action( 'add_meta_boxes', $this, 'meta_box' );
	}

	/**
	 * Register JS and CSS.
	 */
	public function register_scripts() {
		$assets_url = elegant_crm()->plugin_dir_url . '/template/assets/';
		wp_register_script( 'elegant-script', $assets_url . 'elegant-crm.js', [ 'jquery' ], elegant_crm()->VER, true );
		wp_register_style( 'elegant-style', $assets_url . 'elegant-crm.css', [], elegant_crm()->VER );

		echo '<script type="text/javascript">';
			echo 'ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '";';
		echo'</script>';
	}

	/**
	 * Ajax callback for processing form.
	 */
	public function submit_form() {
		$nonce = ! empty( $_POST['__nonce'] ) ? $_POST['__nonce'] : '';

		// Check nonce.
		if ( ! wp_verify_nonce( $nonce, 'crm_form' ) ) {
			wp_send_json_error( [
				'msg' => __( 'Failed to verify nonce', 'elegant-crm' ),
			], 401 );
		}

		// Process fields.
		$fields = Shortcode::instance()->fields;
		$fields['current_time'] = [];

		$fields_key = array_keys( $fields );

		// Get values from post.
		$values = wp_array_slice_assoc( $_POST, $fields_key );

		// Sanitize.
		$values = array_map( 'sanitize_text_field', array_map( 'wp_unslash', $values ) );

		// Remove empty fields.
		$values = array_filter( $values );

		// Get empty fields.
		$empty_fields = array_diff( $fields_key, array_keys( $values ) );

		if ( ! empty( $empty_fields ) ) {
			wp_send_json_error( [
				'msg'            => __( 'All fields required.', 'elegant-crm' ),
				'missing_fields' => $empty_fields,
			], 422 );
		}

		$post_id = wp_insert_post( [
			'post_title'   => $values['name'] . ' ' . $values['email'] . ', Budget: ' . $values['budget'],
			'post_content' => $values['message'],
			'post_type'    => 'elegant_customer',
			'post_status'  => 'private',
			'meta_input'   => [
				'__crm_phone'  => $values['phone'],
				'__crm_email'  => $values['email'],
				'__crm_budget' => $values['budget'],
			],
		], true );

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( [
				'msg' => __( 'Something went wrong while posting, please try again', 'elegant-crm' ),
			], 422 );
		}

		wp_send_json_success( [
			'msg' => __( 'Thank you for contacting us, we will get back to you soon', 'elegant-crm' ),
		]);

		die;
	}

	/**
	 * Register custom metabox in wp-admin.
	 */
	public function meta_box() {
		add_meta_box( 'elegant_crm_mb', 'Lead details', [ $this, 'lead_details' ], 'elegant_customer', 'side', 'high' );
	}

	/**
	 * Display custom metabox.
	 */
	public function lead_details ( $post ) {
		?>
			<div class="misc-pub-section">
				<strong><?php esc_attr_e( 'Budget:', 'elegant-crm' ); ?></strong> <span><?php echo get_post_meta( $post->ID, '__crm_budget', true ); ?></span>

				<br>

				<strong><?php esc_attr_e( 'Phone:', 'elegant-crm' ); ?></strong> <span><?php echo get_post_meta( $post->ID, '__crm_phone', true ); ?></span>

				<br>

				<strong><?php esc_attr_e( 'Email:', 'elegant-crm' ); ?></strong>
				<span>
					<?php $email = get_post_meta( $post->ID, '__crm_email', true ); ?>
					<a href="<?php echo esc_url( $email, 'mailto' ); ?>"><?php echo esc_html( $email ); ?></a>
				</span>

			</div>
		<?php
	}
}
