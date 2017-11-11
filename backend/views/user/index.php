<h2>管理员列表</h2>
<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>姓名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>上一次登录时间</th>
        <th>上一次最后登录ip</th>
        <th>操作</th>
    </tr>
    <?php foreach($lists as $list):?>
        <tr>
        <td><?=$list->id?></td>
        <td><?=$list->username?></td>
        <td><?=$list->email?></td>
        <td><?=$list->status==10?'正常':'禁用'?></td>
        <td><?=date('Y-m-d H:i:s',$list->last_login_time)?></td>
        <td><?=$list->last_login_ip?></td>
        <td>
            <?= \yii\bootstrap\Html::a('编辑',['user/edit','id'=>$list->id],['class'=>'btn btn-info'])?>
            <?= \yii\bootstrap\Html::button('删除',['class'=>'dels btn  btn-danger'])?>
        </td>
        </tr>
    <?php endforeach;?>

</table>
<?php


$url = \yii\helpers\Url::to(['user/del']);
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
echo \yii\widgets\LinkPager::widget(['pagination'=>$pages]);