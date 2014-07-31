<?
Yii::setPathOfAlias('FriendshipModule' , dirname(__FILE__));

class FriendshipModule extends CWebModule {
	public $friendshipTable = 'friendship';
	public $layout = 'application.modules.user.views.layouts.yum';
	public $controllerMap=array(
			'friendship'=>array(
				'class'=>'FriendshipModule.controllers.YumFriendshipController'),
			);

}
