<span class="language"><label>Language:</label> 
	<?php echo CHtml::form(); ?>
    <span id="langdrop">
        <?php echo CHtml::dropDownList('_lang', $currentLang, array(
            'en' => 'English', 'vi' => 'Vietnamese'), array('submit' => '')); ?>
    </span>
    <?php echo CHtml::endForm(); ?>
</span>
