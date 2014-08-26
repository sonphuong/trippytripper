<?
$this->breadcrumbs = array(
	Yum::t('Usergroups'),
	Yum::t('Browse'),
	);
?>

<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_view',
			'template' => '{sorter} {summary} <div style="clear:both;"></div> {items} <div style="clear:both;"></div> {pager}',
			)); ?>

<?php echo CHtml::link(Yum::t('Create new Usergroup'), array(
			'//usergroup/groups/create')); ?>
