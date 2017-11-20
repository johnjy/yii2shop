<?php
namespace frontend\controllers;

use frontend\components\Sms;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\RegistForm;
use yii\web\Controller;

class MemberController extends Controller{
    //用户登录
    public function actionLogin(){
//        $model=new \frontend\models\LoginForm();
        $model=new LoginForm();

        $request=\Yii::$app->request;

        if($request->isPost){
            $model->load($request->post(),'');

           if($model->validate()){
//

               if($model->login()){

                   $user=new Member();
                   $user=Member::findOne(['username'=>$model->username]);
                   $ip=\Yii::$app->request->userIP;
                   $user->id=\Yii::$app->user->id;
                   $user->update($user->last_login_ip,[$ip]);
//                   var_dump($model);die;
//                   echo 1; die;
                   return $this->redirect(['goods/index']);
               }

           }
        }


        return $this->render('login',['model'=>$model]);
    }
    //zhuxiao
        public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['member/login']);
    }

    //用户注册
    public function actionRegist(){

        $model=new \frontend\models\LoginForm();
        //设置场景
        $model->scenario=LoginForm::SCENARIO_Regist;
        $request=\Yii::$app->request;
        if($request->isPost){

            $model->load($request->post(),'');


            if($model->validate()){
                $user=new Member();

                $user->username=$model->username;
                $user->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                $user->email=$model->email;
                $user->tel=$model->tel;
////                var_dump($user);die;

                $user->save();
                return $this->redirect(['login']);
            }
        }

        return $this->render('regist',['model'=>$model]);
    }
    //验证用户名唯一
    public function actionCheckName($username){
        $user=Member::findOne(['username'=>$username]);
        if($user){
            return 'false';
        }
        return 'true';
    }

    //短信验证

    public function actionMsg($tel){
        //短信验证码

            $code=rand(1000,9999);
        //发送短信\
            //判断两次短信发送时间间隔
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $redis->get('captcha_'.$tel);

        $result=600-$redis->get('captcha_'.$tel);
//        if(){
//
//        }
        $response = Sms::sendSms(
            "YMC洛水之南", // 短信签名
            "SMS_109480438", // 短信模板编号
            $tel, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code,
            )
        );
        //根据$response结果判断是否发送成功 $response->Code
        if($response->Code == 'OK'){
            $redis=new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('captcha_'.$tel,$code,60*60);
            return 'success';
        }

    }
    public function actionCheckCode(){
        //取出电话号码和验证码

        $tel=\Yii::$app->request->post('tel');

        $captcha=\Yii::$app->request->post('captcha');

        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $code=$redis->get('captcha_'.$tel);
        if($code == $captcha){

            return 'true';
        }else{
            return 'false';
        }

    }
}