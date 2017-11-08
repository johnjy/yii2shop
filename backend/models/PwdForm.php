<?php
namespace backend\models;

use yii\db\ActiveRecord;

class PwdForm extends  ActiveRecord{
    public $oldpassword;
    public $password;
    public $repassword;
    public function attributeLabels()
    {
        return [
            'oldpassword'=>'旧密码',
            'password'=>'新密码',
            'repassword'=>'确认密码'
        ];
    }

    public function rules()
    {
        return [
            [['oldpassword','password','repassword'],'required'],
            ['repassword','compare','compareAttribute'=>'password','message'=>'两次密码不一致'],
        ];

    }


}