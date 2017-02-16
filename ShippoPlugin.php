<?php

namespace Craft;

use CommerceShippo\ShippingMethod as ShippingMethod;

class ShippoPlugin extends BasePlugin
{
	private $_name = 'Shippo';
	private $_description = 'Live shipping rates with Shippo.';
	private $_version = '1.0.0';
	private $_developer = 'Taylor Daughtry';
	private $_developerUrl = 'https://taylordaughtry.com';

	public function getName()
	{
		return $this->_name;
	}

	public function getDescription()
	{
		return $this->_description;
	}

	public function getVersion()
	{
		return $this->_version;
	}

	public function getDeveloper()
	{
		return $this->_developer;
	}

	public function getDeveloperUrl()
	{
		return $this->_developerUrl;
	}

	public function init()
	{
		require CRAFT_PLUGINS_PATH . 'shippo/vendor/autoload.php';
	}

	public function commerce_registerShippingMethods($order = null)
	{
		$shippingMethods = [];

			$rates = craft()->shippo_rates->getRates($order);

			foreach ($rates as $rate) {
				$shippingMethods[] = new ShippingMethod($order, $rate);
			}

			return $shippingMethods;
	}
}