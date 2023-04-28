<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\common\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseController;
use shopack\aaa\frontend\common\models\VoucherModel;
use shopack\aaa\common\enums\enuVoucherType;
use shopack\aaa\common\enums\enuVoucherStatus;

class BasketController extends BaseController
{
	public function init()
  {
    parent::init();

    $viewPath = dirname(dirname(__FILE__))
      . DIRECTORY_SEPARATOR
      . 'views'
      . DIRECTORY_SEPARATOR
      . $this->id;

    $this->setViewPath($viewPath);
  }

  public function actionIndex()
	{
    $voucherModel = VoucherModel::find()
      ->andWhere(['vchOwnerUserID' => Yii::$app->user->identity->usrID])
      ->andWhere(['vchType' => enuVoucherType::Basket])
      ->andWhere(['vchStatus' => enuVoucherStatus::New])
      ->andWhere(['vchRemovedAt' => 0])
      ->all();

		return $this->render('index', [
			'voucherModel' => $voucherModel,
		]);
	}

	// public function actionAddItem()
	// {
	// }

	// public function actionUpdateItem()
	// {
	// }

	// public function actionRemoveItem()
	// {
	// }

	public function actionCheckout()
	{




	}

}
