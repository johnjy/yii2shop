<?php
namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class OrderController extends Controller{
    //订单首页
    public function actionIndex(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }
        $member_id=\Yii::$app->user->identity->id;
        //得到地址数据
        $address=Address::find()->where(['member_id'=>$member_id])->all();
        //得到购物车商品
        $carts=Cart::find()->where(['member_id'=>$member_id])->all();
        $cart=ArrayHelper::map($carts,'goods_id','amount');
        //得到商品具体信息
        $goods=Goods::find()->where(['in','id',array_keys($cart)])->all();
        return $this->render('index',['address'=>$address,'carts'=>$cart,'goods'=>$goods]);
    }
    //订单处理
    public function actionOrder(){
//            echo 1;die;
        $requset=\Yii::$app->request;

//        var_dump($requset->post());die;
        if($requset->isPost){
            $order=new Order();
            $member_id=\Yii::$app->user->id;
            $address_id=$requset->post('address_id');
            $delivery=$requset->post('delivery');
            $pay=$requset->post('pays');
            $address=new Address();
            $address=Address::findOne(['id'=>$address_id,'member_id'=>$member_id]);

            //添加地址信息
            $order->member_id=$member_id;

            $order->name=$address->name;

            $order->address=$address->detail;

            $order->province=$address->province;
            $order->city=$address->city;
            $order->area=$address->area;
            $order->tel=$address->phone;

            //添加邮寄信息
            $order->delivery_id=$delivery;
            $order->delivery_name=Order::$delivery[$order->delivery_id][0];
            $order->delivery_price=Order::$delivery[$order->delivery_id][1];

            //添加付钱方式
            $order->payment_id=$pay;
            $order->payment_name=Order::$pay[$order->payment_id][0];

            $order->total=$order->delivery_price;
            $order->status=1;
            $order->create_time=time();
            //开启事务
            $transaction=\Yii::$app->db->beginTransaction();
            try{
                if($order->save()){
                    $carts=Cart::find()->where(['member_id'=>$member_id])->all();

                    foreach($carts as $cart){
                        $goods=Goods::find()->where(['id'=>$cart->goods_id])->one();
//                        var_dump($cart->goods_id);die;
                        //判断库存是否足够
                        if($cart->amount > $goods->stock){
//                            return 0;
//                            return $this->redirect(['goods/cart']);
                            throw new Exception($goods->name.'商品库存不足');
                        }
                        $order_goods=new OrderGoods();
                        $order_goods->order_id=$order->id;
                        $order_goods->goods_id=$cart->goods_id;
                        $order_goods->goods_name=$goods->name;
                        $order_goods->logo=$goods->logo;
                        $order_goods->price=$goods->shop_price;
                        $order_goods->amount=$cart->amount;
                        $order_goods->total=$order_goods->price * $order_goods->amount;

                        $a=$order_goods->save();

                        $order->total+=$order_goods->total;//订单金额累加
                        Goods::updateAllCounters(['stock'=>-$cart->amount],['id'=>$cart->goods_id]);
//                        echo 2;die;echo 2;die;
                    }


                    Cart::deleteAll(['member_id'=>$member_id]);

                    $order->save();

                }
                $transaction->commit();
                return 1;
            }catch (Exception $e){
                //
                $transaction->rollBack();
                return $e->getMessage();exit;

            }

        }

    return $this->render('order');

    }
    //订单列表
    public function actionList(){
        $member_id=\Yii::$app->user->id;
        $orders=Order::findAll(['member_id'=>$member_id]);
        $order=ArrayHelper::map($orders,'id','id');


        $order_goods=OrderGoods::find()->where(['in','order_id',$order])->all();
        $order_goods=ArrayHelper::map($order_goods,'order_id','logo');

//        var_dump($order_goods);die;

        return $this->render('list',['orders'=>$orders,'order_goods'=>$order_goods]);
    }
}