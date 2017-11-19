<?php
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\EditForm;
use backend\models\PwdForm;
use backend\models\User;
use common\models\LoginForm;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use yii\captcha\CaptchaAction;

class UserController extends Controller{

    //登录
    public function actionLogin(){
        $model=new \backend\models\LoginForm();
        $requset=\Yii::$app->request;
        if($requset->isPost){
            $model->load($requset->post());
            if($model->validate()){
                if($model->login()){
                    $models=new User();
                    $models=User::findOne(['username'=>$model->username]);
                    $models->last_login_time=time();
                    $models->last_login_ip=\Yii::$app->request->userIP;
//                    $models->auth_key=Yii::$app->security->generateRandomString();
                    $models->save(false);

                    \Yii::$app->session->setFlash('success','登录成功');
                    return $this->redirect(['user/index']);
                }
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //注销
    public function actionLogout(){
//        Yii::$app->user->logout();
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
//        return $this->goHome();
    }

    //管理员列表
    public function actionIndex(){
        //分页
        $pages=new Pagination();
        $pages->totalCount=User::find()->count();
        $pages->pageSize=4;
        $lists=User::find()->limit($pages->limit)->offset($pages->offset)->all();
        return $this->render('index',['lists'=>$lists,'pages'=>$pages]);
    }

    //添加管理员
    public function actionAdd(){
        $model=new User();
        $requset=new Request();
        //取出角色列表
        $auth=\Yii::$app->authManager;
        $roles=$auth->getRoles();
        $roles=ArrayHelper::map($roles,'name','description');


        if($requset->isPost){
            $model->load($requset->post());
            if($model->validate()){

                if($model->checkuser()){

                    $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);

                    $model->save();
                    $id=$model->getId();
//                    var_dump($id);die;
                    //遍历出角色并赋予
                    foreach($model->roles as $roleName){
                        $role=$auth->getRole($roleName);
                        $auth->assign($role,$id);

                    }

                    \Yii::$app->session->setFlash('success','添加成功');
                    return $this->redirect('index');

                }

            }
        }
        return $this->render('add',['model'=>$model,'roles'=>$roles]);
    }

    //修改管理员
    public function actionEdit($id){
        //实例化表单
        $model=new EditForm();
        $requset=new Request();
        //查询id得到修改数据
        $model=User::findOne(['id'=>$id]);
        //取出角色列表
        $auth=\Yii::$app->authManager;
        $roles=$auth->getRoles();
        $roles=ArrayHelper::map($roles,'name','description');

        $password=$model->password_hash;
        if($requset->isPost){
            $model->load($requset->post());
            if($model->password===null){
                $model->password=$password;

            }
                if($model->validate()){
                    //验证数据
                   if($model->check()){
                       $model->save();

                       $id=$model->getId();
                        //删除旧角色
                         $auth->revokeAll($id);
//
                       //遍历出角色并赋予
                       foreach($model->roles as $roleName){
                           $role=$auth->getRole($roleName);
                           $auth->assign($role,$id);

                       }

                       \Yii::$app->session->setFlash('success','修改成功');
                       return $this->redirect('index');
                   }
            }

        }
        //得到已有的角色
        $role=ArrayHelper::map($auth->getRolesByUser($model->id),'name','name');


        $model->roles=$role;
//        $model->description=$roles->description;
        return $this->render('edit',['model'=>$model,'roles'=>$roles]);

    }
    //修改管理员密码
    public function actionPwd(){
        //实例化表单
        $model=new PwdForm();
        $requset=\Yii::$app->request;
        //得到表单数据
        if($requset->isPost){
            $model->load($requset->post());
            //验证数据
            if($model->validate()){
            $password_hash=\Yii::$app->user->identity->password_hash;
                //如果验证成功,保存数据
                if(\Yii::$app->security->validatePassword($model->oldpassword,$password_hash)){
                    User::updateAll([
                        'password_hash'=>\Yii::$app->security->generatePasswordHash($model->password)
                    ],
                        [
                            'id'=>\Yii::$app->user->id
                        ]);
                    \Yii::$app->user->logout();
                    \Yii::$app->session->setFlash('success','修改密码成功,请重新登录');
                    return $this->redirect(['user/login']);
                }else{
                    $model->addError('oldpassword','旧密码不正确');
                }
            }
        }

        return $this->render('pwd',['model'=>$model]);
    }

    //删除管理员
    public function actionDel(){
        $request=new Request();
        $id=$request->post('id');
        $del=User::findOne(['id'=>$id]);
        $del->delete();
        return 1;
    }
    public function behaviors()
    {
        return[
            'rbac'=>[
                'class'=>RbacFilter::className(),
              'except'=>["login",'logout'],
//
            ]
        ];
    }
}