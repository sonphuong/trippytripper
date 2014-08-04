<div class="from" id="dis_join_div">
    <form method="post">
        <div class="row buttons">
            <?php
            echo CHtml::ajaxSubmitButton (Yii::t('translator','Quit'),
                CController::createUrl('trip/disJoin'),
                array(
                    'success' => 'js:function(data) {disJoinSuccess(data);}'
                ,'data' => 'user_id='.Yii::app()->user->id.'&trip_id='.$_GET['id'].''
                ),
                array('class'=>'grayButton')

            );
            ?>
        </div>
    </form>
</div>
