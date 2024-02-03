<?php

namespace app\models;

use app\models\traits\SetGetTrait;
use DateTime;
use sjaakp\spatial\ActiveRecord;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;

/**
 * Class Address
 *
 * @property int      $id
 * @property string   $zip
 * @property string   $city
 * @property string   $address
 * @property mixed    $point
 * @property DateTime $createdAt
 * @property DateTime $updatedAt
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Address extends ActiveRecord {

	use SetGetTrait;


	/**
	 * @return void
	 */
	public function init(): void {
		parent::init();
		$this->on(self::EVENT_AFTER_UPDATE, [$this, 'geocode']);
		$this->on(self::EVENT_AFTER_INSERT, [$this, 'geocode']);
	}


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return 'addresses';
	}


	/**
	 * @return string
	 */
	public function getAddressString(): string {
		return $this->zip . ' ' . $this->city . ', ' . $this->address;
	}


	/**
	 * @param Event $event
	 *
	 * @return void
	 * @throws InvalidConfigException
	 * @throws Exception
	 */
	public function geocode(Event $event): void {
		$addressChanged = isset($event->changedAttributes) &&
			(isset($event->changedAttributes['zip']) && $event->changedAttributes['zip'] !== $this->zip) ||
			(isset($event->changedAttributes['city']) && $event->changedAttributes['city'] !== $this->city) ||
			(isset($event->changedAttributes['address']) && $event->changedAttributes['address'] !== $this->address);
		if ($addressChanged || !$this->point) {
			$client = new Client();
			$request = $client->createRequest()
				->setMethod('GET')
				->setUrl('https://nominatim.openstreetmap.org/search')
				->setData([
					'city'    => $this->city,
					'street'  => $this->address,
					'address' => $this->address,
					'format'  => 'jsonv2'
				])
				->addHeaders([
					'Accept-Language' => 'hu',
					'User-Agent'      => 'MEET Lutheran/1.0'
				]);
			$response = $client->send($request);
			if ($response->getStatusCode() == 200) {
				$poi = $response->getData()[0] ?? null;
				if ($poi) {
					$this->point = json_encode([
						'type'     => 'Feature',
						'geometry' => [
							'type'        => 'Point',
							'coordinates' => [
								(float) $poi['lon'],
								(float) $poi['lat']
							]
						]
					]);
				}
			}

			$this->save();
		}
	}


	/**
	 * @return array|null
	 */
	public function getLatLng(): ?array {
		$point = $this->point;
		if ($point && is_array(($pointData = json_decode($point, true)))) {
			return [
				'lng' => $pointData['geometry']['coordinates'][0],
				'lat' => $pointData['geometry']['coordinates'][1]
			];
		}

		return null;
	}


}
