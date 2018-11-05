<?php
require CANDY_PATH . 'inc/tokens.class.php';
require CANDY_PATH . 'inc/product.class.php';

class Candy_Cart {

	public $token;
	public $items;
	public $totals;

	function __construct()
	{
		$this->token = Candy_Tokens::get() ?: Candy_Tokens::generate();
		$this->init();
		$this->items = $this->cart->items ?: [];
		$this->totals = $this->cart->totals;

		$this->save();
	}

	public function init()
	{
		$data = $_COOKIE[$this->token];
		$this->cart = json_decode($data, true);
	}

	public function healItems()
	{
		foreach($this->items as $key => $item){
			$this->items[$key]['product'] = Candy_Product::find($item['id']);
		}
	}

	public function save()
	{
		$this->healItems();
		$this->calculateTotals();
		$cart = json_encode([
			'items' => $this->items,
			'totals' => $this->totals,
		]);

		setCookie($this->token, $cart);
	}

	/**
	*	Get Cart
	*
	*	@return obj Cart Object
	*/
	public function get()
	{
		return [
			'items' => $this->items,
			'totals' => $this->totals,
		];
	}


	/**
	*	Add Item To Cart
	*
	*	@param INT post ID
	*	@param INT qty
	*/
	public function add(
		$id,
		$qty)
	{
		$product = Candy_Product::find($id);

		$key = $this->find($id);
		if($key !== false){
			$this->items[$key]->qty += $qty;
		} else {
			$item = [
				'id' => $id,
				'product' => $product,
				'qty' => $qty,
			];

			$this->items[] = $item;
		}

		$this->save();
	}

	/**
	*	Remove Item From Cart
	*
	*	@param INT post ID
	*	@param INT qty
	*	@throws Exception item not found
	*/
	public function remove(
		$id)
	{
		$key = $this->find($id);
		if($key === false){
			throw new Exception('Product not found in cart.');
		}

		unset($this->items[$key]);
	}

	/**
	*	Find an item in the Cart
	*
	*	@param INT post ID
	*
	*	@return string key // bool false
	*/
	public function find(
		$id)
	{
		foreach($this->items as $key => $item){
			if($item->id == $id){
				return $key;
			}
		}
		return false;
	}

	public function calculateTotals()
	{
		$sum = 0;
		if(!empty($this->items)){
			foreach($this->items as $item){
				$sum += ((int)$item->qty * (int)$item->product->meta['price']);
			}
		}

		$this->totals = [
			'sub_total' => $sum,
			'tax' => $sum * apply_filters('candy_tax_percentage', 0),
			'shipping' => apply_filters('candy_shipping_cost', 0),
		];
	}

}