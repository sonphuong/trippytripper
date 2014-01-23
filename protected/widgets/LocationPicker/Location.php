<?php
/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
/**
 * Location class.
 * Location is the data structure for location info
 */
class Location extends CFormModel
{
	public $location;
	public $latitude;
	public $longitude;
	public $zoom;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('location, latitude, longitude', 'required'),
		);
	}

}