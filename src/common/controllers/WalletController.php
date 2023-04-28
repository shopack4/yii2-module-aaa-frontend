<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\common\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use shopack\aaa\frontend\common\models\WalletModel;
use shopack\aaa\frontend\common\models\WalletSearchModel;
use shopack\aaa\frontend\common\models\WalletIncreaseForm;

class WalletController extends BaseCrudController
{
	public $modelClass = WalletModel::class;
	public $searchModelClass = WalletSearchModel::class;

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

  public function actionIncrease($id)
  {
    $model = new WalletIncreaseForm;
    $model->walletID = $id;

		$formPosted = $model->load(Yii::$app->request->post());
		$done = false;
		if ($formPosted)
			$done = $model->process();

    if (Yii::$app->request->isAjax) {
      if ($done != false) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
          'redirect' => $done['paymentUrl'],
          // 'modalDoneFragment' => $this->modalDoneFragment,
        ]);
      }

      if ($formPosted) {
        return $this->renderJson([
          'status' => 'Error',
          'message' => Yii::t('app', 'Error'),
          // 'id' => $id,
          'error' => Html::errorSummary($model),
        ]);
      }

      return $this->renderAjaxModal('_increase', [
        'model' => $model,
      ]);
    }

    if ($done != false)
      return $this->redirect($done['paymentUrl']);

    return $this->render('increase', [
      'model' => $model,
    ]);
  }

}
