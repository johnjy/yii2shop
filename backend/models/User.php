<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface{
    public  $password;
    public $roles;
    public function attributeLabels()
    {
        return [
          'username'=>'姓名',
            'password'=>'密码',
            'email'=>'邮箱',
            'status'=>'状态'
        ];
    }

    public function rules()
    {
        return [
            [['username','password','status'],'required'],
            ['email','email'],
            ['roles','required'],
        ];
    }
    public function checkuser(){
        $admin=User::findOne(['username'=>$this->username]);
        $email=User::findOne(['email'=>$this->email]);

        if($admin){

                $this->addError('username','用户名已存在');
                return false;

        }elseif($email){
            $this->addError('email','邮箱已注册');
            return false;
        }
        return true;
    }

    public function check(){
        $user=User::find()->where(['!=','id',$this->id])->andWhere(['username'=>$this->username])->one();
        $email=User::find()->where(['!=','id',$this->id])->andWhere(['email'=>$this->email])->one();

        if($user){
            $this->addError('username','用户已存在');
            return false;
        }
        if($email){
            $this->addError('email','邮箱已注册');
            return false;
        }
        return true;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
        // TODO: Implement findIdentity() method.
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
        // TODO: Implement getId() method.
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
        // TODO: Implement validateAuthKey() method.
    }

    public function getMenus(){

        $menuItems=[];
        //获取一级菜单
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        foreach($menus as $menu){
            $items=[];
            //遍历该一级菜单的子菜单
            foreach($menu->children as $child){
                if(\Yii::$app->user->can($child->url)){
                    $items[]=['label'=>$child->label,'url'=>[$child->url]];

                }
            }
            $menuItem =['label'=>$menu->label,'items'=>$items];
            //将该组菜单放入菜单组
            if($items){
                $menuItems[]=$menuItem;
            }
        }
        return $menuItems;
    }
}