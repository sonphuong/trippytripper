<?php
$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
?>
<?php echo Yii::t('translator','About us');?>

<?php
$send = Yii::app()->queue->send('test');
// receive all available messages for current user and immediately delete them from the queue
$messages = Yii::app()->queue->receive();
Nfy::log();
var_dump($messages);
?>