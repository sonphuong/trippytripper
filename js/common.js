function approveJoinSuccess(data){
    data = JSON.parse(data);
    if(data.status==1){
        $("#seats_left").html(data.seatsLeft);
        $("#join_status_"+data.userId).html("");
        $("#member_"+data.userId).class("approved");
    }
    else{
        $("#errorSummary").html(data.msg);
    }

}
function disJoinSuccess(data){
    data = JSON.parse(data);
    if(data.status==1){
        $("#seats_left").html(data.seatsLeft);
        $("#join_status_"+data.userId).html("");
        $("#member_"+data.userId).class("approved");
    }
    else{
        $("#errorSummary").html(data.msg);
    }
}
function ownerDisJoinSuccess(data){
    data = JSON.parse(data);
    if(data.status==1){
        //$("#join_status_"+data.userId).html("");
        //$("#member_"+data.userId).class("approved");
        $("#frm_owner_dis_join").html("This trip is no longer anymore!");
    }
    else{
        $("#errorSummary").html(data.msg);
    }
}

function makeAutoComplete(tid,options){
    var text = (document.getElementById(''+tid+''));
    var autocomplete = new google.maps.places.Autocomplete(text,options);
}
$( document ).ready(function() {
    $("#num_friend").click(function(){
        $("#friend_notis").show();
    });
    $("#num_email").click(function(){
        $("#mail_notis").show();
    });
    $("#num_trip").click(function(){
        $("#trip_notis").show();
    });
});