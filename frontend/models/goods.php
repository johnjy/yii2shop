<?php
namespace frontend\models;

use backend\models\GoodsGallery;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord{

    public static function getGallery(){
        $html =  '<ul>';
        $gallery=GoodsGallery::find()->where(['goods_id'=>4])->all();
//        var_dump($gallery);die;
        foreach($gallery as $key=>$value){
            $html.='<li'.($key==0?'class="cur"':'').'>
            <a class="zoomThumbActive" href="javascript:void(0);" rel="{gallery:"gal1", smallimage: '.'http://www.adminshop.com/'.$value->path.'}"><img width=54 src="'.'http://www.adminshop.com/'.$value->path.'"></a></li>';
            $html.='</ul>';

    }
        return $html;
    }
}