<?php

class CommentController extends Controller
{
    public $layout = 'column2';

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated users to access all actions
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionAdd()
    {
        $model = new Comment;
        if (isset($_POST['Comment'])) {
            $arrData = $_POST['Comment'];
            $model->content = $arrData['content'];
            $model->user_id = Yii::app()->user->id;
            $model->user_name = Yii::app()->user->name;
            $model->avatar = Yii::app()->user->avatar;
            $model->trip_id = $arrData['trip_id'];
            $model->save();
            //add to notification
            Yii::import('application.controllers.NotisController');
            Yii::import('application.controllers.TripController');
            //get trippers to send notification
            $tripController = new TripController($model->trip_id);
            $trippers = $tripController->getTripper($model->trip_id);
            //get trip from and to
            $tripRoute = $tripController->getTripRoute($model->trip_id);
            
            foreach ($trippers as $key => $tripper) {
                NotisController::add('trip',$tripper['user_id'],$model->trip_id,Yii::t('translator','Comment on <b>'.$tripRoute.'</b>'));    
            }
            

        }
        //get the record have just insert
        $lastCommentData = $this->getLastComment(Yii::app()->user->id, $arrData['trip_id']);
        $lastCommentObj = '<li>';
        $lastCommentObj .= Yum::module("avatar")->getAvatarThumb(Yii::app()->user->id,Yii::app()->user->avatar);
        $lastCommentObj .= '&nbsp<span class="username">' . Yii::app()->user->name . '</span>:&nbsp' . $lastCommentData['content'] . '</li>';
        echo $lastCommentObj;
    }

    public function getLastComment($userId, $tripId)
    {
        $sql = "SELECT `content`,`create_time`,`user_name`,`avatar`
		FROM comments  
		WHERE user_id = :user_id
			AND trip_id = :trip_id
		ORDER BY id DESC
		LIMIT 1
		";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $command->bindParam(':trip_id', $tripId, PDO::PARAM_INT);

        $data = $command->queryAll();
        return $data[0];
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel()->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_POST['ajax']))
                $this->redirect(array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Comment', array(
            'criteria' => array(
                'with' => 'post',
                'order' => 't.status, t.create_time DESC',
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }


    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel()
    {
        if ($this->_model === null) {
            if (isset($_GET['id']))
                $this->_model = Comment::model()->findbyPk($_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }
}
