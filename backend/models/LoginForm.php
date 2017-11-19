<?php
namespace backend\models;



use yii\base\Model;
use yii\db\ActiveRecord;


class LoginForm extends ActiveRecord{
    public $username;
    public $password;
    public $password_hash;
    public $code;
    public $rememberMe;
    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['code','captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'rememberMe'=>'记住我',
            'code'=>'验证码',
        ];
    }

    public function login(){
        $admin=User::findOne(['username'=>$this->username]);

        if($admin){
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){

                \Yii::$app->user->login($admin,3600*24);

                  \Yii::$app->user->login($admin, $this->rememberMe ? 3600 * 24 * 30 : 0);
                    return true;

            }else{
                $this->addError('password','密码错误');
            }
        }else{
            $this->addError('username','账号错误');
        }
        return false;
    }


}