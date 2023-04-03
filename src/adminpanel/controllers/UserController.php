<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\adminpanel\controllers;

use Yii;
use shopack\base\frontend\rest\RestClientDataProvider;
use shopack\aaa\frontend\common\auth\BaseCrudController;
use shopack\aaa\frontend\common\models\UserModel;
use shopack\aaa\frontend\common\models\UserSearchModel;

class UserController extends BaseCrudController
{
	public $modelClass = UserModel::class;
	public $searchModelClass = UserSearchModel::class;

	// public function actionProfile()
	// {
	// 	if (Yii::$app->user->isGuest)
	// 		return $this->goHome();

  //   return $this->render('profile/profile', [
  //     'model' => $this->findModel(Yii::$app->user->identity->usrID),
  //   ]);
	// }

 	/**
	 * used for dropdowns (allow search and lazy loading)
	 * #fixedPaging using ActiveDataProvider
	 */
	public function actionSelect2List($q=null, $id=null, $page=0, $perPage=20)
	{
		$out['total_count'] = 0;
		$out['items'] = [['id' => '', 'firstname' => '', 'lastname' => '', 'email' => '']];

    if (!empty($q)) {
			$q = strtolower(trim($q));
			$query = UserModel::find()
				->orWhere("usrID = {$q}")
				->orWhere("LOWER(usrLastName) LIKE '%{$q}%'")
				->orWhere("LOWER(usrFirstName) LIKE '%{$q}%'")
				->orWhere("LOWER(usrEmail) LIKE '%{$q}%'")
			;

			$dataProvider = new RestClientDataProvider([
				'query' => $query,
				'sort' => [
					'attributes' => [
						'usrFirstName',
						'usrLastName',
						'usrEmail',
					],
					'defaultOrder' => [
						'usrFirstName' => SORT_ASC,
						'usrLastName' => SORT_ASC,
						'usrEmail' => SORT_ASC,
					],
				],
				'pagination' => [
					'pageSize' => $perPage,
				],
			]);

      $cnt = $dataProvider->getTotalCount();
			$models = array_values($dataProvider->getModels());
			$arr = [];

      if (!empty($models)) {
				foreach ($models as $model) {
					$arr[] = [
						'id'        => $model->usrID,
						'firstname' => $model->usrFirstName,
						'lastname'  => $model->usrLastName,
						'email'     => $model->usrEmail,
					];
				}
			}

			$out['total_count'] = $cnt;
			$out['items'] = $arr;

		} elseif ($id > 0) {
			$user = UserModel::findOne($id);
			$out['total_count'] = 1;
			$out['items'] = [
				[
					'id'        => $id,
					'firstname' => $user->usrFirstName,
					'lastname'  => $user->usrLastName,
					'email'     => $user['usrEmail'],
				],
			];
		}

		return $this->renderJson($out);
	}

}
