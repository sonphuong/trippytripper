<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/select2.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/thickbox.css" />

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select2.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/thickbox.js"></script>
	<?php Yii::app()->clientScript->scriptMap=array('jquery.js'=>false,);?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
        <div style="float:left;"><?php $this->widget('application.widgets.langbox.LangBox'); ?></div>
	<?php if(!Yii::app()->user->isGuest): ?>
	<div style="float:right">
		<a id="myavatar" href="/index.php/profile/profile/view"><img class="photo" src="<?php echo Yum::module('avatar')->getAvatarThumb(Yii::app()->user->avatar); ?>" width="42" height="42"></a>
		<div id="mymenu">
			<ul>
				<li><a href="/index.php/trip/myTrips"><?php echo Yii::t('translator','My trips'); ?></a></li>
				<li><a href="/index.php/friendship/friendship/index"><?php echo Yii::t('translator','My friends'); ?></a></li>
				<li><a href="/index.php/message/message/index"><?php echo Yii::t('translator','My inbox'); ?></a></li>
				<li><a href="/index.php/usergroup/groups/index"><?php echo Yii::t('translator','My groups'); ?></a></li>
				<li><a href="/index.php/site/logout"><?php echo Yii::t('translator','Logout'); ?></a></li>
			</ul>
		</div>
	</div>
		
	<?php $this->widget('application.widgets.notification.Notification'); endif; ?>
	</div><!-- header -->
	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>Yii::t('translator','Offer'), 'url'=>array('/trip/offer'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>Yii::t('translator','Trips'), 'url'=>array('/trip/searchTrip')),
				array('label'=>Yii::t('translator','Trippers'), 'url'=>array('/user/user/browse'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>Yii::t('translator','Join us'), 'url'=>array('/registration/registration'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>Yii::t('translator','Login'), 'url'=>array('/user/auth'), 'visible'=>Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->

	<?php /*$this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); */?><!-- breadcrumbs -->

	<?php echo $content; ?>

	<div id="footer">
        <a href="/index.php/site/index"><?php echo Yii::t('translator','Homepage'); ?></a>
        <a href="/index.php/site/about"><?php echo Yii::t('translator','About us'); ?></a>
        <a href="/index.php/site/contact"><?php echo Yii::t('translator','Contact'); ?></a>
        <div>www.trippytripper.org <?php echo date('Y'); ?> &copy;</div>

	</div><!-- footer -->

</div><!-- page -->

</body>
</html>