<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace shopack\aaa\frontend\common\models;

use Yii;
use yii\base\Model;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;
use shopack\base\common\helpers\HttpHelper;

class ImageChangeForm extends Model
{
  public $postback;
  public $image;

  public function rules()
  {
    return [
      ['postback', 'required'],
      ['image', 'required'],
    ];
  }

  public function attributeLabels()
	{
		return [
			'postback' => Yii::t('aaa', 'postback'),
			'image' => Yii::t('aaa', 'Image'),
		];
	}

  public function process()
  {
    if (Yii::$app->user->isGuest)
      throw new UnauthorizedHttpException("This process is not for guest.");

    // if ($this->validate() == false)
    //   throw new UnauthorizedHttpException(implode("\n", $this->getFirstErrors()));

		if (empty($_FILES))
			throw new NotFoundHttpException('nothing to do');

    $files = [];

		foreach ($_FILES as $imageSetKey => $imageSet) {
			if (is_array($imageSet['name'])) {
				foreach ($imageSet['name'] as $fieldName => $name) {
					$full_path = $imageSet['full_path'][$fieldName];
					$type      = $imageSet['type'][$fieldName];
					$tmp_name  = $imageSet['tmp_name'][$fieldName];
					$error     = $imageSet['error'][$fieldName];
					$size      = $imageSet['size'][$fieldName];

					$files[$fieldName] = [
						'tempFileName' => $tmp_name,
						'fileName' => $name,
					];
				}
			} else {
				$name      = $imageSet['name'];
				$full_path = $imageSet['full_path'];
				$type      = $imageSet['type'];
				$tmp_name  = $imageSet['tmp_name'];
				$error     = $imageSet['error'];
				$size      = $imageSet['size'];

				$files[$imageSetKey] = [
					'tempFileName' => $tmp_name,
					'fileName' => $name,
				];
			}
		}

    //--
    list ($resultStatus, $resultData) = HttpHelper::callApi('aaa/user/update-image',
      HttpHelper::METHOD_POST,
      [
        'id' => Yii::$app->user->identity->usrID,
      ],
      [],
      $files,
    );

    if ($resultStatus < 200 || $resultStatus >= 300)
      throw new \Exception(Yii::t('aaa', $resultData['message'], $resultData));

    return true; //[$resultStatus, $resultData['result']];
  }

}
