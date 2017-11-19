<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m171113_100244_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('姓名'),
            'province'=>$this->string()->comment('省'),
            'city'=>$this->string()->comment('市'),
            'area'=>$this->string()->comment('地区'),
            'detail'=>$this->string()->comment('详细地址'),
            'phone'=>$this->string()->comment('手机号码'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
