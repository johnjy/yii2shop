<?php
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use backend\filters\RbacFilter;

class ArticleController extends Controller{

    //文章列表
    public function actionIndex(){
        $require=Article::find();
        $pages=new Pagination();
        $pages->totalCount=$require->count();
        $pages->pageSize=1;
        $category=ArrayHelper::map(ArticleCategory::find()->all(),'id','name');
        $lists=$require->where(['status'=>0])->orWhere(['status'=>1])->limit($pages->limit)->offset($pages->offset)->all();
        return $this->render('index',['lists'=>$lists,'pages'=>$pages,'category'=>$category]);
    }
    //文章添加
    public function actionAdd(){
        $requset=new Request();
        $model=new Article();
        $modeld=new ArticleDetail();
        if($requset->isPost){
            $model->load($requset->post());
            $modeld->content=$model->content;
            $model->create_time=time();
            if($model->validate() && $modeld->validate()){
                $model->save(false);
                $id=\Yii::$app->db->getLastInsertID();
                $modeld->article_id=$id;
                $modeld->save(false);
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            $category=ArrayHelper::map(ArticleCategory::find()->all(),'id','name');
            return $this->render('add',['model'=>$model,'category'=>$category]);
        }

    }

    //文章修改
    public function actionEdit($id){

        $requset=new Request();
        $model=new Article();
//        $modeld=new ArticleDetail();
        $model=Article::findOne(['id'=>$id]);
        if($requset->isPost){
            $model->load($requset->post());
            $model->create_time=time();
            if($model->validate()){
                $model->save(false);
                return $this->redirect(['article/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            $category=ArrayHelper::map(ArticleCategory::find()->all(),'id','name');
            return $this->render('add',['model'=>$model,'category'=>$category]);
        }
    }

    //文章删除
    public function actionDel(){
        $request=new Request();
        $id=$request->post('id');
        $del=Article::findOne(['id'=>$id]);
        $del->status=-1;
        $del->save(false);
        return 1;
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