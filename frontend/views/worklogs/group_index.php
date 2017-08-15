<script type="text/javascript">
    var interval = null;
</script>
<?php


$this->title = 'My Yii Application';

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = 'group worklogs';


$dataProvider = new yii\data\ArrayDataProvider([
    'allModels' => $queue,
    'pagination' => [
        'pageSize' => 50,
    ],
]);

    if(!isset($jqls) && !\yii::$app->request->isAjax)
        echo '<div class="jumbotron">';
        
    if (!\yii::$app->request->isAjax)
        echo $this->render('group_jql_input',['queue'=> array_shift($queue)]);
    
    if(!isset($jqls) && !\yii::$app->request->isAjax)
        echo '</div>';
    
if(isset($Queue_new) && !empty($Queue_new->errors)){
    foreach ($Queue_new->errors as $key => $error) { ?>
        
    <div class="alert alert-danger">
        <?= $key.':'.(implode('.', $error)) ?>
    </div>        
        
    <?php }    
}

?>

<?php if (!\yii::$app->request->isAjax) { ?>
<div class="group-index">
<?php } ?>
<?=yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'format' => 'raw',
            'header' => 'status',
            'value'=>function($data) {
                return $data['status'];
            },
        ],
        [
            'format' => 'html',
            'header' => 'link',
            'value'=>function($data) {
                if('done'===$data['status'])
                    return '<a href="queue/'.$data['id'].'" >result</span>';
                return '';
            },
        ],
        [
            'format' => 'html',
            'header' => 'xls',
            'value'=>function($data) {
                if('done'===$data['status'])
                    return '<a href="queue/'.$data['id'].'?toxls=1" >xls</span>';
                return '';
            },
        ],
        
        'progress',
        'issues',
        'worklogs',
        [
            'format' => 'raw',
            'header' => 'estimation',
            'value'=>function($data) {
                return timeconvert($data['estimation']);
            },
        ],
        [
            'format' => 'raw',
            'header' => 'spend',
            'value'=>function($data) {
                return timeconvert($data['spend']);
            },
        ],
        [
            'format' => 'raw',
            'header' => 'jql',
            'value'=>function($data) {
                return json_decode($data['data'],true)['jql'];
            },
        ],
                    
        [
            'format' => 'raw',
            'header' => 'date from',
            'value'=>function($data) {
                return date('Y m d',$data['dataFrom']);
            },
        ],
        [
            'format' => 'raw',
            'header' => 'date to',
            'value'=>function($data) {
                return date('Y m d',$data['dataTo']);
            },
        ],
        [
            'format' => 'raw',
            'header' => 'create at',
            'value'=>function($data) {
                return date('Y m d h:i',$data['created_at']);
            },
        ],
        [
            'format' => 'raw',
            'header' => 'updated at',
            'value'=>function($data) {
                return date('Y m d h:i',$data['updated_at']);
            },
        ],
                    
    ],
]); ?>


<?php if (!\yii::$app->request->isAjax) { ?>
</div>
<?php } ?>

<?php if (!\yii::$app->request->isAjax){

    
$js = <<<js
        
function func() {
    $.get( "?", function( data ) {
        $( ".group-index" ).html( data );
    });    
}
interval = setInterval(func, 1000);
js;
        
        
$this->registerJs($js, \yii\web\View::POS_READY);
}
?>