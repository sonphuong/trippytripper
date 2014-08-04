<div id="trip_list">
    <div class="row">
        <div class="cell user cell1">
            <a href="/index.php/profile/profile/view/id/<?php echo $data['user_id']; ?>"><img class="avatar" src="/<?php echo $data['avatar']; ?>" width="100" height="100"></a>
            <span class="username"><?php echo $data['username']; ?></span>
            <div><?php echo Yii::t('translator','Since');?>: <?php echo date('d-m-Y',$data['createtime']);?></div>
            <div><?php echo Yii::t('translator','Last online');?>: <?php echo date('d-m-Y',$data['lastvisit']);?></div>
        </div>
        <div class="cell cell2 verticalLine">
            <div class="date">
            <?php
            $leaveDate = new DateTime($data['leave']);
            $date = $leaveDate->format('Y-m-d');
            $time = $leaveDate->format('h:m');
            $today = date('Y-m-d');
            $today = new DateTime($today);
            $interval = $leaveDate->diff($today);
            if ($interval->days === 0) {
                echo 'Hôm nay - ' . $time;
            } elseif ($interval->days === 1) {
                echo 'Ngày mai - ' . $time;
            } else {
                echo $date . ' : ' . $time;
            }
            ?>
            </div>
            <a href="view/?id=<?php echo $data['id']; ?>" rel="nofollow" class="fromTo">
            <div>
                <span><?php echo $data['from']; ?></span>
                <span>→</span>
                <span><?php echo $data['to']; ?></span>
            </div>    
            </a>
            <div>
                <span class="pickUpPoint">
                    <?php echo Yii::t('translator','Pick up point');?>:
                </span>
                <strong><?php echo $data['gathering_point']; ?></strong>
            </div>
        </div>
        <div class="cell offer cell3">
            <div class="price" itemprop="location">
                <u>đ</u> <?php echo $data['fee']; ?>K
            </div>
            <span class="priceUnit"><?php echo Yii::t('translator','per passenger');?></span>
            <div class="availability">
                <strong><?php echo $data['seat_avail']; ?></strong> <span><?php echo Yii::t('translator','seat(s) left');?></span>
            </div>
        </div>
    </div>
</div>