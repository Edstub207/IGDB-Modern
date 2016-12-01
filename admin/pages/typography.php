<?php
/**
 * Typography Page
 *
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>	
<div id="cb-admin-wrap" class="wrap">
	<h1><?php echo esc_html( $this->name ) . ' ' . esc_html( $this->lets_review_get_version() ); ?></h1>
	<p>To find out about new updates and news about the plugin - make sure to follow <a href="http://www.facebook.com/cubellthemes">Cubell Themes Facebook page</a>.</p>
	
	<form action="options.php" method="post">
		<?php settings_fields( 'lets-review-settings-type' ); ?>
		<?php do_settings_sections( 'lets-review-typography' ); ?>
		<?php submit_button( esc_html__( 'Save Changes', 'lets-review' ) ); ?>
		<?php wp_nonce_field( 'lets-review-typography', 'lets-review-typography-nonce' ); ?>
	</form>
</div>