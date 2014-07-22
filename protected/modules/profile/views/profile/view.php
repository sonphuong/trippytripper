<?php
if(!$profile = $model->profile)
	$profile = new YumProfile;


$this->pageTitle = Yum::t('Profile');
$this->title = CHtml::activeLabel($model,'username');
$this->breadcrumbs = array(Yum::t('Profile'), $model->username);
Yum::renderFlash();
?>
<div id="profile">
<?php echo $model->getAvatar(); ?>
    <?php echo '<h2>'.$model->username.'</h2>'; ?>
<?php
	if(!Yii::app()->user->isGuest && Yii::app()->user->id == $model->id) {
		echo CHtml::link(Yum::t('Edit profile'), array('//profile/profile/update'));
		echo '&nbsp';
		echo CHtml::link(Yum::t('Upload avatar image'), array('//avatar/avatar/editAvatar/?width=269&height=236'), array('class'=>''));
	}
?>
<?php
if(Yum::hasModule('friendship'))
$this->renderPartial(
		'application.modules.friendship.views.friendship.friends', array(
			'model' => $model)); ?>
<br />
<?php
if(@Yum::module('message')->messageSystem != 0)
$this->renderPartial('/message/write_a_message', array(
			'model' => $model)); ?>
<br />
<?php
$this->widget('PcStarRankWidget', array('modelId' => $model->id, 'modelClassName' => get_class($model)));
//$this->widget('PcStarRankWidget', array('modelId' => $model->id, 'modelClassName' => 'YumUser'));
?>
<div class="row"><?php echo Yii::t('translator','Leaded tours'); ?>: <?php echo $tourNo; ?></div>
<!--<div class="row">Tỷ lệ trả lời: 50% </div>-->
<div class="row"><?php echo Yii::t('translator','Last online');?>: <?php echo Time::timeAgoInWords(date('d-m-Y',$model->lastvisit)); ?></div>
<div class="row"><?php echo Yii::t('translator','Member since');?>: <?php echo date('d-m-Y',$model->createtime);?></div>
<?php $this->renderPartial(Yum::module('profile')->publicFieldsView, array(
			'profile' => $model->profile)); ?>
<br />
<?
if(Yum::module('profile')->enableProfileComments
		&& Yii::app()->controller->action->id != 'update'
		&& isset($model->profile)
		)
	$this->renderPartial(Yum::module('profile')->profileCommentIndexView, array(
			 'model' => $model->profile,
             'commentAble' =>$commentAble
    )); ?>
 </div>
