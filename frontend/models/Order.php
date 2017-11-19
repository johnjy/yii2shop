<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord{
    public static $delivery=[
        1=>['普通快递送货上门',15,],
        2=>['特快专递',40,],
        3=>['加急快递送货上门',40,],
        4=>['平邮',15,],
    ];
    public static $pay=[
        1=>['货到付款','送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        2=>['在线支付','	即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        3=>['上门自提','自提时付款，支持现金、POS刷卡、支票支付'],
        4=>['邮局汇款','通过快钱平台收款 汇款后1-3个工作日到账'],
    ];
    public function rules()
    {
        return [
            ['member_id','required'],
            ['name','required'],
            ['province','required'],
            ['city','required'],
            ['area','required'],
            ['address','required'],
            ['tel','required'],
            ['delivery_id','required'],
            ['delivery_name','required'],
            ['delivery_price','required'],
            ['payment_id','required'],
            ['payment_name','required'],
            ['total','required'],
            ['status','required'],
            ['create_time','required'],
        ];
    }
}
