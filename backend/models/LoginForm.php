<?php
namespace backend\models;


use yii\base\Model;


class LoginForm extends Model{
    public $username;
    public $password;
    public $password_hash;
    public function rules()
    {
        return [
            [['username','password'],'required']
        ];
    }
    public function login(){
        $admin=User::findOne(['username'=>$this->username]);

        if($admin){
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){

                \Yii::$app->user->login($admin);

            }else{
                $this->addError('password','密码错误');
            }
        }else{
            $this->addError('username','账号错误');
        }
        return false;
    }
}