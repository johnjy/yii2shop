<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $email;
    public $tel;
//    public $captcha;

    const SCENARIO_Regist='regist';
    //验证规则
    public function rules()
    {
        return [
            ['username', 'required'],
            ['password', 'required'],
            ['email','email','on'=>[self::SCENARIO_Regist]],
            ['tel','required','on'=>[self::SCENARIO_Regist]],

        ];
    }
    //登录方法
    public function login(){

        $user=Member::findOne(['username'=>$this->username]);
        if($user){
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){

                \Yii::$app->user->login($user,3600*24);
//                var_dump($user);die;

//                \Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
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