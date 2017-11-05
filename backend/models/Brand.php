<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord
{

    public $imgFile;
    public $code;

    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'intro' => '简介',
//            'logo' => 'LOGO',
            'sort' => '排序',
            'status' => '状态',
            'code' => '验证码'

        ];

    }

    public function rules()
    {
        return [
            [['name', 'intro', 'sort', 'status'], 'required'],
//            [['logo'], 'string', 'max' => 255],
//         ['imgFile', 'file', 'extensions' => ['jpg', 'png', 'gif'], 'skipOnEmpty' => false]

        ];
    }
}