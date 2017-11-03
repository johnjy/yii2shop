<?php
namespace backend\models;

use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{
    public $code;
    public function attributeLabels()
    {

        return [
            'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态',
            'code'=>'验证码'
        ];
    }

    public function rules()
    {
        return [
            [['name','intro','sort','status'],'required']
        ];
    }
}