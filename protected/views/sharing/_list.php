<ul class="trip-search-results">
    <?php foreach ($allRides as $key => $ride):?>
    <li class="trip" itemscope="" itemtype="http://schema.org/Event">
        <a href="view/?id=<?php echo $ride['id']; ?>" rel="nofollow" class="trip-search-oneresult">
            <article class="table_row">
                <div class="cell user cell2">
                    <img class="photo" src="/images/sonphuong.jpg" width="42" height="42">
                    <div class="user-info">
                        <h3 class="username"><?php echo $ride['username']; ?></h3>
                    </div>
                    <div class="user-trust">
                        <?php $this->widget('PcStarRankWidget', array('modelId' => $ride['user_id'], 'modelClassName' => 'YumUser'));?>
                        <div class="rating-container">
                            <!-- <span class="star-rating star_5"></span> -->
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
                    <div class="time" itemprop="startDate"><?php echo $ride['name']; ?></div>
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
                <div class="cell_last offer cell2">
                    <div class="price price-green" itemprop="location">
                        <strong>
                            <span>
                               <u>đ</u> <?php echo $ride['fee']; ?>K
                            </span>                    
                        </strong>
                        <span class="priceUnit">per passenger</span>
                    </div>
                    <div class="availability">
                        <strong><?php echo $ride['seat_avail']; ?></strong> <span>seats left</span>
                    </div>
                </div>
            </article>
        </a>
    </li>
    <?php endforeach;?>
</ul>
