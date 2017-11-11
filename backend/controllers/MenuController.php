<?php
namespace backend\controllers;

use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use backend\filters\RbacFilter;

class MenuController extends Controller{

        //菜单列表
    public function actionIndex(){

        $lists=Menu::find()->all();
        return $this->render('index',['lists'=>$lists]);
    }

    //添加菜单
    public function actionAdd(){

        $model=new Menu();
        //取出菜单
        $menus=Menu::find()->all();
        $menus=ArrayHelper::map($menus,'id','label');

        //取出路由
        $auth=\Yii::$app->authManager;
        $urls=$auth->getPermissions();
        $urls=ArrayHelper::map($urls,'name','name');
        $request=new Request();
        //判断传值方式
        if($request->isPost){
            $model->load($request->post());
            //验证通过 保存
            if($model->validate() ){
//                var_dump($model);die;
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect('index');
            }

        }
        return $this->render('add',['model'=>$model,'urls'=>$urls,'menus'=>$menus]);

    }

    //修改菜单
    public function actionEdit($id){
        $model=new Menu();
        //得到编辑数据
        $model=Menu::findOne(['id'=>$id]);

        $menus=Menu::find()->all();
        $menus=ArrayHelper::map($menus,'parent_id','label');
        //取出路由
        $auth=\Yii::$app->authManager;
        $urls=$auth->getPermissions();
        $urls=ArrayHelper::map($urls,'name','name');
        $request=new Request();
        //判断传值方式
        if($request->isPost){
            $model->load($request->post());
            //验证通过 保存
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('index');
            }

        }
        return $this->render('add',['model'=>$model,'urls'=>$urls,'menus'=>$menus]);

    }

    //删除菜单
    public function actionDel(){

        $request=new Request();
        $id=$request->post('id');
        //查询该菜单是否有子菜单
        $menu=Menu::find()->where(['parent_id'=>$id])->one();
        if(!$menu){
            $del=Menu::findOne(['id'=>$id]);
            $del->delete();
            return 1;

        }


    }

    public function behaviors()
    {
        return[
            'rbac'=>[
                'class'=>RbacFilter::className(),

            ]
        ];
    }

}
