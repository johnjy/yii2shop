<?php
namespace backend\controllers;

use backend\models\Brand;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller{

    //品牌列表
    public function actionIndex(){
        $require=Brand::find();
        $pages=new Pagination();
        //统计数据条数
        $pages->totalCount=$require->count();
        $pages->pageSize=2;
        $lists=Brand::find()->where(['status'=>0])->orWhere(['status'=>1])->limit($pages->limit)->offset($pages->offset)->all();

//        var_dump($lists);die;
        return $this->render('index',['lists'=>$lists,'pages'=>$pages]);
    }

    //品牌添加
    public function actionAdd(){

        $model=new Brand();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                $ext=$model->imgFile->extension;
                $file='/upload/'.uniqid().'.'.$ext;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
                $model->logo=$file;
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className()
            ]
        ];
    }
    //品牌修改
    public function actionEdit($id){

        $model=new Brand();
        $request=new Request();
        $model=Brand::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
                $ext=$model->imgFile->extension;
                $file='/upload/'.uniqid().'.'.$ext;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
                $model->logo=$file;
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }
    //品牌删除
    public function actionDel(){
        $requset=new Request();
        $id=$requset->post('id');
        $del=Brand::findOne(['id'=>$id]);
        $del->status=-1;
        $del->save(false);
        return 1;
    }

}