<div class="form search_box" id="offer">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'top-websites-cr-form',
        'enableAjaxValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit'=>true,
            'validateOnChange'=>true,
            'validateOnType'=>false,
        ),
    )); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <div class="label cell"><label><?php echo Yii::t('translator', 'From');?><span class="required">*</span></label></div>
        <div class="cell">
        <?php echo $form->textField($model, 'from', array('id' => 'goFrom', 'class' => 'from', 'value'=>$fromVal,'autofocus'=>'autofocus')); ?>
        </div>
    </div>
    <div class="row">
        <div class="label cell"><label><?php echo Yii::t('translator', 'To');?><span class="required">*</span></label></div>
        <div class="cell"><?php echo $form->textField($model, 'to', array('id' => 'goTo', 'class' => 'to', 'value'=>$toVal)); ?></div>
    </div>

    <div class="row">
        <div class="label cell">
            <label><?php echo Yii::t('translator','Departure date');?><span class="required">*</span></label>
        </div>
        <div class="cell">
            <?php
            $this->widget('application.widgets.timepicker.timepicker', array(
                    'model' => $model,
                    'name' => 'leave',
                    'options' => array(
                        'showOn' => 'focus',
                        'dateFormat' => 'yy-mm-dd',
                        'timeFormat' => 'hh:mm',
                    ),
                ));
            ?>
        </div>
    <?php //echo $form->error($model,'date_from'); ?>
    </div>
    <div class="row">
        <div class="label cell">
        <label for="return"><?php echo Yii::t('translator','Return date');?><span class="required">*</span></label>
        </div>
        <div class="cell">
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
    </div>
    <div class="row">
        <div class="label cell"><label for="seat_avail"><?php echo Yii::t('translator', 'Seats');?><span class="required">*</span></label></div>
        <div class="cell">
            <?php echo $form->textField($model, 'seat_avail', array('maxlength' => '2')); ?>
        </div>
    </div>
    <div class="row">
        <div class="label cell"><label><?php echo Yii::t('translator', 'Fee');?><span class="required">*</span></label></div>
        <div class="cell">
            <?php echo $form->textField($model, 'fee', array('maxlength' => '6')); ?>
        </div>
    </div>
    <div class="row">
        <div class="label cell"><label><?php echo Yii::t('translator', 'Gathering point');?><span class="required">*</span></label></div>
        <div class="cell gathering_point"><?php echo $form->textField($model, 'gathering_point'); ?></div>
    </div>
    <div class="row"><label><?php echo Yii::t('translator', 'Trip details');?>:</label></div>
    <div class="row">
        <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 45)); ?>
    </div>
    <div class="row"/>
    <input type="submit" class="orangeButton" value="<?php echo Yii::t('translator', 'Offer');?>">
</div>
<?php $this->endWidget(); ?></div>
<div id="map-canvas"/>
<!-- google maps -->
<input id="fb_access_token" type="hidden" value="<?php echo Yii::app()->params['FB_ACCESS_TOKEN']; ?>">

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
        //first load
        var from = $('#goFrom').val();
        var to = $('#goTo').val();
        if(from.length>0 && to.length>0){
            calcRoute($('#goFrom').val(),$('#goTo').val());
            console.log('first load');
        }
    }
    function calcRoute(start,end) {
        var digiLocation = {
            "lat": "51.597336",
            "long": "-0.109035"
        };
        directionsDisplay = new google.maps.DirectionsRenderer();
        var request = {
            origin: start,
            destination: new google.maps.LatLng(digiLocation.lat, digiLocation.long),
            //destination: end,
            //travelMode: google.maps.TravelMode.DRIVING
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                console.log('set direction');
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    
    //load again when change the value of destination
    $('#goTo').change(function () {
        calcRoute($('#goFrom').val(),$('#goTo').val());
    });
</script>