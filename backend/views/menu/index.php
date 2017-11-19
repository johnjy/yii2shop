<h2>菜单列表</h2>
<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>名称</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($lists as $list):?>
        <tr>
            <td><?=$list->id?></td>
            <td> <?=($list->parent_id>0?str_repeat('----',1):'').$list->label ?></td>
            <td><?=$list->sort?></td>
            <td>
                <?php if(Yii::$app->user->can('menu/edit')){
                echo \yii\bootstrap\Html::a('编辑',['menu/edit','id'=>$list->id],['class'=>'btn btn-warning']);}?>
                <?php if(Yii::$app->user->can('menu/del')){
                echo \yii\bootstrap\Html::button('删除',['class'=>'dels btn  btn-danger']);}?>
            </td>
        </tr>
    <?php endforeach;?>

</table>
<?php

$url = \yii\helpers\Url::to(['menu/del']);
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
                    alert('该菜单下有子菜单,删除失败');
                }
            })
        }
    })
JS
);