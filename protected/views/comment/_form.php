<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>true,
)); ?>
	<?php $avatar = Yum::module("avatar")->getAvatarThumb(Yii::app()->user->id,Yii::app()->user->avatar); ?>
	<div class="row">
		<?php echo $avatar; ?>
		<?php echo $form->textArea($model,'content',array('rows'=>2, 'cols'=>30)); ?>
		<?php echo $form->hiddenField($model,'trip_id',array('value'=>$_GET['id'])); ?>
		<span class="commentButton">
		<?php echo CHtml::ajaxSubmitButton ("Send",
			CController::createUrl('comment/add'), 
			array('success' => 'js:function(data) { 
				                              		$("#commentsList").append(data);
				                              		$("#Comment_content").val("");
												}')
			//array('update' => '#commentsList')
			,array('class' => 'blueButton')
			);
		?>
		</span>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
