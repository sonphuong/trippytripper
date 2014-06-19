<form method="post" id="frm_owner_dis_join">
    <div class="row buttons">
        <span>Message to tripper:</span>
        <textarea></textarea>
    </div>
    <div class="row buttons">
        <?php
        echo CHtml::ajaxSubmitButton ("Bỏ tour",
            CController::createUrl('sharing/disJoin'),
            array(
                'success' => 'js:function(data) {ownerDisJoinSuccess(data);}'
            ,'data' => 'user_id='.Yii::app()->user->id.'&ride_id='.$_GET['id'].''
            )
        );
        ?>
    </div>
</form>

