<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\widgets\grid\GridView;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
// use shopack\aaa\common\enums\enuGeoStateStatus;
use shopack\aaa\frontend\common\models\GeoStateModel;

$this->title = Yii::t('aaa', 'States');
$this->params['breadcrumbs'][] = Yii::t('aaa', 'System');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="geo-state-index w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end">
        <?= GeoStateModel::canCreate() ? Html::createButton(null, [
          'sttCountryID' => $sttCountryID ?? $_GET['sttCountryID'] ?? null
        ]) : '' ?>
			</div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <?php
				echo Yii::$app->controller->renderPartial('_index.php', [
					'searchModel' => $searchModel,
					'dataProvider' => $dataProvider,
					'sttCountryID' => $sttCountryID ?? $_GET['sttCountryID'] ?? null,
				]);
			?>
    </div>
  </div>
</div>
