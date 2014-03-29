<form method="post">
    <div class="row buttons">
        <?php
        echo CHtml::ajaxSubmitButton ("Bỏ tour",
            CController::createUrl('sharing/disJoin'),
            array(
                'success' => 'js:function(data) {disJoinSuccess(data);}'
            ,'data' => 'user_id='.Yii::app()->user->id.'&ride_id='.$_GET['id'].''
            )
        );
        ?>
    </div>
</form>

