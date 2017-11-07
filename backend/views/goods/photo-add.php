
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
<div><img id="img" width="100" /></div>
<?php
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),//指定依赖关系,webuploader.js必须在jquery后面加载(依赖于jquery)
//'position'=>\yii\web\View::POS_END//指定加载文件的位置
]);
$url = \yii\helpers\Url::to(['uploads']);
$this->registerJs(
    <<<JS

// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/js/Uploader.swf',

    // 文件接收服务端。
    server: '{$url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
    title: 'Images',
    extensions: 'gif,jpg,jpeg,bmp,png',
    mimeTypes: 'image/jpg,image/jpeg,image/png',//弹出选择框慢的问题

    }
    });
    //文件上传成功  回显图片
    uploader.on( 'uploadSuccess', function( file ,response) {
    //$( '#'+file.id ).addClass('upload-state-done');
    //response.url  //上传成功的文件路径
    //将图片地址赋值给img
    //console.log(file);
    // console.log(response);
    $("#img").attr('src',response.url);
    //将图片地址写入logo
   $("#goodsgallery-path").val(response.url);


});
JS

);

$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'path')->hiddenInput();
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
