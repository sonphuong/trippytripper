<?php
/**
 * @author phuongds dosonphuong@gmail.com
 */
class NotisController extends Controller{
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel()
    {
        if ($this->_model === null) {
            if (isset($_GET['id']))
                $this->_model = Notis::model()->findbyPk($_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public static function add($notisType,$toUserId,$tripId=false,$tripMsg=false,$joiner=false)
    {
        $model = new Notis;
        $model->to_user_id = $toUserId;
        $model->from_user_name = Yii::app()->user->name;
        $model->from_user_id = Yii::app()->user->id;
        $model->from_avatar = Yii::app()->user->avatar;
        if($joiner){
            $model->from_user_name = $joiner['from_user_name'];
            $model->from_user_id = $joiner['from_user_id'];
            $model->from_avatar = $joiner['from_avatar'];
        }
        $model->notis_type = $notisType;
        if($model->notis_type=='friend')
            $model->message=Yii::t('translator','send you a friend request');
        if($model->notis_type=='email')
            $model->message=Yii::t('translator','send you a message');
        if($model->notis_type=='trip'){
            $model->trip_id = $tripId;
            $model->message=$tripMsg;
        }
        $model->save();
    }
    public function actionGetNotis(){
        $loginId = Yii::app()->user->id;
        $objs = array('friend','email','trip');
        $notis = array();
        $limit = 10;
        foreach ($objs as $obj) {
            //number +++++++++++++++++++++++++++++++++++++++++++++++
            $sqlCount = "SELECT count(1) as count
                FROM notis
                WHERE to_user_id = :loginId
                AND notis_type =  :obj
                AND notis_read = '0'
            ";
            $commandCount = Yii::app()->db->createCommand($sqlCount);
            $commandCount->bindParam(':loginId', $loginId, PDO::PARAM_INT);
            $commandCount->bindParam(':obj', $obj, PDO::PARAM_STR);
            $itemCount = $commandCount->queryRow();
            $itemCount = $itemCount['count'];
            if($itemCount>$limit) $limit = $itemCount;
            //number +++++++++++++++++++++++++++++++++++++++++++++++
            //get notis---------------------------------------------
            $sql = "SELECT *,id AS notis_id
                    FROM notis
                    WHERE to_user_id = :loginId
                    AND notis_type =  :obj
                    ORDER BY create_time DESC
                    LIMIT $limit
            ";
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':loginId', $loginId, PDO::PARAM_INT);
            $command->bindParam(':obj', $obj, PDO::PARAM_STR);
            $rs = $command->queryAll();
            $notis[$obj] = $rs;
            $notis[$obj.'Count'] = $itemCount;
            //get notis---------------------------------------------
        }
        $isAjax = Yii::app()->request->isAjaxRequest;
        if($isAjax){
            echo json_encode($notis);
        }
        else{
            return  $notis;
        }
    }
    /**
     * [actionRead description]
     * @param  [array] $ids [id of notis]
     * @return [type]      [description]
     */
    public function actionRead(){
        $ids = Yii::app()->request->getParam('ids');
        $loginId = Yii::app()->user->id;
        $sql = 'UPDATE notis SET notis_read = 1 WHERE id IN ('.$ids.')';
        $command = Yii::app()->db->createCommand($sql);
        $rs = $command->execute();
    }
}
?>