<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\common\models;

use Yii;
use yii\base\Model;
use shopack\base\common\helpers\HttpHelper;

class ApproveCodeForm extends Model
{
  public $input;
  public $code;

  public function rules()
  {
    return [
      ['input', 'required'],
      ['code', 'required'],
    ];
  }

  public function process()
  {
    if ($this->validate() == false)
      return false;

      list ($resultStatus, $resultData) = HttpHelper::callApi('aaa/auth/accept-approval',
      HttpHelper::METHOD_POST,
      [],
      [
        'input' => $this->input,
        'code' => $this->code,
      ]
    );

    if ($resultStatus < 200 || $resultStatus >= 300)
      throw new \Exception(Yii::t('aaa', $resultData['message'], $resultData));

    //check result['token'] -> set cookie

    return true; //[$resultStatus, $resultData['result']];
  }

}
