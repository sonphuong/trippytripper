<?php Yum::register('css/yum.css');
$this->breadcrumbs=array(
		Yum::t('Usergroups')=>array('index'),
		$model->title,
		);
 ?>
<div class="title"> <?php echo $model->title;  ?> </div>
<p> <?php echo $model->description; ?> </p>
<?
if($model->owner)
	printf('%s: %s',
			Yum::t('Owner'),
			CHtml::link($model->owner->username, array(
					'//profile/profile/view', 'id' => $model->owner_id)));
?>
<?php
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$model->getParticipantDataProvider(),
    'itemView'=>'_participant', 
    'template' => '{sorter} {summary} <div style="clear:both;"></div> {items} <div style="clear:both;"></div> {pager}',
)); 
?>
<div style="clear: both;"> </div> 
<?
printf('<h3> %s </h3>', Yum::t('Messages'));
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$model->getMessageDataProvider(),
    'itemView'=>'_message', 
)); 
?>
<?php echo CHtml::link(Yum::t('Write a message'), '', array(
			'onClick' => "$('#usergroup_message').toggle(500)")); ?>
<div style="display:none;" id="usergroup_message">
<?php $this->renderPartial('_message_form', array('group_id' => $model->id)); ?>
</div>
<div style="clear: both;"> </div>
