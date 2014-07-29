function approveJoinSuccess(data){
    data = JSON.parse(data);
    if(data.status==1){
        $("#seats_left").html(data.seatsLeft);
        $("#join_status_"+data.userId).html("");
    }
    else{
        $("#errorSummary").html(data.msg);
    }

}
function declineJoinSuccess(data){
    data = JSON.parse(data);
    console.log(data);
    if(data.status==1){
        $("#member_"+data.userId).html("");
    }
    else{
        $("#errorSummary").html(data.msg);
    }

}
function disJoinSuccess(data){
    data = JSON.parse(data);
    if(data.status==1){
        $("#seats_left").html(data.seatsLeft);
        $("#member_"+data.userId).html("");
        $("#dis_join_div").html("");
    }
    else{
        $("#errorSummary").html(data.msg);
    }
}
function ownerDisJoinSuccess(data){
    data = JSON.parse(data);
    if(data.status==1){
        //$("#join_status_"+data.userId).html("");
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
