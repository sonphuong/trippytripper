$(document).ready(function () {
    var from = $('#goFrom').val();
    var to = $('#goTo').val();
    //first load
    if(from.length>0 && to.length>0){
        calcRoute();
        console.log('first load');
        
    }
    $('#goTo').change(function () {
        console.log('calcRoute');
        calcRoute();
    });

    google.maps.event.addDomListener(window, 'load', initialize);
    //auto complete
    var app = angular.module('trippytripper',['ui.bootstrap']);
    app.controller('autoCompleteController',function($scope,$http){
        getCountries();
        function getCountries(){
            $http.get('trip/getFriends').success(function(data){
                $scope.countries = data;
            });
        };

    });
});

function initialize() {
    var directionsDisplay = new google.maps.DirectionsRenderer();
    var directionsService = new google.maps.DirectionsService();
    var map;
    var lat = 21.0845;
    var lng = 105.638122;
    var mapZoom = 6;
    
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
}
function calcRoute() {
    var start = $('#goFrom').val();
    var end = $('#goTo').val();
    var directionsService = new google.maps.DirectionsService();
    var directionsDisplay = new google.maps.DirectionsRenderer();
    var request = {
        origin: start,
        destination: end,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
        }
    });
}