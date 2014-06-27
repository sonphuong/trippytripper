<?php echo CHtml::form(); ?>
    <div id="langdrop">
        <?php echo CHtml::dropDownList('_lang', $currentLang, array(
            'en' => 'En', 'vi' => 'Vi'), array('submit' => '')); ?>
    </div>
<?php echo CHtml::endForm(); ?>