<?
$this->breadcrumbs=array(
	Yum::t('Usergroups')=>array('index'),
	Yum::t('Create'),
);

?>

<?php $this->title = Yum::t('Create Usergroup'); ?> 
<?
$this->renderPartial('_form', array(
			'model' => $model,
			'buttons' => 'create'));

?>

