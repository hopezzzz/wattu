<div class="row">
    <div class="col-md-6">
        <div class="panel-body">
          <div class="row" style="margin-top:-25px">
            <div class="col-md-12 no-padding">
              <div class="boxs">
                <h3>My Sales</h3>
              </div>
            </div>
          </div>


            <?php
            $targetAmount               = (isset($states['Sales']['targetAmount'])) ? $states['Sales']['targetAmount'] : 0 ;
            $totalSalesAmount           = (isset($states['Sales']['totalSalesAmount'])) ? $states['Sales']['totalSalesAmount'] : 0 ;
            $totalSalesPercentage       = (isset($states['Sales']['totalSalesPercentage'])) ? $states['Sales']['totalSalesPercentage'] : 0 ;
            $targetAmountType           = (isset($states['Sales']['targetAmountType'])) ? $states['Sales']['targetAmountType'] : 0 ;

            $financemyTarget            = (isset($states['financeOrders']['myTarget'])) ? $states['financeOrders']['myTarget'] : 0  ;
            $financeOrdersType          = (isset($states['financeOrders']['targetType'])) ? $states['financeOrders']['targetType'] : 0  ;
            $financeOrdersOrder         = (isset($states['financeOrders']['totalOrder'])) ? $states['financeOrders']['totalOrder'] : 0  ;
            $financeOrdersPercentage    = (isset($states['financeOrders']['orderPercentage'])) ? $states['financeOrders']['orderPercentage'] : 0  ;

            $approvalOrdersTarget       = (isset($states['approvalOrders']['myTarget'])) ? $states['approvalOrders']['myTarget'] : 0  ;
            $approvalOrdersType         = (isset($states['approvalOrders']['targetType'])) ? $states['approvalOrders']['targetType'] : 0  ;
            $approvalOrdersOrder        = (isset($states['approvalOrders']['totalOrder'])) ? $states['approvalOrders']['totalOrder'] : 0  ;
            $approvalOrdersPercentage   = (isset($states['approvalOrders']['orderPercentage'])) ? $states['approvalOrders']['orderPercentage'] : 0  ;

            $cancelledOrdersTarget      = (isset($states['cancelledOrders']['myTarget'])) ? $states['cancelledOrders']['myTarget'] : 0  ;
            $cancelledOrdersType        = (isset($states['cancelledOrders']['targetType'])) ? $states['cancelledOrders']['targetType'] : 0  ;
            $cancelledOrdersOrder       = (isset($states['cancelledOrders']['totalOrder'])) ? $states['cancelledOrders']['totalOrder'] : 0  ;
            $cancelledOrdersPercentage  = (isset($states['cancelledOrders']['orderPercentage'])) ? $states['cancelledOrders']['orderPercentage'] : 0  ;

            $refuesedOrdersTarget       = (isset($states['refuesedOrders']['myTarget'])) ? $states['refuesedOrders']['myTarget'] : 0  ;
            $refuesedOrdersType         = (isset($states['refuesedOrders']['targetType'])) ? $states['refuesedOrders']['targetType'] : 0  ;
            $refuesedOrdersOrder        = (isset($states['refuesedOrders']['totalOrder'])) ? $states['refuesedOrders']['totalOrder'] : 0  ;
            $refuesedOrdersPercentage   = (isset($states['refuesedOrders']['orderPercentage'])) ? $states['refuesedOrders']['orderPercentage'] : 0  ;

            $currentDateTime  = date('Y-m-d H:i:s',strtotime('now'));
            $past12hour       = date('Y-m-d H:i:s',strtotime('-12 hours'));
            $past24hour       = date('Y-m-d H:i:s',strtotime('-24 hours'));
            $past48hour       = date('Y-m-d H:i:s',strtotime('-48 hours'));
            $count12 = $count24 = $count48 = $else = 0;
            foreach ($states['reAssingOrders'] as $key => $value) {

              if ($past12hour  <= $value->modifiedDate ) {
                $count12++;
              } elseif ($past12hour >= $value->modifiedDate &&  $past24hour <= $value->modifiedDate) {
                $count24++;
              } elseif($past24hour >= $value->modifiedDate && $value->modifiedDate >= $past48hour) {
                $count48++;
              }
              else{
                  $else++;
              }


            }

            ?>
          <div class="col-md-6 text">
            <h3>Target &nbsp;&nbsp;&nbsp;&nbsp;<span class="salePrice">$<?php echo $targetAmount ?></span></h3>
          </div>
          <div class="col-md-6 text">
            <h3>Sales &nbsp;&nbsp;&nbsp;&nbsp;<span class="salePrice">$<?php echo $totalSalesAmount ?></span></h3>
          </div>
          <div class="clearfix"></div>
            <div class="dashboard dashboard_metrics">
                <div class="row">
                  <div class="col-md-12">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active " role="progressbar" aria-valuenow="<?php echo $totalSalesPercentage;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $totalSalesPercentage;?>%">
                          <?php echo $totalSalesPercentage;?>%
                        </div>
                    </div>
                    <!-- <h3><?php echo $totalSalesAmount ?></h3> -->
                  </div>
                  <div class="boxs">
                    <h3>My Matrics</h3>
                  </div>
                  <div class="col-md-6">
                    <div class="box">
                      <h3>Refused Orders</h3>
                      <div class="con_t">
                        <h4>#<?php echo $refuesedOrdersOrder ?></h4>
                        <p>No of Order</p>
                        <hr>
                        <h4><?php echo number_format($refuesedOrdersPercentage,1) ?>%</h4>
                        <p>Order Percentage</p>
                      </div>
                    </div>
                  </div>
                    <div class="col-md-6">
                        <div class="box">
                            <h3>Finance</h3>
                            <div class="con_t">
                              <h4>#<?php echo $financeOrdersOrder ?></h4>
                              <p>No of Order</p>
                              <hr>
                              <h4><?php echo number_format($financeOrdersPercentage,1) ?>%</h4>
                              <p>Order Percentage</p>
                            </div>
                        </div>
                    </div>
                      <div class="clearfix" style="margin:30px auto"></div>
                    <div class="col-md-6">
                        <div class="box">
                            <h3>Approval Required</h3>
                            <div class="con_t">
                              <h4>#<?php echo $approvalOrdersOrder ?></h4>
                              <p>No of Order</p>
                              <hr>
                              <h4><?php echo number_format($approvalOrdersPercentage,1) ?>%</h4>
                              <p>Order Percentage</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <h3>Cancelled Orders</h3>
                            <div class="con_t">
                              <h4>#<?php echo $cancelledOrdersOrder ?></h4>
                              <p>No of Order</p>
                              <hr>
                              <h4><?php echo number_format($cancelledOrdersPercentage,1) ?>%</h4>
                              <p>Order Percentage</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="dashboard-listing">
                        <div class="row">
                              <div class="col-md-12 no-padding">
                                <div class="boxs">
                                  <h3>Re-assigned Order summary</h3>
                                </div>
                                <div class="col-md-6">
                                  <div class="item">
                                    Orders <?php echo $count12 ?> < 12 hours
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="item">
                                    Orders <?php echo $count24 ?>  < 12-24 hours
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="item">
                                    Orders <?php echo $count48 ?>  < 24-48 hours
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="item">
                                    Orders <?php echo $else ?> < 48+ hours
                                  </div>
                                </div>
                              </div>
                        </div>

            </div>
        </div>

    </div>

    <div class="col-md-6">
        <div id="notification-row">

            <div class="panel-body">
                <h3>Notifications</h3>
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

                    <?php if (!empty($userNotifications)): ?>
                        <?php foreach ($userNotifications['data'] as $key => $value): ?>
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
            </div>

        </div>
    </div>
</div>
</section>
