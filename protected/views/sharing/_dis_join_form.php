
<div class="form" id="join_div">
    <form method="post">
        <div class="row buttons">
            <?php
            echo CHtml::ajaxSubmitButton ("Bỏ tour",
                CController::createUrl('sharing/disJoin'),
                array(
                    'success' => 'js:function(data) {disJoinSuccess(data);}'
                ,'data' => 'user_id='.$member['user_id'].'&ride_id='.$_GET['id'].''
                )
            );
            ?>
        </div>
    </form>
</div>

