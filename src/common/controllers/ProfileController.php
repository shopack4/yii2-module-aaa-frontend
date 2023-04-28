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

class ProfileController extends BaseController
{
	// public $modelClass = UserModel::class;
	// public $searchModelClass = UserSearchModel::class;

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

	protected function findUserModel()
	{
		if (($model = UserModel::findOne(Yii::$app->user->identity->usrID)) === null)
      throw new NotFoundHttpException('The requested item not exist.');

    return $model;
	}

	public function actionIndex()
	{
		if (Yii::$app->user->isGuest)
			return $this->goHome();

    return $this->render('profile', [
      'model' => $this->findUserModel(),
    ]);
	}

	public function actionUpdateUser()
  {
		if (Yii::$app->user->isGuest)
			return $this->goHome();

		$model = $this->findUserModel();

    if ($model->isSoftDeleted())
      throw new BadRequestHttpException('این آیتم حذف شده است و قابل ویرایش نمی‌باشد.');

		$formPosted = $model->load(Yii::$app->request->post());
		$done = false;
		if ($formPosted)
			$done = $model->save();

    if (Yii::$app->request->isAjax) {
      if ($done) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
          // 'redirect' => $this->doneLink ? call_user_func($this->doneLink, $model) : null,
          'modalDoneFragment' => 'details',
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

      return $this->renderAjaxModal('_form_user', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->redirect(['index']);

    return $this->render('updateUser', [
      'model' => $model
    ]);
  }

	public function actionUpdateImage()
  {
		if (Yii::$app->user->isGuest)
			return $this->goHome();

		$model = $this->findUserModel();

    if ($model->isSoftDeleted())
      throw new BadRequestHttpException('این آیتم حذف شده است و قابل ویرایش نمی‌باشد.');

    $model = new ImageChangeForm();

    $formPosted = $model->load(Yii::$app->request->post());
    $done = false;
    if ($formPosted)
      $done = $model->process();

    if (Yii::$app->request->isAjax) {
      if ($done) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
          // 'redirect' => $this->doneLink ? call_user_func($this->doneLink, $model) : null,
          'modalDoneFragment' => 'details',
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

      return $this->renderAjaxModal('_form_image', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->redirect(['index']);

    return $this->render('updateImage', [
      'model' => $model,
    ]);
  }

  // email-change
  // resend-email-approval
  // mobile-change
  // mobile-approve
  // password-set
  // password-change

  public function actionEmailChange()
  {
		if (Yii::$app->user->isGuest)
			return $this->goHome();

		$model = $this->findUserModel();

    if ($model->isSoftDeleted())
      throw new BadRequestHttpException('این آیتم حذف شده است و قابل ویرایش نمی‌باشد.');

    $model = new EmailChangeForm();

    $formPosted = $model->load(Yii::$app->request->post());
    $done = false;
    if ($formPosted)
      $done = $model->process();

    if (Yii::$app->request->isAjax) {
      if ($done) {
        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
          // 'redirect' => $this->doneLink ? call_user_func($this->doneLink, $model) : null,
          'modalDoneFragment' => 'login',
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

      return $this->renderAjaxModal('_form_email', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->redirect(['index']);

    return $this->render('emailChange', [
      'model' => $model,
    ]);
  }

  public function actionResendEmailApproval()
  {
		if (Yii::$app->user->isGuest)
			return $this->goHome();

		$model = $this->findUserModel();

    if ($model->isSoftDeleted())
      throw new BadRequestHttpException('این آیتم حذف شده است و قابل ویرایش نمی‌باشد.');
  }

  public function actionMobileChange()
  {
    if (Yii::$app->user->isGuest)
      return $this->goHome();

    $model = $this->findUserModel();

    if ($model->isSoftDeleted())
      throw new BadRequestHttpException('این آیتم حذف شده است و قابل ویرایش نمی‌باشد.');

    $model = new MobileChangeForm();

    if ($model->load(Yii::$app->request->post())) {
      $result = $model->process();

      if ($result === true)
        return $this->goHome();

      if ($result === 'challenge') {
        $challengeModel = new ChallengeForm();
        $challengeModel->realm = 'login-by-mobile';
        $challengeModel->type = $model->challenge;
        $challengeModel->key = $model->mobile;
        $challengeModel->rememberMe = $model->rememberMe;

        return AuthHelper::redirectToChallenge($challengeModel);
      }

      if (is_array($result)) {
        list ($resultStatus, $resultData) = $result;

        // if (isset($resultData['challenge'])) {
        //   $challenge = $resultData['challenge'];

        //   $challengeModel = new ChallengeForm();
        //   $challengeModel->realm = 'login-by-mobile';
        //   $challengeModel->type = $challenge;
        //   $challengeModel->key = $model->mobile;
        //   $challengeModel->rememberMe = $model->rememberMe;

        //   return AuthHelper::redirectToChallenge($challengeModel);
        // }

        if (isset($resultData['message'])) {
          $messageText = $resultData['message'];
          unset($resultData['message']);
          $messageText = Yii::t('aaa', $messageText, $resultData);
        }
      }
    }

    $done = false;
    if ($formPosted)
      $done = $model->process();

    if (Yii::$app->request->isAjax) {
      if ($done) {




        // challenge







        return $this->renderJson([
          'message' => Yii::t('app', 'Success'),
          // 'id' => $id,
          // 'redirect' => $this->doneLink ? call_user_func($this->doneLink, $model) : null,
          'modalDoneFragment' => 'login',
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

      return $this->renderAjaxModal('_form_mobile', [
        'model' => $model,
      ]);
    }

    if ($done)
      return $this->redirect(['index']);

    return $this->render('mobileChange', [
      'model' => $model,
    ]);
  }

  public function actionMobileApprove()
  {
		if (Yii::$app->user->isGuest)
			return $this->goHome();

		$model = $this->findUserModel();

    if ($model->isSoftDeleted())
      throw new BadRequestHttpException('این آیتم حذف شده است و قابل ویرایش نمی‌باشد.');
  }

  public function actionPasswordSet()
  {
		if (Yii::$app->user->isGuest)
			return $this->goHome();

		$model = $this->findUserModel();

    if ($model->isSoftDeleted())
      throw new BadRequestHttpException('این آیتم حذف شده است و قابل ویرایش نمی‌باشد.');
  }

  public function actionPasswordChange()
  {
		if (Yii::$app->user->isGuest)
			return $this->goHome();

		$model = $this->findUserModel();

    if ($model->isSoftDeleted())
      throw new BadRequestHttpException('این آیتم حذف شده است و قابل ویرایش نمی‌باشد.');
  }

}
