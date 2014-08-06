<div class="form" id="join_div">
<form method="post" name="join_form">
	<input type="hidden" value="<?php echo $_GET['id']; ?>" name="trip_id">
	<div>
		<?php echo CHtml::ajaxSubmitButton ("Join",
				CController::createUrl('trip/join'), 
				array('update' => '#join_div'),
				array('class'=>'orangeButton')
				);
		?>
	</div>
</form>
</div>