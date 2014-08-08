<div id="my_friend" class="search_options" style="overflow: hidden">
<?php echo CHtml::beginForm(); ?>
<div>
<?php
//echo CHtml::label(Yum::t('Search for username'), 'search_username') . '<br />';
echo CHtml::textField('search_username',
		$search_username, array(
			'submit' => array('//friendship/friendship/index')));
echo CHtml::submitButton(Yum::t('Search'),array('class'=>'orangeButton inlineButton'));
?>
Rearrange by: online - name
</div>
<?

echo CHtml::endForm();
?>

<?php
//$this->title = Yum::t('My friends');
//$this->breadcrumbs = array(Yum::t('Friends'));

if($friends) {
	echo '<div class="list-view">';
	
	foreach($friends as $friend) {
		echo '<div class="items">';
		echo '<div class="view_user">';
		$options = array();
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'groups-form',
			'enableAjaxValidation'=>false,
			));

		echo CHtml::activeHiddenField($friend, 'inviter_id');
		echo CHtml::activeHiddenField($friend, 'friend_id');

		if($friend->status == 1) { // Confirmation Pending
			if($friend->inviter_id == Yii::app()->user->id) {
				$options = CHtml::submitButton(Yum::t('Cancel request'),array(
							'id'=>'cancel_request', 'name'=>'YumFriendship[cancel_request]'));
			} else {
				$options = CHtml::submitButton(Yum::t('Confirm'), array(
							'id'=>'add_request','name'=>'YumFriendship[add_request]'));
//				$options .= CHtml::submitButton(Yum::t('Ignore'), array(
//							'id'=>'ignore_request','name'=>'YumFriendship[ignore_request]'));
				$options .= CHtml::submitButton(Yum::t('Deny'), array(
							'id'=>'deny_request','name'=>'YumFriendship[deny_request]'));
			}
		} else if($friend->status == 2) { // Users are friends
			$options = CHtml::submitButton(Yum::t('Remove friend'),array(
						'id'=>'remove_friend','class'=>'grayButton','name'=>'YumFriendship[remove_friend]','confirm' => Yum::t('Are you sure you want to remove this friend?')));
		}
			if($friend->inviter_id == Yii::app()->user->id)
				$label = $friend->invited;
			else
				$label = $friend->inviter;

			printf('%s<div class="username">%s</div><div>%s</div><div>%s</div><div class="text-right">%s</div>',
			CHtml::link($label->getAvatar(false),array('//profile/profile/view', 'id'=>$label->id)),
			$label->username, 
				$friend->getStatus(),
				CHtml::link(Yum::t('Write a message'), array(
					'//message/message/compose', 'to_user_id'=>$label->id),array('class'=>'aquaLink')),
					$friend->status != 3 ? $options : ''
			);

		$this->endWidget();
	echo '</div>';
	echo '</div>';
	}
	echo '</div>';
} else {
	echo Yum::t('You do not have any friends yet');
}
?>
</div>