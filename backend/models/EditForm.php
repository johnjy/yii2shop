<?php
namespace backend\models;

use yii\db\ActiveRecord;

class EditForm extends ActiveRecord{
    public $roles;

    public function rules()
    {
        return [
          [['username','status'],'required'],
            ['email','email'],
            ['roles','required'],
        ];

    }
    public function check(){
        $user=User::find()->where(['!=','id',$this->id])->andWhere(['username'=>$this->username])->all();
        $email=User::find()->where(['!=','id',$this->id])->andWhere(['email'=>$this->email])->all();

       if($user){
           $this->addError('username','用户已存在');
            return false;
       }
        if($email){
            $this->addError('email','邮箱已注册');
            return false;
        }
        return true;
    }
}
