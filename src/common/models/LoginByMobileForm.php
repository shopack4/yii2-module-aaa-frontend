<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\common\models;

use Yii;
use yii\base\Model;
use shopack\base\common\helpers\HttpHelper;

class LoginByMobileForm extends Model
{
  public $mobile;
  // public $code;
  public $rememberMe = true;
  public $challenge;

  public function rules()
  {
    return [
      ['mobile', 'required'],
      // ['code', 'safe'],
      ['rememberMe', 'boolean'],
    ];
  }

	public function attributeLabels()
	{
		return [
			'mobile' => Yii::t('aaa', 'Mobile'),
			// 'code' => Yii::t('aaa', 'code'),
			'rememberMe' => Yii::t('aaa', 'Remember Me'),
		];
	}

  public function process()
  {
    if ($this->validate() == false)
      return false;

    list ($resultStatus, $resultData) = HttpHelper::callApi('aaa/auth/login-by-mobile',
      HttpHelper::METHOD_POST,
      [],
      [
        'mobile' => $this->mobile,
        // 'code' => $this->code,
        'rememberMe' => $this->rememberMe,
      ]
    );

    if (isset($resultData['token'])) {
      $token = $resultData['token'];
      $user = UserModel::findIdentityByAccessToken($token);

      return Yii::$app->user->login($user, 3600*24*30); //$this->rememberMe ? 3600*24*30 : 0);
    }

    if (isset($resultData['challenge'])) {
      $this->challenge = $resultData['challenge'];
      return 'challenge';
    }

    return [$resultStatus, $resultData];
  }

}
