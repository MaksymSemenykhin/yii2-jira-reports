<?php
use kartik\datetime\DateTimePicker;

$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>

<form action="" method="post"  >
    <input type="hidden" name="_csrf" value="<?=  Yii::$app->request->getCsrfToken()?>" />

<div class="row">

    <div class="col-lg-3">
    </div>

    <div class="col-lg-2">
        <label>
        JQL to search issues:
        </label>
    </div>

    <div class="col-lg-3">
        <input name='jql'  required="required" type="text" value="<?=isset($queue)? json_decode($queue['data'],true)['jql']:''?>"style="width: 100%"/>
    </div>
    <div class="col-lg-1">
        <input type='submit' name='submit' />
    </div>

    <div class="col-lg-3">
    </div>

</div>
    <br />
<div class="row">
    <div class="col-lg-1">
    </div>
    <div class="col-lg-4">
<label>Start Date</label>
        
<div class="form-group">
    <div class='input-group date' id='datetimepicker1'>
        <input type='text' class="form-control" name='datafrom' required="required" value="<?=isset($queue)? date('YYYY-mm-dd',$queue['dataFrom']):''?>"/>
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div> 
     
        


<?php
$this->registerJs("$('#datetimepicker1').datetimepicker({format: 'YYYY-MM-DD'});", \yii\web\View::POS_READY);
$this->registerJs("$('#datetimepicker2').datetimepicker({format: 'YYYY-MM-DD'});", \yii\web\View::POS_READY);
?>
    </div>
    <div class="col-lg-2">
    </div>
    <div class="col-lg-4">
        
<label>End Date</label>
<div class="form-group">
    <div class='input-group date' id='datetimepicker2'>
        <input type='text' class="form-control" name='datato' required="required" value="<?=isset($queue)? date('YYYY-mm-dd',$queue['dataTo']):''?>" />
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar"></span>
        </span>
    </div>
</div> 
        
    </div>
    <div class="col-lg-1">
    </div>
    
</div>


</form>

