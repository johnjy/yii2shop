<?php
namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;


class ArticleCategoryController extends Controller{

    //文章分类列表
    public function actionIndex(){
        $require=ArticleCategory::find();
        $pages=new Pagination();
        //总数据数
        $pages->totalCount=$require->count();
        //每页数据
        $pages->pageSize=2;
        $lists=$require->where(['status'=>0])->orWhere(['status'=>1])->limit($pages->limit)->offset($pages->offset)->all();
        return $this->render('index',['lists'=>$lists,'pages'=>$pages]);
    }

    //文章分类添加
    public function actionAdd(){
        $requset=new Request();
        $modle=new ArticleCategory();
        if($requset->isPost){
            $modle->load($requset->post());
            if($modle->validate()){
                $modle->save(false);
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($modle->getErrors());
            }

        }else{
            return $this->render('add',['model'=>$modle]);
        }
    }
    //文章分类修改
    public function actionEdit($id){
        $requset=new Request();
        $modle=new ArticleCategory();
        $modle=ArticleCategory:: findOne(['id'=>$id]);
        if($requset->isPost){
            $modle->load($requset->post());
            if($modle->validate()){
                $modle->save(false);
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($modle->getErrors());
            }

        }else{
            return $this->render('add',['model'=>$modle]);
        }
    }
    //文章分类删除
    public function actionDel(){
        $request=new Request();
        $id=$request->post('id');
        $del=ArticleCategory::findOne(['id'=>$id]);
        $del->status=-1;
        $del->save(false);
        return 1;
    }
}