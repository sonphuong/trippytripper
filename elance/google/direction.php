<?php    
$domain = $_SERVER['SERVER_NAME'];
if($domain==='www.trippytripper.org')
    $googleKey = 'AIzaSyD-o3Di-HaEWv6q81Sa-Kh5n5jaZ-Exkr8';
else    
    $googleKey = 'AIzaSyAisOhSjoLbzL_hEtuBhUoS3pr71vhwtu4'; 
?>
<html>
<head>
    <script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleKey; ?>&sensor=true&libraries=places"></script>
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>     
    <script type="text/javascript" src="js/common.js"></script>     
    <script type="text/javascript">
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map;
        var lat = 21.0845;
        var lng = 105.638122;
        var mapZoom = 6;


        function initialize() {
            directionsDisplay = new google.maps.DirectionsRenderer();
            var hanoi = new google.maps.LatLng(lat, lng);
            var mapOptions = {
                zoom: mapZoom,
                center: hanoi
            }
            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            directionsDisplay.setMap(map);
            directionsDisplay.setPanel(document.getElementById('directions-panel'));

            var fromOptions = {
                types: ['(cities)'],
            //componentRestrictions: {country: "us"}
        };
        makeAutoComplete('goFrom', fromOptions);
        makeAutoComplete('goTo', mapOptions);
        //first load
        var from = $('#goFrom').val();
        var to = $('#goTo').val();
        if(from.length>0 && to.length>0){
            calcRoute();
            console.log('first load');
        }
    }
    function calcRoute() {
        console.log('calcRoute');
        var start = $('#goFrom').val();
        var end = $('#goTo').val();
        //directionsDisplay = new google.maps.DirectionsRenderer();
        var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                console.log('set direction');
            }
        });
        if(start.length>0 && end.length>0){
            $("#showDirectionButton").show();

        }
    }
    
    google.maps.event.addDomListener(window, 'load', initialize);
    $(document).ready(function () {
        $("#showDirectionButton").click(function(){
            $("#directions-panel  ").toggle();
        });
    });
</script>
</head>
<body>
    <form>
        <div>
            <div class="row">
                <label>from</label>
                <input type="text" class="from" name="goFrom" id="goFrom" onChange="calcRoute();" style="width:400px;"  />
            </div>
            <div class="row">
                <label>to</label>
                <input type="text" class="to" name="goTo" id="goTo" onChange="calcRoute();" style="width:400px;"/>
            </div>
            <div class="row">
                <input type="button" class="blueButton" value="Show direction"  style="display:none;" id="showDirectionButton">
                <div style="clear:both">&nbsp;</div>
                <div id="directions-panel" style="display:none;"></div>
            </div>
        </div>

    </form>
    <div id="map-canvas"></div>
    <div style="clear:both">&nbsp;</div>
    <div id="directions-panel" style="display:none;"></div>
    <!-- google maps -->
    <input id="fb_access_token" type="hidden" value="CAAFbW0cHhFQBAJLrYfAcjURDK0YP54Qf6iM3r7SijogQnnZBQdRiMxWbDTe3kBiboABkQ817LlBlFZA9b8E7tJHD7YNCJRndYQZCuZCeJphLGZC2ir9V3sDEfGocmTPRtMIEguCBc7rKxWQo8TgUyazfzAUqdtO8ZD">
</body>
</html>

<style type="text/css">
#map-canvas{
    width: 690px;
    height: 535px;
    float: left;
    margin-bottom: 20px;
}
#directions-panel{
    height: auto;
    float: left;
    width: 500px;
    overflow: hidden;
}
</style>