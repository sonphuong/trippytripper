<div class="form" id="search">
    <?php $form = $this->beginWidget('CActiveForm'); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="cell"><?php echo $form->textField($model, 'from', array('id' => 'goFrom', 'class' => 'from', 'value'=>$fromVal)); ?></div>
        <div class="cell"><?php echo $form->textField($model, 'to', array('id' => 'goTo', 'class' => 'to', 'value'=>$toVal)); ?></div>
        
        <div class="cell"><?php
        $this->widget('application.widgets.timepicker.timepicker', array(
            'model' => $model,
            'name' => 'leave',
            'options' => array(
                'showOn' => 'focus',
                'dateFormat' => 'yy-mm-dd',
                'timeFormat' => 'hh:mm',
                'placeholder' => 'Departure date'
            ),
        ));
        ?></div>
        <div class="cell"><?php
        $this->widget('application.widgets.timepicker.timepicker', array(
            'model' => $model,
            'name' => 'return',
            'options' => array(
                'showOn' => 'focus',
                'dateFormat' => 'yy-mm-dd',
                'timeFormat' => 'hh:mm',
                'placeholder' =>'Return date',
            ),
        ));
        ?></div>
        <?php //echo $form->error($model,'date_from'); ?>
        <div class="cell">
            <input type="submit" class="orangeButton" value="<?php echo Yii::t('translator','Search');?>">
        </div>
    </div>
<?php $this->endWidget(); ?>
</div>
<hr/>
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_list', 
    'template' => '{summary} {sorter} {items} <div style="clear:both;"></div> {pager}',
    'sortableAttributes'=>array(
        'username',
        'lastvisit',
    ),
));
?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo Yii::app()->params['GOOGLE_API_KEY']; ?>&sensor=true&libraries=places"></script>
<script type="text/javascript">
    var fromOptions = {
        types: ['(cities)'],
        //componentRestrictions: {country: "us"}
    };
    var mapOptions = {};
    makeAutoComplete('goFrom',fromOptions);
    makeAutoComplete('goTo',mapOptions);
</script>
