<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\common\helpers\ArrayHelper;
use shopack\base\frontend\widgets\DetailView;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\models\GeoTownModel;

$this->title = Yii::t('aaa', 'Town') . ': ' . $model->twnID . ' - ' . $model->twnName;
$this->params['breadcrumbs'][] = Yii::t('aaa', 'System');
$this->params['breadcrumbs'][] = ['label' => Yii::t('aaa', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="geo-town-view w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
				<?= GeoTownModel::canCreate() ? Html::createButton() : '' ?>
        <?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->twnID]) : '' ?>
        <?= $model->canDelete()   ? Html::deleteButton(null,   ['id' => $model->twnID]) : '' ?>
        <?= $model->canUndelete() ? Html::undeleteButton(null, ['id' => $model->twnID]) : '' ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>
    <div class='card-body'>
      <?php
        echo DetailView::widget([
          'model' => $model,
          'enableEditMode' => false,
          'cols' => 2,
          // 'isVertical' => false,
          'attributes' => [
            'twnID',
            // [
            //   'attribute' => 'twnStatus',
            //   'value' => enuGeoTownStatus::getLabel($model->twnStatus),
            // ],
            'twnName',
            [
              'attribute' => 'twnCityID',
              'value' => $model->cityOrVillage->ctvName,
            ],
            'twnCreatedAt:jalaliWithTime',
            [
              'attribute' => 'twnCreatedBy_User',
              'value' => $model->createdByUser->actorName() ?? '-',
            ],
            'twnUpdatedAt:jalaliWithTime',
            [
              'attribute' => 'twnUpdatedBy_User',
              'value' => $model->updatedByUser->actorName() ?? '-',
            ],
            'twnRemovedAt:jalaliWithTime',
            [
              'attribute' => 'twnRemovedBy_User',
              'value' => $model->removedByUser->actorName() ?? '-',
            ],
          ],
        ]);
      ?>
    </div>
  </div>
</div>
