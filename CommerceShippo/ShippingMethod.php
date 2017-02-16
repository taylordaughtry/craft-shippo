<?php

namespace CommerceShippo;

use Commerce\Interfaces\ShippingMethod as CommerceShippingMethod;

class ShippingMethod implements CommerceShippingMethod
{
	private $_order;
	private $_rate;

	public function __construct($order = null, $rate = null)
	{
		$this->_order = $order;
		$this->_rate = $rate;
	}

	public function getType()
	{
		return $this->_rate['provider'];
	}

	public function getName()
	{
		return $this->_rate['servicelevel_name'];
	}

	public function getHandle()
	{
		return $this->toCamelCase($this->_rate['servicelevel_name']);
	}

	public function getId()
	{
		return null;
	}

	public function getCpEditUrl()
	{
		return '';
	}

	public function getRules()
	{
		return [new ShippingRule($this->_order, $this->_rate)];
	}

	public function getIsEnabled()
	{
		return true;
	}

	public static function toCamelCase($str)
	{

		$str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
		$str = trim($str);

		$str = ucwords($str);
		$str = str_replace(' ', '', $str);
		$str = lcfirst($str);

		return $str;
	}
}