<form method="post" id="frm_owner_dis_join">
    <div class="row buttons">
        <span><?php echo Yii::t('translator','Message to trippers'); ?>:</span>
        <textarea></textarea>
    </div>
    <div class="row buttons">
        <?php
        echo CHtml::ajaxSubmitButton (Yii::t('translator','Quit'),
            CController::createUrl('sharing/disJoin'),
            array(
                'success' => 'js:function(data) {ownerDisJoinSuccess(data);}'
            ,'data' => 'user_id='.Yii::app()->user->id.'&trip_id='.$_GET['id'].''
            )
        );
        ?>
    </div>
</form>

