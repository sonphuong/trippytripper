<?
$this->breadcrumbs = array(
	Yum::t('Delete account'));

printf('<h2>%s</h2>', Yum::t('Are you really sure you want to delete your Account?'));

printf('<p>%s</p>', Yum::t('Please enter your password to confirm deletion:'));

echo CHtml::form(array('delete'));
echo CHtml::passwordField('confirmPassword') . "<br /><br />";
echo CHtml::linkButton(Yum::t('Cancel deletion'), array(
			'submit' => array('profile')
			,'class'=>'grayButton'
			));
echo CHtml::submitButton(Yum::t('Confirm deletion'), array('class'=>'orangeButton'));
echo CHtml::endForm();
?>
