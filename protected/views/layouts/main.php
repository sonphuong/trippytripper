<?php
$fbconfig = Yum::module()->facebookConfig;
if(isset($fbconfig)) {
    Yii::import('application.modules.user.vendors.facebook.*');
        require_once('Facebook.php');
    $facebook = new Facebook($fbconfig);
    $fb_session = $facebook->getSession();
    if($fb_session && Yii::app()->user->isGuest)
                if($this->action->id != 'login')
                        $this->redirect($this->createUrl('/user/auth/login'));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" ng-app="trippytripper">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="description" content="trippytripper.org is a traveler community, helping traveler connect" />
	<meta name="keywords" content="Traveler, Car share, Tripper, Travel, Trip" />
	<meta name="robots" content="index,follow" />
	<meta name="rating" content="travel"/>
	<meta name="author" content="trippytripper.org"/>
	<meta name="copyright" content="(C) Copyright trippytripper.org."/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/select2.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/thickbox.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/layout.css" />
	
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select2.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/thickbox.js"></script>
	<!-- angular -->
	<!-- <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/angular.min.js"></script> -->
	<!-- angular: autocomplete -->
	<!-- <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/ui-bootstrap-tpls-0.9.0.js"></script> -->

	<!-- Place this tag in your head or just before your close body tag. -->
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<?php Yii::app()->clientScript->scriptMap=array('jquery.js'=>false,);?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<?php if(isset($fbconfig)): ?>
<div id="fb-root"></div>
<script>
window.fbAsyncInit = function() {
    FB.init({
        appId   : '<?php echo $facebook->getAppId(); ?>',
        session : <?php echo json_encode($fb_session); ?>, // don't refetch the session when PHP already has it
        status  : <?php echo $fbconfig['status']; ?>, // check login status
        cookie  : <?php echo $fbconfig['cookie']; ?>, // enable cookies to allow the server to access the session
        xfbml   : <?php echo $fbconfig['xfbml']; ?> // parse XFBML
    });

    // whenever the user logs in, we refresh the page
    FB.Event.subscribe('auth.login', function() {
        window.location.reload();
    });
};

(function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/<?php echo $fbconfig['lang']; ?>/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
}());
</script>
<?php endif; ?>	
<!-- <div id="fb-root"></div> -->
<script>/*(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=830328023674875&version=v2.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));*/</script>
<div id="wrapper">
	<div id="header">
		<div class="wrap-ct">
        	<h1 id="logo"><a href="/index.php"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.png" alt="trippytripper" /></a>
        	</h1>
			<div class="connect-us">
				<span class="tit"><?php echo Yii::t('translator','Connect with us'); ?>:</span>
				<ul class="list">
					<li><a target="_blank" href="https://www.facebook.com/trippytripper.org"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icon_social_01.gif" alt="" /></a></li>
					<li><a target="_blank" href="https://twitter.com/trippytripper_"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icon_social_02.gif" alt="" /></a></li>
					<li><a target="_blank" href="https://plus.google.com/101230437362157655560"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icon_social_03.gif" alt="" /></a></li>
					<li><a target="_blank" href="#none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icon_social_04.gif" alt="" /></a></li>
					<li><a target="_blank" href="#none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icon_social_05.gif" alt="" /></a></li>
					<li><a target="_blank" href="#none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icon_social_06.gif" alt="" /></a></li>
					<li><a target="_blank" href="#none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/icon_social_07.gif" alt="" /></a></li>
				</ul>
			</div>
		</div>
		<?php ///echo CHtml::encode(Yii::app()->name); ?>
		<?php
		if($this->pageTitle =='Homepage') $class = 'home';
		else $class ='sub-page';
		?>
		<?php if($class=='sub-page'):?>
		<div id="gnb">
			<!-- <div id="gnb_left">&nbsp;</div> -->
			<div class="bg"><!-- id = mainmenu -->
				<div class="gnb_right">
					<div class="gnb_center">
						<ul class="gnb">
							<?php $this->widget('zii.widgets.CMenu',array(
								'items'=>array(
									array('label'=>Yii::t('translator','Offer'), 'url'=>array('/trip/offer'), 'visible'=>!Yii::app()->user->isGuest),
									array('label'=>Yii::t('translator','Location'), 'url'=>array('/location/location')),
									array('label'=>Yii::t('translator','Trips'), 'url'=>array('/trip/searchTrip')),
									array('label'=>Yii::t('translator','Trippers'), 'url'=>array('/user/user/browse'), 'visible'=>!Yii::app()->user->isGuest),
									array('label'=>Yii::t('translator','Join us'), 'url'=>array('/registration/registration'), 'visible'=>Yii::app()->user->isGuest),
									array('label'=>Yii::t('translator','Login'), 'url'=>array('/user/auth'), 'visible'=>Yii::app()->user->isGuest)
								),
							)); ?>
						</ul>
						<?php if(!Yii::app()->user->isGuest): ?>		
						<div class="right-area">
							<?php $this->widget('application.widgets.notification.Notification'); ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<!-- <div id="gnb_right"></div> -->
			</div><!-- mainmenu -->	
		</div>
		<?php endif;?>
	</div><!-- header -->
	<div id="container" class="<?php echo $class; ?>"> 
	<?php if($class=='sub-page'):?>
	<div class="wrap-ct">
		<?php echo $content; ?>
	</div>
	<?php else:?>	
	<?php echo $content; ?>
	<?php endif;?>	
	</div><!-- page -->
	<div id="footer">
		<div id="siteInfo">
			<span class="copyright">trippytripper.org <?php echo date('Y'); ?> &copy; &nbsp;</span>
			<span class="fb-like" data-href="https://www.facebook.com/trippytripper.org/" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></span>
			<!-- Place this tag where you want the +1 button to render. -->
			<span class="g-plusone" data-size="medium"></span>
			<div class="right-area">
				<ul class="menu">
					<li><a href="/index.php/site/index"><?php echo Yii::t('translator','Homepage'); ?></a> |</li>
					<li><a href="/index.php/site/about"><?php echo Yii::t('translator','About us'); ?></a> |</li>
					<li><a href="/index.php/site/contact"><?php echo Yii::t('translator','Contact'); ?></a> </li>
				</ul>
				<?php
				if(!Yii::app()->user->isGuest)
				$this->widget('application.widgets.langbox.LangBox'); 
				?>
			</div>
		</div>
	</div><!--//footer-->
</div>
</body>
</html>