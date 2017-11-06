<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Goods extends ActiveRecord{
    public $code;
    public $keyword;

    public function attributeLabels()
    {
        return[
            'name'=>'商品名称',
            'logo'=>'LOGO',
            'goods_category_id'=>'商品分类',
            'brand_id'=>'品牌分类',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'is_on_sale'=>'状态',
            'sort'=>'排序',
            'code'=>'验证码',

        ];
    }
    public function rules()
    {
        return [
            [['name','logo','goods_category_id','brand_id',
                'market_price','shop_price','stock','is_on_sale','sort'],'required']
        ];
    }

}