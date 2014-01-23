<article class="table_row">
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

    <div class="cell offer span2">
        <div class="price price-green" itemprop="location">
            <strong>
                <span>
                   <u>đ</u> <?php echo $ride['fee']; ?> K
                </span>                    
            </strong>
            <span class="priceUnit">per passenger</span>
        </div>
        <div class="availability">
            <strong><?php echo $ride['seat_avail']; ?></strong> <span>seats left</span>
        </div>
        <?php if($joinStatus==1): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('Approved'); ?>
            </div>
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
<div class="row">
    <ul id="commentsList">
    <?php foreach ($allComments as $key => $value): ?>
        <li><img src="<?php echo $value['avatar']; ?>" alt="" width="32px" height="32px" /> <?php echo $value['content']; ?></li>
    <?php endforeach; ?>
        
    </ul>
</div>
<div id="comments">
    <?php /*if($model->commentCount>=1): ?>
        <h3>
            <?php echo $model->commentCount . 'comment(s)'; ?>
        </h3>
 
        <?php $this->renderPartial('_comments',array(
            'ride'=>$model,
            'comments'=>$model->comments,
        ));*/ ?>
    <?php //endif; ?>
    <?php $this->renderPartial('/comment/_form',array(
            'model'=>$comment,
        )); ?>
</div>

