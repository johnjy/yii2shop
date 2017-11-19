
<?php if(Yii::$app->user->can('goods/gallery-add')){
    echo \yii\bootstrap\Html::a('添加图片',['goods/gallery-add','id'=>$id],['class'=>'btn btn-warning']);}?>

<table class="table table-bordered">
    <?php foreach($photoes as $photoe):?>
        <tr>

            <td id="<?=$photoe->id?>"><?=\yii\bootstrap\Html::img($photoe->path)?></td>
            <td>
                <?php if(Yii::$app->user->can('goods/gallery-del')){
                echo \yii\bootstrap\Html::button('删除',['class'=>'dels btn  btn-danger']);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
    $url = \yii\helpers\Url::to(['goods/gallery-del']);
    $this->registerJs(
        <<<JS
        $('table').on('click','.dels',function() {
    if(confirm('是否删除')){
    //var id = $(this).closest('tr').find("td:first").text();
    var id =$(this).closest('tr').find("td:first").attr('id');

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
