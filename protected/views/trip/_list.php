<div id="trip_list">

<div class="row">
    <div class="cell user cell1">
        <a href="avatar"><img class="avatar" src="<?php echo Yum::module('avatar')->getAvatarThumb($data['avatar']); ?>" width="42" height="42"></a>
        <div class="user-info">
            <h3 class="username"><?php echo $data['username']; ?></h3>
        </div>
        <div class="user-trust">
            <div class="rating-container">
                <?php
                //$this->widget('PcStarRankWidget', array('modelId' => $data['user_id'], 'modelClassName' => get_class($model)));
                ?>
            </div>
            
            <div class="row"><?php echo Yii::t('translator','Member since');?>: <?php echo date('d-m-Y',$data['createtime']);?></div>
            
            <div class="preferences-container">
                <span class="blabla prefs tip"></span>
                <span class="no-smoking prefs tip"></span>
            </div>
        </div>
    </div>
    <div class="cell cell2">
        <h4/>
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
        <a href="view/?id=<?php echo $data['id']; ?>" rel="nofollow" class="trip-search-oneresult">
        <h3 class="fromto" itemprop="name">
            <span class="trip-roads-stop"><?php echo $data['from']; ?></span>
            <span class="arrow-ie">→</span>
            <span class="trip-roads-stop"><?php echo $data['to']; ?></span>
        </h3>
        </a>
        <dl class="geo-from">
            <dt>
        <span class="icon icon-marker-green-small">
            <?php echo Yii::t('translator','Pick up point');?>
        </span>
            </dt>
            <dd class="tip">
                <?php echo $data['gathering_point']; ?>
            </dd>
        </dl>
    </div>
    <div class="cell offer cell3">
        <div class="price price-green" itemprop="location">
            <strong>
            <span>
               <u>đ</u> <?php echo $data['fee']; ?>K
            </span>
            </strong>
            <span class="priceUnit"><?php echo Yii::t('translator','per passenger');?></span>
        </div>
        <div class="availability">
            <strong><?php echo $data['seat_avail']; ?></strong> <span><?php echo Yii::t('translator','seat(s) left');?></span>
        </div>
    </div>
</div>
</div>