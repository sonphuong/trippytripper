<?php
Yii::import('application.controllers.NotisController'); 
class Notification extends CWidget
{
    public $assets = '';
    public function init(){
        $this->assets = Yii::app()->assetManager->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
        
        Yii::app()->clientScript
        ->registerScriptFile( $this->assets.'/js/notification.js' )
        ->registerCssFile( $this->assets.'/css/notification.css' );
    }
    public function run()
    {
        $this->render('notification');
    }
    
    
}
?>