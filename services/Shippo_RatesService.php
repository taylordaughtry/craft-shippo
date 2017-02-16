<?php

namespace Craft;

require CRAFT_PLUGINS_PATH . 'shippo/vendor/autoload.php';

use \Shippo as Shippo;
use \Shippo_Shipment as Shippo_Shipment;

class Shippo_RatesService extends BaseApplicationComponent
{
	private $apiKey;

	public function __construct()
	{
		$this->apiKey = '*****';
	}

	public function getRates($order)
	{
		Shippo::setApiKey($this->apiKey);

		$from_address = [
			'object_purpose' => 'PURCHASE',
			'name' => 'Mr Hippo',
			'company' => 'Shippo',
			'street1' => '215 Clayton St.',
			'city' => 'San Francisco',
			'state' => 'CA',
			'zip' => '94117',
			'country' => 'US',
			'phone' => '+1 555 341 9393',
			'email' => 'mr-hippo@goshipppo.com',
		];

		$to_address = [
			'object_purpose' => 'PURCHASE',
			'name' => 'Ms Hippo',
			'company' => 'San Diego Zoo',
			'street1' => '2920 Zoo Drive',
			'city' => 'San Diego',
			'state' => 'CA',
			'zip' => '92101',
			'country' => 'US',
			'phone' => '+1 555 341 9393',
			'email' => 'ms-hippo@goshipppo.com',
		];

		$parcel = [
			'length'=> '5',
			'width'=> '5',
			'height'=> '5',
			'distance_unit'=> 'in',
			'weight'=> '2',
			'mass_unit'=> 'lb',
		];

		$shipment = Shippo_Shipment::create([
			'object_purpose'=> 'PURCHASE',
			'address_from'=> $from_address,
			'address_to'=> $to_address,
			'parcel'=> $parcel,
			'async'=> false,
		]);

		return $shipment['rates_list'];
	}
}