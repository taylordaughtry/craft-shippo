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

	public function hasSettings()
	{
		return true;
	}

	public function defineSettings()
	{
		return [
			'apiKey' => [
				AttributeType::String,
				'default' => '',
				'required' => true
			],
			'fromAddress' => [
				AttributeType::Mixed,
				'default' => [
					'name' => 'Default Name',
					'company' => craft()->getSiteName(),
					'street1' => '1600 Pennsylvania Ave NW',
					'city' => 'Washington',
					'state' => 'DC',
					'zip' => '20500',
					'country' => 'US',
					'phone' => '+1 234 567 8901',
					'email' => craft()->email->settings['emailAddress']
				],
				'required' => true
			]
		];
	}

	public function getSettingsHtml()
	{
		return craft()->templates->render('shippo/Shippo_Settings', [
			'settings' => $this->getSettings()
		]);
	}

	public function init()
	{
		require CRAFT_PLUGINS_PATH . 'shippo/vendor/autoload.php';
	}

	public function commerce_registerShippingMethods($order = null)
	{
		if ($order && $order->shippingAddress) {
			$shippingMethods = [];

			$rates = craft()->shippo_rates->getRates($order);

			foreach ($rates as $rate) {
				$shippingMethods[] = new ShippingMethod($order, $rate);
			}

			return $shippingMethods;
		}
	}
}