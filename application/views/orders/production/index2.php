
<div class="row">
  <div class="col-md-12">
    <section class="sale_panel">
      <?php $this->load->view('orders/searchBar'); ?>
      <section class="Production_tabs_panel">
        <header class="panel-heading">
          <ul class="nav nav-tabs">
            <li class="active">
              <a data-toggle="tab" href="#Production "><span>100</span>Production Queue </a>
            </li>
            <li class="">
              <a data-toggle="tab" href="#Recently_Completed"><span>50</span> Completed (past 7 Days)</a>
            </li>
          </ul>
        </header>

        <div class="tab-content">
          <div id="Production" class="tab-pane active">
            <div class="panel-body">
              <div class="table-responsive">
                <table id="sort2" class="grid table table-striped table-sortable">
                  <thead>
                    <tr>
                      <th>Priority</th>
                      <th>Order</th>
                      <th>Est.Ready</th>
                      <th>Block Type</th>
                      <th>Items</th>
                      <th>#Blocks</th>
                      <th>Change Status</th>
                    </tr>
                  </thead>
                  <tbody class="ui-sortable">
                    <?php if (!empty($records)) {


                      $currentday = 0;
                      $currentDayProductionCount = 0;
                      $readyAsstimatedDate = '';
                        foreach ($records as $key => $value) { ?>
                            <tr id="order_<?php echo $value->orderRef;?>" style="display: table-row;">
                              <td><b>O</b> <?php $key++; echo $key  ?></td>
                              <td><?php echo $value->businessName.' # '.$value->orderNo; ?></td>
        										  <td colspan="4">
        											<table style="width:100%;">
        												<tbody class="ui-sortable">
                                  <?php   $currentOrderBlock = 0;
                                  if (!empty($value->orderItems)): ?>
                                    <?php foreach ($value->orderItems as $key => $orderItems): ?>
                                      <tr>
                                        <td class="est-date">  <?php  if ($key == 0 && $readyAsstimatedDate!='') echo $readyAsstimatedDate; ?></td>
                                        <td class="block_type"><?php $blockType = array('NA','Recon','Comfort'); echo $blockType[$orderItems->blockType];?></td>
                                        <td class="items"><?php echo ucwords($orderItems->itemName) ?>: <?php echo  getDiamentions($orderItems->height,$orderItems->width,$orderItems->length,$orderItems->length) ?></td>
                                        <td><?php $currentOrderBlock +=$orderItems->blockPercentage;
                                        echo  $orderItems->blockPercentage; ?></td>
                                      </tr>
                                    <?php endforeach; ?>
                                  <?php endif; ?>
        											</tbody></table>
        										  </td>
        										  <td>
        											<select class="form-control">
        												<option>Active</option>
        												<option>Active</option>
        												<option>Active</option>
        											</select>
        										  </td>
        										  </tr>
                        <?php
                          $production = getCurrrentProductionOutput(date('D', strtotime('+'.$currentday.' day')));
                          // check first $currentDayProductionCount == 0 then add $current order production
                          if ($currentDayProductionCount == 0) {
                            $currentDayProductionCount = $currentOrderBlock;
                          }else{
                            // check first $currentDayProductionCount == 0 then add $current order production multiple items
                            $currentDayProductionCount += $currentOrderBlock;
                          }

                          if($production >= $currentDayProductionCount)
                          {
                            $readyAsstimatedDate =date('d-m-Y', strtotime('+'.$currentday.' day'));
                          }
                          else
                          {
                            $productionDaySkiparray = array('Sat' => 2 ,'Sun' => 1);
                            if (array_key_exists('Sat', $productionDaySkiparray)) {
                              $currentday = $productionDaySkiparray[date('D')];
                            }
                            $production = getCurrrentProductionOutput(date('D', strtotime('+'.$currentday.' day')));
                            if($production >= $currentDayProductionCount)
                            {
                              $totalDeliveredOrders = $currentDayProductionCount;
                              $readyAsstimatedDate = date('d-m-Y', strtotime('+'.$currentday.' day'));
                            }
                            else{
                              $readyAsstimatedDate =  date('d-m-Y', strtotime('+'.$currentday.' day'));
                            }
                          }
                          $currentday++;
                         }
                         // echo "<pre>";print_r($totalDeliveredOrders);echo "<pre>";
                    }
                    else
                    { ?>
                        <tr><td align="center" colspan="13">No Order Found.</td></tr>
                      <?php   } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div id="Recently_Completed" class="tab-pane">
            <div class="panel-body">
              <div class="table-responsive">
                <table id="" class="grid table table-striped table-sortable">
                  <thead>
                    <tr>
                      <th>Priority</th>
                      <th>Order</th>
                      <th>Est.Ready</th>
                      <th>Block Type</th>
                      <th>Items</th>
                      <th>#Blocks</th>
                      <th>Change Status</th>
                    </tr>
                  </thead>
                  <tbody class="ui-sortable" style="">
                    <tr style="">
                      <td><b>O</b> 1</td>
                      <td>Ajay's Shop #1234</td>
                      <td><span>30/3/18</span>
                        <table class="table table-striped">
                          <tbody class="ui-sortable" style=""><tr style="">
                            <td>04/04/18</td>
                          </tr>
                        </tbody></table>
                      </td>
                      <td><span>Comfort</span>
                        <table class="table table-striped">
                          <tbody class="ui-sortable" style=""><tr style="">
                            <td>Recon</td>
                          </tr>
                        </tbody></table>
                      </td>
                      <td><span>Comfort Cushions: 20x20x4(50pcs), 20x20x5 (25pcs), 80x40x2 (10pcs)
                        <br>Comfort Mattresses: 48x74x6 (3pcs), 54x74x6 (1pc)</span>
                        <table class="table table-striped">
                          <tbody class="ui-sortable" style=""><tr style="">
                            <td>Recon Cushions : 20x20x6 (25pcs), 80x40x1 (10pcs)</td>
                          </tr>
                        </tbody></table>
                      </td>
                      <td><span>2.5</span>
                        <table class="table table-striped">
                          <tbody class="ui-sortable" style=""><tr style="">
                            <td>1.2</td>
                          </tr>
                        </tbody></table>
                      </td>
                      <td><span>
                        <select class="form-control">
                          <option>Active</option>
                          <option>Active</option>
                          <option>Active</option>
                        </select></span>
                        <table class="table table-striped">
                          <tbody class="ui-sortable" style=""><tr style="">
                            <td>
                              <select class="form-control">
                                <option>Active</option>
                                <option>Active</option>
                                <option>Active</option>
                              </select>
                            </td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                    <tr style="">
                      <td><b>O</b> 2</td>
                      <td>Tinisha's Shop #7890</td>
                      <td>24/3/18</td>
                      <td>Comfort</td>
                      <td><span>Comfort Cushions: 20x20x4(100pcs)</span></td>
                      <td>0.5</td>
                      <td>
                        <select class="form-control">
                          <option>Active</option>
                          <option>Active</option>
                          <option>Active</option>
                        </select>
                      </td>
                    </tr>
                    <tr style="">
                      <td><b>O</b> 3</td>
                      <td>Vivek's Shop #5678</td>
                      <td>25/3/18</td>
                      <td>Comfort</td>
                      <td><span>Comfort Cushions: 20x20x5(20pcs)</span>
                      </td>
                      <td>0.2</td>
                      <td>
                        <select class="form-control">
                          <option>Active</option>
                          <option>Active</option>
                          <option>Active</option>
                        </select>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>

    </section>
