<?php

namespace backend\models;

use common\models\Towns;
/**
 *  Стандартизированный метод применения фильтров
 *  Отдельная модель, унаследованная от модели, для которой
 *  будет применен фильтр.
 */
class SharesManufacturersFilter extends \common\models\SharesManufacturers
{
  public $selected_manufacturers = [];

  public $Manufacture;
  
  public $selected_town = [];
  
  public $mnf_town;
  
  public $shr_start_date;
  public $shr_end_date;

  public function rules()
  {
      return [
          [['shr_manufacture', 'mnf_town', 'mnf_title', 'shr_start_date', 'shr_end_date'], 'safe'],
          [['shr_start_date', 'shr_end_date'], 'date']
      ];
  }

  public function attributeLabels()
  {
      return [
        'shr_title' => 'Название товара / (ID:XX)',
        'shr_manufacture' => 'Производитель',
        'shr_start_date' => 'Начиная от',
        'shr_end_date' => 'Заканчивая',
      ];
  }
  
  public function add( &$query )
  {
    /* Фильтрация по производителю */
    if(!empty($this->shr_manufacture) ){
      if($this->Manufacture = Manufacturers::findOne($this->shr_manufacture))
      {
        $query
          ->joinWith('manufacturers')
          ->andWhere(['sot_manufacturers.mnf_id' => $this->shr_manufacture]);

        $this->selected_manufacturers = [
          $this->Manufacture->mnf_id => "{$this->Manufacture->activity} «{$this->Manufacture->mnf_title}»"
        ];
      }
    }
    
     /* Фильтрация по переданному городу/городам*/
    if( !empty($this->mnf_town) ){
      $query
      ->joinWith('manufacturers')
      ->andWhere(['mnf_town' => $this->mnf_town]);

      $this->selected_town[$this->mnf_town] = Towns::find()->andWhere(['twn_vk_id' => $this->mnf_town])->one()->twn_title;
    }

    if (!empty($this->shr_start_date)) {
      $query
      ->andWhere(['>=', 'shr_start_date', strtotime("{$this->shr_start_date} 00:00:00")]);
    }
    if (!empty($this->shr_end_date)) {
      $query
      ->andWhere(['<=', 'shr_end_date', strtotime("{$this->shr_end_date} 23:59:59")]);
    }
  }
}


