<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Cart extends ActiveRecord{

    public function rules()
    {
        return [
            ['goods_id','required'],
            ['amount','required'],
            ['member_id','required'],

        ];
    }

}