<?php
Yii::import('application.controllers.NotisController'); 
class Notis extends CWidget
{
    public $assets = '';
    public function init(){
        $this->assets = Yii::app()->assetManager->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
        
        Yii::app()->clientScript
        ->registerScriptFile( $this->assets.'/js/notis.js' )
        ->registerCssFile( $this->assets.'/css/notis.css' );
    }
    public function run()
    {
        $this->render('notis');
    }
    
    
}
?>