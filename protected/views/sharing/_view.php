<article class="row_detail">
    <div class="cell user cell2">
        <img class="photo" src="/images/sonphuong.jpg" width="42" height="42">
        <div class="user-info">
            <h3 class="username"><?php echo $ride['username']; ?></h3>
        </div>
        <div class="user-trust">
            <div class="rating-container">
                <span class="star-rating star_5"></span>
                2 ratings
            </div>
            <div class="facebook-container">
                <span class="tip user-trust-fb tip">
                    <b class="icon-fb-small"></b>
                    113 friends
                </span>
            </div>
            <div class="preferences-container">
                <span class="blabla prefs tip"></span>
                <span class="no-smoking prefs tip"></span>
            </div>
        </div>
    </div>

    <div class="cell cell5">
        <h2 class="time" itemprop="startDate"><?php echo $ride['name']; ?></h2>
        <div class="rowsep"><?php echo $ride['description']; ?></div>
        <h4 />
        <?php 
            $leaveDate = new DateTime($ride['leave']);
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
            <span class="trip-roads-stop"><?php echo $ride['from']; ?></span>
            <span class="arrow-ie">→</span>
            <span class="trip-roads-stop"><?php echo $ride['to']; ?></span>
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
                   <u>đ</u> <?php echo $ride['fee']; ?>K
                </span>                    
            </strong>
            <span class="priceUnit">1 người</span>
        </div>
        <div class="availability">
            <strong><?php echo $ride['seat_avail']; ?></strong> <span>seats left</span>
        </div>
        <?php if($joinStatus==9): ?>
        <?php elseif($joinStatus==1): ?>
            <div class="flash-success">You Joined</div>
        <?php elseif($joinStatus==2): ?>
            <div class="flash-success">
                Waiting for approve
            </div>
        <?php else: ?>
            <?php $this->renderPartial('/sharing/_join_form',array(
                'model'=>$model,
            )); ?>
        <?php endif; ?>
    </div>
</article>

<article class="row_detail">
    <form method="post">
        <input type="hidden" value="<?php echo $_GET['id']; ?>" name="ride_id">
        <div class="cell cell3">
            Members
            <ul>
                <?php 
                if(!empty($members)){
                    foreach ($members as $key => $member) {
                        if($isOwner === true && $member['join_status']==2){
                            echo '<li><span style="float:left">'.$member['user_name'] .'</span>';                                
                            echo '<span id="join_status_'.$member['user_id'].'">'; 
                            echo '<input type="hidden" name="user_id" value="'.$member['user_id'].'" />' ;   
                            echo CHtml::ajaxSubmitButton ("Accept",
                                  CController::createUrl('sharing/acceptJoin'), 
                                  array('success' => 'js:function(data) {$("#join_status_'.$member['user_id'].'").html("");}'));    
                            echo '</span></li>';
                        }
                        elseif($member['join_status']==1){
                            echo '<li><span style="float:left">'.$member['user_name'] .'</span>';                                
                        }
                    }
                }?>
            </ul>
        </div>
    </form>
    <div class="cell cell5">
        <ul id="commentsList">
        <?php foreach ($allComments as $key => $value): ?>
            <li><img src="<?php echo $value['avatar']; ?>" alt="" width="32px" height="32px" /> 
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

