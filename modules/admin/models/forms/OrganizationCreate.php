<?php

namespace app\modules\admin\models\forms;

use app\models\OrganizationType;
use app\models\Post;
use Exception;
use yii\base\Model;

/**
 * Class PostCreate
 *
 * PostCreate form
 *
 * @package app\modules\admin\models\forms
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class OrganizationCreate extends Model {

	public ?string $orgName = null;

	public ?string $orgPhone = null;

	public ?string $orgEmail = null;

	public ?string $orgTypeId = null;

	public ?string $orgAddressZip = null;

	public ?string $orgAddressCity = null;

	public ?string $orgAddressStreet = null;

	public ?string $refereeEmail = null;

	public ?string $refereeName = null;

	public ?string $pastorName = null;

	public ?string $pastorEmail = null;

	public ?string $superintendentName = null;


	/**
	 * @inheritdoc
	 */
	public function rules(): array {
		$emailRules = fn($slug) => [
			[$slug, 'trim'],
			[$slug, 'required'],
			[$slug, 'email'],
		];
		$nameRules = fn($slug) => [
			[$slug, 'trim'],
			[$slug, 'required'],
		];

		return [
			...$nameRules('refereeName'),

			...$emailRules('refereeEmail'),

			['orgName', 'required'],
			['orgName', 'trim'],
			['orgAddressZip', 'required'],
			['orgAddressZip', 'trim'],
			['orgAddressCity', 'required'],
			['orgAddressCity', 'trim'],
			['orgAddressStreet', 'required'],
			['orgAddressStreet', 'trim'],
			['orgPhone', 'string', 'max' => 100],
			...$emailRules('orgEmail'),
			['orgTypeId', 'required'],
			['orgTypeId', 'in', 'range' => array_keys(OrganizationType::getList())],

			...$nameRules('pastorName'),
			...$emailRules('pastorEmail'),

			...$nameRules('superintendentName'),
		];
	}


	/**
	 * @return Post|null the saved model or null if saving fails
	 * @throws Exception
	 */
	public function create(): ?Post {
		if (!$this->validate()) {
			return null;
		}

		return null;
	}


	/**
	 * @return array
	 */
	public function attributeLabels(): array {
		return [
			'orgName'            => 'Név',
			'orgPhone'           => 'Telefonszám',
			'orgEmail'           => 'E-mail cím',
			'orgTypeId'          => 'Típus',
			'orgAddressZip'      => 'Irányítószám',
			'orgAddressCity'     => 'Település',
			'orgAddressStreet'   => 'Utca, házszám...',
			'refereeName'        => 'Név',
			'refereeEmail'       => 'E-mail cím',
			'pastorName'         => 'Név',
			'pastorEmail'        => 'E-mail cím',
			'superintendentName' => 'Felügyelő neve',
		];
	}


}
