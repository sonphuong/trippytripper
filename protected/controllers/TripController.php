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
        $sql = "SELECT 
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
                $isOwner = $this->isOwner($myTrip['id']);
                if($isOwner) $includeWaiting = true; 
                else $includeWaiting = false; 
                $members = $this->getTripper($myTrip['id'],$includeWaiting);

                $myTrips[$key]['isOwner'] = $isOwner;
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
     * @param int tripId
     * @param int userId //user who ask to join
     * @param boolean $getOwner //including owner
     */
    public function getTripper($tripId,$includeWaiting=false)
    {
        $joinStatus = "AND RD.join_status IN (1, 9)";            
        if($includeWaiting) $joinStatus .= "OR RD.join_status = 2";
            
        $sql = "SELECT RD.user_name,RD.user_id, U.avatar, RD.join_status
        FROM trip_user RD 
        INNER JOIN user U ON U.id = RD.user_id
        WHERE RD.trip_id = :tripId
        $joinStatus
        ORDER BY RD.join_status ASC
        ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
        $members = $command->queryAll();
        $return = array();
        foreach ($members as $key => $value) {
            $return[$value['user_id']] = $value;
        }
        return $return;
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
			R.description,
			U.username,
			U.avatar,
            U.createtime,
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
  //       /*LEFT JOIN ranking_votes RV ON RV.user_id = */
        $sqlCount = "SELECT COUNT(1) as count
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
  

        $count=Yii::app()->db->createCommand($sqlCount)->queryScalar();
        $dataProvider=new CSqlDataProvider($sql, array(
            'totalItemCount'=>$count,
            'sort'=>array(
                'attributes'=>array(
                     'leave', 'fee'
                ),
            ),
            'pagination'=>array(
                'pageSize'=>Yii::app()->params['RECORDS_PER_PAGE'],
            ),
        ));


        $this->render('search_trip',array(
                    'dataProvider'=>$dataProvider,
                    'model' =>$model,
                    'fromVal' => $fromVal,
                    'toVal' => $toVal
                    ));

    }

    /**
     * Displays a particular model.
     */
    public function actionView()
    {
        $model = new Trip;
        $id = $_GET['id'];
        $sql = "SELECT 
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
        $allComments = $this->actionGetComments($id);
        $joinStatus = $this->getJoinStatus($id);

        $isOwner = $this->isOwner($id);
        if($isOwner) $includeWaiting = true; 
        else $includeWaiting = false; 
        $members = $this->getTripper($id,$includeWaiting);

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
    public function getTripRoute($id)
    {
        $model = new Trip;
        $sql = "SELECT
                    R.from, 
                    R.to
                FROM user U 
                INNER JOIN trip R ON U.id = R.user_id
                WHERE R.id = :id
                ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':id', $id, PDO::PARAM_INT);
        $data = $command->queryRow();
        $isAjax = Yii::app()->request->isAjaxRequest;       
        $return = $data['from'] .' - '. $data['to'];
        return $return;
    }
    //get list comments
    public function actionGetComments($tripId=null)
    {
        $data = array();
        if($tripId==null){
            $tripId = Yii::app()->request->getParam('tripId');
        }            
        $sql = "SELECT content,create_time,user_name,avatar 
		FROM comments  
		WHERE trip_id = :trip_id 
		ORDER BY create_time DESC
		";
        //paging+++++++++++++++++++++++++++++++++++++++++++++++
        $sqlCount = "SELECT count(1) AS count 
        FROM comments  
        WHERE trip_id = :trip_id 
        ORDER BY create_time DESC 
        ";
        $commandCount = Yii::app()->db->createCommand($sqlCount);
        $commandCount->bindParam(':trip_id', $tripId, PDO::PARAM_INT);
        $itemCount = $commandCount->queryRow();
        
        $itemCount = $itemCount['count'];
        $offset = Yii::app()->request->getParam('offset', 0);
        
        $sql .= ' LIMIT ' . $offset . ', ' . Yii::app()->params['COMMENTS_PER_TIME'] . '';
        //paging+++++++++++++++++++++++++++++++++++++++++++++++
        
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':trip_id', $tripId, PDO::PARAM_INT);

        //no more record to get
        if($offset < $itemCount){
            $data = $command->queryAll();
            //reorder follow by time
            $data = array_reverse($data);
        }
        

        $isAjax = Yii::app()->request->isAjaxRequest;

        if($isAjax){
            $html = '';
            if(!empty($data)){
                foreach ($data as $key => $value){
                    $html .='<li><img src="/'.$value['avatar'].'" alt="" width="32px" height="32px" />';
                    $html .=$value['user_name'].':'.$value['content'];
                    $html .='</li>';
                }    
            }
            $ajaxData['html'] = $html;
            $ajaxData['offset'] = $offset+Yii::app()->params['COMMENTS_PER_TIME'];
            $ajaxData['noMoreComments'] = 0;
            if($ajaxData['offset']>=$itemCount)
                $ajaxData['noMoreComments'] = 1;
            echo json_encode($ajaxData);
        }
        else{
            return $data;    
        }
        
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
                $model->avatar = '';
                $model->join_status = 2; //waiting
                $model->trip_id = $_POST['trip_id'];
                $model->save();
                //notification--------------------------------------------------
                $toUsers = $this->getOwner($model->trip_id);
                $action = "asking to join";
                $this->noticeTripper($model->trip_id,$toUsers,$action);
                //notification--------------------------------------------------
                Yii::app()->user->setFlash('joinRequested', Yii::t('translator','Waiting for approve'));
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
        } else {
            $return = $this->disJoin($tripId,$userId);
        }

        echo json_encode($return);
    }
    /**
     * [noticeTripper notice to the tripper that this trip has deleted]
     * @param  [int] $tripId
     * @param  [array] $fromUser
     * @param  [array] $toUser 
     * @param  string $action
     * @return [void]
     */
    private function noticeTripper($tripId,$toUsers,$action,$fromUser=false){
        //add to notification
        Yii::import('application.controllers.NotisController');
        //get trip from and to
        $tripRoute = $this->getTripRoute($tripId);
        if(count($toUsers)>0){
            foreach ($toUsers as $toUser) {
                $message = "$action <b>".$tripRoute."</b>";   
                NotisController::add('trip',$toUser['user_id'],$tripId,Yii::t('translator',$message),$fromUser);
            }
        }
    }
    public function actionOwnerDisJoin(){
        $this->layout=false;
        $model = new Trip;
        $this->render('_owner_dis_join_form', array('model'=>$model));
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
        
        //notification--------------------------------------------------
        $toUsers = $this->getTripper($tripId,true);
        $action = "cancel";
        $this->noticeTripper($tripId,$toUsers,$action);
        //notification--------------------------------------------------
        
        //if owner disjoin send message to all tripper
        $trippersNum = count($toUsers);
        if(!empty($toUsers)){
            $sql = "INSERT INTO message 
                (`timestamp`,`from_user_id`,`to_user_id`,`title`,`message`)
                VALUES";
            $i = 1;    
            foreach($toUsers as $tripper){
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
                //notification--------------------------------------------------
                $toUsers = $this->getTripper($tripId);
                $action = "quit";
                $this->noticeTripper($tripId,$toUsers,$action);
                //notification--------------------------------------------------
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
                        //notification--------------------------------------------------
                        $owner = $this->getOwner($tripId);
                        $user = new User;
                        $fromUserObj = $user->findByPk($userId);
                        $fromUser['from_user_name'] = $fromUserObj->username;
                        $fromUser['from_user_id'] = $fromUserObj->id;
                        $fromUser['from_avatar'] = $fromUserObj->avatar;
                        $toUsers = $this->getTripper($tripId);
                        $action = "joined";
                        $this->noticeTripper($tripId,$toUsers,$action,$fromUser);
                        //notification--------------------------------------------------
                        $return['seatsLeft'] = $seatsLeft;
                        $return['userId'] = $userId;
                        $return['status'] = 1;
                        $return['msg'] = 'success';
                    }
                }
            } else {
                Yii::app()->user->setFlash('joinRequested', Yii::t('translator','Waiting'));
            }
        } else {
            $return['status'] = 0;
            $return['msg'] = 'You are not allow to do this action';
        }

        echo json_encode($return);
    }

    /*
    the owner of the trip accept user to join
    */
    public function actionDeclineJoin()
    {
        $tripId = $_POST['trip_id'];
        $userId = $_POST['user_id'];
        $loginId = Yii::app()->user->id;
        $return['status'] = 0;
        $return['msg'] = 'unsuccess';
        if ($this->isOwner($tripId)) {
            $sql = "DELETE FROM trip_user
                    WHERE trip_id =:tripId
                    AND user_id =:userId
                    ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':userId', $userId, PDO::PARAM_INT);
            $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
            
            if ($command->execute()) {
                //notification--------------------------------------------------
                $owner = $this->getOwner($tripId);
                $toUsers = array(0=>array('user_id'=>$userId));
                $action = "decline to join";
                $this->noticeTripper($tripId,$toUsers,$action);
                //notification--------------------------------------------------
                $return['userId'] = $userId;
                $return['status'] = 1;
                $return['msg'] = 'success';
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

   
    /**
     * get members who is involes the the tour
     * @param int tripId
     */
    public function getOwner($tripId)
    {
        $sql = "SELECT RD.user_name,RD.user_id, U.avatar, RD.join_status
        FROM trip_user RD 
        INNER JOIN user U ON U.id = RD.user_id
        WHERE RD.trip_id = :tripId
        AND RD.join_status = 9
        ORDER BY RD.join_status ASC
        ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':tripId', $tripId, PDO::PARAM_INT);
        $owner = $command->queryAll();
        return $owner;
    }

}
