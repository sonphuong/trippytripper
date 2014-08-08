<div id="trip_detail" class="row">
    <div class="cell cell2 form">
        
        <div class="fromTo">
            <span><?php echo $trip['from']; ?> → <?php echo $trip['to']; ?></span>
        </div>
        <div class="row">
            <label><?php echo Yii::t('translator','Departure date:'); ?> &nbsp;</label>
            <label class="date">
            <?php 
                $leaveDate = new DateTime($trip['leave']);
                $date = $leaveDate->format('Y-m-d');
                $time = $leaveDate->format('h:m');
                $today = date('Y-m-d');
                $today = new DateTime($today);                            
                $interval = $today->diff($leaveDate);
                $days = (int)$interval->format("%r%a");
                if($days===0) echo Yii::t('translator','Today').' - ' . $time;
                elseif($days===1)   echo Yii::t('translator','Tomorrow').' - ' . $time;    
                else echo $date .' : '. $time;
            ?>
            </label>
        </div>
        <div class="row">
            <label><?php echo Yii::t('translator','Return date:'); ?> &nbsp;</label>
            <label class="date">
            <?php 
                $leaveDate = new DateTime($trip['return']);
                $date = $leaveDate->format('Y-m-d');
                $time = $leaveDate->format('h:m');
                $today = date('Y-m-d');
                $today = new DateTime($today);                            
                $interval = $today->diff($leaveDate);
                $days = (int)$interval->format("%r%a");
                if($days===0) echo Yii::t('translator','Today').' - ' . $time;
                elseif($days===1)   echo Yii::t('translator','Tomorrow').' - ' . $time;    
                else echo $date .' : '. $time;
            ?>
            </label>
        </div>
        <div class="row">
            <label class="pickUpPoint">
                <?php echo Yii::t('translator','Pick up point');?>:
            </label>
            <label>
                <?php echo $trip['gathering_point']; ?>
            </label>
        </div>
    
    <div class="row des"><?php echo $trip['description']; ?></div>
    <div class="row">
    <form method="post">
        <input type="hidden" value="<?php echo $_GET['id']; ?>" name="trip_id">
        <div class="trippers"><?php echo Yii::t('translator','Trippers');?>:</div>
    </br>
        <?php 
        if($isOwner === true) $memberlistClass = 'memberListVer';
        else $memberlistClass = 'memberListHor';
        ?>
        <ul class="<?php echo $memberlistClass?>">
            <?php 
            if(!empty($members)){
                foreach ($members as $key => $member) {
                    $avatar ='';
                    $thumb = Yum::module("avatar")->getAvatarThumbPhoto($member["avatar"]);
                    $avatar .='<a class="tooltips" href="/index.php/profile/profile/view/id/'.$member['user_id'].'">';
                    if(!$isOwner) $avatar .= '<span>'.$member['user_name'].'</span>';
                    $avatar .='<img alt="" class="avatar" src="'.$thumb.'"  width="40px" height="40px" />';
                    $avatar .='</a>';
                    echo '<li id="member_'.$member['user_id'].'">'.$avatar;
                    if($isOwner === true) echo CHtml::link($member['user_name'], array('//profile/profile/view/id/'.$member['user_id']),array('class'=>'username'));                            
                    if($member['join_status']==2){
                        echo '<span id="join_status_'.$member['user_id'].'">';
                        if($isOwner === true){
                            if($trip['seat_avail']>0){
                                echo CHtml::ajaxSubmitButton ("Accept",
                                    CController::createUrl('trip/acceptJoin'),
                                    array(
                                        'success' => 'js:function(data) {approveJoinSuccess(data);}'
                                    ,'data' => 'user_id='.$member['user_id'].'&trip_id='.$_GET['id'].''
                                    ),
                                    array('class'=>'orangeButton')

                                );

                            }
                            echo CHtml::ajaxSubmitButton ("Decline",
                                    CController::createUrl('trip/DeclineJoin'),
                                    array(
                                        'success' => 'js:function(data) {declineJoinSuccess(data);}'
                                    ,'data' => 'user_id='.$member['user_id'].'&trip_id='.$_GET['id'].''
                                    ),
                                    array('class'=>'grayButton')
                                );
                            
                        }
                        else{
                            echo ' - '.Yii::t('translator','Waiting for approve'); 
                        }
                        echo '</span></li>';
                    }
                }
            }
            else{
                echo Yii::t('translator','No one join yet');
            }
            ?>
        </ul>
    </form>
    </div>
</br>
        <div class="boxTitle"><?php echo Yii::t('translator','Comments');?></div>
            <div class="row">
            <div id="viewMoreComments">
            <input type="hidden" name="commentsOffset" id = "commentsOffset" value="<?php echo Yii::app()->params['COMMENTS_PER_TIME']; ?>">
            <?php 
            echo CHtml::ajaxLink(
                $text = Yii::t('translator','view previous comments'), 
                $url = '/index.php/trip/getComments', 
                $ajaxOptions=array (
                    'type'=>'GET',
                    'dataType'=>'json',
                    'data'=>array("offset"=>"js:$('#commentsOffset').val()","tripId"=>Yii::app()->request->getQuery('id')),
                    'success'=>'function(objReturn){ 
                        $("#commentsList").prepend(objReturn.html); 
                        $("#commentsOffset").val(objReturn.offset);     
                        if(objReturn.noMoreComments==1){
                            $("#viewMoreComments").html("");
                        }
                    }'
                    ), 
                $htmlOptions=array ()
                );
            ?>
            </div>
            <ul id="commentsList">
            <?php  foreach ($allComments as $key => $value): ?>
                <?php $avatar = Yum::module("avatar")->getAvatarThumb($value['user_id'],$value["avatar"]); ?>
                <li>
                <?php echo $avatar; ?>    
                <?php echo CHtml::link($value['user_name'], array('//profile/profile/view/id/'.$value['user_id']),array('class'=>'username')); ?>:
                <?php echo $value['content']; ?>
                </li>
            <?php endforeach; ?>
            </ul>

            <?php if($joinStatus==1 || $joinStatus==9): ?>
            <div id="comments">
                <?php $avatar = Yum::module("avatar")->getAvatarThumb(Yii::app()->user->id,Yii::app()->user->avatar); ?>
                <?php $this->renderPartial('/comment/_form',array(
                        'model'=>$comment,
                    )); ?>
            </div>
            
            <?php endif; ?>
            </div>
    
    
    </div>
    <div class="cell cell3">
        <div class="box">
            <div class="boxTitle"><?php echo Yii::t('translator','Price');?></div>
            <div class="boxContent">
                <div class="price">
                    <u>đ</u> <?php echo $trip['fee']; ?>K
                </div>
                <div class="priceUnit"><?php echo Yii::t('translator','per passenger');?></div>
                <div class="availability">
                    <strong id="seats_left"> <?php echo $trip['seat_avail']; ?></strong> <span> <?php echo Yii::t('translator', 'seat(s) left');?> </span>
                    <?php if($trip['seat_avail']==0): ?>
                    <h2 style="color:red"><?php echo Yii::t('translator', 'Fulled'); ?></h2>
                    <?php endif;?>
                </div>
                <!-- check current date (no meaning for these below actions if the trip already done) -->
                <?php if($days>=0):?>
                <?php if($joinStatus==9): ?><a href="/index.php/trip/ownerDisJoin/?height=150&width=400&trip_id=<?php echo $_GET['id']; ?>" class="thickbox"><?php echo Yii::t('translator','Cancel this tour');?></a>
                    <?php //$this->renderPartial('/trip/_owner_dis_join_form',array('model'=>$model,)); ?>
                <?php elseif($joinStatus==1): ?>
                    <?php $this->renderPartial('/trip/_dis_join_form',array('model'=>$model,)); ?>
                <?php elseif($joinStatus==2): ?>
                    <div class="flash-success">
                        <?php echo Yii::t('translator','Waiting for approve');?>
                    </div>
                <?php else: ?>
                    <?php 
                    //if seat is availabel 
                    if($trip['seat_avail']>0)
                    $this->renderPartial('/trip/_join_form',array('model'=>$model,)); 
                    ?>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        </br>
        <div class="box">
            <div class="boxTitle">Leader info</div>
            <div class="boxContent">
            <div class="row">
            <span>
            <?php echo Yum::module("avatar")->getAvatar($trip['user_id'],$trip["avatar"]); ?>
            </span>
            <span class="username"><?php echo $trip['username']; ?> </span>
            </div>
            <?php //$this->widget('PcStarRankWidget', array('modelId' => $trip['user_id'], 'modelClassName' => 'YumUser')); ?>
            <div class=""><?php echo Yii::t('translator','Leaded tours'); ?>: <?php //echo $tourNo; ?></div>
            <!--<div class="row">Tỷ lệ trả lời: 50% </div>-->
            <div class=""><?php echo Yii::t('translator','Member since');?>: <?php echo date('d-m-Y',$trip["createtime"]);?></div>
            <div class=""><?php echo Yii::t('translator','Last online');?>: <?php echo Time::timeAgoInWords(date('d-m-Y',$trip["lastvisit"])); ?></div>
            </div>
        </div>
    <?php //endif; ?>
    </div>
</div>
