<?php

/**
 * This is the model class for table "trip".
 *
 * The followings are the available columns in table 'trip':
 * @property string $id
 * @property string $user_id
 * @property string $from
 * @property string $to
 * @property string $leave
 * @property integer $seat_avail
 * @property string $return
 */
class Trip extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'trip';
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
			array('from, to, leave, return, fee, seat_avail, gathering_point, description', 'required'),
			array('leave, return', 'date', 'format' => 'yyyy-mm-dd hh:mm'),
			array('leave',
	            'compare',
	            'compareValue' => date('Y-m-d H:i:s'),
	            'operator' => '>',
	            'message' => 'Departure date must be after today.'
	        ),
	        array('leave',
	            'compare',
	            'compareAttribute' => 'return',
	            'operator' => '<=',
	            'message' => 'Departure date must be before return date.'
	        ),
	        array('return',
	            'compare',
	            'compareAttribute' => 'leave',
	            'operator' => '>=',
	            'message' => 'Return date must be after departure date.'
	        ),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('from, to, leave, return, fee, seat_avail, gathering_point, description', 'safe', 'on'=>'add'),
			array('id, user_id, from, to, leave, seat_avail, return_trip, return,fee', 'safe', 'on'=>'search'),
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
			'leave' => 'Departure date',
			'seat_avail' => 'Seat Avail',
			'return_trip' => 'Return Trip',
			'return' => 'Return date',
			'fee' => 'Fee',
			'gathering_point' => 'Gathering point',
			'description' => 'Description'
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
	 * @return Trip the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function searchTrip(){
		$sql = "SELECT R.*, U.username
					FROM trip R 
				INNER JOIN user U
					ON R.user_id = U.id

				";
		$this->query($sql);		
	}
	
}
