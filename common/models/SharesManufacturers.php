<?php
namespace common\models;

use yii\db\ActiveRecord;
use backend\models\Manufacturers;
use backend\models\Services;


class SharesManufacturers extends ActiveRecord
{
  /**
   *  Трейт расширения функционала класса
  */
  use \backend\models\ModelExtentions;
  
  public $shr_tempimage;
  private $count_shares;
  
  public static function tableName() {
    return "sot_shares_manufacturers";
  }
   
  public function beforeValidate() {
    if ($this->shr_image) {
      $this->shr_tempimage = $this->shr_image;
    }
    
    if (empty($this->shr_start_date)) {
      $this->shr_start_date = strtotime(date('Y-m-d'));
    } elseif (!is_numeric($this->shr_start_date)) {
      $this->shr_start_date = strtotime($this->shr_start_date);
    }
    
    if (!is_numeric($this->shr_end_date)) {
      $this->shr_end_date = strtotime($this->shr_end_date);
    }
    
    if (!$this->validateCountShares()) {
      return false;
    }
    
    return true;
  }
  
  public function validateDate() {
    if ($this->shr_start_date >= $this->shr_end_date) {
      $this->addError('Проверьте дату окончания: Дата окончания акции должна быть больше даты начала');
      return false;
    }
    
    return true;
  }
  
  public function validateCountShares() {
    if ($this->shr_id) {
      return true;
    }
    
    if(!$this->count_shares) {
      $this->count_shares = $this::find()
        ->where(['shr_manufacture' => $this->shr_manufacture])
        ->count();
    }
    
    if ($this->count_shares > 2) {
      $this->addError('Больше 3 акций размещать нельзя');
      return false;
    }
    return true;
  }
  
  public function rules()
  {
    return [
      [['shr_image', 'shr_content', 'shr_start_date', 'shr_end_date', 'shr_discount'], 'safe'],
      [['shr_image', 'shr_end_date'], 'required'],
      [['shr_content'], 'string'],
      [['shr_discount'], 'integer', 'min' => 1, 'max' => 99],
      [['shr_end_date'], 'validateDate']
    ];
  }
  
  public function getManufacturers(){
    return $this
    ->hasOne(Manufacturers::className(), ['mnf_id' => 'shr_manufacture']);
  }
  
   public function getServices(){
    return $this
     ->hasMany(Services::className(), ['srv_id' => 'sim_service'])
     ->viaTable('sot_services_in_manuf', ['sim_manufacture' => 'shr_manufacture'], function($query){
       return $query->onCondition(['>', 'sim_till', time()]);
     });
  }
  
  public function getProducts() {
    return $this
    ->hasOne(products::className(), ['prod_id' => 'shr_product']);
  }
  
  public function getOrganizations(){
    return $this
      ->hasMany(Manufacturers::className(), ['mnf_id' => 'shr_manufacture']);
  }
  
  public function attributeLabels() {
    return [
      'shr_start_date' => 'Начало действия акции',
      'shr_end_date' => 'Конец действия акции',
      'shr_content' => 'Контент',
      'shr_discount' => 'Скидка на товар',
      'shr_manufacture' => 'Компания',
      'shr_image' => 'Изображение для акции',
    ];
  }
}