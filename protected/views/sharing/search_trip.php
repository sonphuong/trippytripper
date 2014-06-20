<div class="header-main-block">
    <h2>Route</h2>
</div>
<div class="form search_box">
    <?php $form = $this->beginWidget('CActiveForm'); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->textField($model, 'from', array('id' => 'goFrom', 'class' => 'from', 'value'=>$fromVal)); ?>
    </div>
    <div class="row"><?php echo Yii::t('translator','Example: Hanoi, Vietnam');?></div>
    <div class="row">
        <?php echo $form->textField($model, 'to', array('id' => 'goTo', 'class' => 'to', 'value'=>$toVal)); ?>
    </div>

    <div class="row"><?php echo Yii::t('translator','Departure date');?>:</div>
    <div class="row">
        <?php
        $this->widget('application.widgets.timepicker.timepicker', array(
            'model' => $model,
            'name' => 'leave',
            'options' => array(
                'showOn' => 'focus',
                'dateFormat' => 'yy-mm-dd',
                'timeFormat' => 'hh:mm',
            ),
        ));
        ?>
        <?php //echo $form->error($model,'date_from'); ?>
    </div>
    <div class="row"><?php echo Yii::t('translator','Return date');?>:</div>
    <div class="row">
        <?php
        $this->widget('application.widgets.timepicker.timepicker', array(
            'model' => $model,
            'name' => 'return',
            'options' => array(
                'showOn' => 'focus',
                'dateFormat' => 'yy-mm-dd',
                'timeFormat' => 'hh:mm',
            ),
        ));
        ?>
    </div>
    <div class="row"/>
    <input type="submit" value="<?php echo Yii::t('translator','Search');?>"></div>
<?php $this->endWidget(); ?>
</div>
<hr/>
<div class="row"><?php $this->renderPartial('/sharing/_list', array('allTrips' => $allTrips,)); ?></div>
<?php $this->widget('CLinkPager', array('pages' => $pages,)); ?>



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
