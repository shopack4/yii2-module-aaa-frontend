<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\DetailView;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\frontend\common\models\UserModel;
use shopack\aaa\common\enums\enuUserStatus;
use shopack\aaa\common\enums\enuGender;

$this->title = Yii::t('aaa', 'User') . ': ' . $model->usrID;
$this->params['breadcrumbs'][] = Yii::t('aaa', 'System');
$this->params['breadcrumbs'][] = ['label' => Yii::t('aaa', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
        <?= UserModel::canCreate() ? Html::createButton() : '' ?>
        <?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->usrID]) : '' ?>
        <?= $model->canDelete()   ? Html::deleteButton(null,   ['id' => $model->usrID]) : '' ?>
        <?= $model->canUndelete() ? Html::undeleteButton(null, ['id' => $model->usrID]) : '' ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <div class='row'>
        <div class='col-8'>
          <?php
            echo DetailView::widget([
              'model' => $model,
              'enableEditMode' => false,
              'cols' => 2,
              'isVertical' => false,
              'attributes' => [
                'usrID',
                [
                  'attribute' => 'usrStatus',
                  'value' => enuUserStatus::getLabel($model->usrStatus),
                ],
                [
                  'attribute' => 'usrEmail',
                  'valueColOptions' => ['class' => ['dir-ltr', 'text-start']],
                ],
                'usrEmailApprovedAt:jalaliWithTime',
                [
                  'attribute' => 'usrMobile',
                  'valueColOptions' => ['class' => ['dir-ltr', 'text-start']],
                ],
                'usrMobileApprovedAt:jalaliWithTime',
                'usrSSID',

                [
                  'attribute' => 'usrGender',
                  'value' => enuGender::getLabel($model->usrGender),
                ],
                'usrFirstName',
                'usrFirstName_en',
                'usrLastName',
                'usrLastName_en',
                'usrRoleID',
                [
                  'attribute' => 'usrPrivs',
                  'visible' => $model->canViewColumn('usrPrivs'),
                  'value' => json_encode($model->usrPrivs),
                ],
                [
                  'attribute' => 'hasPassword',
                  'value' => $model->hasPassword ? Yii::t('app', 'Has') : Yii::t('app', 'Has not'),
                ],
                'usrPasswordCreatedAt:jalaliWithTime',
                'usrBirthDate:jalali',
                [
                  'attribute' => 'usrCountryID',
                  'value' => $model->country->cntrName ?? null,
                ],
                [
                  'attribute' => 'usrStateID',
                  'value' => $model->state->sttName ?? null,
                ],
                [
                  'attribute' => 'usrCityOrVillageID',
                  'value' => $model->cityOrVillage->ctvName ?? null,
                ],
                [
                  'attribute' => 'usrTownID',
                  'value' => $model->town->twnName ?? null,
                ],
                'usrHomeAddress',
                'usrZipCode',
              ],
            ]);
          ?>
        </div>
        <div class='col-4'>
          <?php
            echo DetailView::widget([
              'model' => $model,
              'enableEditMode' => false,
              'attributes' => [
                'usrCreatedAt:jalaliWithTime',
                [
                  'attribute' => 'usrCreatedBy_User',
                  'value' => $model->createdByUser->actorName() ?? '-',
                ],
                'usrUpdatedAt:jalaliWithTime',
                [
                  'attribute' => 'usrUpdatedBy_User',
                  'value' => $model->updatedByUser->actorName() ?? '-',
                ],
                'usrRemovedAt:jalaliWithTime',
                [
                  'attribute' => 'usrRemovedBy_User',
                  'value' => $model->removedByUser->actorName() ?? '-',
                ],
              ],
            ]);
          ?>

          <p>&nbsp;</p>

          <div class='card border-default'>
            <div class='card-header bg-default'>
              <div class="float-end">
              </div>
              <div class='card-title'><?= Yii::t('aaa', 'Image') ?></div>
              <div class="clearfix"></div>
            </div>
            <div class='card-body text-center'>
              usrImage
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
