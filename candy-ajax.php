<?php
/*
*	Candy Plugin Ajax Functions
*/
class Candy_Ajax_Response {

	public function setStatus($value)
	{
		$this->status = $value;
		return $this;
	}

	public function setMessage($value)
	{
		$this->message = $value;
		return $this;
	}

	public function setData($value)
	{
		$this->data = $value;
		return $this;
	}

	public function setStatusCode($code)
	{
		return $this;
	}

	public function dispatch()
	{
		header("Content-type: application/json");
		$data = [
			'status' => $this->status,
			'message' => $this->message,
		];

		if($this->data) $data['data'] = $this->data;
		echo json_encode($data, JSON_PRETTY_PRINT);
		wp_die();
	}

}

function candy_ajax_add_to_cart()
{
	try{
		if(!isset($_GET['id'])) {
			throw new Exception('Request parameter "id" is required.');
		}
		if(!isset($_GET['qty'])) {
			throw new Exception('Request parameter "qty" is required.');
		}
		global $candy;
		$candy->add($_GET['id'], $_GET['qty']);
		(new Candy_Ajax_Response)
			->setStatus('ok')
			->setMessage('Item added to cart')
			->setData($candy->get())
			->dispatch();
	} catch(Exception $e){
		(new Candy_Ajax_Response)->setStatus('fail')->setMessage($e->getMessage())->dispatch();
	}
}
add_action('wp_ajax_nopriv_candy_add_to_cart', 'candy_ajax_add_to_cart');
add_action('wp_ajax_candy_add_to_cart', 'candy_ajax_add_to_cart');

function candy_ajax_remove_from_cart()
{
	try{
		if(!isset($_GET['id'])) {
			throw new Exception('Request parameter "id" is required.');
		}
		global $candy;
		$candy->remove($_GET['id']);
		(new Candy_Ajax_Response)
			->setStatus('ok')
			->setMessage('Item removed from cart')
			->setData($candy->get())
			->dispatch();
	} catch(Exception $e){
		(new Candy_Ajax_Response)->setStatus('fail')->setMessage($e->getMessage())->dispatch();
	}
}
add_action('wp_ajax_nopriv_candy_remove_from_cart', 'candy_ajax_remove_from_cart');
add_action('wp_ajax_candy_remove_from_cart', 'candy_ajax_remove_from_cart');

function candy_ajax_get_cart()
{
	try{
		global $candy;
		(new Candy_Ajax_Response)
			->setStatus('ok')
			->setMessage('Cart retrieved')
			->setData($candy->get())
			->dispatch();
	} catch(Exception $e){
		(new Candy_Ajax_Response)->setStatus('fail')->setMessage($e->getMessage())->dispatch();
	}
}
add_action('wp_ajax_nopriv_candy_get_cart', 'candy_ajax_get_cart');
add_action('wp_ajax_candy_get_cart', 'candy_ajax_get_cart');