<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Address extends ActiveRecord{

    public function rules()
    {
        return [
           ['name','required'] ,
           ['province','required'] ,
           ['city','required'] ,
           ['area','required'] ,
           ['detail','required'] ,
           ['phone','required'] ,
        ];
    }

}