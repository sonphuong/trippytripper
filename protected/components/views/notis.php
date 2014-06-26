<div>
    <span id="num_friend" style="color: red;font-size: bold;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/friend_icon.png"/> 1</span>
    <div id="friend_notis" style="display: none">
        <?php foreach($friendNotis as $friendNoti):?>
    	<div>Tania: <?php echo $friendNoti['message'];?></div>
        <?php endforeach;?>
    </div>
    
    <span id="num_email" style="color: red;font-size: bold;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icon_email.gif"/>1</span>
	<div id="mail_notis" style="display: none">
    	<?php foreach($emailNotis as $emailNoti):?>
        <div>Tania: <?php echo $emailNoti['message'];?></div>
        <?php endforeach;?>
    </div>
    
    <span id="num_trip" style="color: red;font-size: bold;"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/trip_icon.png"/>1</span>
    <div id="trip_notis" style="display: none">
        <?php foreach($tripNotis as $tripNoti):?>
        <div>Tania: <?php echo $tripNoti['message'];?></div>
        <?php endforeach;?>

    	<!-- <div>Tania: comment on Hanoi-Thai Nguye</div>
    	<div>demo: Join Hanoi-Thai Nguyen</div>
    	<div>Longx: Leave Hanoi-Thai Nguyen</div>
    	<div>admin(Owner): Leave Hanoi-Thai Nguyen</div> -->
    </div>
</div>	