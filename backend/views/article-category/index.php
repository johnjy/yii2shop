<h2>文章分类列表</h2>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($lists as $list):?>
        <tr>
            <td><?=$list->id?></td>
            <td><?=$list->name?></td>
            <td><?=$list->intro?></td>
            <td><?=$list->sort?></td>
            <td>
                <?php if(Yii::$app->user->can('article-category/edit')){
                echo \yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$list->id],['class'=>'btn btn-info']);}?>
                <?php if(Yii::$app->user->can('article-category/del')){
                echo \yii\bootstrap\Html::button('删除',['class'=>'dels btn  btn-info']);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
$url = \yii\helpers\Url::to(['article-category/del']);
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