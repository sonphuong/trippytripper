<ul class="trip-search-results">
    <?php foreach ($myRides as $key => $ride):?>
    <li class="trip" itemscope="" itemtype="http://schema.org/Event">
        
            <article class="table_row">
                
                <div class="cell cell3">
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
                    <a href="view/?id=<?php echo $ride['id']; ?>" rel="nofollow" class="trip-search-oneresult">
                    <div>
                        <span class="trip-roads-stop"><?php echo $ride['from']; ?></span>
                        <span class="arrow-ie">→</span>
                        <span class="trip-roads-stop"><?php echo $ride['to']; ?></span>
                    </div>
                    </a>
                    <dl>
                        <span>
                            Pick up point: Ciputra
                        </span>
                    </dl>
                    <div class="price price-green" itemprop="location">
                        <u>đ</u> <?php echo $ride['fee']; ?>K
                        <span class="priceUnit">1 người</span>
                    </div>
                    <div class="availability">
                        <strong><?php echo $ride['seat_avail']; ?></strong> <span>seats left</span>
                    </div>
                </div>
                <div class="cell cell5">
                Members
                    <ul>
                        <?php 
                        if(!empty($ride['members'])){
                            foreach ($ride['members'] as $key => $member) {
                                echo '<li>'.$member['user_name'];
                                echo '<span id="join_status" style="float:left">';    
                                if($member['join_status']==2){
                                    echo CHtml::ajaxLink ("approve",
                                          CController::createUrl('sharing/approve'), 
                                          array('success' => 'js:function(data) { 
                                                                                    $("#join_status").html("");
                                                                                }')
                                          );    
                                }
                                else{
                                    echo '';                                    
                                }
                                echo '</span></li>';

                            }
                        }?>
                    </ul>
                </div>
            </article>
        
    </li>
    <?php endforeach;?>
</ul>
