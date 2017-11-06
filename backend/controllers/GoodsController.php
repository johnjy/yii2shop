<?php
namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends Controller{
    public $enableCsrfValidation=false;

    //商品列表
    public function actionIndex(){
        $model=new Goods();

        $requset=Goods::find();
        $pages=new Pagination();
        $pages->totalCount=$requset->count();
        $pages->pageSize=3;
        $lists=$requset->where(['status'=>1])->limit($pages->limit)->offset($pages->offset)->all();
        return $this->render('index',['lists'=>$lists,'pages'=>$pages,'model'=>$model]);

    }

    //商品添加
    public function actionAdd(){

        $model=new Goods();
        $intro=new GoodsIntro();
        $count=new GoodsDayCount();
        //设置goods_category_id默认值
        $model->goods_category_id=0;
        //品牌分类
        $brands=ArrayHelper::map(Brand::find()->all(),'id','name');
        $request=new Request();
        if($request->isPost){
//            var_dump($request->post());die;
           $intro->load($request->post());

           $model->load($request->post());
//            var_dump($a);die;

            if($model->validate() && $intro->validate()){

                $sum=GoodsDayCount::find(['day'=>date('Y-m-d',time())])->one();
//               var_dump($sum);die;
                if(!empty($sum)){
                   $sum->count+=1;
//                    var_dump($count->count);die;
                    $model->sn=date('Ymd',time())*100000+$sum->count;
                    $sum->save(false);
                }else{
                    $count->day=date('Y-m-d',time());
                    $count->count=1;
                    $count->save(false);
                    $model->sn=date('Ymd',time()).'00000';
                }
//                var_dump( $sn);die;
                //得到当前商品数

                $model->create_time=time();
                $model->status=1;
                $model->save(false);
                //得到数据保存的id
                $id=\Yii::$app->db->getLastInsertID();
                $intro->goods_id=$id;
                $intro->save(false);
                return $this->redirect('index');
            }
        }

        return $this->render('add',['model'=>$model,'intro'=>$intro,'brands'=>$brands]);

    }
    //ajax图片上传
    public function actionUploads()
    {
        if (\Yii::$app->request->isPost) {

            $imgFile = UploadedFile::getInstanceByName('file');

            //判断是否有文件上传
            if ($imgFile) {
                $fileName = '/upload/' . uniqid() . '.' . $imgFile->extension;

                $imgFile->saveAs(\Yii::getAlias('@webroot'). $fileName, 0);
                return Json::encode(['url'=>$fileName]);
//
            }
        }
    }


    //ueditor插件
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }

    //商品修改

    public function actionEdit($id){

        $model=new Goods();
        $intro=new GoodsIntro();
        $count=new GoodsDayCount();
        $model=Goods::findOne(['id'=>$id]);

        $intro=GoodsIntro::findOne(['goods_id'=>$id]);
//        var_dump($intro);die;
        //设置goods_category_id默认值
        //品牌分类
        $brands=ArrayHelper::map(Brand::find()->all(),'id','name');
        $request=new Request();
        if($request->isPost){
//            var_dump($request->post());die;
            $intro->load($request->post());

            $model->load($request->post());

            if($model->validate() && $intro->validate()){
                $model->create_time=time();
                $model->save(false);
                //得到数据保存的id
                $intro->save(false);
                return $this->redirect('index');
            }
        }

        return $this->render('add',['model'=>$model,'intro'=>$intro,'brands'=>$brands]);

    }

    //商品删除
    public function actionDel(){
        $rquest=new Request();
        $id=$rquest->post('id');
        $del=Goods::findOne(['id'=>$id]);
        $del->status=0;
        $del->save(false);
        return 1;

    }
//    //商品搜索
//    public function actionSearch(){
//
//
//    }

}