
    <?php
    // pr($statistics['orderStats']);die;
    // Reassign Order percentage
    $totalOrders = $statistics['orderStats']->totalOrder;
    $reasignPercentage        = number_format(($statistics['orderStats']->reAssignedOrders > 0 ) ? $statistics['orderStats']->reAssignedOrders / $statistics['orderStats']->reAssignedOrders * 100 : 0,1).'%';
    $reasignOrders            = ($statistics['orderStats']->reAssignedOrders > 0 ) ? $statistics['orderStats']->reAssignedOrders : 0;

    // total sales gross amount
    $grossSales               = number_format(($statistics['orderStats']->grossSales > 0 ) ? $statistics['orderStats']->grossSales : 0,2);

    // total manager approval needed oreders
    $managerApprovedOrders    = number_format(($statistics['orderStats']->managerApprovedOrders > 0 && $statistics['orderStats']->totalOrder > 0) ? $statistics['orderStats']->managerApprovedOrders / $statistics['orderStats']->totalOrder * 100 : 0,1).'%';
    $totalManagerOrders       = ($statistics['orderStats']->managerApprovedOrders > 0 ) ? $statistics['orderStats']->managerApprovedOrders : 0;

    // total numbers of cancel order
    $cancelledOrders          = number_format(($statistics['orderStats']->cancelledOrders > 0 && $statistics['orderStats']->totalOrder > 0 ) ? $statistics['orderStats']->cancelledOrders / $statistics['orderStats']->totalOrder * 100 : 0,1).'%';
    $totalCancelledOrders          = ($statistics['orderStats']->cancelledOrders > 0 ) ? $statistics['orderStats']->cancelledOrders : 0;

    // total numbers of cancel order
    $aprovedOrdersPercentage  = number_format(($statistics['orderStats']->aprovedOrders > 0 && $statistics['orderStats']->approvalQueue > 0 ) ? $statistics['orderStats']->aprovedOrders / $statistics['orderStats']->approvalQueue * 100 : 0,1).'%';
    $totalAprovedOrder        = ($statistics['orderStats']->aprovedOrders > 0 ) ? $statistics['orderStats']->aprovedOrders : 0;

    // total blocks
    $blockPercentage          = number_format(($statistics['blockPercentage']->productPercentage > 0 && $statistics['blockPercentage']->totalBlocks > 0) ? $statistics['blockPercentage']->productPercentage / $statistics['blockPercentage']->totalBlocks * 100 : 0,1).'%';
    $totalblocks              = ($statistics['blockPercentage']->productPercentage > 0 ) ? $statistics['blockPercentage']->productPercentage : 0;

    // getting dispatch percentage
    $dispatchPercentage       = number_format(($statistics['dispatch']->dispached > 0 && $statistics['dispatch']->orderCount > 0 ) ? $statistics['dispatch']->dispached / $statistics['dispatch']->orderCount * 100 : 0,1).'%';
    $totalDispatch            = ($statistics['dispatch']->dispached > 0 ) ? $statistics['dispatch']->dispached : 0;

    // total numbers of refuesedOrders
    $refuesedOrderPercentage  = number_format((isset($statistics['dispatchStats']->refuesedOrder) &&  $statistics['dispatchStats']->refuesedOrder > 0 && $statistics['dispatchStats']->dispatchOrders > 0) ? $statistics['dispatchStats']->refuesedOrder / $statistics['dispatchStats']->dispatchOrders * 100 : 0,1).'%';
    $totalRefuesedOrder       = (isset($statistics['dispatchStats']->refuesedOrder) && $statistics['dispatchStats']->refuesedOrder > 0 ) ? $statistics['dispatchStats']->refuesedOrder : 0;

    // total numbers of defectiveGoods
    $defectiveGoodsPercentage  = number_format((isset($statistics['dispatchStats']->defectiveGoods) && $statistics['dispatchStats']->defectiveGoods > 0 ) ? $statistics['dispatchStats']->defectiveGoods / $statistics['dispatchStats']->dispatchOrders * 100 : 0,1).'%';
    $totalDefectiveGoods       = (isset($statistics['dispatchStats']->defectiveGoods) && $statistics['dispatchStats']->defectiveGoods > 0 ) ? $statistics['dispatchStats']->defectiveGoods : 0;

    // total numbers of loadingErrors
    $loadingErrorsPercentage  = number_format((isset($statistics['dispatchStats']->loadingErrors) && $statistics['dispatchStats']->loadingErrors > 0 ) ? $statistics['dispatchStats']->loadingErrors / $statistics['dispatchStats']->dispatchOrders * 100 : 0,1).'%';
    $totalLoadingErrors       = (isset($statistics['dispatchStats']->loadingErrors) && $statistics['dispatchStats']->loadingErrors > 0 ) ? $statistics['dispatchStats']->loadingErrors : 0;




     ?>
    <div class="row">
        <div class="five_col">
            <div class="box">
                <h3>Sales</h3>
                <h5><b>Re-assigned Orders</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $reasignOrders ?></h4>
                  <p>No of Order</p>
                    <h4><?php echo $reasignPercentage ?></h4>
                    <p>Order Percentage</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status</h3>
                    <div class="pipeline">
                        <h4>10 Orders <span>Past 12 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="five_col">
            <div class="box">
                <h3>Sales</h3>
                <h5><b>Management Approval Required</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $totalManagerOrders ?> </h4>
                  <p>No of Order</p>
                  <h4><?php echo $managerApprovedOrders?></h4>
                  <p>Order Percentage</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status</h3>
                    <div class="pipeline">
                        <h4>10 Orders <span>Past 12 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="five_col">
            <div class="box">
                <h3>Sales</h3>
                <h5><b>Gross Sales</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $totalOrders ?> </h4>
                  <p>No of Order</p>
                  <h4><?php echo ($grossSales)?></h4>
                  <p>Gross Amount</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status d</h3>
                    <div class="pipeline">
                        <h4><a data-toggle="collapse" data-target="#10_Orders">10 Orders <span>Past 12 Hours</span></a></h4>
                        <div class="clearfix"></div>
                        <div id="10_Orders" class="collapse">
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                        </div>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="five_col">
            <div class="box">
                <h3>Sales</h3>
                <h5><b>Refused Goods</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $totalRefuesedOrder?> </h4>
                  <p>No of Order</p>
                  <h4><?php echo  $refuesedOrderPercentage ?></h4>
                  <p>Order Percentage</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status</h3>
                    <div class="pipeline">
                        <h4>10 Orders <span>Past 12 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="five_col">
            <div class="box">
                <h3>Sales</h3>
                <h5><b>Cancelled Orders</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $totalCancelledOrders ?> </h4>
                  <p>No of Order</p>
                  <h4><?php echo $cancelledOrders?></h4>
                  <p>Order Percentage</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status</h3>
                    <div class="pipeline">
                        <h4>10 Orders <span>Past 12 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="margin:15px auto" class="clearfix"></div>
    <div class="row">
        <div class="five_col">
            <div class="box">
                <h3>Production</h3>
                <h5><b>Foam Produced</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $totalblocks ?></h4>
                  <p>Total Blocks</p>
                    <h4><?php echo $blockPercentage ?></h4>
                    <p>Block Percentage</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status</h3>
                    <div class="pipeline">
                        <h4>10 Orders <span>Past 12 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="five_col">
            <div class="box">
                <h3>Production</h3>
                <h5><b>Defective Goods</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $totalDefectiveGoods ?> </h4>
                  <p>No of Order</p>
                  <h4><?php echo $defectiveGoodsPercentage?></h4>
                  <p>Order Percentage</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status</h3>
                    <div class="pipeline">
                        <h4>10 Orders <span>Past 12 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="five_col">
            <div class="box">
                <h3>Dispatch</h3>
                <h5><b>Dispatched Orders</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $totalDispatch ?> </h4>
                  <p>No of Order</p>
                  <h4><?php echo ($dispatchPercentage)?></h4>
                  <p>Order Percentage</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status d</h3>
                    <div class="pipeline">
                        <h4><a data-toggle="collapse" data-target="#10_Orders">10 Orders <span>Past 12 Hours</span></a></h4>
                        <div class="clearfix"></div>
                        <div id="10_Orders" class="collapse">
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                            <div class="expand">
                                <h5>Sam Xyonch <span>#1541515</span></h5>
                                <h6>$150.00</h6>
                            </div>
                        </div>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="five_col">
            <div class="box">
                <h3>Dispatch</h3>
                <h5><b>Loading Errors</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $totalLoadingErrors?> </h4>
                  <p>No of Order</p>
                  <h4><?php echo  $loadingErrorsPercentage ?></h4>
                  <p>Order Percentage</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status</h3>
                    <div class="pipeline">
                        <h4>10 Orders <span>Past 12 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="five_col">
            <div class="box">
                <h3>Approval</h3>
                <h5><b>Approved Orders</b></h5>
                <div class="con_t">
                  <h4>#<?php echo $totalAprovedOrder ?> </h4>
                  <p>No of Order</p>
                  <h4><?php echo $aprovedOrdersPercentage?></h4>
                  <p>Order Percentage</p>
                </div>
                <div class="pipeline_status hide">
                    <h3>Pipeline Status</h3>
                    <div class="pipeline">
                        <h4>10 Orders <span>Past 12 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>15 Orders <span>Past 18 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>20 Orders <span>Past 20 Hours</span></h4>
                    </div>
                    <div class="pipeline">
                        <h4>250 Orders <span>Past 2 days</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
