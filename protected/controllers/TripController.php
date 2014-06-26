<?php

/**
 * @author phuongds
 */
class TripController extends Controller
{
    public $layout = 'column1';
    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    public function accessRules() {
        return array(
            array('allow',
                'actions'=>array('myTrips'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all other users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * get all available tour
     */
    public function getAllTrips()
    {

    }


    /**
     * get login-user's tours
     */
    public function actionMyTrips()
    {
        $loginId = Yii::app()->user->id;
        $sql = "SELECT T.name,
                        T.description,
                        U.username, 
                        U.avatar, 
                        T.leave, 
                        T.return, 
                        T.return_trip, 
                        T.gathering_point, 
                        T.seat_avail, 
                        T.from, 
                        T.to, 
                        T.fee, 
                        T.id
                FROM trip_user TU 
                INNER JOIN trip T  ON T.id = TU.trip_id
                INNER JOIN user U ON U.id = T.user_id
                WHERE TU.user_id = :loginId
                ORDER BY T.leave DESC
        ";
        
        
        
        //paging+++++++++++++++++++++++++++++++++++++++++++++++
        $sqlCount = "SELECT count(1) as count
            FROM trip_user TU 
            INNER JOIN trip T  ON T.id = TU.trip_id
            INNER JOIN user U ON U.id = T.user_id
            WHERE TU.user_id = :loginId
            ORDER BY T.leave DESC
        ";
        $commandCount = Yii::app()->db->createCommand($sqlCount);
        $commandCount->bindParam(':loginId', $loginId, PDO::PARAM_INT);
        $itemCount = $commandCount->queryRow();
        
        $itemCount = $itemCount['count'];
        $pages = new CPagination($itemCount);
        $pages->setPageSize(Yii::app()->params['RECORDS_PER_PAGE']);
        $page = (isset($_GET['page']) ? $_GET['page'] : 0);
        $sql .= ' LIMIT ' . $page . ', ' . Yii::app()->params['RECORDS_PER_PAGE'] . '';
        //paging+++++++++++++++++++++++++++++++++++++++++++++++
        
        //get all member invole to the trips
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':loginId', $loginId, PDO::PARAM_INT);
        $myTrips = $command->queryAll();
        if(!empty($myTrips)){
            foreach ($myTrips as $key => $myTrip) {
                $members = $this->getTripper($myTrip['id']);
                $myTrips[$key]['members'] = $members;
            }    
        }

        $this->render('my_trips', array(
            //'members' => $members,
            'myTrips' => $myTrips,
            'pages'=>$pages
        ));
    }


    /**
     * get members who is involes the the tour
     */
    public function getTripper($tripId)
    {
        $sql = "SELECT RD.user_name,RD.user_id, U.avatar, RD.join_status
		FROM trip_user RD 
		INNER JOIN user U ON U.id = RD.user_id
		WHERE RD.trip_id = :tripId
		AND RD.join_status = 1
		OR RD.join_status = 2
		ORDER BY RD.join_status ASC
		";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
        $members = $command->queryAll();
        return $members;
    }

    /**
     * publish a tour
     */
    public function actionOffer()
    {
        /*$baseUrl = Yii::app()->baseUrl; 
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile("https://maps.googleapis.com/maps/api/js?key=".Yii::app()->params['GOOGLE_API_KEY']."&sensor=true&libraries=places");
        $cs->registerScriptFile($baseUrl.'/js/offer.js');*/
        
        $fromVal = '';
        $toVal = '';
        $model = new Trip;
        if (isset($_POST['Trip'])) {
            //========================================================
            //careful when using json_decode with non-utf8 (vietnamese language)
            $model->attributes = $_POST['Trip'];
            $fromVal = $_POST['Trip']['from'];
            $toVal = $_POST['Trip']['to'];
            $model->user_id = Yii::app()->user->id;
            if ($model->validate()) {
                if ($model->save()) {
                    $lastInsertId = Yii::app()->db->getLastInsertID();
                    $tripUser = new TripUser();
                    $tripUser->trip_id = $lastInsertId;
                    $tripUser->user_id = Yii::app()->user->id;
                    $tripUser->user_name = Yii::app()->user->name;
                    //$tripUser->avatar = Yii::app()->user->avatar;
                    $tripUser->join_status = 9; //owner
                    if ($tripUser->validate()) {
                        $tripUser->save();
                    }
                    $this->redirect(array('searchTrip'));
                }
            }
        }
        $this->render('offer', array(
            'model' => $model
            ,'fromVal' => $fromVal
            ,'toVal' => $toVal
        ));
    }

    /**
     * Search a tour.
     */
    public function actionSearchTrip()
    {
        $model = new SearchTripForm;
        //search++++++++++++++++++++++++++++
        $from = '';
        $fromVal = '';
        $to = '';
        $toVal='';
        $leave = '';
        $return = '';
        
        if (isset($_POST['SearchTripForm'])) {
            $model->attributes = $_POST['SearchTripForm'];
            if (!empty($_POST['SearchTripForm']['from'])) {
                $from = 'AND R.from LIKE "%' . $_POST['SearchTripForm']['from'] . '%"';
                $fromVal = $_POST['SearchTripForm']['from'];

            }
            if (!empty($_POST['SearchTripForm']['to'])) {
                $to = 'AND R.to LIKE "%' . $_POST['SearchTripForm']['to'] . '%"';
                $toVal = $_POST['SearchTripForm']['to'];
            }
            if (!empty($_POST['SearchTripForm']['leave'])) $leave = 'AND R.leave >= DATE("' . $_POST['SearchTripForm']['leave'] . '")';
            if (!empty($_POST['SearchTripForm']['return'])) $return = 'AND R.return <= DATE("' . $_POST['SearchTripForm']['return'] . '")';
        }
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
		FROM user U
		INNER JOIN trip R ON U.id = R.user_id
		WHERE 1
        AND R.leave >= NOW()
		$from
		$to
		$leave
		$return
		ORDER BY R.leave ASC
		";
        /*LEFT JOIN ranking_votes RV ON RV.user_id = */
        //paging+++++++++++++++++++++++++++++++++++++++++++++++
        $sqlCount = "SELECT count(1) as count
		FROM user U
		INNER JOIN trip R ON U.id = R.user_id
		WHERE 1
        AND R.leave >= NOW()
		$from
		$to
		$leave
		$return
		ORDER BY R.leave ASC
		";
        $itemCount = Yii::app()->db->createCommand($sqlCount)->queryRow();
        $itemCount = $itemCount['count'];
        $pages = new CPagination($itemCount);
        $pages->setPageSize(Yii::app()->params['RECORDS_PER_PAGE']);
        $page = (isset($_GET['page']) ? $_GET['page'] : 1);
        $sql .= ' LIMIT ' . ($page - 1) . ', ' . Yii::app()->params['RECORDS_PER_PAGE'] . '';
        $allTrips = Yii::app()->db->createCommand($sql)->queryAll();
        //paging+++++++++++++++++++++++++++++++++++++++++++++++

        $this->render('search_trip', array(
            'model' => $model,
            'fromVal' => $fromVal,
            'toVal' => $toVal,
            'itemCount' => $itemCount,
            'pageSize' => Yii::app()->params['RECORDS_PER_PAGE'],
            'pages' => $pages,
            'allTrips' => $allTrips
        ));
    }

    /**
     * Displays a particular model.
     */
    public function actionView()
    {
        $model = new Trip;
        $id = $_GET['id'];
        $sql = "SELECT R.name,
					R.description, 
					U.username, 
                    U.id AS user_id, 
					U.avatar, 
					R.leave, 
					R.return, 
					R.return_trip, 
					R.seat_avail, 
                    R.gathering_point,
					R.from, 
					R.to, 
					R.fee, 
					R.id
				FROM user U 
				INNER JOIN trip R ON U.id = R.user_id
				WHERE R.id = :id
				";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':id', $id, PDO::PARAM_INT);
        $data = $command->queryAll();
        $comment = new Comment;
        $allComments = $this->getAllComments($id);
        $joinStatus = $this->getJoinStatus($id);
        $members = $this->getTripper($id);
        $isOwner = $this->isOwner($id);

        $this->render('_view', array(
            'trip' => $data[0],
            'model' => $model,
            'comment' => $comment,
            'members' => $members,
            'allComments' => $allComments,
            'joinStatus' => $joinStatus,
            'isOwner' => $isOwner

        ));

    }

    //get list comments
    public function getAllComments($tripId)
    {
        $sql = "SELECT content,create_time,user_name,avatar
		FROM comments  
		WHERE trip_id = :trip_id
		ORDER BY create_time DESC
		LIMIT 3
		";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':trip_id', $tripId, PDO::PARAM_INT);

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
            $model = new TripUser;
            if (isset($_POST['trip_id'])) {
                $model->user_id = Yii::app()->user->id;
                $model->user_name = Yii::app()->user->name;
                //$model->avatar = Yii::app()->user->avatar;
                $model->join_status = 2; //waiting
                $model->trip_id = $_POST['trip_id'];
                $model->save();
                Yii::app()->user->setFlash('joinRequested', Yii::t('translator','waiting for approve'));
            }
            echo '<div class="flash-success">
	                ' . Yii::app()->user->getFlash('joinRequested') . '
	            </div>';
        } else {
            echo '<div class="flash-success">'.Yii::t('translator','You need to be a member').'</div>';
        }

    }

    /*
    the owner of the trip accept user to join
    */
    public function actionDisJoin()
    {
        $tripId = $_POST['trip_id'];
        $userId = $_POST['user_id'];
        $loginId = Yii::app()->user->id;
        
        if ($this->isOwner($tripId)) {
            $return = $this->ownerDisJoin($tripId,$userId);
            $this->noticeTripper($tripId,$userId);
        } else {
            $return = $this->disJoin($tripId,$userId);
        }

        echo json_encode($return);
    }
    /**
     * [noticeTripper notice to the tripper that this trip has deleted]
     * @param  [int] $tripId
     * @param  [int] $owner
     * @return [void]
     */
    private function noticeTripper($tripId,$owner){
        $trippers = $this->getTripper($tripId);
        $trippersNum = count($trippers);
        if(!empty($trippers)){
            $sql = "INSERT INTO message 
                (`timestamp`,`from_user_id`,`to_user_id`,`title`,`message`)
                VALUES
                ";
            $i = 1;    
            foreach($trippers as $tripper){
                if($i<$trippersNum){
                    $comma = ", ";
                }
                else{
                    $comma = "";
                }
                $sql .="(:timestamp,:owner,'".$tripper['user_id']."','','Sorry this trip has removed by the owner')".$comma;
                $i++;
            }

            $command = Yii::app()->db->createCommand($sql);
            $timestamp = time();
            $command->bindParam(':owner', $owner, PDO::PARAM_INT);
            $command->bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
            $result = $command->execute();
        }
        
    }
    private function ownerDisJoin($tripId,$userId){
        $return['status'] = 0;
        $return['msg'] = 'unsuccess';
        //update status to delete
        $sql = "UPDATE trip
                SET del_flg =:deleted
                WHERE id =:tripId
                ";
        $deleted = Yii::app()->params['DELETED'];
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
        $command->bindParam(':deleted', $deleted, PDO::PARAM_INT);

        if ($command->execute()) {
            //update status
            $sql = 'UPDATE trip_user
                SET del_flg =:deleted
                WHERE trip_id =:tripId
                ';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
            $command->bindParam(':deleted', $deleted, PDO::PARAM_INT);
            if ($command->execute()) {
                $return['userId'] = $userId;
                $return['status'] = 1;
                $return['msg'] = 'success';
            }
        }  
        return $return;  
    }

    private function disJoin($tripId,$userId){
        $return['status'] = 0;
        $return['msg'] = 'unsuccess';
        $sql = 'SELECT seat_avail FROM trip WHERE id = :tripId';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
        $seatAvail = $command->queryRow();
        $seatAvail = $seatAvail['seat_avail'];
        //update seat
        $seatsLeft = $seatAvail + 1;
        $sql = "UPDATE trip
                SET seat_avail =:seatsLeft
                WHERE id =:tripId
                ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
        $command->bindParam(':seatsLeft', $seatsLeft, PDO::PARAM_INT);

        if ($command->execute()) {
            //update status
            $sql = 'DELETE FROM trip_user
                WHERE trip_id =:tripId
                AND user_id =:userId
                ';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':userId', $userId, PDO::PARAM_INT);
            $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
            if ($command->execute()) {
                $return['seatsLeft'] = $seatsLeft;
                $return['userId'] = $userId;
                $return['status'] = 1;
                $return['msg'] = 'success';
            }
        }  
        return $return;  
    }
    /*
    get join status to display the join button
    */
    public function getJoinStatus($tripId)
    {
        $joinStatus = '';
        $loginId = Yii::app()->user->id;
        $sql = "SELECT RU.join_status
				FROM trip_user RU
				INNER JOIN trip R ON RU.trip_id = R.id
				INNER JOIN user U ON RU.user_id = U.id
				WHERE RU.trip_id = :trip_id
					AND RU.user_id = :user_id
				LIMIT 1	
				";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':user_id', $loginId, PDO::PARAM_INT);
        $command->bindParam(':trip_id', $tripId, PDO::PARAM_INT);
        $data = $command->queryAll();
        if ($data)
            $joinStatus = $data[0]['join_status'];
        return $joinStatus;
    }

    /*
    the owner of the trip accept user to join
    */
    public function actionAcceptJoin()
    {
        $tripId = $_POST['trip_id'];
        $userId = $_POST['user_id'];
        $loginId = Yii::app()->user->id;
        $return['status'] = 0;
        $return['msg'] = 'unsuccess';
        if ($this->isOwner($tripId)) {
            $sql = 'SELECT seat_avail FROM trip WHERE id = :tripId AND user_id =:loginId';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':loginId', $loginId, PDO::PARAM_INT);
            $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
            $seatAvail = $command->queryAll();
            $seatAvail = $seatAvail[0]['seat_avail'];
            //if seat is still available
            if ($seatAvail >= 1) {
                //update seat
                $seatsLeft = $seatAvail - 1;
                $sql = "UPDATE trip
					SET seat_avail =:seatsLeft
					WHERE id =:tripId
					AND user_id =:loginId
					";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(':loginId', $loginId, PDO::PARAM_INT);
                $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
                $command->bindParam(':seatsLeft', $seatsLeft, PDO::PARAM_INT);

                if ($command->execute()) {
                    //update status
                    $sql = "UPDATE trip_user
                    SET join_status = 1
                    WHERE trip_id =:tripId
                    AND user_id =:userId
                    ";
                    $command = Yii::app()->db->createCommand($sql);
                    $command->bindParam(':userId', $userId, PDO::PARAM_INT);
                    $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
                    if ($command->execute()) {
                        $return['seatsLeft'] = $seatsLeft;
                        $return['userId'] = $userId;
                        $return['status'] = 1;
                        $return['msg'] = 'success';
                    }
                }
            } else {
                Yii::app()->user->setFlash('joinRequested', Yii::t('Waiting for approve','Cho duyet'));
            }
        } else {
            $return['status'] = 0;
            $return['msg'] = 'You are not allow to do this action';
        }

        echo json_encode($return);
    }


    /**
     * check the login user is the owner of the trip
     */
    private function isOwner($tripId)
    {
        $joinStatus = $this->getJoinStatus($tripId);
        if ($joinStatus == 9) {
            return true;
        } else {
            return false;
        }

    }


}