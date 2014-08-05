
<ul class="ctrl">
    <li><a id ="friend"  class="friend" href="#none"></a><span id="friend_num"></span></li>
    <li><a id ="msg" class="msg" href="#none"></a><span id="email_num"></span></li>
    <li><a id ="tripp" class="tripp" href="#none"></a><span id="trip_num"></span></li>
</ul>
<div id="friend_notis" class="notis"></div>
<div id="email_notis" class="notis"></div>
<div id="trip_notis" class="notis"></div>
<!-- <div class="notis">
    <span class="notis_header">Notifications</span>
    
    <span class="notis_footer">View all</span>
</div> -->
 <div class="user">
    <a id="myavatar" href="#none" class="avatar">
        <img src="<?php echo Yum::module('avatar')->getAvatarThumb(Yii::app()->user->avatar); ?>" width="30" height="30">
    </a>
 </div>
 
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
    var myArray = ["#friend_notis", "#email_notis", "#trip_notis","#mymenu"];
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
        $("#friend_notis").toggle();
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
        $("#email_notis").toggle();
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
        $("#trip_notis").toggle();
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
        friendNotis +='<img src="/'+friends[i].from_avatar+'" alt="" width="32px" height="32px" />';                               
        friendNotis += friends[i].from_user_name +' '+ friends[i].message;
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
        emailNotis +='<img src="/'+emails[i].from_avatar+'" alt="" width="32px" height="32px" />';                               
        emailNotis += emails[i].from_user_name +' '+ emails[i].message;
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
        tripNotis +='<img src="/'+trips[i].from_avatar+'" alt="" width="32px" height="32px" />';                               
        tripNotis += trips[i].from_user_name +' '+ trips[i].message;
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