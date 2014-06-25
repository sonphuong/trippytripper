<form method="post">
    <div class="row buttons">
        <?php
        echo CHtml::ajaxSubmitButton (Yii::t('translator','Quit'),
            CController::createUrl('sharing/disJoin'),
            array(
                'success' => 'js:function(data) {disJoinSuccess(data);}'
            ,'data' => 'user_id='.Yii::app()->user->id.'&trip_id='.$_GET['id'].''
            )
        );
        ?>
    </div>
</form>

