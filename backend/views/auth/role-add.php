<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo '<h2>角色添加</h2>';

//    echo $form->field($model,'name',['readonly'=>1])->textInput();

echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo $form->field($model,'permissions',['inline'=>1])->checkboxList($permissions);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();