<div class="form">
<?
$this->breadcrumbs = array(
		Yum::t('Profile') => array('//profile/profile/view'),
		Yum::t('Upload avatar'));

if(Yii::app()->user->isAdmin())
	echo Yum::t('Set Avatar for user ' . $model->username);
else if($model->avatar) {
	echo $model->getAvatar();
}
else
	echo Yum::t('You do not have set an avatar image yet');

	echo '<br />';

if(Yum::module('avatar')->avatarMaxWidth != 0)
	echo '<p>' . Yum::t('The image should have at least 50px and a maximum of 200px in width and height. Supported filetypes are .jpg, .gif and .png') . '</p>';

	/*echo CHtml::errorSummary($model);*/
	echo CHtml::beginForm(array(
				'//avatar/avatar/editAvatar', 'id' => $model->id), 'POST', array(
	'enctype' => 'multipart/form-data'));
	//echo CHtml::activeLabelEx($model, 'avatar');
	echo CHtml::activeFileField($model, 'avatar');

	echo CHtml::error($model, 'avatar');
	// if(Yum::module('avatar')->enableGravatar) 
	// echo CHtml::link(Yum::t('Use Gravatar'), array(
	// 			'//avatar/avatar/enableGravatar', 'id' => $model->id));

	// echo '&nbsp;';
	// echo CHtml::link(Yum::t('Remove Avatar'), array(
	// 			'//avatar/avatar/removeAvatar', 'id' => $model->id));
	echo '<br />';
	echo CHtml::submitButton(Yum::t('Upload avatar'),array('class'=>'orangeButton'));
	echo CHtml::endForm();
	
?>
</div>