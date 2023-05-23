<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\common\models;

use Yii;
use shopack\base\frontend\rest\RestClientActiveRecord;
// use shopack\aaa\common\enums\enuMessageStatus;

class MessageTemplateModel extends RestClientActiveRecord
{
	use \shopack\aaa\common\models\MessageTemplateModelTrait;

	public static $resourceName = 'aaa/message-template';
  public static $primaryKey = ['mstID'];

	public function attributeLabels()
	{
		return [
			'mstID'             => Yii::t('app', 'ID'),
			'mstKey'            => Yii::t('app', 'Key'),
			'mstMedia'          => Yii::t('app', 'Media'),
			'mstLanguage'       => Yii::t('app', 'Language'),
			'mstTitle'          => Yii::t('app', 'Title'),
			'mstBody'           => Yii::t('app', 'Body'),
			'mstParamsPrefix'   => Yii::t('app', 'Params Prefix'),
			'mstParamsSuffix'   => Yii::t('app', 'Params Suffix'),
			'mstIsSystem'       => Yii::t('app', 'System Template'),
			'mstStatus'         => Yii::t('app', 'Status'),
			'mstCreatedAt'      => Yii::t('app', 'Created At'),
			'mstCreatedBy'      => Yii::t('app', 'Created By'),
			'mstCreatedBy_User' => Yii::t('app', 'Created By'),
			'mstUpdatedAt'      => Yii::t('app', 'Updated At'),
			'mstUpdatedBy'      => Yii::t('app', 'Updated By'),
			'mstUpdatedBy_User' => Yii::t('app', 'Updated By'),
			'mstRemovedAt'      => Yii::t('app', 'Removed At'),
			'mstRemovedBy'      => Yii::t('app', 'Removed By'),
			'mstRemovedBy_User' => Yii::t('app', 'Removed By'),
		];
	}

	public function isSoftDeleted()
  {
    return false; //($this->mstStatus == enuMessageStatus::Removed);
  }

	public static function canCreate() {
		return true;
	}

	public function canUpdate() {
		return true; //($this->mstStatus != enuMessageStatus::Removed);
	}

	public function canDelete() {
		return true; //($this->mstStatus != enuMessageStatus::Removed);
	}

	public function canUndelete() {
		return false; //($this->mstStatus == enuMessageStatus::Removed);
	}

}
