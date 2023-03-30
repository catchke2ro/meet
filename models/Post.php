<?php

namespace app\models;

use app\modules\meet\models\interfaces\DataTableModelInterface;
use Yii;
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
class Post extends ActiveRecord implements DataTableModelInterface {

	const IMAGE_BASE = '/assets/img/posts';


	/**
	 * @return string
	 */
	public static function tableName(): string {
		return Yii::$app->params['table_prefix'].'posts';
	}


	/**
	 * return
	 */
	public static function getImageBasePath(): string {
		return Yii::$app->getBasePath() . '/web' . self::IMAGE_BASE;
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
		$tags = $this->getTagsArray();
		if (is_array($tags) && $tags) {
			$firstTag = reset($tags);
			return slug($firstTag);
		}
		return null;
	}


	/**
	 * @return array|mixed
	 */
	public function getTagsArray(): array {
		return json_decode($this->tags, true) ?: [];
	}


	/**
	 * @return array
	 */
	public function toDataTableArray(): array {
		return [
			'id'        => $this->id,
			'title'      => $this->title,
			'order'      => $this->order,
		];
	}


	/**
	 * @return array|string[]
	 */
	public function getDataTableActions(): array {
		return [
			'edit'   => '<a href="/meet/posts/edit/' . $this->id . '" class="fa fa-pencil" title="Szerkesztés"></a>',
			'delete' => '<a href="/meet/posts/delete/' . $this->id . '" class="fa fa-trash" title="Törlés" onclick="return confirm(\'Biztos törlöd?\')"></a>',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getTextSearchColumns(): array {
		return [
			'title',
		];
	}


	/**
	 * @return array|string[]
	 */
	public static function getOrderableColumns(): array {
		return [
			'title',
			'order',
		];
	}


}
