<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', 'sf_add_settings_page' );

function sf_add_settings_page() {
	add_submenu_page(
		'edit.php?post_type=simply_faq',
		__( 'FAQ Settings', 'simply-faqs' ),
		__( 'Settings', 'simply-faqs' ),
		'manage_options',
		'simply-faqs-settings',
		'sf_settings_page_cb'
	);
}

add_action( 'admin_init', 'sf_register_settings' );

function sf_register_settings() {
	register_setting( 'sf_settings_group', 'sf_limit', array( 'sanitize_callback' => 'intval', 'default' => -1 ) );
}

function sf_settings_page_cb() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'FAQ Settings', 'simply-faqs' ); ?></h1>
		<p style="color:#666;margin-bottom:24px"><?php esc_html_e( 'Defaults for [simply_faqs]. The shortcode auto-detects the page\'s FAQ category — no attribute needed when the page has one assigned.', 'simply-faqs' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'sf_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="sf_limit"><?php esc_html_e( 'Default limit', 'simply-faqs' ); ?></label></th>
					<td>
						<input type="number" id="sf_limit" name="sf_limit" min="-1"
							value="<?php echo esc_attr( get_option( 'sf_limit', -1 ) ); ?>"
							style="width:80px">
						<p class="description"><?php esc_html_e( '-1 shows all FAQs in the category.', 'simply-faqs' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
