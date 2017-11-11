<?php
namespace backend\models;

use yii\base\Model;


class RoleForm extends Model{
    public $description;
    public $permissions;
    public $name;
    public $oldname;

    //场景    必须对应验证规则
        //定义常量
    const   SCENARIO_Add ='add';
    const   SCENARIO_Edit='edit';



    public function rules()
    {
        return [//验证规则没有定义场景则所有场景生效
            [['name','description'],'required'],

            [['permissions'],'safe'],
            //自定义添加验证规则\on表示在该场景生效
            ['name','validateName','on'=>[self::SCENARIO_Add]],//添加生效,修改不生效
                    //随意命名
            ['name','validateUpdateName','on'=>[self::SCENARIO_Edit]],
        ];
    }

    public function validateName(){
        //自定义添加验证方法 只处理验证失败的情况
        $auth=\Yii::$app->authManager;
        $model=$auth->getPermission($this->name);
        if($model){
            //权限已存在
            $this->addError('name','权限已存在');
        }
    }
    //自定义修改验证方法
    public function validateUpdateName(){
        //只处理验证失败的情况 名称被修改新名称已存在
        $auth=\Yii::$app->authManager;
        if($this->oldname !=$this->name){
            $model=$auth->getPermission($this->name);
            if($model){
                $this->addError('name','权限已存在');
            }
        }


    }


    public function attributeLabels()
    {
        return[
            'name'=>'名称(路由)',
            'description'=>'描述',
            'permissions'=>'权限',
        ];
    }
    public function update($name){
        $auth=\Yii::$app->authManager;
        $premission=$auth->getPermission($name);

    }
}