<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple markers</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
function initialize() {
  var myLatlng = new google.maps.LatLng(41.669797, -86.249490);
  var myLatlng1 = new google.maps.LatLng(37.586684, -99.104802);
  var myLatlng2 = new google.maps.LatLng(33.145235, -92.776677);
  
  var mapOptions = {
    zoom: 4,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Address: abx Post code: 123 City: lodon'
  });
  var marker1 = new google.maps.Marker({
      position: myLatlng1,
      map: map,
      title: 'Sonphuong: is it OK?'
  });
  var marker2 = new google.maps.Marker({
      position: myLatlng2,
      map: map,
      title: 'Sonphuong: Sample'
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>