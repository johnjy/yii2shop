<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    public $code;
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'intro'=>'简介',
            'article_category_id'=>'文章分类id',
            'status'=>'状态',
            'sort'=>'排序',
            'create_time'=>'创建时间',
            'code'=>'验证码'
        ];
    }
    public function rules()
    {
        return [
            [['name','intro','article_category_id','status','sort','create_time'],'required']
        ];
    }
}