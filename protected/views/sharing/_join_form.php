<div class="form" id="join_div">
<form method="post" name="join_form">
	<div class="row">
		<textarea name="content" cols="30" rows="3"></textarea>
		<input type="hidden" value="<?php echo $_GET['id']; ?>" name="ride_id">
	</div>
	<div class="row buttons">
		<?php echo CHtml::ajaxSubmitButton ("JOIN",
                          CController::createUrl('sharing/join'), 
                          array('update' => '#join_div')
                          );
		?>
	</div>
</form>
</div>