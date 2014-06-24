<?php if(!empty($myTrips)): ?>
    <ul class="trip-search-results">
        <?php foreach ($myTrips as $key => $trip):?>
        <li class="trip" itemscope="" itemtype="http://schema.org/Event">
            
                <article class="table_row">
                    
                    <div class="cell cell3">
                        <div class="time" itemprop="startDate"><?php echo $trip['name']; ?></div>

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
                        <a href="view/?id=<?php echo $trip['id']; ?>" rel="nofollow" class="trip-search-oneresult">
                        <div>
                            <span class="trip-roads-stop"><?php echo $trip['from']; ?></span>
                            <span class="arrow-ie">→</span>
                            <span class="trip-roads-stop"><?php echo $trip['to']; ?></span>
                        </div>
                        </a>
                        <dl>
                            <span>
                                <?php echo Yii::t('translator','Pick up point');?>: <?php echo $trip['gathering_point']; ?>
                            </span>
                        </dl>
                        <div class="price price-green" itemprop="location">
                            <u>đ</u> <?php echo $trip['fee']; ?>K
                            <span class="priceUnit"><?php echo Yii::t('translator','per passenger');?></span>
                        </div>
                        <div class="availability">
                            <strong><?php echo $trip['seat_avail']; ?></strong> <span><?php echo Yii::t('translator','seat(s) left');?></span>
                        </div>
                    </div>
                    <div class="cell cell5">
                    <?php echo Yii::t('translator','Trippers');?>
                        <ol class="memberList">
                            <?php 
                            $members = $trip['members'];
                            if(!empty($members)){
                                foreach ($members as $key => $member) {
                                    if($member['join_status']==2){
                                        echo '<li class="" id="member_'.$member['user_id'].'"><img src="/'.$member['avatar'].'" alt="" width="32px" height="32px" />'.CHtml::link($member['user_name'], array('//profile/profile/view/'.$member['user_id']));                            
                                        echo '<span id="join_status_'.$member['user_id'].'">';
                                        if($trip['seat_avail']>0){
                                            echo CHtml::ajaxSubmitButton ("Accept",
                                                CController::createUrl('sharing/acceptJoin'),
                                                array(
                                                    'success' => 'js:function(data) {approveJoinSuccess(data);}'
                                                ,'data' => 'user_id='.$member['user_id'].'&trip_id='.$trip['id'].''
                                                )
                                            );
                                        }
                                        else{
                                            echo '<span>&nbsp; '.Yii::t('translator','fulled').'</span>';
                                        }
                                        echo '</span></li>';
                                    }
                                    elseif($member['join_status']==1){
                                        echo '<li class="" id="member_'.$member['user_id'].'"><img src="/'.$member['avatar'].'" alt="" width="32px" height="32px" />'.CHtml::link($member['user_name'], array('//profile/profile/view/'.$member['user_id']));
                                    }
                                }
                            }
                            else{
                                echo Yii::t('translator','No one join yet');
                            }
                            ?>
                        </ol>
                    </div>
                </article>
            
        </li>
        <?php endforeach;?>
    </ul>
<?php else: ?>    
    <div><?php echo Yii::t('translator',"You haven't not publish any trip yet! click on 'Offer' to publish a trip");?></div>
<?php endif; ?>