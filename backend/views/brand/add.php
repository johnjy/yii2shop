<?php
$from=\yii\bootstrap\ActiveForm::begin();
echo $from->field($model,'name')->textInput();
echo $from->field($model,'intro')->textarea();
echo $from->field($model,'imgFile')->fileInput();
echo \yii\bootstrap\Html::img($model->logo,['width'=>50]);
echo $from->field($model,'sort')->textInput();
echo $from->field($model,'status',['inline'=>1])->radioList([0=>'隐藏',1=>'正常']);
echo $from->field($model,'code')->widget(\yii\captcha\Captcha::className(),['template'=>
    '<div class="row"><div class="col-lg-1">{input}</div><div class="col-lg-1">{image}</div></div>']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();