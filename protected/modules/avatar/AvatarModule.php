<?php
Yii::setPathOfAlias('AvatarModule' , dirname(__FILE__));

class AvatarModule extends CWebModule {
	public $defaultController = 'avatar';

	// override this with your custom layout, if available
	public $adminLayout = 'application.modules.user.views.layouts.yum';
	public $layout = 'application.modules.user.views.layouts.yum';

	public $avatarPath = 'images/avatars';

	// Set avatarMaxWidth to a value other than 0 to enable image size check
	public $avatarMaxWidth = 0;

	public $avatarThumbnailWidth = 50; // For display in user browse, friend list
	public $avatarDisplayWidth = 120;

	public $enableGravatar = true;

	public $controllerMap=array(
		'avatar'=>array('class'=>'AvatarModule.controllers.YumAvatarController'),
	);

	public function init() {
		$this->setImport(array(
					'application.modules.user.controllers.*',
					'application.modules.user.models.*',
					'application.modules.avatar.controllers.*',
					'application.modules.avatar.models.*',
					));
	}
	public static function getAvatarThumbPhoto($avatar){
		if($avatar=='gravatar')
			$avatar = '/no_avatar.jpg';
		else{
	    	//subtring the path to put thumb
	    	$avatar = substr($avatar, strrpos($avatar,"/"));
    	}
    	
    	return '/images/avatars/thumbs'.$avatar;
    }
	public static function getAvatarThumb($userId,$avatar,$nolink=false){
		$html = '';
		if($avatar=='gravatar')
			$avatar = '/no_avatar.jpg';
		else{
	    	//subtring the path to put thumb
	    	$avatar = substr($avatar, strrpos($avatar,"/"));
    	}
    	if($nolink==false) $html .= '<a href="/index.php/profile/profile/view/id/'.$userId.'">';
    	$html .= '<img class="avatar" src="/images/avatars/thumbs'.$avatar.'" alt="" width="40px" height="40px" />';
    	if($nolink==false) $html .='</a>';
		return $html;

    }
    public static function getAvatar($userId,$avatar){
    	$html = '<a href="/index.php/profile/profile/view/id/'.$userId.'">';
    	$html .= '<img class="avatar" src="/'.$avatar.'" alt="" width="120px" height="120px" />';
    	$html .='</a>';
		return $html;

    }
}
