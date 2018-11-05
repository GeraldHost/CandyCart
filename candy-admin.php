<?php
/**
*	Add Candy WP-ADMIN menu item
*/
function candy_add_menu_item()
{
	add_menu_page('candy', 'Candy', 'manage_options', 'candy', function(){
		require CANDY_PATH . '/templates/admin.php';
	});
}
add_action('admin_menu', 'candy_add_menu_item');

function candy_remove_order_meta_boxes()
{
	remove_meta_box('postcustom', 'orders', 'normal');
}
add_action('admin_menu', 'candy_remove_order_meta_boxes');

function candy_add_order_meta_boxes()
{
	add_meta_box('candy-order-summary', 'Order Summary', function(){
		if(file_exists(get_template_directory() . '/candy/templates/order-summary.php')){
			require get_template_directory() . '/candy/templates/order-summary.php';
		} else {
			require CANDY_PATH . '/templates/order-summary.php';
		}
	}, 'orders', 'normal');
}
add_action('admin_menu', 'candy_add_order_meta_boxes');

/**
*	Register Cart Post Type
*/
function candy_register_cart_post_type()
{
	$args = [
		'public' => true,
		'publicly_queryable' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false,
		'supports' => array('title','editor','custom-fields'),
	];

	register_post_type('cart', $args);
}
add_action('init', 'candy_register_cart_post_type');

function candy_enqueue_scripts()
{
	wp_enqueue_script( 'candy', CANDY_URL . 'assets/dist/candy.min.js', '1', true);
	wp_localize_script( 'candy', 'candy', array( 'ajax' => admin_url('admin-ajax.php') ) );
}
add_action('wp_enqueue_scripts', 'candy_enqueue_scripts');

function candy_register_orders_post_type()
{
	$plural = 'Orders';
	$single = 'Order';

	$labels = [
		'name' => _x($plural, 'post type general name'),
		'singular_name' => _x($single, 'post type singular name'),
		'add_new' => _x('Add New', $single),
		'add_new_item' => __('Add New '. $single),
		'edit_item' => __('Edit '.$single),
		'new_item' => __('New '.$single),
		'view_item' => __('View '.$single),
		'search_items' => __('Search '.$plural),
		'not_found' =>  __('No '.$plural.' found'),
		'not_found_in_trash' => __('No '.$plural.' found in Trash'),
		'parent_item_colon' => '',
	];

	$args = [
		'labels' => $labels,
		'show_ui' => true,
		'public' => true,
		'publicly_queryable' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive' => false,
		'supports' => array('title','custom-fields'),
		'menu_icon' => 'dashicons-store',
	];

	register_post_type('orders', $args);
}
add_action('init', 'candy_register_orders_post_type');