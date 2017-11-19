<h2>商品分类列表</h2>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    <?php foreach($lists as $list):?>
        <tr>

            <td><?=$list->id?></td>
            <td> <?=str_repeat('----',$list->depth).$list->name ?></td>
            <td>
                <?php if(Yii::$app->user->can('goods-category/edit-category')){
                echo \yii\bootstrap\Html::a('修改',['goods-category/edit-category','id'=>$list->id],['class'=>'btn btn-info']);}?>
                <?php if(Yii::$app->user->can('goods-category/del-category')){
                echo \yii\bootstrap\Html::button('删除',['class'=>'dels btn  btn-info']);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
$url = \yii\helpers\Url::to(['goods-category/del-category']);
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
                    alert('删除失败,请先删除子分类');
                }
            })
        }
    })
JS
);
echo \yii\widgets\LinkPager::widget(['pagination'=>$pages]);