<?php
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
    public function actionAdd()
    {
    	//
    }

	public function actionGetNotis(){
		$loginId = Yii::app()->user->id;
		$objs = array('friend','email','trip');
		$notis = array();
		foreach ($objs as $obj) {
		    $sql = "SELECT *,id AS notis_id
		            FROM notis
		            WHERE to_user_id = :loginId
		            AND notis_type =  '".$obj."'
		            ORDER BY create_time DESC
		    ";
		    
		    //number +++++++++++++++++++++++++++++++++++++++++++++++
		    $sqlCount = "SELECT count(1) as count
		        FROM notis
		        WHERE to_user_id = :loginId
		        AND notis_type =  '".$obj."'
		        AND notis_read = '0'
		    ";
		    $commandCount = Yii::app()->db->createCommand($sqlCount);
		    $commandCount->bindParam(':loginId', $loginId, PDO::PARAM_INT);
		    $itemCount = $commandCount->queryRow();
		    $itemCount = $itemCount['count'];
		    //number +++++++++++++++++++++++++++++++++++++++++++++++
		    
		    //get all member invole to the trips
		    $command = Yii::app()->db->createCommand($sql);
		    $command->bindParam(':loginId', $loginId, PDO::PARAM_INT);
		    $rs = $command->queryAll();	
		    $notis[$obj] = $rs;
		    $notis[$obj.'Count'] = $itemCount;
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