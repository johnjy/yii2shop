<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Menu extends ActiveRecord{

    public function rules()
    {
        return [
            [['label','parent_id'],'required'],
            ['url','safe'],
            ['label','unique','message'=>'该菜单已存在'],
            ['sort','required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'label'=>'菜单名称',
            'parent_id'=>'上级菜单',
            'sort'=>'排序',
            'url'=>'路由',
        ];
    }
    //自定义添加规则
    public function add(){
        $menu=Menu::findOne(['label'=>$this->label]);
        if($menu){
            $this->addError('label','菜单已存在');
        }
    }


    //-级菜单和二级菜单的关系
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);

    }

}