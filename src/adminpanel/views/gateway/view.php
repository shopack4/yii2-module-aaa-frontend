<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\PopoverX;
use shopack\base\common\helpers\Url;
use shopack\base\common\helpers\HttpHelper;
use shopack\base\frontend\widgets\DetailView;
use shopack\base\frontend\helpers\Html;
use shopack\aaa\common\enums\enuGatewayStatus;
use shopack\aaa\frontend\common\models\GatewayModel;

$this->title = Yii::t('aaa', 'Gateway') . ': ' . $model->gtwID . ' - ' . $model->gtwName;
$this->params['breadcrumbs'][] = Yii::t('aaa', 'System');
$this->params['breadcrumbs'][] = ['label' => Yii::t('aaa', 'Gateways'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="gateway-view w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
				<?= GatewayModel::canCreate() ? Html::createButton() : '' ?>
        <?= $model->canUpdate()   ? Html::updateButton(null,   ['id' => $model->gtwID]) : '' ?>
        <?= $model->canDelete()   ? Html::deleteButton(null,   ['id' => $model->gtwID]) : '' ?>
        <?= $model->canUndelete() ? Html::undeleteButton(null, ['id' => $model->gtwID]) : '' ?>
        <?php
          PopoverX::begin([
            // 'header' => 'Hello world',
            'closeButton' => false,
            'toggleButton' => [
              'label' => Yii::t('aaa', 'Logs'),
              'class' => 'btn btn-default',
            ],
            'placement' => PopoverX::ALIGN_AUTO_BOTTOM,
          ]);

          echo DetailView::widget([
            'model' => $model,
            'enableEditMode' => false,
            'attributes' => [
              'gtwCreatedAt:jalaliWithTime',
              [
                'attribute' => 'gtwCreatedBy_User',
                'value' => $model->createdByUser->actorName ?? '-',
              ],
              'gtwUpdatedAt:jalaliWithTime',
              [
                'attribute' => 'gtwUpdatedBy_User',
                'value' => $model->updatedByUser->actorName ?? '-',
              ],
              'gtwRemovedAt:jalaliWithTime',
              [
                'attribute' => 'gtwRemovedBy_User',
                'value' => $model->removedByUser->actorName ?? '-',
              ],
            ],
          ]);

          PopoverX::end();
        ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>
    <div class='card-body'>
      <?php
        $attributes = [
          'gtwID',
          [
            'attribute' => 'gtwStatus',
            'value' => enuGatewayStatus::getLabel($model->gtwStatus),
          ],
          'gtwName',
          [
            'attribute' => 'gtwKey',
            'valueColOptions' => ['class' => ['latin-text']],
          ],
          [
            'attribute' => 'gtwPluginType',
            'value' => Yii::t('aaa', $model->gtwPluginType),
          ],
          'gtwPluginName',
          // 'gtwRemovedAt',
          // 'gtwRemovedBy',
        ];

        $fnShowKindSchema = function($kind, $prop) use($model, &$attributes) {
          if ($model->canViewColumn($prop)) {
            $result = HttpHelper::callApi("aaa/gateway/plugin-{$kind}-schema", HttpHelper::METHOD_GET, [
              'key' => $model->gtwPluginName,
            ]);
            if ($result && $result[0] == 200) {
              $list = $result[1];

              if (isset($list)) {
                $tableRows = [];

                $tableRows[] = Html::tag('tr',
                    Html::tag('th', Yii::t('aaa', 'Name'))
                  . Html::tag('th', Yii::t('aaa', 'Value'))
                );

                foreach ($list as $item) {
                  $paramValue = ($item['type'] == 'password'
                    ? '********'
                    : ($model->$prop[$item['id']] ?? ''));

                  if (isset($item['data']) && is_array($item['data'])) {
                    $paramValue = Yii::t('aaa', $item['data'][$paramValue] ?? $paramValue);
                  }

                  $tableRows[] = Html::tag('tr',
                      Html::tag('td', Yii::t('aaa', $item['label']), ['class' => ['headcell']])
                    . Html::tag('td', $paramValue)
                  );
                }

                array_push($attributes, [
                  'attribute' => $kind,
                  'label' => $model->getAttributeLabel($prop),
                  'format' => 'raw',
                  // 'valueColOptions' => ['class' => ['dir-ltr', 'latin-text']],
                  'value' => Html::tag('table', implode('', $tableRows), [
                    'class' => ['table', 'table-bordered', 'table-striped'],
                  ]),
                ]);
              }
            }
          }
        };

        //params
        $fnShowKindSchema('params', 'gtwPluginParameters');

        //restrictions
        $fnShowKindSchema('restrictions', 'gtwRestrictions');

        //usages
        $fnShowKindSchema('usages', 'gtwUsages');

        //payment
        if ($model->gtwPluginType == 'payment') {
          array_push($attributes, [
            'attribute' => 'callbackurl',
            'label' => Yii::t('aaa', 'Callback Url'),
            'format' => 'raw',
            'valueColOptions' => ['class' => ['dir-ltr', 'latin-text']],
            'value' => Url::to(['/aaa/payment/callback'], true) . '/{ONP-ID}',
          ]);
        }

        //webhooks
        $result = HttpHelper::callApi('aaa/gateway/plugin-webhooks-schema', HttpHelper::METHOD_GET, [
          'key' => $model->gtwPluginName,
        ]);
        if ($result && $result[0] == 200) {
          $list = $result[1];

          if (isset($list)) {
            $tableRows = [];

            $tableRows[] = Html::tag('tr',
                Html::tag('th', Yii::t('aaa', 'Name'))
              . Html::tag('th', Yii::t('aaa', 'Address'))
            );

            foreach ($list as $key => $item) {
              $tableRows[] = Html::tag('tr',
                  Html::tag('td', Yii::t('aaa', $item['title']), ['class' => ['headcell']])
                . Html::tag('td', Url::to(['/aaa/gateway/webhook',
                    'gtwkey' => $model->gtwKey,
                    'command' => $key,
                  ], true), ['class' => ['dir-ltr', 'latin-text']])
              );
            }

            array_push($attributes, [
              'attribute' => 'webhooks',
              'label' => Yii::t('aaa', 'Webhooks'),
              'format' => 'raw',
              // 'valueColOptions' => ['class' => ['dir-ltr', 'latin-text']],
              'value' => Html::tag('table', implode('', $tableRows), [
                'class' => ['table', 'table-bordered', 'table-striped'],
              ]),
            ]);

          }
        }

        echo DetailView::widget([
          'model' => $model,
          'enableEditMode' => false,
          'attributes' => $attributes,
        ]);
      ?>
    </div>
  </div>
</div>
