<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>true,
)); ?>
	<div class="row">
		<?php echo $form->textArea($model,'content',array('rows'=>3, 'cols'=>30)); ?>
		<?php echo $form->hiddenField($model,'trip_id',array('value'=>$_GET['id'])); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::ajaxSubmitButton ("Send",
                              CController::createUrl('comment/add'), 
                              array('success' => 'js:function(data) { 
									                              		$("#commentsList").append(data);
																	}')
                              //array('update' => '#commentsList')
                              );
		?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->