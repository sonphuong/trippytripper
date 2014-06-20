<?php
$this->pageTitle=Yii::app()->name . ' - index';
$this->breadcrumbs=array(
	'Home',
);
$this->widget('nfy.extensions.webNotifications.WebNotifications', array('url'=>$this->createUrl('/nfy/default/poll')));
?>
