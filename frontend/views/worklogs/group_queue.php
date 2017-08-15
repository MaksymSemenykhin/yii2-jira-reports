<?php

/* @var $this yii\web\View */

//        echo '<pre>';
//        print_r(json_decode($Queue->data,true)['users']);
//        exit;
if($toxls){
    header('Content-type: application/excel');
    $filename = 'filename.xls';
    header('Content-Disposition: attachment; filename='.$filename);
}

       
$dataProvider = new yii\data\ArrayDataProvider([
    'allModels' => json_decode($Queue->data,true)['users'],
    'pagination' => [
        'pageSize' => 50,
    ],
    'sort' => [
        'attributes' => ['name'],
    ],
]);


$this->title = 'My Yii Application';

$this->params['breadcrumbs'] = [];
$this->params['breadcrumbs'][] = ['label' => 'group worklogs', 'url' => ['worklogs/group']];
$this->params['breadcrumbs'][] = 'queue';
echo '<p>'.json_decode($Queue->data,true)['jql']."</p>";
?> 

<div class="group-queue">
<?=yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'format' => 'html',
            'header' => 'Issue',
            'value'=>function($data) {
                return '<a href="https://isddesign.atlassian.net/browse/'.$data['key'].'" >'.$data['key'].'</a>';
            },
        ],
        'name',
        [
            'format' => 'raw',
            'header' => 'Spent time ',
            'value'=>function($data) {
                return timeconvert($data['timeSpentSeconds']);
            },
        ],        
    ],
]); ?>

