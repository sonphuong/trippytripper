<?php
class Notis extends CWidget
{
    public function run()
    {
    	$friendNotis = $this->getNotis('friend');
    	$emailNotis = $this->getNotis('email');
    	$tripNotis = $this->getNotis('trip');

        $this->render('notis', array('friendNotis' => $friendNotis,'emailNotis' => $emailNotis,'tripNotis' => $tripNotis));
    }
    public function getNotis($obj){
        $loginId = Yii::app()->user->id;
        $sql = "SELECT *
                FROM notis
                WHERE to_user_id = :loginId
                AND notis_type =  '".$obj."'
                AND notis_read = '0'
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
        return  $rs;
    	
    }
    
}
?>