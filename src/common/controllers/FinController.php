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
use shopack\aaa\frontend\common\models\UserModel;
use shopack\aaa\frontend\common\models\ImageChangeForm;
use shopack\aaa\frontend\common\models\EmailChangeForm;
use shopack\aaa\frontend\common\models\MobileChangeForm;

class FinController extends BaseController
{
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors[BaseController::BEHAVIOR_AUTHENTICATOR]['except'] = [
			'online-payment-callback',
		];

		return $behaviors;
	}

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
		if (Yii::$app->user->isGuest)
			return $this->goHome();

    return $this->render('index');
	}

  public function actionOnlinePaymentCallback()
  {

  }

}
