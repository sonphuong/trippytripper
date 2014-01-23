<div class="header-main-block">
    <h2>Route</h2>
</div>
<div class="form search_box">
    <?php $form=$this->beginWidget('CActiveForm'); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->textField($model,'from',array('id'=>'goFrom', 'class'=>'from')); ?>
    </div>
    <div class="row">Example: Hanoi, Vietnam</div>
    <div class="row">
        <?php echo $form->textField($model,'to',array('id'=>'goTo', 'class'=>'to')); ?>
    </div>

    <div class="row">Departure date:</div>
    <div class="row">
    <?php 
    $this->widget('application.widgets.timepicker.timepicker', array(
        'model'=>$model,
        'name'=>'leave',
        'options' => array(
            'showOn'=>'focus',
            'dateFormat'=>'yy-mm-dd',
            'timeFormat'=>'hh:mm',
        ),
    ));
    ?>
    <?php //echo $form->error($model,'date_from'); ?>
    </div>
    <div class="row">Return date:</div>
    <div class="row">
        <?php 
        $this->widget('application.widgets.timepicker.timepicker', array(
            'model'=>$model,
            'name'=>'return',
            'options' => array(
                'showOn'=>'focus',
                'dateFormat'=>'yy-mm-dd',
                'timeFormat'=>'hh:mm',
            ),
        ));
        ?>
    </div> 
    <div class="row"/><input type="submit" value="Search"></div>
    <?php $this->endWidget(); ?>
</div>
<hr/>
<div class="row"><?php $this->renderPartial('/sharing/_list',array('allRides'=>$allRides,)); ?></div>