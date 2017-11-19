<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class OrderGoods extends ActiveRecord{

    public function rules()
    {
        return [
            ['order_id','required'],
            ['goods_id','required'],
            ['goods_name','required'],
            ['logo','required'],
            ['price','required'],
            ['amount','required'],
            ['total','required'],
        ];
    }

}