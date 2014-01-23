<?php
/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/*
 *
*
<?php

Yii::import('ext.LocationPicker.Location');

$model = new Location();
$this->widget ( 'ext.LocationPicker.LocationWidget',
		array (
				'model' => $model,
				'map_key' => 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
		));

?>

You can get your own key using Google Map API: https://code.google.com/apis/console

*/
class LocationWidget extends CWidget
{
	public $model = null;
	
	/** please set your own key*/
	public $map_key = '';
	/** @var string longitude attribute name in $model */
	public $locationAttribute = 'location';

	/** @var string latitude attribute name in $model */
	public $latitudeAttribute = 'latitude';

	/** @var string longitude attribute name in $model */
	public $longitudeAttribute = 'longitude';

	/** @var string longitude attribute name in $model */
	public $zoomAttribute = 'zoom';

	/** @var string Label for picker link, when not set “Pick Location will be used */
	public $label = null;

	public $assets;

	public $defaultLocation = 'New Delhi, India';
	public $defaultZoom = '10';
	/**
	 *  Publish assets and generate input ids when they is not set
	 */
	public function init()
	{
		$this->assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets'); //, false, -1, true
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=' . $this->map_key . '&sensor=false');
		//$cs->registerScriptFile($this->assets . '/jquery-1.7.2.min.js');
		$cs->registerScriptFile($this->assets . '/jquery-gmaps-latlon-picker.js');
		$cs->registerCssFile($this->assets . '/jquery-gmaps-latlon-picker.css');
	}

	public function run()
	{

		$model = $this->model;

		if (!isset($model->{$this->locationAttribute})) $model->{$this->locationAttribute} = $this->defaultLocation;
		if (!isset($model->{$this->zoomAttribute})) $model->{$this->zoomAttribute} = $this->defaultZoom;
		?>

<style>
.map_canvas img {
	max-width: none !important;
}

.map_canvas {
	margin-left: 20px;
	padding: 5px;
}
</style>
<div id="gmap" class="map_canvas" />
<fieldset id="_MAP_783" class="gllpLatlonPicker">
	<div class="row">
		<?php echo CHtml::activelabelEx($model,$this->locationAttribute); ?>
		<?php echo CHtml::activeTextField($model,$this->locationAttribute, array('class'=>'gllpSearchField gllpLocationName span4')); ?>
		<input class="gllpSearchButton" value="search" type="button">

		<div class="row">
			<div class="gllpMap">Google Maps</div>
		</div>
		<div class="row">
			<?php echo CHtml::activelabelEx($model,$this->latitudeAttribute); ?>
			<?php echo CHtml::activeTextField($model,$this->latitudeAttribute, array('class'=>'gllpLatitude')); ?>

			<?php echo CHtml::activelabelEx($model,$this->longitudeAttribute); ?>
			<?php echo CHtml::activeTextField($model,$this->longitudeAttribute, array('class'=>'gllpLongitude')); ?>
		</div>
		<div class="row">
			<?php echo CHtml::activelabelEx($model,$this->zoomAttribute); ?>
			<?php echo CHtml::activeTextField($model,$this->zoomAttribute, array('class'=>'gllpZoom')); ?>
		</div>
	</div>
</fieldset>
</div>
<?php

	}
}