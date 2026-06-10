<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ==========================================================================
// TAXONOMY — simply_faq_cat
// Registered for both simply_faq AND page so pages can be assigned a
// category. The [simply_faqs] shortcode auto-detects the page's category.
// ==========================================================================

add_action( 'init', 'sf_register_taxonomy' );

function sf_register_taxonomy() {
	register_taxonomy( 'simply_faq_cat', array( 'simply_faq', 'page' ), array(
		'labels' => array(
			'name'              => __( 'FAQ Categories', 'simply-faqs' ),
			'singular_name'     => __( 'FAQ Category', 'simply-faqs' ),
			'search_items'      => __( 'Search Categories', 'simply-faqs' ),
			'all_items'         => __( 'All Categories', 'simply-faqs' ),
			'edit_item'         => __( 'Edit Category', 'simply-faqs' ),
			'update_item'       => __( 'Update Category', 'simply-faqs' ),
			'add_new_item'      => __( 'Add New Category', 'simply-faqs' ),
			'new_item_name'     => __( 'New Category Name', 'simply-faqs' ),
			'menu_name'         => __( 'Categories', 'simply-faqs' ),
		),
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'faq-category' ),
		'show_in_rest'      => true,
	) );
}


// ==========================================================================
// CPT — simply_faq
// Question = post title. Answer = post content (editor).
// ==========================================================================

add_action( 'init', 'sf_register_cpt' );

function sf_register_cpt() {
	register_post_type( 'simply_faq', array(
		'labels' => array(
			'name'               => __( 'FAQs', 'simply-faqs' ),
			'singular_name'      => __( 'FAQ', 'simply-faqs' ),
			'add_new'            => __( 'Add New FAQ', 'simply-faqs' ),
			'add_new_item'       => __( 'Add New FAQ', 'simply-faqs' ),
			'edit_item'          => __( 'Edit FAQ', 'simply-faqs' ),
			'new_item'           => __( 'New FAQ', 'simply-faqs' ),
			'search_items'       => __( 'Search FAQs', 'simply-faqs' ),
			'not_found'          => __( 'No FAQs found', 'simply-faqs' ),
			'not_found_in_trash' => __( 'No FAQs found in trash', 'simply-faqs' ),
			'menu_name'          => __( 'FAQs', 'simply-faqs' ),
		),
		'public'            => false,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'show_in_rest'      => true,   // enables block editor for answer
		'supports'          => array( 'title', 'editor', 'page-attributes' ),
		'menu_icon'         => 'dashicons-editor-help',
		'menu_position'     => 26,
		'taxonomies'        => array( 'simply_faq_cat' ),
	) );
}


// ==========================================================================
// ADMIN COLUMNS — drag handle + category
// ==========================================================================

add_filter( 'manage_simply_faq_posts_columns', 'sf_admin_columns' );

function sf_admin_columns( $columns ) {
	return array(
		'cb'          => $columns['cb'],
		'sf_order'    => '',
		'title'       => $columns['title'],
		'sf_category' => __( 'Category', 'simply-faqs' ),
		'date'        => $columns['date'],
	);
}

add_action( 'manage_simply_faq_posts_custom_column', 'sf_admin_column_content', 10, 2 );

function sf_admin_column_content( $column, $post_id ) {
	if ( $column === 'sf_order' ) {
		echo '<span class="sf-drag-handle" title="' . esc_attr__( 'Drag to reorder', 'simply-faqs' ) . '">&#9776;</span>';
	}
	if ( $column === 'sf_category' ) {
		$terms = get_the_terms( $post_id, 'simply_faq_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
		} else {
			echo '&mdash;';
		}
	}
}


// ==========================================================================
// ADMIN — enqueue sortable JS + inline styles on FAQ list screen
// ==========================================================================

add_action( 'admin_enqueue_scripts', 'sf_admin_enqueue' );

function sf_admin_enqueue( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || $screen->id !== 'edit-simply_faq' ) return;

	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script(
		'sf-sort',
		plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/sf-sort.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		'1.0.0',
		true
	);
	wp_localize_script( 'sf-sort', 'sfSort', array(
		'nonce'   => wp_create_nonce( 'sf_save_order' ),
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	) );

	wp_add_inline_style( 'list-tables', '
		.column-sf_order { width: 32px; padding: 8px 4px !important; text-align: center; }
		.sf-drag-handle { cursor: grab; font-size: 16px; color: #aaa; display: inline-block; padding: 4px 6px; line-height: 1; }
		.sf-drag-handle:hover { color: #555; }
		#the-list tr.sf-dragging { background: #f0f6fc; outline: 2px dashed #72aee6; }
	' );
}


// ==========================================================================
// AJAX — save new menu_order after drag
// ==========================================================================

add_action( 'wp_ajax_sf_save_order', 'sf_ajax_save_order' );

function sf_ajax_save_order() {
	check_ajax_referer( 'sf_save_order', 'nonce' );
	if ( ! current_user_can( 'edit_posts' ) ) wp_die( -1 );

	$order = isset( $_POST['order'] ) ? array_map( 'absint', (array) $_POST['order'] ) : array();
	foreach ( $order as $position => $post_id ) {
		if ( $post_id ) {
			wp_update_post( array( 'ID' => $post_id, 'menu_order' => $position ) );
		}
	}
	wp_send_json_success();
}
