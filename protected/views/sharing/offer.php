<div class="header-main-block">
    <h2>Route</h2>
</div>
<div class="form search_box">
    <?php $form = $this->
        beginWidget('CActiveForm'); ?>
    <?php echo $form->
        errorSummary($model); ?>
    <div class="row">
        <?php echo $form->
            textField($model, 'from', array('id' => 'goFrom', 'class' => 'from', 'value'=>$fromVal)); ?>
    </div>
    <div class="row">Example: Hanoi, Vietnam</div>
    <div class="row">
        <?php echo $form->
            textField($model, 'to', array('id' => 'goTo', 'class' => 'to', 'value'=>$toVal)); ?>
    </div>

    <div class="row">Departure date:</div>
    <div class="row">
        <?php
        $this->
            widget('application.widgets.timepicker.timepicker', array(
                'model' => $model,
                'name' => 'leave',
                'options' => array(
                    'showOn' => 'focus',
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'hh:mm',
                ),
            ));
        ?>
        <?php //echo $form->error($model,'date_from'); ?></div>
    <div class="row">Return date:</div>
    <div class="row">
        <?php
        $this->
            widget('application.widgets.timepicker.timepicker', array(
                'model' => $model,
                'name' => 'return',
                'options' => array(
                    'showOn' => 'focus',
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'hh:mm',
                ),
            ));
        ?>
    </div>
    <div class="row">
        <span class="label">Seats:</span>
        <span class="fieldset">
            <?php echo $form->
                textField($model, 'seat_avail', array('maxlength' => '2', 'size' => '2')); ?>
        </span>
    </div>
    <div class="row">
        <span class="label">Fee:</span>
        <span class="fieldset">
            <?php echo $form->
                textField($model, 'fee', array('maxlength' => '6', 'size' => '6')); ?>
        </span>
    </div>
    <div class="row">
        <span class="label">Gathering point:</span>
        <span class="fieldset">
            <?php echo $form->textField($model, 'gathering_point'); ?></span>
    </div>
    <div class="row">
        <span class="label">Title:</span>
        <span class="fieldset">
            <?php echo $form->textField($model, 'name'); ?></span>
    </div>
    <div class="row">Trip details:</div>
    <div class="row">
        <?php echo $form->
            textArea($model, 'description', array('rows' => 6, 'cols' => 45)); ?>
    </div>
    <div class="row"/>
    <input type="submit" value="Offer">
</div>
<?php $this->endWidget(); ?></div>
<div id="directions-panel"/>
<div id="map-canvas"/>
<!-- google maps -->
<input id="fb_access_token" type="hidden" value="<?php echo Yii::app()->
    params['FB_ACCESS_TOKEN']; ?>">

<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo Yii::app()->params['GOOGLE_API_KEY']; ?>&sensor=true&libraries=places"></script>
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
    }
    function calcRoute(start,end) {
        directionsDisplay = new google.maps.DirectionsRenderer();
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
    google.maps.event.addDomListener(window, 'load', initialize);

    $(document).ready(function () {
        
    });
    var from = $('#goFrom').val();
    var to = $('#goTo').val();
    //first load
    if(from.length>0 && to.length>0){
        calcRoute(from,to);
        console.log('first load');
        $('#goTo').change(function () {
            console.log('calcRoute');
            calcRoute(from,$('#goTo').val());
        });
    }
</script>