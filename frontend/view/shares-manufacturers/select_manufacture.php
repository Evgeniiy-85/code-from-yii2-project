<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Products;
use common\models\Sections;
use backend\components\UI;

$this->registerCssFile('/plugins/tapmodo-Jcrop/css/jquery.Jcrop.min.css');
$this->registerJsFile('/plugins/tapmodo-Jcrop/js/jquery.Jcrop.min.js');
$this->registerJsFile('/js/products.js');

$this->title = 'Новый товар';

$this->params['breadcrumbs'][] = [
  'label' => 'Акции фабрик',
  'url' => ['/'.Yii::$app->controller->id]
];

$form = ActiveForm::begin([
  'id' => 'form-shares',
  'action' => "/{$this->context->id}",
  'options' => [
    'enctype' => 'multipart/form-data'
  ]
]);
?>


<div class="box box-primary box-solid">
  <div class="box-header with-border">
    <h3 class="box-title">Выберите организацию, для которой добавляется товар</h3>
  </div>
  <div class="box-body row">
    <?php print $form->field($model, "shr_manufacture", [
      'options' => ['class' => 'col-xs-12 form-group']
    ])
    ->dropDownList([], [
      'class' => 'form-control manufactury-picker',
      'onchange' => 'window.location = "/'. $this->context->id .'/add?manufacture="+ $(this).val()[0];',
      'data-mnf_section' => implode(',', array_merge(Yii::$app->params['sections_opt'], Yii::$app->params['sections_furnitura_opt']) ),
    ]) ?>
  </div>

  <div class="box-footer">
    <a class="btn btn-default" href="<?php print "/{$this->context->id}" ?>">Отмена</a>
  </div>
</div>