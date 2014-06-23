<?php
$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
//echo Yii::t('translator','About us');

$send = Yii::app()->queue->send('test');

// receive all available messages for current user and immediately delete them from the queue
//$messages = Yii::app()->queue->receive();
?>