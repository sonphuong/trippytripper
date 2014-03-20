<?php

/**
 * This is the model class for table "ride".
 *
 * The followings are the available columns in table 'ride':
 * @property string $id
 * @property string $user_id
 * @property string $from
 * @property string $to
 * @property string $leave
 * @property integer $seat_avail
 * @property string $return_trip
 * @property string $return
 */
class Ride extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ride';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('seat_avail,fee', 'numerical', 'integerOnly'=>true),
			//array('user_id, from, to', 'length', 'max'=>20),
			array('return_trip', 'length', 'max'=>1),
			array('from, to, leave, return, fee, seat_avail', 'required'),
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
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, from, to, leave, seat_avail, return_trip, return,fee,name,description', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			 'table1_relation' => array(self::BELONGS_TO, 'user', 'user_id'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'from' => 'From',
			'to' => 'To',
			'leave' => 'Leave',
			'seat_avail' => 'Seat Avail',
			'return_trip' => 'Return Trip',
			'return' => 'Return',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('to',$this->to,true);
		$criteria->compare('leave',$this->leave,true);
		$criteria->compare('seat_avail',$this->seat_avail);
		$criteria->compare('return_trip',$this->return_trip,true);
		$criteria->compare('return',$this->return,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ride the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function searchRide(){
		$sql = "SELECT R.*, U.username
					FROM ride R 
				INNER JOIN user U
					ON R.user_id = U.id

				";
		$this->query($sql);		
	}
	
}
