<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\StringHelper;
use shopack\base\frontend\helpers\Html;
use shopack\base\frontend\widgets\grid\GridView;
use shopack\aaa\common\enums\enuWalletTransactionStatus;
use shopack\aaa\common\enums\enuVoucherType;
?>

<?php
	if (isset($statusReport))
		echo $statusReport;

    // (is_array($statusReport) ? Html::icon($statusReport[0], ['plugin' => 'glyph']) . ' ' . $statusReport[1] : $statusReport);

  echo GridView::widget([
    'id' => StringHelper::generateRandomId(),
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,

    'columns' => [
      [
        'class' => 'kartik\grid\SerialColumn',
      ],
      'wtrID',
      [
        'attribute' => 'wtrWalletID',
        'value' => function($model) {
          return Yii::t('app', $model->wallet->walName);
        },
      ],
      [
        'attribute' => 'credit',
        'label' => 'واریز',
        'contentOptions' => [
          'class' => ['text-nowrap', 'tabular-nums'],
        ],
        'value' => function($model) {
          if ($model->wtrAmount > 0)
            return Yii::$app->formatter->asToman($model->wtrAmount);
          return '';
        },
      ],
      [
        'attribute' => 'debit',
        'label' => 'برداشت',
        'contentOptions' => [
          'class' => ['text-nowrap', 'tabular-nums'],
        ],
        'value' => function($model) {
          if ($model->wtrAmount < 0)
            return Yii::$app->formatter->asToman(abs($model->wtrAmount));
          return '';
        },
      ],
      [
        'attribute' => 'wtrVoucherID',
        'value' => function($model) {
          if (empty($model->wtrVoucherID))
            return null;
          return $model->wtrVoucherID . ' - ' . enuVoucherType::getLabel($model->voucher->vchType);
        },
      ],
      'wtrOnlinePaymentID',
      // 'wtrOfflinePaymentID',
      // [
      //   'class' => \shopack\base\frontend\widgets\grid\EnumDataColumn::class,
      //   'enumClass' => enuWalletTransactionStatus::class,
      //   'attribute' => 'wtrStatus',
      // ],
      [
        'attribute' => 'wtrCreatedAt',
        'format' => 'jalaliWithTime',
        'contentOptions' => [
          'class' => ['text-nowrap', 'small'],
        ],
      ],
      [
        'class' => \shopack\base\frontend\widgets\ActionColumn::class,
        'header' => Yii::t('app', 'Actions'),
        'template' => false,
      ],
    ],
  ]);

?>
