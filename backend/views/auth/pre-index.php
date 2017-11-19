
<!--引入Javascript / CSS （CDN）-->
<?php
//注册css文件
$this->registerCssFile('@web/DataTables/media/css/jquery.dataTables.css');
//$this->registerJsFile('@web/DataTables/media/js/jquery-1.10.2.min.js');
$this->registerJsFile('@web/DataTables/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className(),//指定依赖关系.js必须在jquery后面加载(依赖于jquery)
    //'position'=>\yii\web\View::POS_END//指定加载文件的位置
]);

?>
<h2>权限列表</h2>
<table id="table_id_example" class="table table-bordered">
    <thead>
    <tr>
        <td>权限名</td>
        <td>描述</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($pres as $pre):?>

        <tr>
            <td><?=$pre->name?></td>
            <td><?=$pre->description?></td>
            <td>
                <?php if(Yii::$app->user->can('auth/pre-edit')){
                echo \yii\bootstrap\Html::a('编辑',['auth/pre-edit','name'=>$pre->name],['class'=>'btn btn-warning']);}?>
                <?php if(Yii::$app->user->can('auth/pre-del')){
                echo \yii\bootstrap\Html::button('删除',['class'=>'dels btn  btn-danger']);}?>
            </td>
        </tr>

    <?php endforeach;?>
    </tbody>

</table>

<!-- jQuery -->

<?php
$url = \yii\helpers\Url::to(['auth/pre-del']);
$this->registerJs(<<<JS
    <!--初始化Datatables-->

    $('#table_id_example').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
});


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