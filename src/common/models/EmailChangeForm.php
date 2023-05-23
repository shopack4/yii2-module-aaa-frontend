<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\common\models;

use Yii;
use yii\base\Model;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use shopack\base\common\helpers\HttpHelper;

class EmailChangeForm extends Model
{
  public $email;

  public function rules()
  {
    return [
      ['email', 'required'],
    ];
  }

  public function attributeLabels()
	{
		return [
			'email' => Yii::t('aaa', 'Email'),
		];
	}

  public function process()
  {
    if (Yii::$app->user->isGuest)
      throw new UnauthorizedHttpException("This process is not for guest.");

    if ($this->validate() == false)
      throw new UnauthorizedHttpException(implode("\n", $this->getFirstErrors()));

    // if (AuthHelper::isEmail($this->email) == false)
    //   throw new UnprocessableEntityHttpException("Invalid email");

    list ($resultStatus, $resultData) = HttpHelper::callApi('aaa/user/email-change',
      HttpHelper::METHOD_POST,
      [],
      [
        'email' => $this->email,
      ]
    );

    if ($resultStatus < 200 || $resultStatus >= 300)
      throw new \yii\web\HttpException($resultStatus, Yii::t('aaa', $resultData['message'], $resultData));

    return true; //[$resultStatus, $resultData['result']];
  }

}
