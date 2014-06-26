<?php
class Notis extends CWidget
{
    public function run()
    {
    	$friendNotis = '';
    	$mailNotis = '';
    	$tripNotis = '';
        $this->render('notis', array('friendNotis' => $friendNotis,'mailNotis' => $mailNotis,'tripNotis' => $tripNotis));
    }
    public function getFriendNotis(){

    }
    public function getEmailNotis(){
    	
    }
    public function getTripNotis(){
    	
    }
}
?>