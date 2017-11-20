<?php
namespace frontend\models;

use backend\models\GoodsGallery;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord{

    public static function getGallery(){

        $goods_id=\Yii::$app->request->get('goods_id');
        $html =  '<ul>';
        $gallery=GoodsGallery::find()->where(['goods_id'=>$goods_id])->all();
//        var_dump($gallery);die;
        $url="http://www.adminshop.com";
        foreach($gallery as $key=>$value){
            $html.='<li'.($key==0?'class="cur"':'').'>';//class="zoomThumbActive"
            $html.='<a '.($key==0?'class="zoomThumbActive"':'').' href="javascript:void(0);" rel="{gallery:\'gal1\', smallimage:\''.str_replace('/','\\',$url.$value->path).'\'}"><img width=54 src="'.$url.$value->path.'"></a></li>';


    }   $html.='</ul>';
        return $html;
    }
}