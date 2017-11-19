<h2>角色列表</h2>
<table class="table table-bordered">
    <tr>
        <td>角色</td>
        <td>描述</td>

        <td>添加时间</td>
        <td>操作</td>
    </tr>
    <?php foreach($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>

            <td><?=date('Y-m-d H:i:s',$role->createdAt)?></td>
            <td>
                <?php if(Yii::$app->user->can('auth/role-edit')){
                echo \yii\bootstrap\Html::a('编辑',['auth/role-edit','name'=>$role->name],['class'=>'btn btn-warning']);}?>
                <?php if(Yii::$app->user->can('auth/role-del')){
                echo \yii\bootstrap\Html::button('删除',['class'=>'dels btn  btn-danger']);}?>
            </td>
        </tr>
    <?php endforeach;?>

</table>
<?php
    $url = \yii\helpers\Url::to(['auth/role-del']);
    $this->registerJs(<<<JS
        $('table').on('click','.dels',function() {
    if(confirm('是否删除')){
    var name = $(this).closest('tr').find("td:first").text();
    var that = this;
    var url = "{$url}";
    $.post(url,{name:name},function(data) {
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