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

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select2.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js"></script>
	<?php Yii::app()->clientScript->scriptMap=array('jquery.js'=>false,);?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
        <div style="float:left;"><?php $this->widget('application.widgets.langbox.LangBox'); ?></div>
	</div><!-- header -->
	<?php $this->widget('application.widgets.notis.Notis'); ?>
	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>Yii::t('translator','Offer'), 'url'=>array('/trip/offer')),
				array('label'=>Yii::t('translator','Trips'), 'url'=>array('/trip/searchTrip')),
				array('label'=>Yii::t('translator','Trippers'), 'url'=>array('/user/user/browse')),
				array('label'=>Yii::t('translator','Join us'), 'url'=>array('/registration/registration'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>Yii::t('translator','Login'), 'url'=>array('/user/auth'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>''.Yii::app()->user->name.'', 'url'=>array('/profile/profile/view'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>Yii::t('translator','My trips'), 'url'=>array('/trip/myTrips'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>Yii::t('translator','Logout'), 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
				
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