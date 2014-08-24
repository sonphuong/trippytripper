<?php $this->beginContent('/layouts/main'); ?>
<div class="container">
	<div id="content">
		<div class="boxTitle"><?php echo Yii::t('translator',$this->pageTitle);?></div>
		<div class="boxContent">
		<?php echo $content; ?>
		</div>
	</div><!-- content -->
</div>
<?php $this->endContent(); ?>