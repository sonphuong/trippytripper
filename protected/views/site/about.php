<?php
$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
echo Yii::t('translator','About us');
$queue = new NfyDbQueue();
$queue->id = 1;
$queue->subscribe(1);
$send = $queue->send('test');

// receive all available messages for current user and immediately delete them from the queue
//$messages = $queue->receive();

//$this->widget('nfy.extensions.webNotifications.WebNotifications', array('url'=>$this->createUrl('/nfy/default/poll', array('id'=>'queueComponentId'))));

?>
<?php $this->widget('nfy.extensions.webNotifications.WebNotifications', array(
    'url'=>'ws://ws.pusherapp.com:80/app/XXXclient=javascript&protocol=6',
    'method'=>WebNotifications::METHOD_PUSH,
    'websocket'=>array(
        'onopen'=>'js:function(socket){return function(e) {
            socket.send(JSON.stringify({
                "event": "pusher:subscribe",
                "data": {"channel": "test_channel"}
            }));
        };}',
        'onmessage'=>'js:function(_socket){return function(e) {
            var message = JSON.parse(e.data);
            var data = JSON.parse(message.data);
            if (typeof data.title != "undefined" && typeof data.body != "undefined") {
                notificationsPoller.addMessage(data);
                notificationsPoller.display();
            }
        };}',
    ),
)); ?>