
<ul class="ctrl">
    <li><a id ="friend" class="friend" href="#none"></a><span id="friend_num"></span></li>
    <li><a id ="msg" class="msg" href="#none"></a><span id="email_num"></span></li>
    <li><a id ="tripp" class="tripp" href="#none"></a><span id="trip_num"></span></li>
</ul>
<div class="notisContainer">
    <span class="notisArrow" id="friend_index"></span>
    <span class="notisArrow" id="msg_index"></span>
    <span class="notisArrow" id="trip_index"></span>
    <div class="boxNotis notis">
        <div class="boxTitle" id="notisBoxHeader"><?php echo Yii::t('translator','Notifications'); ?></div>
        <div id="notisContent" class="boxContent"></div>
    </div>
</div>
<div style="display:none;">
<div id="friend_notis"></div>
<div id="email_notis"></div>
<div id="trip_notis"></div>
</div>
<?php
    $id = Yii::app()->user->id;
    if(is_numeric($id))
    $user = YumUser::model()->findByPk($id);
    echo $user->getAvatar(true,true);         
?>
<?php //echo Yii::app()->user->name; ?>
<div id="mymenu">
    <ul>
        <li><a href="/index.php/profile/profile/view"><?php echo Yii::t('translator','My profile'); ?></a></li>
        <li><a href="/index.php/trip/myTrips"><?php echo Yii::t('translator','My trips'); ?></a></li>
        <li><a href="/index.php/friendship/friendship/index"><?php echo Yii::t('translator','My friends'); ?></a></li>
        <li><a href="/index.php/message/message/index"><?php echo Yii::t('translator','My inbox'); ?></a></li>
        <li><a href="/index.php/usergroup/groups/index"><?php echo Yii::t('translator','My groups'); ?></a></li>
        <li><a href="/index.php/site/logout"><?php echo Yii::t('translator','Logout'); ?></a></li>
    </ul>
</div>

<script>
$(document).mouseup(function (e)
{
    var myArray = [".notisContainer","#mymenu"];
    $.each(myArray, function(index, value){
        var container = $(value);
        if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.hide();
        }
    });
});

$( document ).ready(function() {

    $("#myavatar").click(function() {
        $("#mymenu").toggle();
    });
    $("#friend").click(function(){
        $(".notisContainer").toggle();
        $(".notisArrow").css("left","40%");
        $('#notisContent').html($("#friend_notis").html());
        $("#friend_num").html('');
        $("#friend_num").removeClass('numNotis');
        //get notis id
        var friendIds = [];
        $(".liFriend").each(function(){
            friendIds.push($(this).attr("name"));
        });
        updateReadStatus(friendIds);
    });
    $("#msg").click(function(){
        $(".notisContainer").toggle();
        $(".notisArrow").css("left","58%");
        $('#notisContent').html($("#email_notis").html());
        $("#email_num").html('');
        $("#email_num").removeClass('numNotis');
        //get notis id
        var emailIds = [];
        $(".liEmail").each(function(){
            emailIds.push($(this).attr("name"));
        });
        updateReadStatus(emailIds);
    });
    $("#tripp").click(function(){
        $(".notisContainer").toggle();
        $(".notisArrow").css("left","73%");
        $('#notisContent').html($("#trip_notis").html());
        $("#trip_num").html('');
        $("#trip_num").removeClass('numNotis');
        //get notis id
        var tripIds = [];
        $(".liTrip").each(function(){
            tripIds.push($(this).attr("name"));
        });
        updateReadStatus(tripIds);
    });
    getNotis();
    function getNotis(){
        $.ajax({
            url: '/index.php/notis/getNotis',
            success: function(respone){
                data = JSON.parse(respone);
                genNotisHTML(data);
            }
        });
    }    
    setInterval(function(){getNotis();}, 10000);
});

function updateReadStatus(ids){
    var quote = '"';
    ids = ids.join('","');
    ids = quote.concat(ids).concat(quote);
    $.ajax({
        url: '/index.php/notis/read',
        data: {"ids":ids},
        type: "POST",
        success: function(respone){
            console.log(respone);
        }
    });
}

function genNotisHTML(notis){
    //friend ++++++++++++++++++++++
    var friendNotis='';
    var friends = notis.friend;
    friendNotis += '<ul>';
    for (var i = 0; i < friends.length; i++) {
        friendNotis +='<a href="/index.php/friendship/friendship/index">';
        friendNotis +='<li name="'+friends[i].notis_id+'" class="liFriend">';
        friendNotis +='<img class="avatar" src="'+Yii::app()->request->baseUrl+'/'+friends[i].from_avatar+'" alt="" width="32px" height="32px" />';                               
        friendNotis += '<span class="username">'+ friends[i].from_user_name +'</span>' +' '+  friends[i].message;
        friendNotis += '</li>';
        friendNotis += '</a>';
    };          
    friendNotis += '</ul>';
    $('#friend_notis').html(friendNotis);
    if(notis.friendCount>0){
        $("#friend_num").addClass('numNotis');    
        $("#friend_num").html(notis.friendCount);
    }
    //friend -----------------------
    //email ++++++++++++++++++++++
    var emailNotis='';
    var emails = notis.email;
    emailNotis += '<ul>';
    for (var i = 0; i < emails.length; i++) {
        emailNotis +='<a href="/index.php/message/message/index">';
        emailNotis +='<li name="'+emails[i].notis_id+'" class="liEmail">';
        emailNotis +='<img class="avatar" src="'+Yii::app()->request->baseUrl+'/'+emails[i].from_avatar+'" alt="" width="32px" height="32px" />';                               
        emailNotis += '<span class="username">'+emails[i].from_user_name+'</span>' +' '+ emails[i].message;
        emailNotis += '</li>';
        emailNotis += '</a>';
    };          
    emailNotis += '</ul>';
    $('#email_notis').html(emailNotis);
    if(notis.emailCount>0){
        $("#email_num").addClass('numNotis');
        $("#email_num").html(notis.emailCount);    
    }
    
    //email -----------------------
    //trip ++++++++++++++++++++++
    var tripNotis='';
    var trips = notis.trip;
    tripNotis += '<ul>';
    for (var i = 0; i < trips.length; i++) {
        tripNotis +='<a href="/index.php/trip/view/?id='+trips[i].trip_id+'">';
        tripNotis +='<li name="'+trips[i].notis_id+'" class="liTrip">';
        tripNotis +='<img class="avatar" src="'+Yii::app()->request->baseUrl+'/'+trips[i].from_avatar+'" alt="" width="32px" height="32px" />';                               
        tripNotis += '<span class="username">'+trips[i].from_user_name+'</span>' +' '+ trips[i].message;
        tripNotis += '</li>';
        tripNotis += '</a>';
    };          
    tripNotis += '</ul>';
    $('#trip_notis').html(tripNotis);
    if(notis.tripCount>0){
        $("#trip_num").addClass('numNotis');    
        $("#trip_num").html(notis.tripCount);
    }
    
    //trip -----------------------
}
</script>