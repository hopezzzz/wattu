
    <div class="row">
      <div class="col-md-12">
        <section class="pages" id="user_management">
        <div class="notification-search">
                    <div class="row">
                        <div class="vded">
                            <div class="form-group col-md-6">
                                <label for="">Search By Type</label>
                                <select class="form-control searchNotification">
                                    <option value="Unread">Unread</option>
                                    <option value="Starred">Starred</option>
                                    <option value="all">All</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Search By Date</label>
                                <select class="form-control searchNotification">
                                    <option value="7">Past 7 days</option>
                                    <option value="30">Past 30 days</option>
                                    <option value="60">Past 60 days</option>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-12">
                                <p>Search By Name
                                    <input type="text" class="form-control customerSearch" id="customerSearch" placeholder="customer name">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                
        <div class="notify_panel">
            <?php if (!empty($notification)): ?>
                <?php foreach ($notification['data'] as $key => $value): ?>
                    <div class="act-time">
                        <div class="activity-body act-in" id="notification_<?php echo $value->notificationRef;?>">
                            <span class="arrow"></span>
                            <div class="text">
                                <a href="javascript:void(0);" data-noti="notification" data-status="<?php echo $value->starredStaus;?>" class="updateNotification" data-name="<?php echo ucfirst($value->notificationTitle);?>" data-type="notification" data-ref="<?php echo $value->notificationRef;?>"><?php if( $value->starredStaus == 0 ){?><i class="fa fa-star-o statusTD"></i><?php } else{?><i class="fa fa-star statusTD"></i><?php } ?> </a>
                                <!-- <a class="activity-img" href="#"><img alt="" src="img/chat-avatar.jpg" class="avatar"></a> -->
                                <p class="attribution"><a href="javascript:void(0)" data-ref="<?php echo $value->notificationRef;?>" class="markAsReadNotification"><?php echo ucwords($value->notificationContactName);?></a> Order Number
                                    <?php echo "#".$value->orderNo;?>
                                </p>
                                <p class="attribution">Time:-
                                    <?php echo date('d-m-Y H:i:s',strtotime($value->addedOn)); ?>
                                </p>
                                <p>
                                    <?php echo $value->notificationMessage; ?> <a href="javascript:void(0)" data-href="<?php echo base_url().'order-details/'.$value->orderRef;?>" data-ref="<?php echo $value->notificationRef;?>" class="readStatus"><em>Order Detail</em></a></p>
                                <span class="new"><?php echo $readStatus = ($value->readStatus == 1 ) ? "unread" :''; ?></span>
                            </div>
                        </div>

                    </div>
                    <?php endforeach; ?>
                        <?php endif; ?>
            </div>
         
        </section>
    </div>
    </div>


