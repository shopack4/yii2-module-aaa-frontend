<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use kartik\grid\GridView;
use shopack\base\frontend\helpers\Html;
use shopack\base\common\helpers\StringHelper;
use shopack\aaa\common\enums\enuGatewayStatus;
use shopack\aaa\frontend\common\models\GatewayModel;

$this->title = Yii::t('aaa', 'Shopping Card');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="shopping-card-index w-100">
  <div class='card border-default'>
		<div class='card-header bg-default'>
			<div class="float-end"><?= Html::createButton('پرداخت', ['checkout'], ['modal' => false]) ?></div>
      <div class='card-title'><?= Html::encode($this->title) ?></div>
			<div class="clearfix"></div>
		</div>

    <div class='card-body'>
      <?php
        if (empty($voucherModel)) {
          echo 'سبد خرید خالی است.';
        } else {
          echo Html::div(
              Html::div('شرح', ['class' => 'col'])
            . Html::div('قیمت واحد', ['class' => 'col-1'])
            . Html::div('تعداد / مقدار', ['class' => 'col-1'])
            . Html::div('قیمت کل', ['class' => 'col-1']),
            [
              'class' => 'row',
            ]
          );

          $items = $voucherModel[0]->vchItems;
          foreach ($items as $item) {
            $key       = $item['key'];
            $service   = $item['service'];
            $slbkey    = $item['slbkey'];
            $slbid     = $item['slbid'];
            $desc      = $item['desc'];
            $unitprice = $item['unitprice'];
            $qty       = $item['qty'];

            echo Html::div(
                Html::div($desc, ['class' => 'col'])
              . Html::div($unitprice, ['class' => 'col-1'])
              . Html::div($qty, ['class' => 'col-1'])
              . Html::div($unitprice * $qty, ['class' => 'col-1']),
              [
                'class' => 'row',
              ]
            );
          }
        }
      ?>
    </div>
  </div>
</div>
