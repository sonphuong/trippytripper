<?php
//$this->title = Yum::t('Browse users');
//$this->breadcrumbs=array(Yum::t('Browse users'));
Yum::register('css/yum.css'); 
?>
<div class="search_options">
<?php echo CHtml::beginForm(); ?>
<div style="float: left;">
<?
//echo CHtml::label(Yum::t('Search for username'), 'search_username') . '<br />';
echo CHtml::textField('search_username',
		$search_username, array(
			'submit' => array('//user/user/browse')));
echo CHtml::submitButton(Yum::t('Search'),array('class'=>'orangeButton inlineButton'));
?>
</div>
<?php
echo CHtml::endForm();

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view', 
	'template' => '{sorter} <div style="clear:both;"></div>  {items} <div style="clear:both;"></div>{summary}  {pager}',
    'sortableAttributes'=>array(
        'username',
        'lastvisit',
    ),
));

?>
</div>
<div style="clear: both;"> </div>