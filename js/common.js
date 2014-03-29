var countryList = "['VN']";
var accessToken='CAAFbW0cHhFQBAJLrYfAcjURDK0YP54Qf6iM3r7SijogQnnZBQdRiMxWbDTe3kBiboABkQ817LlBlFZA9b8E7tJHD7YNCJRndYQZCuZCeJphLGZC2ir9V3sDEfGocmTPRtMIEguCBc7rKxWQo8TgUyazfzAUqdtO8ZD';
$(document).ready(function(){
	$("#goFrom").select2({
            placeholder: 'From',
            minimumInputLength: 1,
            allowClear: true,
            multiple: false,
            ajax: {
                url: 'https://graph.facebook.com/search?type=adcity&access_token=' + accessToken,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        'q': term,
                        'limit': '50',
                        //'country_list': countryList
                    };  
                },
                results: function (json) {
                    var result = new Array();
                    if (json.data) {
                        for (var i = 0; i < json.data.length; i++) {
                            //var _tmp = escape('{"id":"' + json.data[i].key + '","name":"' + json.data[i].name + '"}');
                            result.push({
                                id: json.data[i].name,
                                text: json.data[i].name
                            });
                        }
                    }
                    return {
                        results: result
                    };
                }
            }
        });
	$("#goTo").select2({
            placeholder: 'To',
            minimumInputLength: 1,
            allowClear: false,
            multiple: false,
            ajax: {
                url: 'https://graph.facebook.com/search?type=adcity&access_token=' + accessToken,
                dataType: 'json',
                quietMillis: 100,
                data: function (term) {
                    return {
                        'q': term,
                        'limit': '50',
                        //'country_list': countryList
                    };  
                },
                results: function (json) {
                    var result = new Array();
                    if (json.data) {
                        for (var i = 0; i < json.data.length; i++) {
                            //var _tmp = escape('{"id":"' + json.data[i].key + '","name":"' + json.data[i].name + '"}');
                            result.push({
                                id: json.data[i].name,
                                text: json.data[i].name
                            });
                        }
                    }
                    return {
                        results: result
                    };
                }
            }
        });
})
//=========================================================================================
function approveJoinSuccess(data){
    data = JSON.parse(data);
    console.log(data);
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
    console.log(data);
    if(data.status==1){
        $("#seats_left").html(data.seatsLeft);
        $("#join_status_"+data.userId).html("");
        $("#member_"+data.userId).class("approved");
    }
    else{
        $("#errorSummary").html(data.msg);
    }

}