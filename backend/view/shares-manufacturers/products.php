<?php
$this->registerJsFile('/plugins/ckeditor/ckeditor.js');
$this->registerJsFile('/plugins/ckeditor/style.js');

$this->registerCssFile('/plugins/datepicker/datepicker3.css');
$this->registerJsFile('/plugins/datepicker/bootstrap-datepicker.js');
$this->registerJsFile('/plugins/datepicker/locales/bootstrap-datepicker.ru.js');

use backend\components\UI; 
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\components\Helpers;


$shares_manufacturers_products = [];
    foreach($shares_manufacturers as $shr_product){
      $shares_manufacturers_products[] = $shr_product->shr_product;
    }

?>

<?php foreach ($categories as $category) {
?>
<div>
    <div class="box box-solid box-primary section-content">
        <div class="box-header">
            <h4 class="box-title"><?php print $category->cat_title?></h4>
        </div>
        <div class="box-body">
            <div class="box-body" data-cat="2">
                <?php foreach ($products as $product) {?>
                <?php 
                    $product_categories = [];
                    foreach($product->categories as $prod_cat){
                      $product_categories[] = $prod_cat->cat_id;
                    }
                  ?>
                <?php if( ArrayHelper::isIn($category->cat_id, $product_categories) ){?>
                    
                <div class="col-md-4 col-lg-3">
        <div class="box box-primary box-product">
          <div class="box-header with-border text-right">
              
                <?php $is_product_in_stock = ArrayHelper::isIn($product->prod_id, $shares_manufacturers_products);
                if ($is_product_in_stock) {?>
                    <div class="context-menu pull-left">
                        <span class="fa fa-star-o"></span>
                    </div>
                <?php }?>
              
              
            <?php print UI::contextMenu([
                  [
                    'icon' => 'fa-pencil',
                    'text' => 'Редактировать',
                    'href' => "/akcii-fabrik/edit?prod_id={$product->prod_id}&mnf={$manufacture->mnf_id}",
                  ],[
                    'icon' => 'fa-remove',
                    'text' => 'Удалить товар из акций',
                    'href' => "/akcii-fabrik/delete?prod_id=$product->prod_id&mnf=$manufacture->mnf_id",
                    'style'=> "color:#F44336",
                    'onclick' => 'return confirm(\'Точно удалить?\')',
                  ]
                ], ['class' => 'pull-right']) ?>
                <?php print explode('<hr />', $stock->akc_content)[0] ?>
          </div>
          <div class="box-body row">
            <div class="col-xs-12">
              <a href="/products<?php echo ('moderation' == $this->context->action->id ? '/moderation' : ''), '/', $product->prod_id, '?manufacture=', $manufacture->mnf_id ?>">
                <?php if($product->prod_image): ?>
                  <img src="<?php print Yii::getAlias("@fileserver/products/thumb/{$product->prod_image}") ?>" />
                <?php else: ?>
                  <div class="text-center">
                    <span class="fa fa-file-image-o" style="font-size: 100px; padding: 24px 0"></span>
                  </div>
                <?php endif ?>
              </a>
            </div>
            <div class="col-xs-12">
              <h4 class="box-title">
                <?php echo Html::encode($product->prod_title) ?>
              </h4>
              <div style="white-space: normal;">
                <?php print $categories_list ?>
              </div>
              <div class="fab-info">
                <?php echo $manufacture->activity, ' «', $manufacture->mnf_title, '»<br> г. ', $town->twn_title ?>
              </div>
            </div>
          </div>
          <div class="box-footer" style="color: #aaa; font-size: 12px;">
            <span class="fa fa-clock-o"></span> <?php print Helpers::dateSpeller($product->prod_added, true) ?>
          </div>
        </div>
      </div>
                    <?php }?>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<?php }?>

<style>
    .box.box-primary.box-product .pull-left .fa-star-o {
        font-size:20px;
    }
</style>