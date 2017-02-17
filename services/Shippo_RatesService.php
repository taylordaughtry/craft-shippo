<?php

namespace Craft;

require CRAFT_PLUGINS_PATH . 'shippo/vendor/autoload.php';

use \Shippo as Shippo;
use \Shippo_Shipment as Shippo_Shipment;

class Shippo_RatesService extends BaseApplicationComponent
{
	private $settings;
	private $address;

	public function __construct()
	{
		$this->settings = craft()->plugins->getPlugin('Shippo')->getSettings();

		$this->address = $this->settings->fromAddress;
	}

	/**
	 * Gets the rates for a provided order from the Shippo API.
	 *
	 * TODO: Allow the 'From' Address to be defined in the plugin config.
	 * TODO: Allow product sizing & weight information to be passed to Shippo.
	 *
	 * @param  {OrderModel} | $order The current order being processed
	 * @return {array} | The rates for the order being processed
	 */
	public function getRates($order)
	{
		Shippo::setApiKey($this->settings->apiKey);

		$shippingAddress = $order->shippingAddress;

		$from_address = [
			'object_purpose' => 'PURCHASE',
			'name' => $this->address['name'],
			'company' => $this->address['company'],
			'street1' => $this->address['street1'],
			'city' => $this->address['city'],
			'state' => $this->address['state'],
			'zip' => $this->address['zip'],
			'country' => $this->address['country'],
			'phone' => $this->address['phone'],
			'email' => $this->address['email']
		];

		$to_address = [
			'object_purpose' => 'PURCHASE',
			'name' => $shippingAddress->firstName . ' ' . $shippingAddress->lastName,
			'company' => $shippingAddress->businessName,
			'street1' => $shippingAddress->address1,
			'city' => $shippingAddress->city,
			'state' => $shippingAddress->getState()->abbreviation,
			'zip' => $shippingAddress->zipCode,
			'country' => $shippingAddress->getCountry()->iso,
			'phone' => $shippingAddress->phone,
			'email' => $order->email
		];

		$parcel = [
			'length'=> '5',
			'width'=> '5',
			'height'=> '5',
			'distance_unit'=> 'in',
			'weight'=> '2',
			'mass_unit'=> 'lb'
		];

		$shipment = Shippo_Shipment::create([
			'object_purpose'=> 'PURCHASE',
			'address_from'=> $from_address,
			'address_to'=> $to_address,
			'parcel'=> $parcel,
			'async'=> false
		]);

		ShippoPlugin::log(json_encode($shipment->__toString()), LogLevel::Info);

		return $shipment['rates_list'];
	}
}