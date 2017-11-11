<?php
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthController extends Controller{

    //角色列表
    public function actionRoleIndex(){
        $auth=\Yii::$app->authManager;
        $roles=$auth->getRoles();
//        var_dump($roles);die;
        return $this->render('role-index',['roles'=>$roles]);


    }
    //角色添加
    public function actionRoleAdd(){
        $auth=\Yii::$app->authManager;
        $model=new RoleForm();

        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
           if($model->validate()){
               //创建角色
                $role=$auth->createRole($model->name);
               //添加描述
                $role->description=$model->description;
                $auth->add($role);
               //权限添加
               foreach($model->permissions as $permissionName){
                   $permission=$auth->getPermission($permissionName);
                   $auth->addChild($role,$permission);
               }
               \Yii::$app->session->setFlash('success','添加角色成功');
               return $this->redirect('role-index');
           }

        }
        $permissions=$auth->getPermissions();
        $permissions=ArrayHelper::map($permissions,'name','description');
        return  $this->render('role-add',['model'=>$model,'permissions'=>$permissions]);
    }

    //角色修改
    public function actionRoleEdit($name){
        $auth=\Yii::$app->authManager;
        $role=$auth->getRole($name);
        $model=new RoleForm();
//        var_dump($model);die;
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){

                $newrole=$auth->createRole($model->name);
                $newrole->description=$model->description;
                $auth->update($role->name,$newrole);
                $auth->removeChildren($newrole);

                //权限添加
                foreach($model->permissions as $permissionName){
                    $permission=$auth->getPermission($permissionName);
                    $auth->addChild($role,$permission);
                }
                \Yii::$app->session->setFlash('success','修改角色成功');
                return $this->redirect('role-index');

            }

        }
        $model->name=$role->name;
        $model->description=$role->description;
        //得到已有的权限
        $permission=ArrayHelper::map($auth->getPermissionsByRole($request->get('name')),'name','name');
//        var_dump($permission);die;
        $model->permissions=$permission;
        $permissions=$auth->getPermissions();
        $permissions=ArrayHelper::map($permissions,'name','description');
        return  $this->render('role-add',['model'=>$model,'permissions'=>$permissions]);

    }

    //角色删除
    public function actionRoleDel(){
        $auth=\Yii::$app->authManager;
        $requset=\Yii::$app->request;
        $name=$requset->post('name');
        $role=$auth->getRole($name);
        $auth->remove($role);
        return 1;

    }
    //权限列表
    public function actionPreIndex(){
        $auth=\Yii::$app->authManager;
        $pres=$auth->getPermissions();
        return $this->render('pre-index',['pres'=>$pres]);
    }

    //创建权限
    public function actionPreAdd(){
        $auth=\Yii::$app->authManager;
        $requst=\Yii::$app->request;
        $model=new RoleForm();
        //设置场景
        $model->scenario=RoleForm::SCENARIO_Add;
        if($requst->isPost){
            $model->load($requst->post());
//            var_dump($model);die;
            if($model->validate()){
                $name=$model->name;
                $premission=$auth->createPermission($name);
                $premission->description=$model->description;
                $auth->add($premission);
                \Yii::$app->session->setFlash('success','添加权限成功');
                return $this->redirect('pre-index');
            }
        }
        return $this->render('pre-add',['model'=>$model]);
    }
    //修改权限
    public function actionPreEdit($name){
        $auth=\Yii::$app->authManager;
        //获取修改权限信息
        $permission=$auth->getPermission($name);
        //权限不存在  提示
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }

        $model=new RoleForm();
        $requst=\Yii::$app->request;

        $model->name=$permission->name;
        $model->oldname=$permission->name;
        $model->description=$permission->description;
        $model->scenario=RoleForm::SCENARIO_Edit;

        if($requst->isPost){
            $model->load($requst->post());
            if($model->validate()){
                $newPermission=$auth->createPermission($model->name);
                $newPermission->description=$model->description;
                $auth->update($permission->name,$newPermission);
                \Yii::$app->session->setFlash('success','修改权限成功');
                return $this->redirect('pre-index');
            }
        }


        return $this->render('pre-add',['model'=>$model]);

    }
    //权限删除
    public function actionPreDel(){
        $auth=\Yii::$app->authManager;
        $requset=\Yii::$app->request;
        $name=$requset->post('name');
        $role=$auth->getPermission($name);
        $auth->remove($role);
        return 1;

    }
    public function behaviors()
    {
        return[
            'rbac'=>[
                'class'=>RbacFilter::className()
            ]
        ];
    }

    //测试数据
    public function actionTest(){

        $auth=\Yii::$app->authManager;
        //创建角色
//        $role=$auth->createRole('超级管理员');
//        $auth->add($role);

        //创建权限
//        $permission=$auth->createPermission('auth/add');
//        $permission->description='添加用户';
//        $auth->add($permission);

        //角色分配权限
        //得到角色
        $role=$auth->getRole('超级管理员');
        //得到权限
        $permission=$auth->getPermission('auth/add');
        //分配
//        $auth->addChild($role,$permission);
        //用户分配角色
//        $auth->assign($role,5);
    }
}