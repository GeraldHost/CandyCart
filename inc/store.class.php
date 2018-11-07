<?php

class Candy_Store {

	public static $currency_symbols = [
		'gbp' => '£',
		'usd' => '$',
		'eur' => '€',
	];

	public static function getStoreCurrency()
	{
		return apply_filters('candy_store_currency', 'gbp');
	}

	public static function getCurrencySymbol()
	{
		$currency = self::getStoreCurrency();
		$symbols = apply_filters('candy_store_currency_symbols', self::$currency_symbols);

		return $symbols[$currency] ?: '£';
	}

}
