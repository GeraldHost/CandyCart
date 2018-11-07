<?php

class Candy_Order {

	public function process(
		$customer,
		$order,
		Candy_Cart $cart)
	{
		wp_set_current_user(0);
		$this->createOrder($customer, $order, $cart->items, $cart->totals);
		$this->processItems($cart);
		$this->updateStatus('processing');

		try{
			if(!$this->charge()){
				throw new Exception('Something went wrong. You have not been charged. Please contact us.');
			}
		} catch(Exception $e){
			// charge failed
			// reverse the items qty to their orginal qty
			foreach($cart->items as $item){
				Candy_Product::updateProductQty(-$item['qty']);
			}
			// re-throw the message
			throw new Exception($e->getMessage());
		}

		$this->updateStatus('complete');
	}

	public function createOrder(
		array $customer,
		array $order,
		$items,
		$totals)
	{
		$customer_fields = apply_filters('candy_customer_fields', [
			'customer_name',
			'customer_email',
		]);

		$order_fields = apply_filters('candy_order_fields', [
			'shipping_address',
			'shipping_address_1',
			'shipping_address_2',
			'shipping_postcode'
		]);

		// create new order post
		$postId = wp_insert_post([
			'post_type' => 'orders'
		], true);

		if(is_wp_error($postId)){
			throw new Exception($postId->get_error_message());
		}

		foreach($customer_fields as $field){
			if(!isset($customer[$field])){
				throw new Exception('Customer field ' . $field . ' is required.');
			}
			add_post_meta($postId, $field, $customer[$field]);
		}

		foreach($order_fields as $field){
			if(!isset($order[$field])){
				throw new Exception('Order field ' . $field . ' is required.');
			}
			add_post_meta($postId, $field, $order[$field]);
		}

		add_post_meta($postId, 'totals', serialize($totals));
		add_post_meta($postId, 'items', serialize($items));

		wp_update_post([
			'ID' => $postId,
			'post_title' => 'Order : ' . $postId,
		]);
	}
	
	public function processItems(
		Candy_Cart $cart)
	{
		// loop through cart items check they are available and update their qty
		foreach($cart->items as $key => $item){
			if(!Candy_Product::available($item['id'])){
				$cart->remove($item['id']);
				throw new Exception('Cart item no longer available. It has been removed from your cart.');
			}

			// update cart item qty
			Candy_Product::updateProductQty($item['qty']);
		}
	}

	public function charge()
	{
		// charge customer
		do_action('candy_order_charge');
		
		return true;
	}

}