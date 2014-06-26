<div class="form" id="join_div">
<form method="post" name="join_form">
	<input type="hidden" value="<?php echo $_GET['id']; ?>" name="trip_id">
	<div class="row buttons">
		<?php echo CHtml::ajaxSubmitButton ("JOIN",
                          CController::createUrl('trip/join'), 
                          array('update' => '#join_div')
                          );
		?>
	</div>
</form>
</div>