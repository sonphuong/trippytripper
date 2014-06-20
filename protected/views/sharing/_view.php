<div class="errorSummary"></div>
<article class="row_detail">
    <div class="cell user cell2">
        <a href="/index.php/profile/profile/view/id/<?php echo $trip['id']; ?>">
        <img class="photo" src="/<?php echo $trip['avatar']; ?>" width="42" height="42">
        <div class="user-info">
            <h3 class="username"><?php echo $trip['username']; ?></h3>
        </div>
        </a>
        <div class="user-trust">
            
            <div class="facebook-container">
                <span class="tip user-trust-fb tip">
                    <b class="icon-fb-small"></b>
                    113 friends
                </span>
            </div>
            
        </div>
    </div>

    <div class="cell cell5">
        <h2 class="time" itemprop="startDate"><?php echo $trip['name']; ?></h2>
        <div class="rowsep"><?php echo $trip['description']; ?></div>
        <h4 />
        <?php 
            $leaveDate = new DateTime($trip['leave']);
            $date = $leaveDate->format('Y-m-d');
            $time = $leaveDate->format('h:m');
            $today = date('Y-m-d');
            $today = new DateTime($today);                            
            $interval = $leaveDate->diff($today);

            if($interval->days===0){
                echo 'Hôm nay - ' . $time;
            }
            elseif($interval->days===1){
                echo 'Ngày mai - ' . $time;    
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
                Pick up point
            </span>
            </dt>
            <dd class="tip">
                Ciputra
            </dd>
        </dl>
    </div>

    <div class="cell border_right offer span2">
        <div class="price price-green" itemprop="location">
            <strong>
                <span>
                   <u>đ</u> <?php echo $trip['fee']; ?>K
                </span>                    
            </strong>
            <span class="priceUnit">1 người</span>
        </div>
        <div class="availability">
            <strong id="seats_left"><?php echo $trip['seat_avail']; ?></strong> <span>seats left</span>
        </div>
        <?php if($interval->days>0):?>
        <?php if($joinStatus==9): ?>
            <?php $this->renderPartial('/sharing/_owner_dis_join_form',array('model'=>$model,)); ?>
        <?php elseif($joinStatus==1): ?>
            <?php $this->renderPartial('/sharing/_dis_join_form',array('model'=>$model,)); ?>
        <?php elseif($joinStatus==2): ?>
            <div class="flash-success">
                Waiting for approve
            </div>
        <?php else: ?>
            <?php $this->renderPartial('/sharing/_join_form',array('model'=>$model,)); ?>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</article>

<article class="row_detail">
    <form method="post">
        <input type="hidden" value="<?php echo $_GET['id']; ?>" name="trip_id">
        <div class="cell cell3">
            Members
            <ol class="memberList">
                <?php 
                if(!empty($members)){
                    foreach ($members as $key => $member) {
                        if($isOwner === true && $member['join_status']==2){
                            echo '<li class="waiting" id="member_'.$member['user_id'].'"><img src="/'.$member['avatar'].'" alt="" width="32px" height="32px" />'.CHtml::link($member['user_name'], array('//profile/profile/view/'.$member['user_id']));                            echo '<span id="join_status_'.$member['user_id'].'">';
                            if($trip['seat_avail']>0){
                                echo CHtml::ajaxSubmitButton ("Accept",
                                    CController::createUrl('sharing/acceptJoin'),
                                    array(
                                        'success' => 'js:function(data) {approveJoinSuccess(data);}'
                                    ,'data' => 'user_id='.$member['user_id'].'&trip_id='.$_GET['id'].''
                                    )
                                );
                            }
                            else{
                                echo '<span>&nbsp; No more seat</span>';
                            }
                            echo '</span></li>';
                        }
                        elseif($member['join_status']==1){
                            echo '<li class="approved" id="member_'.$member['user_id'].'"><img src="/'.$member['avatar'].'" alt="" width="32px" height="32px" />'.CHtml::link($member['user_name'], array('//profile/profile/view/'.$member['user_id']));
                        }
                    }
                }?>
            </ol>
        </div>
    </form>
    <div class="cell cell5">
        <ul id="commentsList">
        <?php foreach ($allComments as $key => $value): ?>
            <li><img src="/<?php echo $value['avatar']; ?>" alt="" width="32px" height="32px" />
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
