<div>rearrange by: departure date - prices</div>
<ul class="trip-search-results">
    <?php foreach ($allTrips as $key => $trip): ?>
        <li class="trip" itemscope="" itemtype="">
            <a href="view/?id=<?php echo $trip['id']; ?>" rel="nofollow" class="trip-search-oneresult">
                <article class="table_row">
                    <div class="cell user cell2">
                        <img class="photo" src="<?php echo Yum::module('avatar')->getAvatarThumb($trip['avatar']); ?>" width="42" height="42">

                        <div class="user-info">
                            <h3 class="username"><?php echo $trip['username']; ?></h3>
                        </div>
                        <div class="user-trust">
                            <div class="rating-container">
                                <?php
                                $this->widget('PcStarRankWidget', array('modelId' => $trip['user_id'], 'modelClassName' => get_class($model)));
                                ?>
                            </div>
                            <div class="row"><?php echo Yii::t('translator','Leaded tours'); ?>: <?php echo $trip['tour_no']; ?></div>
                            <div class="row"><?php echo Yii::t('translator','Member since');?>: <?php echo date('d-m-Y',$trip['createtime']);?></div>
                            
                            <div class="preferences-container">
                                <span class="blabla prefs tip"></span>
                                <span class="no-smoking prefs tip"></span>
                            </div>
                        </div>
                    </div>
                    <div class="cell cell5">
                        <div class="time" itemprop="startDate"><?php echo $trip['name']; ?></div>
                        <h4/>
                        <?php
                        $leaveDate = new DateTime($trip['leave']);
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
                    <div class="cell border_right offer cell2">
                        <div class="price price-green" itemprop="location">
                            <strong>
                            <span>
                               <u>đ</u> <?php echo $trip['fee']; ?>K
                            </span>
                            </strong>
                            <span class="priceUnit"><?php echo Yii::t('translator','per passenger');?></span>
                        </div>
                        <div class="availability">
                            <strong><?php echo $trip['seat_avail']; ?></strong> <span><?php echo Yii::t('translator','seat(s) left');?></span>
                        </div>
                    </div>
                </article>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

