<?php
$this->pageTitle=Yii::app()->name . ' - index';
$this->breadcrumbs=array(
	'Home',
);
?>
<?php if(!Yii::app()->user->isGuest): ?>
<?php $this->redirect('/index.php/trip/searchTrip'); ?>
<?php else: ?>
<div id="banner">
	<div class="wrap-ct">
		<div class="desc"><span>trippytripper is a community of travelers</span><br />
		<span>a channel for travelers, a social network for travelers.</span></div>
		<div class="btn">
			<a href="/index.php/registration/registration" class="btn-blue">Join Us</a>
			<a href="/index.php/user/auth" class="btn-orange">Log In</a>
		</div>
	</div>
</div>
<div class="lst-comment">
	<div class="wrap-ct">
		<p class="tit-area">Let's build a beautiful community from trippytripper.<br /></p>
		<!-- <strong>See who's helping out now:</strong>
		<div class="line">
			<span class="pin"></span>
		</div>
		<ul class="comments">
			<li>
				<div class="box">
					<p>I like Yakitori Totto on 55th Street between 7th and 8th. Upstairs. Arrive early as they don't take reservations.</p>
					<p>The bar Le Bernardin serves food now, if you don't mind splurging. I second the motion that Casa Mono is great …</p>
					<span class="arrow"></span>
				</div>
				<div class="user">
					<span class="img"><img src="images/icon_comment.gif" alt="" /></span>
					<span class="name"><strong>tuandx</strong>,abcxyz</span>
				</div>
			</li>
			<li>
				<div class="box">
					<p>I like Yakitori Totto on 55th Street between 7th and 8th. Upstairs. Arrive early <br />
					as they don't take reservations.</p>
					<p>The bar Le Bernardin serves food nowif you don't mind splurging. I second the , if you don't mind splurging. I second the <br />
						motion that Casa Mono is great …</p>
					<span class="arrow"></span>
				</div>
				<div class="user">
					<span class="img"><img src="images/icon_comment.gif" alt="" /></span>
					<span class="name"><strong>tuandx</strong>,abcxyz</span>
				</div>
			</li> 
			
		</ul>
		-->
	</div>
</div>
<?php endif; ?>