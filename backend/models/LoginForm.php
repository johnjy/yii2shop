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

                \Yii::$app->user->login($admin,3600*24);
                if ($this->validate()) {
                    return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
                } else {
                    return false;
                }

            }else{
                $this->addError('password','密码错误');
            }
        }else{
            $this->addError('username','账号错误');
        }
        return false;
    }


}