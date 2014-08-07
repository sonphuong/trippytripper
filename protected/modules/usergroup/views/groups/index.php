<?
$this->breadcrumbs = array(
	Yum::t('Usergroups'),
	Yum::t('Browse'),
	);
?>
<div class="boxTitle"><?php echo Yii::t('translator','My Groups');?></div>
<div class="boxContent">

<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_view',
			)); ?>

<?php echo CHtml::link(Yum::t('Create new Usergroup'), array(
			'//usergroup/groups/create')); ?>
</div>