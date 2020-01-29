<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use \common\models\SharesManufacturers;
use backend\models\Manufacturers;
use yii\data\Pagination;

/**
* Site controller
*/
class SharesManufacturersController extends Controller
{
    public function actionIndex()
    {
        $pageSize = 7;
        
        $query = SharesManufacturers::find()
        ->select('*')
        ->joinWith('manufacturers')
        ->innerJoin('sot_services_in_manuf', 'sot_shares_manufacturers.shr_manufacture = sot_services_in_manuf.sim_manufacture')
        ->where(['mnf_status' => Manufacturers::STATUS_ACTIVE])
        ->andWhere(['<=', 'shr_start_date', time()])
        ->andWhere(['>', 'shr_end_date', time()])
        ->andWhere(['in', 'sot_services_in_manuf.sim_service', [10,12]])
        ->andWhere(['>', 'sot_services_in_manuf.sim_till', time()])
        ->andWhere(['sot_services_in_manuf.sim_status' => 1])
        ->groupBy('shr_id')
        ->orderBy(['count(sot_services_in_manuf.sim_service)' => SORT_DESC])
        ->addOrderBy(['sot_services_in_manuf.sim_date' => SORT_ASC]);

        $pages = new Pagination([
          'pageSize' => $pageSize,
          'defaultPageSize' => $pageSize,
          'totalCount' => $query->count()
        ]);

        $query
        ->offset($pages->offset)
        ->limit($pages->limit);

        return $this->render('index',
            ['models' => $query->all(),
            'pages' => $pages
        ]);
    }
}