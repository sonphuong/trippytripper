<?php 
Yii::app()->clientScript->registerCssFile(
		Yii::app()->getAssetManager()->publish(
			Yii::getPathOfAlias('YumModule.assets.css').'/yum.css'));

$module = Yum::module();
$this->beginContent($module->baseLayout); ?>

<div class="container">
		<div id="usermenu">
		<?php Yum::renderFlash(); ?>
		<?php $this->renderMenu(); ?>
		</div>
	<div id="yumcontent">
		<div id="usercontent">
		<?
		if (Yum::module()->debug) {
			echo CHtml::openTag('div', array(
						'style' => 'background-color: red;color:white;padding:5px;'));
			echo Yum::t(
					'You are running the Yii User Management Module {version} in Debug Mode!', array(
						'{version}' => Yum::module()->version));
			echo CHtml::closeTag('div');
		}
		?>

		<?php 
		if($this->title)
			printf('<h2> %s </h2>', $this->title);  ?>
			<?php echo $content;  ?>
		</div>
	</div>

</div>
<?php $this->endContent(); ?>
