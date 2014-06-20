<?php
$this->pageTitle=Yii::app()->name . ' - index';
$this->breadcrumbs=array(
	'Home',
);
// $channel = new NfyChannels;
// $channel->name = 'test';
// $channel->route_class = 'MyDbRoute';
// $channel->message_template = 'Attribute changed from {old.attribute} to {new.attribute}';
// $channel->save();
// $subscription = new NfySubscriptions;
// $subscription->user_id = Yii::app()->user->getId();
// $subscription->channel_id = $channel->id;
// $subscription->save();
// send one message 'test'
// Yii::app()->queue->send('test');
// // receive all available messages without using subscriptions and immediately delete them from the queue
// $messages = $queue->receive();
$this->widget('nfy.extensions.webNotifications.WebNotifications', array('url'=>$this->createUrl('/nfy/default/poll', array('id'=>'queueComponentId'))));

?>
