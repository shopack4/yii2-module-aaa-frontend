<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\common\components;

use Yii;
use yii\web\User as BaseUser;
use shopack\base\frontend\helpers\PrivHelper;

class User extends BaseUser
{
	public $identityClass = \shopack\aaa\frontend\common\models\UserModel::class;
	public $enableAutoLogin = true;
	public $loginUrl = ['aaa/auth/login'];
	public $autoRenewCookie = false;

	public $identityCookie = [
		'name' => '_jwt',
		'httpOnly' => true,
	// 	// 'sameSite' => '', //\yii\web\Cookie::SAME_SITE_STRICT,
	];

	//current sessions jwt token dataset
	public ?\Lcobucci\JWT\Token\Plain $accessToken = null;

	public static function getJwtByCookie()
	{
    $cookieName = Yii::$app->user->identityCookie['name'];
    $authCookie = Yii::$app->request->getCookies()->getValue($cookieName);
    if ($authCookie !== null) {
      $authCookie = json_decode($authCookie, true);
      $authCookie = $authCookie[1];
			return $authCookie;
		}

		return null;
	}

	protected function regenerateCsrfToken()
	{
		$request = Yii::$app->getRequest();
		if ($request->enableCsrfCookie || $this->enableSession) {
			/*-*/ // $request->getCsrfToken(true);
			/*+*/ $request->getCsrfToken(false);
		}
	}

	public function loginByAccessToken($token, $type = null)
	{
		$identity = parent::loginByAccessToken($token, $type);

		if ($identity)
			$this->accessToken = Yii::$app->jwt->parse($token);

		return $identity;
	}

	public function hasPriv($path, $priv='1')
	{
		return PrivHelper::hasPriv($path, $priv);
	}

}
