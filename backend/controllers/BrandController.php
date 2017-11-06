<?php
namespace backend\controllers;

use backend\models\Brand;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
//-------------------------------------
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends Controller{
    public $enableCsrfValidation=false;
    //品牌列表
    public function actionIndex(){
        $require=Brand::find();
        $pages=new Pagination();
        //统计数据条数
        $pages->totalCount=$require->count();
        $pages->pageSize=2;
        $lists=Brand::find()->where(['status'=>0])->orWhere(['status'=>1])->limit($pages->limit)->offset($pages->offset)->all();

//        var_dump($lists);die;
        return $this->render('index',['lists'=>$lists,'pages'=>$pages]);
    }

    //品牌添加
    public function actionAdd(){

        $model=new Brand();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
//                $ext=$model->imgFile->extension;
//                $file='/upload/'.uniqid().'.'.$ext;
//                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
//                $model->logo=$file;
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }

    //处理ajax图片上传
    public function actionUpload()
    {

        if (\Yii::$app->request->isPost) {

            $imgFile = UploadedFile::getInstanceByName('file');

            //判断是否有文件上传
            if ($imgFile) {
                $fileName = '/upload/' . uniqid() . '.' . $imgFile->extension;
                $imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, 0);

//                return Json::encode(['url'=>$fileName]);
//            // 需要填写的 Access Key 和 Secret Key
                $accessKey ="1Hv2YibPiCRPjmkICHDcLVr6anGHm6BzWmKYvtkA";
                $secretKey = "yjGoA3qa3hSRlOxtKDl-TXuPBOpWToY2K6aLYeUt";
                $bucket = "yii2shop";
                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);
                // 生成上传 Token
                $token = $auth->uploadToken($bucket);
                // 要上传文件的本地路径
                $filePath = \Yii::getAlias('@webroot').$fileName;
                // 上传到七牛后保存的文件名
                $key = $fileName;
                $domian='oyxe9r27m.bkt.clouddn.com';
                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();
                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//                echo "\n====> putFile result: \n";
                if ($err !== null) {
//                    var_dump($err);
                    return Json::encode(['error'=>$err]);
                } else {
//                    var_dump($ret);

                    return Json::encode(['url'=>'http://'.$domian.'/'.$fileName]);

                }
                // -------------------------------------

            }
        }
    }



    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className()
            ]
        ];
    }
    //品牌修改
    public function actionEdit($id){

        $model=new Brand();
        $request=new Request();
        $model=Brand::findOne(['id'=>$id]);
        if($request->isPost){
            $model->load($request->post());
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            if($model->validate()){
//                $ext=$model->imgFile->extension;
//                $file='/upload/'.uniqid().'.'.$ext;
//                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
//                $model->logo=$file;
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
            }
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }
    //品牌删除
    public function actionDel(){
        $requset=new Request();
        $id=$requset->post('id');
        $del=Brand::findOne(['id'=>$id]);
        $del->status=-1;
        $del->save(false);
        return 1;
    }

}