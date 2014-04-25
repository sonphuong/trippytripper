<?php

/**
 *
 */
class SharingController extends Controller
{
    public $layout = 'column1';
    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * get all available tour
     */
    public function getAllRides()
    {

    }


    /**
     * get login-user's tours
     */
    public function actionMyRides()
    {
        $sql = "SELECT R.name,
						R.description, 
						U.username, 
						U.avatar, 
						R.leave, 
						R.return, 
						R.return_trip, 
						R.seat_avail, 
						R.from, 
						R.to, 
						R.fee, 
						R.id
				FROM USER U 
				INNER JOIN ride R ON U.id = R.user_id
				WHERE R.user_id = :loginId
				ORDER BY R.leave DESC
		";
        $command = Yii::app()->db->createCommand($sql);
        $loginId = Yii::app()->user->id;
        $command->bindParam(':loginId', $loginId, PDO::PARAM_INT);
        //get all member invole to the rides

        $myRides = $command->queryAll();
        foreach ($myRides as $key => $myRide) {
            $members = $this->getRideMembers($myRide['id']);
            $myRides[$key]['members'] = $members;

        }
        $this->render('my_rides', array(
            'members' => $members,
            'myRides' => $myRides
        ));
    }


    /**
     * get members who is involes the the tour
     */
    public function getRideMembers($rideId)
    {
        $sql = "SELECT RD.user_name,RD.user_id, U.avatar, RD.join_status
		FROM ride_user RD 
		INNER JOIN user U ON U.id = RD.user_id
		WHERE RD.ride_id = :rideId
		AND RD.join_status = 1
		OR RD.join_status = 2
		ORDER BY RD.join_status ASC
		";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':rideId', $rideId, PDO::PARAM_INT);
        $members = $command->queryAll();
        return $members;
    }

    /**
     * publish a tour
     */
    public function actionOffer()
    {
        $model = new Ride;
        if (isset($_POST['Ride'])) {
            //========================================================
            //careful when using json_decode with non-utf8 (vietnamese language)
            $model->attributes = $_POST['Ride'];
            $model->user_id = Yii::app()->user->id;
            if ($model->validate()) {
                if ($model->save()) {
                    $lastInsertId = Yii::app()->db->getLastInsertID();
                    $rideUser = new RideUser();
                    $rideUser->ride_id = $lastInsertId;
                    $rideUser->user_id = Yii::app()->user->id;
                    $rideUser->user_name = Yii::app()->user->name;
                    //$rideUser->avatar = Yii::app()->user->avatar;
                    $rideUser->join_status = 9; //owner
                    if ($rideUser->validate()) {
                        $rideUser->save();
                    }
                    //$this->redirect(array('index'));

                }
            }
        }
        $this->render('offer', array('model' => $model));
    }

    /**
     * Search a tour.
     */
    public function actionSearchRide()
    {
        $model = new Ride;
        //search++++++++++++++++++++++++++++
        $from = '';
        $to = '';
        $leave = '';
        $return = '';
        //AND R.leave >= NOW()
        if (!empty($_POST['Ride']['from'])) $from = 'AND R.from = "' . $_POST['Ride']['from'] . '"';
        if (!empty($_POST['Ride']['to'])) $to = 'AND R.to = "' . $_POST['Ride']['to'] . '"';
        if (!empty($_POST['Ride']['leave'])) $leave = 'AND R.leave >= DATE("' . $_POST['Ride']['leave'] . '")';
        if (!empty($_POST['Ride']['return'])) $return = 'AND R.return <= DATE("' . $_POST['Ride']['return'] . '")';
        //search++++++++++++++++++++++++++++
        $sql = "SELECT
			R.user_id,
			R.name,
			R.description,
			U.username,
			U.avatar,
			R.leave,
			R.return,
			R.return_trip,
			R.seat_avail,
			R.from,
			R.to,
			R.fee,
			R.gathering_point,
			R.id
		FROM USER U
		INNER JOIN ride R ON U.id = R.user_id
		WHERE 1
		$from
		$to
		$leave
		$return
		ORDER BY R.leave ASC
		";
        /*LEFT JOIN ranking_votes RV ON RV.user_id = */
        //paging+++++++++++++++++++++++++++++++++++++++++++++++
        echo $sqlCount = "SELECT count(1) as count
		FROM USER U
		INNER JOIN ride R ON U.id = R.user_id
		WHERE 1
		$from
		$to
		$leave
		$return
		ORDER BY R.leave ASC
		";
        $itemCount = Yii::app()->db->createCommand($sqlCount)->queryRow();
        $itemCount = $itemCount['count'];
        $pages = new CPagination($itemCount);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $page = (isset($_GET['page']) ? $_GET['page'] : 1);
        $sql .= ' LIMIT ' . ($page - 1) . ', ' . Yii::app()->params['listPerPage'] . '';
        $allRides = Yii::app()->db->createCommand($sql)->queryAll();
        //paging+++++++++++++++++++++++++++++++++++++++++++++++

        $this->render('search_ride', array(
            'model' => $model,
            'itemCount' => $itemCount,
            'pageSize' => Yii::app()->params['listPerPage'],
            'pages' => $pages,
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
        $sql = "SELECT R.name,
					R.description, 
					U.username, 
					U.avatar, 
					R.leave, 
					R.return, 
					R.return_trip, 
					R.seat_avail, 
					R.from, 
					R.to, 
					R.fee, 
					R.id
				FROM USER U 
				INNER JOIN ride R ON U.id = R.user_id
				WHERE R.id = :id
				";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':id', $id, PDO::PARAM_INT);
        $data = $command->queryAll();
        $comment = new Comment;
        $allComments = $this->getAllComments($id);
        $joinStatus = $this->getJoinStatus($id);
        $members = $this->getRideMembers($id);
        $isOwner = $this->isOwner($id);

        $this->render('_view', array(
            'ride' => $data[0],
            'model' => $model,
            'comment' => $comment,
            'members' => $members,
            'allComments' => $allComments,
            'joinStatus' => $joinStatus,
            'isOwner' => $isOwner

        ));

    }

    //get list comments
    public function getAllComments($rideId)
    {
        $sql = "SELECT content,create_time,user_name,avatar
		FROM comments  
		WHERE ride_id = :ride_id
		ORDER BY create_time DESC
		LIMIT 30
		";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':ride_id', $rideId, PDO::PARAM_INT);

        $data = $command->queryAll();
        return $data;
    }

    /*
    send joining request to the owner
    */
    public function actionJoin()
    {
        //check login
        if (isset(Yii::app()->user->id)) {
            $model = new RideUser;
            if (isset($_POST['ride_id'])) {
                $model->user_id = Yii::app()->user->id;
                $model->user_name = Yii::app()->user->name;
                //$model->avatar = Yii::app()->user->avatar;
                $model->join_status = 2; //waiting
                $model->ride_id = $_POST['ride_id'];
                $model->save();
                Yii::app()->user->setFlash('joinRequested', 'Waiting for approve');
            }
            echo '<div class="flash-success">
	                ' . Yii::app()->user->getFlash('joinRequested') . '
	            </div>';
        } else {
            echo '<div class="flash-success">
	                You need to be a member.
	            </div>';
        }

    }

    /*
    the owner of the ride accept user to join
    */
    public function actionDisJoin()
    {
        $rideId = $_POST['ride_id'];
        $userId = $_POST['user_id'];
        $loginId = Yii::app()->user->id;
        $return['status'] = 0;
        $return['msg'] = 'unsuccess';
        if ($this->isOwner($rideId)) {
            //the owner can not do this action????
        } else {
            $sql = 'SELECT seat_avail FROM ride WHERE id = :rideId';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':rideId', $rideId, PDO::PARAM_INT);
            $seatAvail = $command->queryRow();
            $seatAvail = $seatAvail['seat_avail'];
            //update seat
            $seatsLeft = $seatAvail + 1;
            $sql = "UPDATE ride
					SET seat_avail =:seatsLeft
					WHERE id =:rideId
					";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':rideId', $rideId, PDO::PARAM_INT);
            $command->bindParam(':seatsLeft', $seatsLeft, PDO::PARAM_INT);

            if ($command->execute()) {
                //update status
                $sql = 'DELETE FROM ride_user
                    WHERE ride_id =:rideId
                    AND user_id =:userId
                    ';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(':userId', $userId, PDO::PARAM_INT);
                $command->bindParam(':rideId', $rideId, PDO::PARAM_INT);
                if ($command->execute()) {
                    $return['seatsLeft'] = $seatsLeft;
                    $return['userId'] = $userId;
                    $return['status'] = 1;
                    $return['msg'] = 'success';
                }
            }
        }

        echo json_encode($return);
    }

    /*
    get join status to display the join button
    */
    public function getJoinStatus($rideId)
    {
        $joinStatus = '';
        $loginId = Yii::app()->user->id;
        $sql = "SELECT RU.join_status
				FROM ride_user RU
				INNER JOIN ride R ON RU.ride_id = R.id
				INNER JOIN user U ON RU.user_id = U.id
				WHERE RU.ride_id = :ride_id
					AND RU.user_id = :user_id
				LIMIT 1	
				";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':user_id', $loginId, PDO::PARAM_INT);
        $command->bindParam(':ride_id', $rideId, PDO::PARAM_INT);
        $data = $command->queryAll();
        if ($data)
            $joinStatus = $data[0]['join_status'];
        return $joinStatus;
    }

    /*
    the owner of the ride accept user to join
    */
    public function actionAcceptJoin()
    {
        $rideId = $_POST['ride_id'];
        $userId = $_POST['user_id'];
        $loginId = Yii::app()->user->id;
        $return['status'] = 0;
        $return['msg'] = 'unsuccess';
        if ($this->isOwner($rideId)) {
            $sql = 'SELECT seat_avail FROM ride WHERE id = :rideId AND user_id =:loginId';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':loginId', $loginId, PDO::PARAM_INT);
            $command->bindParam(':rideId', $rideId, PDO::PARAM_INT);
            $seatAvail = $command->queryAll();
            $seatAvail = $seatAvail[0]['seat_avail'];
            //if seat is still available
            if ($seatAvail >= 1) {
                //update seat
                $seatsLeft = $seatAvail - 1;
                $sql = "UPDATE ride
					SET seat_avail =:seatsLeft
					WHERE id =:rideId
					AND user_id =:loginId
					";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(':loginId', $loginId, PDO::PARAM_INT);
                $command->bindParam(':rideId', $rideId, PDO::PARAM_INT);
                $command->bindParam(':seatsLeft', $seatsLeft, PDO::PARAM_INT);

                if ($command->execute()) {
                    //update status
                    $sql = "UPDATE ride_user
                    SET join_status = 1
                    WHERE ride_id =:rideId
                    AND user_id =:userId
                    ";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(':userId', $userId, PDO::PARAM_INT);
                    $command->bindParam(':rideId', $rideId, PDO::PARAM_INT);
                    if ($command->execute()) {
                        $return['seatsLeft'] = $seatsLeft;
                        $return['userId'] = $userId;
                        $return['status'] = 1;
                        $return['msg'] = 'success';
                    }
                }
            } else {
                Yii::app()->user->setFlash('joinRequested', 'Waiting for approve');
            }
        } else {
            $return['status'] = 0;
            $return['msg'] = 'You are not allow to do this action';
        }

        echo json_encode($return);
    }


    /**
     * check the login user is the owner of the ride
     */
    public function isOwner($rideId)
    {
        $joinStatus = $this->getJoinStatus($rideId);
        if ($joinStatus == 9) {
            return true;
        } else {
            return false;
        }

    }

}
