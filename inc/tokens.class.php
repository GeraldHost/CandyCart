<?php
class Candy_Tokens {

	static public function generate(
		$unique = TRUE)
	{
		if($unique){
			$uid = sprintf(
	            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	            mt_rand(0, 0xffff),
	            mt_rand(0, 0xffff),
	            mt_rand(0, 0xffff),
	            mt_rand(0, 0x0fff) | 0x4000,
	            mt_rand(0, 0x3fff) | 0x8000,
	            mt_rand(0, 0xffff),
	            mt_rand(0, 0xffff),
	            mt_rand(0, 0xffff)
	        );

        	$token = str_replace('-', '', $uid);
		} else {
			$token = substr(md5(uniqid(rand(), true)), 0, 16);
		}

		$token = 'candy-' . $token;
		Candy_Tokens::set($token);
		return $token;
	}

	static public function get()
	{
		$token = $_COOKIE['candy'];
		return $token;
	}

	static public function set(
		$value)
	{
		setcookie('candy', $value);
	}

}