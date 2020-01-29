<?php
  use \frontend\components\Route;
  use \frontend\components\SEO;
  use yii\helpers\Html;
	
  $this->title = 'Акции мебельных фабрик';
?>

<section class="page-shares">
  <div class="shares">
		<div class="wrapper">
			<h1 class="title-section htabs">
				<span class="htab act" id="shares-factories">
					<?php print $this->title ?>
				</span>
			</h1>
      
			<div class="all" id="loader-container">
				<?php if($models):
					foreach ($models as $model): ?>
					<div class="row share">

						<div class="col-xs-12 col-sm-5 col-md-4 col-lg-3 text-left share-right-box share-left-box">
							<div class="share-logo">
								<img src="<?php print Yii::getAlias("@fileserver/shares-manufacturers/{$model->shr_image}") ?>" alt="<?php print $model->products->prod_image?>" />
							</div>

							<?php if($model->shr_discount): ?>
							<div class="discount-box">
								<span class="discount">Скидка </span><span class="discount-value"><?php print $model->shr_discount ?>&nbsp;%</span>
							</div>
							<?php endif ?>
						</div><!--share-left-box-->

						<div class="col-xs-12 col-sm-7 col-md-8 col-lg-9 text-left share-right-box">
							<div class="share-info">
								<div class="share-content">
									<?php print $model->shr_content?>
								</div>

								<div class="share-date">
									<b style="color:red;">Акция проходит c&nbsp;&nbsp;
										<?php print \frontend\components\Helpers::dateSpeller($model->shr_start_date) ?>
										&nbsp;&nbsp;по&nbsp;&nbsp;
										<?php print \frontend\components\Helpers::dateSpeller($model->shr_end_date) ?>
									</b>
								</div>
							</div><!--share-info-->

							<div class="row factory-box">
								<div class="col-xs-12 col-sm-3 factory-logo">
									<img src="<?php print Yii::getAlias("@fileserver/logos/{$model->manufacturers->mnf_logo}") ?>" alt="<?php print $model->products->prod_image?>" />
								</div>

								<div class="col-xs-12 col-sm-8 factory-info">  
									<div class="factory-description">
										<span class="factory-title"><?php print "Мебельная фабрика «{$model->manufacturers->mnf_title}»";?></span>

										<div class="content-line factory-show-products">
											<a data-title="<?php print $model->manufacturers->mnf_title ?>" 
												href="<?php print $model->manufacturers->permalink()?>">
												<span class="shortened">Показать все модели производителя</span>
											</a>
										</div>

										<div class="content-line opage_price-preview">
											<a class="opage_price" 
												data-id="<?php print $model->manufacturers->mnf_id ?>" 
												data-title="<?php print $model->manufacturers->mnf_title ?>" 
												href="<?php print $model->manufacturers->permalink()?>/price">
												<span class="price-get">Отправить фабрике запрос на прайс-лист</span>
											</a>
										</div>

										<?php
										$phones =  !empty($model->manufacturers->extfields->fld_phone_opt)
											? $model->manufacturers->extfields->fld_phone_opt
											: ( $model->manufacturers->extfields->fld_phone_roznica ? $model->manufacturers->extfields->fld_phone_roznica : null );

										if( $phones ):
											$ph_counter = 0;
										?>
											<div class="content-line phones-preview">
												<?php
													foreach(explode(';', $phones) as $ph):
														if(++$ph_counter > 3) break;
												?>
													<span class="phone-container">
														<small class="color-blue icon icon-phone">☎</small>
														<?php print preg_replace("#([0-9\+]+?)([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})\s?$#ui", "$1 ($2) $3-$4-$5", $ph) ?>
													</span>
												<?php endforeach ?>
											</div>
										<?php endif ?>

										<div class="content-line">
											<a href="/redirect?to=<?php print urlencode((preg_match("#^http#ui", $model->manufacturers->mnf_site) ? '' : 'http://') . $model->manufacturers->mnf_site) ?>" target="_blank">
												<small class="icon icon-site color-blue"></small>
													www.<?php print preg_replace(["#http(s)?\://#ui","#^www\.#ui"], ["",""], $model->manufacturers->mnf_site) ?>
											</a>
										</div>
									</div>
								</div><!--factory-info-->
							</div><!--factory-box-->
						</div><!--share-right-box-->
					</div><!--share-->
				<?php endforeach;
				else:
					print 'Акций пока нет';
				endif; ?>

			</div><!--loader-container-->
			<?php if( !empty($pages) && ceil($pages->totalCount/$pages->defaultPageSize) > 1 ): ?>
				<div class="row paginator" id="pagination-organization">
					<div class="col-md-6">
						Страница <?php print Yii::$app->request->get('page') ? Yii::$app->request->get('page') : 1 ?> из <?php print ceil($pages->totalCount/$pages->defaultPageSize) ?>
					</div>
					<div class="col-md-6">
						<?php print Route::pagination($pages->totalCount, $pages->defaultPageSize) ?>
					</div>
				</div>
			
				<script>
					var ael = document.querySelectorAll('#pagination-organization a');
					for(var j in ael) ael[j].href += '#search-organizations';
				</script>
			<?php endif ?> 
		</div>
  </div>
</section>