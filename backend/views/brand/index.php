<h2>品牌列表</h2>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach($lists as $list):?>
        <tr>
            <td><?=$list->id?></td>
            <td><?=$list->name?></td>
            <td><?=\yii\bootstrap\Html::img($list->logo,['width'=>50])?></td>
            <td>
                <?= \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$list->id],['class'=>'btn btn-info'])?>
                <?= \yii\bootstrap\Html::button('删除',['class'=>'dels btn  btn-info'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php

$url = \yii\helpers\Url::to(['brand/del']);
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