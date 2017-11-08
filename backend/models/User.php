<?php
namespace backend\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord{
    public  $password;
    public function attributeLabels()
    {
        return [
          'username'=>'姓名',
            'password'=>'密码',
            'email'=>'邮箱',
            'status'=>'状态'
        ];
    }

    public function rules()
    {
        return [
            [['username','password','email','status'],'required']
        ];
    }
    public function checkuser(){
        $admin=User::findOne(['username'=>$this->username]);
        $email=User::findOne(['email'=>$this->email]);

        if($admin){

                $this->addError('username','用户名已存在');
                return false;

        }elseif($email){
            $this->addError('email','邮箱已注册');
            return false;
        }
        return true;
    }

    public function check(){
        $user=User::find()->where(['!=','id',$this->id])->andWhere(['username'=>$this->username])->one();
        $email=User::find()->where(['!=','id',$this->id])->andWhere(['email'=>$this->email])->one();

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