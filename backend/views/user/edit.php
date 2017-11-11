<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'status',['inline'=>1])->radioList([10=>'启用',0=>'禁用']);
echo $form->field($model,'roles',['inline'=>1])->checkboxList($roles);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();