<div class="view_user" id="user_<?php echo $data->id;?>"> 
<?php echo CHtml::link($data->getAvatar(),array('//profile/profile/view', 'id' => $data->id)); 
$online = '';
if(Yum::hasModule('profile') && Yum::module('profile')->enablePrivacySetting) {
	if($data->privacy && $data->privacy->show_online_status) {
		if($data->isOnline()) {
			$online .= CHtml::image(Yum::register('images/green_button.png'));
		}
	}
}
printf('<div class="username">%s %s</div>', $data->username, $online);			
//$this->widget('PcStarRankWidget', array('modelId' => $data->id, 'modelClassName' => 'YumUser'));
$this->renderPartial('_tooltip', array('data' =>  $data));
?>
</div>