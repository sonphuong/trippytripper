<?php echo CHtml::form(); ?>
<span class="language"><label>Language:</label> 
    <span id="langdrop">
        <?php echo CHtml::dropDownList('_lang', $currentLang, array(
            'en' => 'English', 'vi' => 'Vietnamese'), array('submit' => '')); ?>
    </span>
</span>
<?php echo CHtml::endForm(); ?>