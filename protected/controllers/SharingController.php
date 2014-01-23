<?php
/**
*
*/
class SharingController extends Controller
{
	public $layout='column1';
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public function getAllRides()
	{
		$sql = "SELECT R.name, R.description, U.username, U.avatar, R.leave, R.return, R.return_trip, R.seat_avail, R.from, R.to, R.fee, R.id
		FROM USER U 
		INNER JOIN ride R ON U.id = R.user_id
		WHERE R.leave >= NOW()
		ORDER BY R.leave ASC
		" ;
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		return $data;
	}

	public function actionOffer(){
		$model=new Ride;
		if(isset($_POST['Ride'])){
			$model->attributes = $_POST['Ride'];
			$model->user_id = Yii::app()->user->id;
			if($model->validate()){
				if($model->save()){
					//$this->redirect(array('index'));
				}

			}
		}
		$this->render('offer',array('model'=>$model));	
	}	

	public function actionSearchRide(){
		$model=new Ride;
		$allRides = $this->getAllRides();
		$this->render('search_ride',array(
			'model' => $model,
			'allRides' => $allRides
		));
		
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$model = new Ride;
		$id = $_GET['id'];
		$sql = "SELECT R.name, R.description, U.username, U.avatar, R.leave, R.return, R.return_trip, R.seat_avail, R.from, R.to, R.fee, R.id
				FROM USER U 
				INNER JOIN ride R ON U.id = R.user_id
				WHERE R.id = :id
				" ;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':id',$id,PDO::PARAM_INT);
		$data = $command->queryAll();
		$comment = new Comment;
		$allComments = $this->getAllComments($id);
		$joinStatus = $this->getJoinStatus($id);
		$this->render('_view',array(
			'ride'=>$data[0],
			'model'=>$model,
			'comment'=>$comment,
			'allComments'=>$allComments,
			'joinStatus'=>$joinStatus
		));

	}
	//get list comments
	public function getAllComments($rideId){
		$sql = "SELECT `content`,`create_time`,`user_name`,`avatar`
		FROM comments  
		WHERE ride_id = :ride_id
		ORDER BY create_time DESC
		LIMIT 30
		" ;

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':ride_id',$rideId,PDO::PARAM_INT);

		$data = $command->queryAll();
		return $data;
	}
	public function actionJoin()
	{
		$model = new RideUser;
		if(isset($_POST['content']))
		{
			//send mail to the owner
			$mailContent = $_POST['content'];
			$model->user_id = Yii::app()->user->id;
			$model->user_name = Yii::app()->user->name;
			//$model->avatar = Yii::app()->user->avatar;
			$model->join_status = 2;//waiting
			$model->ride_id = $_POST['ride_id'];
			$model->save();
			Yii::app()->user->setFlash('joinRequested','Waiting for approve');
		}
		echo '<div class="flash-success">
                '.Yii::app()->user->getFlash('joinRequested').' 
            </div>';
	}

	public function getJoinStatus($rideId){
		$joinStatus ='';
		$userId = Yii::app()->user->id;
		$model = new RideUser;
		$sql = "SELECT RU.join_status
				FROM ride_user RU
				INNER JOIN ride R ON RU.ride_id = R.id
				INNER JOIN user U ON RU.user_id = U.id
				WHERE RU.ride_id = :ride_id
					AND RU.user_id = :user_id
				LIMIT 1	
				" ;
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':user_id',$userId,PDO::PARAM_INT);
		$command->bindParam(':ride_id',$rideId,PDO::PARAM_INT);
		$data = $command->queryAll();
		if($data)
		$joinStatus = $data[0]['join_status'];
		return $joinStatus;
	}

	
}
