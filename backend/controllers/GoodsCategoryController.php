<?php
namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use backend\filters\RbacFilter;

class GoodsCategoryController extends Controller{
    //列表
    public function actionIndex(){
        $require=GoodsCategory::find();
        $pages=new Pagination();
        $pages->totalCount=$require->count();
        $pages->pageSize=4;
        $lists=$require->orderBy('tree ASC,lft ASC')->limit($pages->limit)->offset($pages->offset)->all();
        return $this->render('index',['lists'=>$lists,'pages'=>$pages]);

    }

    //添加分类
    public function actionAddCategory(){
        $model=new GoodsCategory();
        //设置parent_id默认值
        $model->parent_id=0;
        $requset=\Yii::$app->request;
        if($requset->isPost){
            $model->load($requset->post());
            if($model->validate()){
                if($model->parent_id == 0){
                    //创建跟节点
                    $model->makeRoot();
                    return $this->redirect(['index']);
                }else{
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                    return $this->redirect(['index']);
                }

            }
        }
        return $this->render('add-category',['model'=>$model]);
    }
    //修改分类
    public function actionEditCategory($id){
        $model=new GoodsCategory();
        //设置parent_id默认值
        $model->parent_id=0;
        $requset=\Yii::$app->request;
        $model=GoodsCategory::findOne(['id'=>$id]);
//        $parent_id=$model->parent_id;
        if($requset->isPost){
            $model->load($requset->post());
            if($model->validate()){
                if($model->parent_id == 0){
                    //修改跟节点
                    if($model->getOldAttribute('parent_id') ==0){
                        $model->save();
                    }else{
                        $model->makeRoot();
                    }


                    return $this->redirect(['index']);
                }else{
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo($parent);
                    return $this->redirect(['index']);
                }

            }
        }
        return $this->render('add-category',['model'=>$model]);

    }
    //删除
    public function actionDelCategory(){
        $requset=new Request();
        $id=$requset->post('id');
        $del=GoodsCategory::findOne(['id'=>$id]);
        //如果存在子分类则无法删除
//        $del->isLeaf();
        $child=GoodsCategory::findAll(['parent_id'=>$id]);
        if(empty($child)){
           $del->deleteWithChildren();
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