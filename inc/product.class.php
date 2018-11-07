<?php
class Candy_Product {

	static public function find(
		$id)
	{
		if(! Candy_Product::exists($id)){		
			throw new Exception('Product not found.');
		}

		$post = get_post($id);
		$price = get_post_meta($id, Candy_Product::getPriceField(), true) ?: 0;
		$post->meta = [
			'price' => $price,
		];

		return $post;
	}

	static public function getProductPostType()
	{
		return apply_filters('candy_set_product_post_type', 'post');
	}

	static public function getPriceField()
	{
		return apply_filters('candy_set_product_price_field', 'price');
	}

	static public function getQtyField()
	{
		return apply_filters('candy_set_product_qty_field', 'qty');
	}

	static public function updateProductQty(
		$qty, $id)
	{
		$currQty = get_post_meta($id, Candy_Product::getQtyField(), true) ?: 0;
		$newQty = (int)$currQty - (int)$qty;
		return update_post_meta($id, 'qty', $newQty, $currQty);
	}

	static public function exists(
		$id)
	{
		$post_type = get_post_type($id);
		if($post_type !== Candy_Product::getProductPostType()){
			new Exception('Post type invalid. Allowed post type is ' . Candy_Product::getProductPostType());
		}
		return is_string( $post_type ) ? true : false;
	}

}