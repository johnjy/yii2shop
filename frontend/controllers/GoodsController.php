<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use frontend\models\Cart;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsController extends Controller{
        public $enableCsrfValidation=false;
    //商品分类列表
        public function actionList($goods_category_id){

            //商品分类  一级  二级  三级

            $goods_category = GoodsCategory::findOne(['id'=>$goods_category_id]);
            //三级分类
            if($goods_category->depth == 2){
                $query = Goods::find()->where(['goods_category_id'=>$goods_category_id]);

            }else{
                //二级分类  14
                //sql:select * from goodscategory where parent_id=14
                //$ids = $goods_category->children()->andWhere(['depth'=>2])->column();
                $ids = $goods_category->children()->andWhere(['depth'=>2])->column();

                $query = Goods::find()->where(['in','goods_category_id',$ids]);

            }
            $pager = new Pagination();
            $pager->totalCount = $query->count();
            $pager->pageSize = 20;

            $models = $query->limit($pager->limit)->offset($pager->offset)->all();
//            var_dump($models);die;
            return $this->render('list',['models'=>$models,'pager'=>$pager]);

        }
        //商品首页
        public function actionIndex(){
            return $this->render('index');

        }
        //详情页
    public function actionDetail($goods_category_id){
//        $goods=Goods::find()



    }

 //购物车

    public function actionCart(){
            //未登录存入cookie
            if(\Yii::$app->user->isGuest){
                $cookies=\Yii::$app->request->cookies;
                $carts=$cookies->getValue('carts');
                if($carts){
                    $carts=unserialize($carts);
                }else{
                    $carts=[];
                }
                //获取购物车内的商品信息
                $goods=Goods::find()->where(['in','id',array_keys($carts)])->all();

            }else{

                //添加完商品后登录
                $member_id=\Yii::$app->user->identity->id;
                $model=new Cart();
                //取出cookie中的数据
                $cookies=\Yii::$app->request->cookies;

                $carts = $cookies->getValue('carts');
//                var_dump($carts);die;
                if($carts){
                    $cart = unserialize($carts);
                    $datas=Cart::find()->where(['member_id'=>$member_id])->all();
                    $data=ArrayHelper::map($datas,'goods_id','amount');
                    //cookie存入数据库
                    foreach($cart as $key=>$value){
                        //若数据库有该商品则累加
                        foreach($data as $it=>$good){
                            if($key==$it){
                                $model->goods_id=$key;
                                $model->amount=$good+$value;
                            }
                        }if($key!=$it){
                            $model->goods_id=$key;
                            $model->amount=$value;
                        }

                        $model->member_id=$member_id;
                        if($model->validate()){

                            $model->save();
                        }
                    }
                    //清除cookie
                    $cookies=\Yii::$app->response->cookies;
                    $cookies->remove('carts');
                }else{
                    $carts = [];
                }


                //登录操作数据库
                $member_id=\Yii::$app->user->identity->id;
//
                $carts=Cart::find()->where(['member_id'=>$member_id])->all();
                $carts= ArrayHelper::map($carts,'goods_id','amount');
//                var_dump($cart[0]->goods_id);die;

                $goods=Goods::find()->where(['in','id',array_keys($carts)])->all();
//                var_dump($carts);die;
            }
//                var_dump($goods);die;
               return $this->render('cart',['goods'=>$goods,'carts'=>$carts]);
            }
    //添加购物车
    public function actionAddCart($goods_id,$amount){
        //未登录存入cookie
        if(\Yii::$app->user->isGuest){
            $cookies=\Yii::$app->request->cookies;
            //得到cookie中的数据
            $carts=$cookies->getValue('carts');
            if($carts){
                $carts=unserialize($carts);
            }else{
                $carts=[];
            }
            //购物车是否存在该商品,存在累加,不存在添加
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id]+=$amount;
            }else{
                $carts[$goods_id]=$amount;
            }
            $cookies=\Yii::$app->response->cookies;
            $cookie=new Cookie();
            $cookie->name='carts';
            $cookie->value=serialize($carts);
            $cookie->expire=time()+3600;
            $cookies->add($cookie);


        }else{



            //登录操作数据库
            $model=new Cart();
            $model->member_id=\Yii::$app->user->identity->id;
            $model->goods_id=$goods_id;
            $model->amount=$amount;
            if($model->validate()){

                $model->save();
            }



        }

        return $this->redirect(['cart']);

    }
    public function actionAjaxCart($type){
        //登录操作数据库 未登录操作cookie
        switch ($type){
            case 'change'://修改购物车

                $goods_id = \Yii::$app->request->post('goods_id');

                $amount = \Yii::$app->request->post('amount');
                if(\Yii::$app->user->isGuest){
                    //取出cookie中的购物车
                    $cookies = \Yii::$app->request->cookies;
                    $carts = $cookies->getValue('carts');
                    if($carts){
                        $carts = unserialize($carts);
                    }else{
                        $carts = [];
                    }
                    //修改购物车商品数量
                    $carts[$goods_id] = $amount;
                    //保存cookie
                    $cookies = \Yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value = serialize($carts);
                    $cookie->expire=time()+3600;
                    $cookies->add($cookie);

                }else{
                    //保存到数据库
//
                    $model=new Cart();
                    $model->member_id=\Yii::$app->user->identity->id;
                    $model->goods_id=$goods_id;
                    $model->amount=$amount;
                    if($model->validate()){
                        $model->save();
                    }
                }
                break;
            case 'del':
                $goods_id = \Yii::$app->request->post('goods_id');
                //未登录操作cookie
                if(\Yii::$app->user->isGuest){
                    //取出cookie中的商品
                    $cookies=\Yii::$app->request->cookies;
                    $carts=$cookies->getValue('carts');
                    $carts=unserialize($carts);
                    //删除选中商品
                    $del=[$goods_id=>$carts[$goods_id]];
                    $carts=array_diff_key($carts,$del);
                    //保存cookie
                    $cookies=\Yii::$app->response->cookies;
                    $cookie=new Cookie();
                    $cookie->name='carts';
                    $cookie->value=serialize($carts);
                    $cookie->expire=time()+3600;
                    $cookies->add($cookie);
                    return 1;
                }else{
                    //登录删除数据库商品信息
                    $member_id=\Yii::$app->user->identity->id;
                    $del=Cart::find()->where((['goods_id'=>$goods_id]))->andWhere(['member_id'=>$member_id])->one();
                    $del->delete();
                    return 1;
                }
//                var_dump($goods_id);die;

                break;
        }


    }

     //商品详情
    public function actionGoods($goods_id){
        $goods=Goods::findOne(['id'=>$goods_id]);

        $gallery=GoodsGallery::findAll(['goods_id'=>$goods_id]);
        $gallery=ArrayHelper::map($gallery,'path','path');
//        var_dump($gallery);die;
        return $this->render('goods',['goods'=>$goods,'gallery'=>$gallery]);
    }
}