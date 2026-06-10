<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ==========================================================================
// SHORTCODE — [simply_faqs]
//
// Usage:
//   [simply_faqs]                         — auto-detects page's FAQ category
//   [simply_faqs category="general"]      — explicit category slug
//   [simply_faqs category="general" limit="10"]
//
// Auto-detection: if the current page has a simply_faq_cat term assigned,
// the shortcode uses it automatically — no attribute needed.
// Category filter buttons appear automatically when FAQs span > 1 category.
// ==========================================================================

add_shortcode( 'simply_faqs', 'sf_shortcode' );

function sf_shortcode( $atts ) {

	$atts = shortcode_atts( array(
		'category' => '',
		'limit'    => get_option( 'sf_limit', -1 ),
	), $atts, 'simply_faqs' );

	$limit = intval( $atts['limit'] );

	// Resolve category: explicit attribute → page's own term → all
	$category = sanitize_text_field( $atts['category'] );

	if ( empty( $category ) ) {
		$page_terms = get_the_terms( get_queried_object_id(), 'simply_faq_cat' );
		if ( $page_terms && ! is_wp_error( $page_terms ) ) {
			$category = $page_terms[0]->slug;
		}
	}

	$args = array(
		'post_type'      => 'simply_faq',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	);

	if ( ! empty( $category ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'simply_faq_cat',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}

	$faqs = new WP_Query( $args );

	if ( ! $faqs->have_posts() ) {
		return '';
	}

	// Collect categories present in these FAQs (in order of first appearance).
	$seen_cats  = array();
	$faq_data   = array();

	while ( $faqs->have_posts() ) {
		$faqs->the_post();
		$id    = get_the_ID();
		$terms = get_the_terms( $id, 'simply_faq_cat' );
		$slug  = '';
		$label = '';
		if ( $terms && ! is_wp_error( $terms ) ) {
			$slug  = $terms[0]->slug;
			$label = $terms[0]->name;
			if ( ! isset( $seen_cats[ $slug ] ) ) {
				$seen_cats[ $slug ] = $label;
			}
		}
		$faq_data[] = array(
			'id'       => $id,
			'title'    => get_the_title(),
			'content'  => get_the_content(),
			'cat_slug' => $slug,
		);
	}
	wp_reset_postdata();

	$show_filters = count( $seen_cats ) > 1;

	ob_start();
	?>
	<div class="sf-faqs-block">

		<?php if ( $show_filters ) : ?>
		<div class="sf-filters" role="group" aria-label="<?php esc_attr_e( 'Filter FAQs by category', 'simply-faqs' ); ?>">
			<button class="sf-filter-btn is-active" data-category=""><?php esc_html_e( 'All', 'simply-faqs' ); ?></button>
			<?php foreach ( $seen_cats as $slug => $label ) : ?>
				<button class="sf-filter-btn" data-category="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<div class="sf-faqs">

			<?php foreach ( $faq_data as $faq ) : ?>
				<div class="sf-faq" data-category="<?php echo esc_attr( $faq['cat_slug'] ); ?>">

					<button class="sf-faq__question" aria-expanded="false">
						<span class="sf-faq__question-text"><?php echo esc_html( $faq['title'] ); ?></span>
						<span class="sf-faq__icon" aria-hidden="true"></span>
					</button>

					<div class="sf-faq__answer">
						<div class="sf-faq__answer-inner">
							<?php echo wp_kses_post( apply_filters( 'the_content', $faq['content'] ) ); ?>
						</div>
					</div>

				</div>
			<?php endforeach; ?>

		</div>

	</div>
	<?php
	return ob_get_clean();
}
