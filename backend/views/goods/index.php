<?php
$form=\yii\bootstrap\ActiveForm::begin(['layout'=>'inline']);
echo $form->field($model,'keyword')->textInput(['style' => 'width : 100px']);
echo $form->field($model,'keyword')->textInput(['style' => 'width : 100px']);
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>货号</th>
        <th>价格</th>
        <th>库存</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach($lists as $list):?>
        <tr>
            <td><?=$list->id?></td>
            <td><?=$list->name?></td>
            <td><?=$list->sn?></td>
            <td><?=$list->shop_price?></td>
            <td><?=$list->stock?></td>
            <td><?=\yii\bootstrap\Html::img($list->logo,['width'=>50])?></td>
            <td>
                <?= \yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$list->id],['class'=>'btn btn-info'])?>
                <?= \yii\bootstrap\Html::a('编辑',['goods/edit','id'=>$list->id],['class'=>'btn btn-warning'])?>
                <?= \yii\bootstrap\Html::button('删除',['class'=>'dels btn  btn-danger'])?>
            </td>
        </tr>
    <?php endforeach;?>
    <?=\yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info'])?>
</table>
<?php

$url = \yii\helpers\Url::to(['goods/del']);
$this->registerJs(<<<JS
    $('table').on('click','.dels',function() {
        if(confirm('是否删除')){
            var id = $(this).closest('tr').find("td:first").text();
            var that = this;
            var url = "{$url}";
            $.post(url,{id:id},function(data) {
                if(data ==1){
                    $(that).closest('tr').fadeOut();
                }else{
                    alert('删除失败');
                }
            })
        }
    })
JS
);
echo  \yii\widgets\LinkPager::widget(['pagination'=>$pages]);