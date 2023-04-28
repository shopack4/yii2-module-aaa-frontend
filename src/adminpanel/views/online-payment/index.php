<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use kartik\grid\GridView;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\common\enums\enuOnlinePaymentStatus;
use shopack\aaa\frontend\common\models\OnlinePaymentModel;

$this->title = Yii::t('aaa', 'Online Payments');
$this->params['breadcrumbs'][] = Yii::t('aaa', 'System');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="online-payment-index w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <?php
      echo GridView::widget([
        'id' => StringHelper::generateRandomId(),
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
          [
            'class' => 'kartik\grid\SerialColumn',
          ],
          'onpID',
          [
            'attribute' => 'owner',
            'value' => function($model) {
              return $model->voucher->owner->displayName();
            },
          ],
          'onpAmount',
          [
            'attribute' => 'onpGatewayID',
            'value' => function($model) {
              return $model->gateway->gtwName;
            },
          ],
          [
            'class' => \shopack\base\frontend\widgets\grid\EnumDataColumn::class,
            'enumClass' => enuOnlinePaymentStatus::class,
            'attribute' => 'onpStatus',
          ],
          [
            'attribute' => 'rowDate',
            'noWrap' => true,
            'format' => 'raw',
            'label' => 'ایجاد / ویرایش',
            'value' => function($model) {
              return Html::formatRowDates(
                $model->onpCreatedAt,
                $model->createdByUser,
                $model->onpUpdatedAt,
                $model->updatedByUser,
                $model->onpRemovedAt,
                $model->removedByUser,
              );
            },
          ],
          [
            'class' => \shopack\base\frontend\widgets\ActionColumn::class,
            // 'header' => OnlinePaymentModel::canCreate() ? Html::createButton() : Yii::t('app', 'Actions'),
            'template' => '{delete}{undelete}',
            'visibleButtons' => [
              'delete' => function ($model, $key, $index) {
                return $model->canDelete();
              },
              'undelete' => function ($model, $key, $index) {
                return $model->canUndelete();
              },
            ],
          ]
        ],
        'export' => false,
      ]);
      ?>
    </div>
  </div>
</div>
