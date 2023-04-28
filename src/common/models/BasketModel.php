<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
use shopack\aaa\common\enums\enuVoucherStatus;

class BasketModel extends RestClientActiveRecord
{
	use \shopack\aaa\common\models\BasketModelTrait;

	public static $resourceName = 'aaa/basket';
  // public static $primaryKey = ['vchID'];

	public function isSoftDeleted()			{ return false; }
	public static function canCreate()	{ return true; }
	public function canUpdate()					{ return true; }
	public function canDelete()					{ return true; }
	public function canUndelete()				{ return true; }

	public function doAddItem()
	{
		$result = self::find()->restCreate($this);
	}

	public function doUpdateItem()
	{
		$result = self::find()->restUpdate($this);
	}

	public function doRemoveItem()
	{
		$result = self::find()->restDelete($this);
	}

	public function doCheckout()
	{
		$result = self::find()
			->endpoint('checkout')
			->restPost($this)
		;
	}

}
