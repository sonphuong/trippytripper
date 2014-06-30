<div class="table_row">
    <div id="friend_num" class="numNotis cell"></div>
    <div id="friend_notis" class = "notis cell"></div>
    
    <div id="email_num" class="numNotis cell"></div>
	<div id="email_notis" class = "notis cell"></div>
    
    <div id="trip_num" class="numNotis cell"></div>
    <div id="trip_notis" class = "notis cell"></div>
</div>	

<script>
$( document ).ready(function() {
    $("#friend_num").click(function(){
        $("#email_notis").hide();
        $("#trip_notis").hide();

        $("#friend_notis").toggle();
        $("#friend_num").html('');
        //get notis id
        var friendIds = [];
        $(".liFriend").each(function(){
            friendIds.push($(this).attr("name"));
        });
        updateReadStatus(friendIds);
    });
    $("#email_num").click(function(){
        $("#friend_notis").hide();
        $("#trip_notis").hide();

        $("#email_notis").toggle();
        $("#email_num").html('');
        //get notis id
        var emailIds = [];
        $(".liEmail").each(function(){
            emailIds.push($(this).attr("name"));
        });
        updateReadStatus(emailIds);
    });
    $("#trip_num").click(function(){
        $("#email_notis").hide();
        $("#friend_notis").hide();

        $("#trip_notis").toggle();
        $("#trip_num").html('');
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

    console.log(ids); 

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
        friendNotis += friends[i].from_user_name +': '+ friends[i].message;
        friendNotis += '</li>';
        friendNotis += '</a>';
    };          
    friendNotis += '</ul>';
    $('#friend_notis').html(friendNotis);
    if(notis.friendCount>0)
    $("#friend_num").html(notis.friendCount);
    //friend -----------------------
    //email ++++++++++++++++++++++
    var emailNotis='';
    var emails = notis.email;
    emailNotis += '<ul>';
    for (var i = 0; i < emails.length; i++) {
        emailNotis +='<a href="/index.php/message/message/index">';
        emailNotis +='<li name="'+emails[i].notis_id+'" class="liEmail">';
        emailNotis +='<img src="/'+emails[i].from_avatar+'" alt="" width="32px" height="32px" />';                               
        emailNotis += emails[i].from_user_name +': '+ emails[i].message;
        emailNotis += '</li>';
        emailNotis += '</a>';
    };          
    emailNotis += '</ul>';
    $('#email_notis').html(emailNotis);
    if(notis.emailCount>0)
    $("#email_num").html(notis.emailCount);
    //email -----------------------
    //trip ++++++++++++++++++++++
    var tripNotis='';
    var trips = notis.trip;
    tripNotis += '<ul>';
    for (var i = 0; i < trips.length; i++) {
        tripNotis +='<a href="/index.php/trip/view/?id="'+trips[i].trip_id+'>';
        tripNotis +='<li name="'+trips[i].notis_id+'" class="liTrip">';
        tripNotis +='<img src="/'+trips[i].from_avatar+'" alt="" width="32px" height="32px" />';                               
        tripNotis += trips[i].from_user_name +': '+ trips[i].message;
        tripNotis += '</li>';
        tripNotis += '</a>';
    };          
    tripNotis += '</ul>';
    $('#trip_notis').html(tripNotis);
    if(notis.tripCount>0)
    $("#trip_num").html(notis.tripCount);
    //trip -----------------------
}
</script>