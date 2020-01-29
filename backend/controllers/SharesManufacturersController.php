<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use \common\models\SharesManufacturers;
use backend\models\SharesManufacturersFilter;
use backend\models\Manufacturers;
use yii\filters\AccessControl;
use backend\models\Files;
use yii\web\UploadedFile;
use yii\data\Pagination;

class SharesManufacturersController extends Controller
{
  public $manufacture;
  
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          [
            'allow' => true,
            'roles' => ['Administrator', 'Director', 'SalesManager', 'Accountant'],
          ],
        ]
      ]
    ];
  }
  public function actions()
  {
      return [
          'error' => [
              'class' => 'yii\web\ErrorAction',
          ],
      ];
  }
  
  public function actionIndex($ID = null) {
    $query = SharesManufacturers::find();
    
    /*begin Фильтрация списка организаций*/
    $filter = new SharesManufacturersFilter;
    $messageModel = new SharesManufacturers;
    
    if($del = Yii::$app->request->get('delete')){
      $messageModel = $this->actionDelete($del);
    }
    
    if (Yii::$app->request->get('reset_filter')) {
      Yii::$app->session->remove('SharesManufacturersFilter');
    } else {
      if (Yii::$app->request->post('SharesManufacturersFilter')) {
        Yii::$app->session->set('SharesManufacturersFilter', Yii::$app->request->post());
        $filter->load(Yii::$app->request->post());
      }
      else {
        $filter->load(Yii::$app->session->get('SharesManufacturersFilter'));
      }
      $filter->add($query);
    }
    /*end Фильтрация списка организаций*/
    
    $pageSize = 5;
    
    $pages = new Pagination([
      'pageSize' => $pageSize,
      'defaultPageSize' => $pageSize,
      'totalCount' => $query->count()
    ]);
    
    $models = $query
    ->offset($pages->offset)
    ->limit($pages->limit)
    ->all();

    return $this->render('index',
      ['models' => $models,
      'pages' => $pages,
      'filter' => $filter,
      'messageModel' => $messageModel
    ]);
  }
  
  public function actionEdit($ID = null) {
    $get = Yii::$app->request->get();
		
		$model = is_numeric($ID) ? SharesManufacturers::findOne($ID) : new SharesManufacturers;
		
		if (isset($get['manufacture'])) {
			$this->manufacture = $get['manufacture'];
		} else {
			return $this->render('select_manufacture', [
        'model' => $model
      ]);
		}
    
    $manufacture = Manufacturers::findOne(['mnf_id' => $this->manufacture]);

    if (empty($manufacture)) {
      throw new \yii\web\HttpException(404, 'Производитель не найден');
    }
    
    if ($ID && empty($model)) {
      throw new \yii\web\HttpException(404, 'Акция не найдена.');
    }
    
    $Files = new Files;
      
    if(!$ID) {//если добавляется акция
      $model->setAttribute('shr_manufacture', $this->manufacture);
      $model->validateCountShares();
    }
    
    if ($post = Yii::$app->request->post('SharesManufacturers')) {
      $model->load(Yii::$app->request->post());
      
      $Files->image = UploadedFile::getInstance($Files, 'image');

      /*begin Загрузка файла*/
      if( !empty($Files->image) && $Files->validate() )
      {
        /* Удаление старого логотипа с сервера */
        $Files->delete('/shares-manufacturers', $model->shr_image);
        
        $shr_image = $Files->upload('/shares-manufacturers', [
          'original_size' => true,
          'thumb_width' => 200,
          'thumb_height' => 400
        ]);
        /*end Загрузка файла*/
        
        $model->setAttribute('shr_image', !empty($shr_image[0]['name']) ? $shr_image[0]['name'] : '');
      }
      elseif($shr_tempimage = $post['shr_tempimage']) {
        $model->setAttribute('shr_image', $shr_tempimage);
      }

      if ($model->save()) {
        $model->addSuccess('Акция сохранена');
      }
    }

    return $this->render('edit',
      ['model' => $model,
      'Files' => $Files,
    ]);
  }
  
  public function actionDelete($ID = null) {
    if(!$ID){
      throw new \yii\web\HttpException(400, "Не передан ID акции для удаления");
    }
    if (is_numeric($ID) && !empty($ID)) {
      $model = SharesManufacturers::find()
      ->where(['shr_id' => $ID])
      ->one();
      
      if(!empty($model)){
        $model->delete();
        $model->addSuccess('Акция удалёна!');
        return $model;
      } else {
				throw new \yii\web\HttpException(400, "Такого товара не существует");
			}
    }
  }
}

