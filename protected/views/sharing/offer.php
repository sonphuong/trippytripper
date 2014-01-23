<div class="header-main-block">
    <h2>Route</h2>
</div>
<div class="form search_box">
    <?php $form=$this->beginWidget('CActiveForm'); ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">Title:</div>
    <div class="row">
        <?php echo $form->textField($model,'name'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->textField($model,'from',array('id'=>'goFrom', 'class'=>'from')); ?>
    </div>
    <div class="row">Example: Hanoi, Vietnam</div>
    <div class="row">
        <?php echo $form->textField($model,'to',array('id'=>'goTo', 'class'=>'to')); ?>
    </div>

    <div class="row">Departure date:</div>
    <div class="row">
    <?php 
    $this->widget('application.widgets.timepicker.timepicker', array(
        'model'=>$model,
        'name'=>'leave',
        'options' => array(
    	    'showOn'=>'focus',
    	    'dateFormat'=>'yy-mm-dd',
            'timeFormat'=>'hh:mm',
    	),
    ));
    ?>
    <?php //echo $form->error($model,'date_from'); ?>
    </div>
    <div class="row">Return date:</div>
    <div class="row">
        <?php 
        $this->widget('application.widgets.timepicker.timepicker', array(
            'model'=>$model,
            'name'=>'return',
            'options' => array(
        	    'showOn'=>'focus',
        	    'dateFormat'=>'yy-mm-dd',
                'timeFormat'=>'hh:mm',
        	),
        ));
        ?>
    </div> 
    <div class="row">Fee:</div>
    <div class="row">
        <?php echo $form->textField($model,'fee'); ?>
    </div>
    <div class="row">Description:</div>
    <div class="row">
        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>45)); ?>
    </div>
    <div class="row"/><input type="submit" value="Offer"></div>
    <?php $this->endWidget(); ?>
</div>
<div id="map-canvas" />

<!-- google maps -->
<script type="text/javascript"
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-o3Di-HaEWv6q81Sa-Kh5n5jaZ-Exkr8&sensor=true">
</script>
<script type="text/javascript">
  function initialize() {
    var mapOptions = {
      center: new google.maps.LatLng(21.0845, 105.638122),
      zoom: 8
    };
    var map = new google.maps.Map(document.getElementById("map-canvas"),
        mapOptions);
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>