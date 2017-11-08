<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();