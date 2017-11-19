<?php
namespace frontend\controllers;

use backend\models\GoodsCategory;
use frontend\models\Address;

use yii\web\Controller;

class AddressController extends Controller{

    public function actionList(){
        $lists=Address::find()->where(['member_id'=>\Yii::$app->user->identity->id])->all();
        $goodsCategory=GoodsCategory::find()->where(['parent_id'=>0])->all();

        return $this->render('list',['lists'=>$lists,'goodsCategory'=>$goodsCategory]);



    }


    public function actionAdd(){

        $requset=\Yii::$app->request;
        $model=new Address();
        if($requset->isPost){
            $model->load($requset->post(),'');

            if($model->validate()){
              $model->member_id=\Yii::$app->user->identity->id;
              $model->save();

                return $this->redirect('list');
            }
        }
        return $this->render('list');
    }
    public function actionDel(){
        $requset=\Yii::$app->request;
        $id=$requset->post('id');
        $del=Address::findOne(['id'=>$id]);
        $del->delete();
        return 1;

    }
    public function actionEdit(){

    }
//    //商品分类
//    public function actionGoodsCategory(){
//        $goodsCategory=GoodsCategory::find()->where(['parent_id'=>0])->all();
//        return  $this->render('list',['goodsCategory'=>$goodsCategory]);
//
//    }
}