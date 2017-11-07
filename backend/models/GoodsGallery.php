<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsGallery extends ActiveRecord{
   public function attributeLabels()
   {
       return[
           'path'=>'',
       ];
   }

    public function rules()
    {
        return [
            ['path','required']
        ];

    }
}