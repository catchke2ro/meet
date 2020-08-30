<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * Class Post
 *
 * @property int    id
 * @property string title
 * @property string image
 * @property array  tags
 * @property string intro
 * @property string text
 * @property int    date
 * @property int    order
 *
 * @package app\models
 * @author  Adam Balint <catchke2ro@miheztarto.hu>
 */
class Post extends ActiveRecord {

	const IMAGE_BASE = '/assets/img/posts';


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'posts';
	}


	/**
	 * @return string
	 */
	public function getImageUrl(): ?string {
		if ($this->image) {
			$url = self::IMAGE_BASE . '/' . $this->image;
			$path = Yii::$app->getBasePath() . '/web/' . $url;
			if (file_exists($path)) {
				return $url;
			}
		}

		return null;
	}


	/**
	 * @return string|null
	 */
	public function getFirstTagSlug(): ?string {
		$tags = json_decode($this->tags, true) ?: [];
		if (is_array($tags) && $tags) {
			$firstTag = reset($tags);
			return slug($firstTag);
		}
		return null;
	}


}
