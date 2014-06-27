<div>
    <span id="num_friend" class="numNotis">1</span>
    <div id="friend_notis" style="display: none">
        <ul>
            <?php foreach($friendNotis as $noti):?>
            <a href="/index.php/friendship/friendship/index">
        	<li>
                <img src="/<?php echo $noti['from_avatar']; ?>" alt="" width="32px" height="32px" />
                <?php echo $noti['from_user_name'].':'.$noti['message']; ?>
            </li>
            </a>
            <?php endforeach;?>
        </ul>
    </div>
    
    <span id="num_email" class="numNotis">1</span>
	<div id="mail_notis" style="display: none">
        <ul>
            <?php foreach($emailNotis as $noti):?>
            <a href="/index.php/message/message/index">
            <li>
                <img src="/<?php echo $noti['from_avatar']; ?>" alt="" width="32px" height="32px" />
                <?php echo $noti['from_user_name'].':'.$noti['message']; ?>
            </li>
            </a>
            <?php endforeach;?>
        </ul>
    </div>
    
    <span id="num_trip" class="numNotis">1</span>
    <div id="trip_notis" style="display: none">
        <ul>
            <?php foreach($tripNotis as $noti):?>
            <a href="/index.php/trip/view/?id=<?php echo $noti['trip_id']; ?>">
            <li>
                <img src="/<?php echo $noti['from_avatar']; ?>" alt="" width="32px" height="32px" />
                <?php echo $noti['from_user_name'].':'.$noti['message']; ?>
            </li>
            </a>
            <?php endforeach;?>
        </ul>
    	<!-- <div>Tania: comment on Hanoi-Thai Nguye</div>
    	<div>demo: Join Hanoi-Thai Nguyen</div>
    	<div>Longx: Leave Hanoi-Thai Nguyen</div>
    	<div>admin(Owner): Leave Hanoi-Thai Nguyen</div> -->
    </div>
</div>	
