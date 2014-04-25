<?php
/**
 * Created by PhpStorm.
 * User: sonphuong
 * Date: 4/25/14
 * Time: 2:09 PM
 */

class SearchRideForm extends CFormModel
{
    public $from;
    public $to;
    public $leave;
    public $return;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('leave, return', 'date'),
            array('leave, return', 'date', 'format' => 'yyyy-mm-dd hh:mm'),
            array('leave',
                'compare',
                'compareValue' => date('Y-m-d H:i:s'),
                'operator' => '>',
                'message' => 'Begin date must be after today.'
            ),
            array('leave',
                'compare',
                'compareAttribute' => 'return',
                'operator' => '<',
                'message' => 'Begin date must be before finish date.'
            ),
            array('return',
                'compare',
                'compareAttribute' => 'leave',
                'operator' => '>',
                'message' => 'End date must be after begin date.'
            ),
        );
    }
}
