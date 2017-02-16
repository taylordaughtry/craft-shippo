<?php

namespace CommerceShippo;

use Commerce\Interfaces\ShippingRule as CommerceShippingRule;

class ShippingRule implements CommerceShippingRule
{
	private $order;
	private $rate;

	public function __construct($order = null, $rate = null)
	{
		$this->_order = $order;
		$this->_rate = $rate;
	}

	public function matchOrder(\Craft\Commerce_OrderModel $order)
	{
		return true;
	}

	public function getIsEnabled()
	{
		return true;
	}

	public function getOptions()
	{
		return [];
	}

	public function getPercentageRate()
	{
		return 0.00;
	}

	public function getPerItemRate()
	{
		return 0.00;
	}

	public function getWeightRate()
	{
		return 0.00;
	}

	public function getBaseRate()
	{
		return $this->_rate['amount'];
	}

	public function getMaxRate()
	{
		return 0.00;
	}

	public function getMinRate()
	{
		return 0.00;
	}

	public function getDescription()
	{
		return $this->_rate['duration_terms'];
	}
}