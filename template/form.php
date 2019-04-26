<?php
/**
 * Template for displaying form.
 *
 * @author     Rahul Aryan <rah12@live.com>
 * @since      1.0.0
 * @package    ElegantCRM
 * @subpackage Templates
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<div id="elegant-crm">
    <form id="elegant-crm-form" class="elegant-form" method="POST">

        <div class="elegant-form-groups">

            <?php if ( ! empty( $fields ) ) : ?>

                <?php foreach ( $fields as $field_key => $field_args ) : ?>

                    <div class="form-group form-group-<?php echo esc_attr( $field_key ); ?>">
                        <label for="field-<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $field_args['label'] ); ?></label>

                        <?php if ( 'textarea' === $field_args['type'] ) : ?>
                            <textarea id="field-<?php echo esc_attr( $field_key ); ?>" cols="<?php echo (int) $field_args['cols']; ?>" rows="<?php echo (int) $field_args['rows']; ?>" name="<?php echo esc_attr( $field_key ); ?>"></textarea>
                        <?php else : ?>
                            <input id="field-<?php echo esc_attr( $field_key ); ?>" maxlength="<?php echo ! empty( $field_args['maxlength'] ) ? (int) $field_args['maxlength'] : ''; ?>" type="text" name="<?php echo esc_attr( $field_key ); ?>">
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>

                <input type="submit" value="<?php esc_attr_e( 'Submit form', 'elegant-crm' ); ?>" />
                <input type="hidden" value="" id="current_time" name="current_time" />
                <input type="hidden" value="elegant_crm_form" name="action" />
                <?php wp_nonce_field( 'crm_form', '__nonce' ); ?>

            <?php else : ?>
                <p><?php esc_attr_e( 'No fields found', 'elegant-crm' ); ?></p>
            <?php endif; ?>

        </div>

    </form>
</div>
