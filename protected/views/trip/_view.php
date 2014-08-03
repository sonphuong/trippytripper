<div class="errorSummary"></div>
<article class="row_detail">
    <div class="cell user cell2">
        <a href="/index.php/profile/profile/view/id/<?php echo $trip['user_id']; ?>">
        <img class="avatar" src="/<?php echo $trip['avatar']; ?>" width="120" height="120">
        <div class="user-info">
            <h3 class="username"><?php echo $trip['username']; ?></h3>
        </div>
        </a>
    </div>

    <div class="cell cell5">
        
        
        <h4 />
        <?php 
            $leaveDate = new DateTime($trip['leave']);
            $date = $leaveDate->format('Y-m-d');
            $time = $leaveDate->format('h:m');
            $today = date('Y-m-d');
            $today = new DateTime($today);                            
            $interval = $today->diff($leaveDate);
            $days = (int)$interval->format("%r%a");
            if($days===0){
                echo Yii::t('translator','Today').' - ' . $time;
            }
            elseif($days===1){
                echo Yii::t('translator','Tomorrow').' - ' . $time;    
            }
            else{
                echo $date .' : '. $time;
            }
        ?>
        
        <h3 class="fromto" itemprop="name">
            <span class="trip-roads-stop"><?php echo $trip['from']; ?></span>
            <span class="arrow-ie">→</span>
            <span class="trip-roads-stop"><?php echo $trip['to']; ?></span>
        </h3>
        
        <dl class="geo-from">
            <dt>
            <span class="icon icon-marker-green-small">
                <?php echo Yii::t('translator','Pick up point');?>
            </span>
            </dt>
            <dd class="tip">
                <?php echo $trip['gathering_point']; ?>
            </dd>
        </dl>
    </div>
    <div class="rowsep"><?php echo $trip['description']; ?></div>
    <div class="cell border_right offer span2">
        <div class="price price-green" itemprop="location">
            <strong>
                <span>
                   <u>đ</u> <?php echo $trip['fee']; ?>K
                </span>                    
            </strong>
            <span class="priceUnit"><?php echo Yii::t('translator','per passenger');?></span>
        </div>
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
</article>

<article class="row_detail">
    <form method="post">
        <input type="hidden" value="<?php echo $_GET['id']; ?>" name="trip_id">
        <div class="cell cell3">
            <h4><?php echo Yii::t('translator','Trippers');?></h4>
            <ul class="memberList">
                <?php 
                if(!empty($members)){
                    foreach ($members as $key => $member) {
                        if($member['join_status']==2){
                            echo '<li class="" id="member_'.$member['user_id'].'"><img class="avatar" src="<?php echo Yum::module("avatar")->getAvatarThumb($member["avatar"]); ?>" alt="" width="32px" height="32px" />'.CHtml::link($member['user_name'], array('//profile/profile/view/id/'.$member['user_id']));                            
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
                        elseif($member['join_status']==1){
                            echo '<li class="" id="member_'.$member['user_id'].'"><img class="avatar" src="/'.$member['avatar'].'" alt="" width="32px" height="32px" />'.CHtml::link($member['user_name'], array('//profile/profile/view/id/'.$member['user_id']));
                        }
                    }
                }
                else{
                    echo Yii::t('translator','No one join yet');
                }
                ?>
            </ul>
        </div>
    </form>
    <div class="cell cell5">
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
            <li><img class="avatar" src="<?php echo Yum::module('avatar')->getAvatarThumb($value['avatar']); ?>" alt="" width="32px" height="32px" />
            <?php echo $value['user_name'].':'; ?>
            <?php echo $value['content']; ?>
            </li>
        <?php endforeach; ?>
        </ul>

        <?php if($joinStatus==1 || $joinStatus==9): ?>
        <div id="comments">
            <?php $this->renderPartial('/comment/_form',array(
                    'model'=>$comment,
                )); ?>
        </div>
        
        <?php endif; ?>
    </div>
</article>
