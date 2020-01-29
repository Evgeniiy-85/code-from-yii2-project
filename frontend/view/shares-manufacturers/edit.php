<?php
$this->registerJsFile('/plugins/ckeditor/ckeditor.js');
$this->registerJsFile('/plugins/ckeditor/style.js');

$this->registerCssFile('/plugins/datepicker/datepicker3.css');
$this->registerJsFile('/plugins/datepicker/bootstrap-datepicker.js');
$this->registerJsFile('/plugins/datepicker/locales/bootstrap-datepicker.ru.js');

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = $model->shr_id ? 'Редактирование акции' : 'Новая акция';

$this->params['breadcrumbs'][] = [
  'label' => "Список организаций для акций",
  'url' => ['/'.Yii::$app->controller->id]
];

$this->params['breadcrumbs'][] = $this->title;
?>

<script> 
  $(function(){
    if( $('.datepicker').size() ){
      $('.datepicker').datepicker({
        language: 'ru',
        format: 'yyyy-mm-dd',
        todayBtn: true,
        autoclose: true
      });
    }
    CKEDITOR.replace( 'text-content', {} );
    CKEDITOR.config.allowedContent = true;
  });
</script>

<div id="shares-edit" class="row" style="max-width: 1300px;">
  <div class="col-md-12">
    <div class="box box-solid">
      <div class="box-header bg-green-gradient">
        <h3 class="box-title">Основная информация</h3>
      </div>
      <div class="box-body row">
        <div class="col-xs-12">
          <?php
            if ($model->success) {
              print $model->alertSuccess();
            }
            if ($model->errors) {
              print $model->alertErrors();
            }
            if ($model->warnings) {
              print $model->alertWarning();
            }
          ?>
        </div>
          
        <?php $form = ActiveForm::begin([
          'id' => 'share-form',
          'action' => '/'.Yii::$app->controller->id.'/'.($model->shr_id ? $model->shr_id : 'add')."?manufacture=$model->shr_manufacture",
        ]); ?>
        
        <?php 
          print $form->field($model, "shr_discount", ['options' => [
            'class' => 'col-md-4 form-group',
          ]])->textInput(['value' => $model->shr_discount]);
        ?>
        
        <?php
          print $form->field($model, "shr_start_date", [
          'template' => '{label}
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              {input} {hint} {error}
            </div>',
          'options' => [
            'class' => 'col-xs-4 form-group'
          ]
          ])->textInput([
            'class' => 'form-control pull-right datepicker',
            'value' => is_numeric($model->shr_start_date) && $model->shr_start_date > 0 ? date('Y-m-d', $model->shr_start_date) : ""
          ]);
          
          print $form->field($model, "shr_end_date", [
            'template' => '{label}
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                {input} {hint} {error}
              </div>',
            'options' => [
              'class' => 'col-xs-4 form-group'
            ]
          ])->textInput([
            'class' => 'form-control pull-right datepicker',
            'value' => is_numeric($model->shr_end_date) && $model->shr_end_date > 0 ? date('Y-m-d', $model->shr_end_date) : ""
          ]);
        ?>
          
        <?php
        print $form->field($model,  'shr_content', [
          'options' => ['class' => 'col-xs-12 form-group']
        ])
        ->textArea([
          'id' => 'text-content',
          'class' => 'wysiwyg-X',
          'rows' => '16',
        ]);
        ?>
        
        <div class="col-xs-12" style="padding: 15px">
          <?php if($model->shr_image): ?>
          <div class="attachment-logo">
            <a target="_blank" class="fnbx dont-replace-href" href="<?php print Yii::getAlias('@fileserver/shares-manufacturers/') .$model->shr_image?>">
              <?php print Html::img('@fileserver/shares-manufacturers/'.$model->shr_image) ?>
            </a>
          </div>
          <?php endif ?>
          <div class="files-filed-block">
            <?php print $form->field($Files, 'image', ['options' => ['class' => '']])->fileInput() ?>
          </div>
        </div>
        
        <?php
          print $form->field($model, "shr_tempimage", [
            'options' => ['class' => '']
          ])->hiddenInput()->label(false);
        ?>
      </div>
      
      <div class="box-footer text-right">
        <a class="btn btn-default" href="/<?php print Yii::$app->controller->id . "?manufacture={$model->shr_manufacture}"?>">Отмена</a>
        <button class="btn btn-success">Сохранить</button>
      </div>
    </div>
    <?php ActiveForm::end() ?>
  </div>
</div>
