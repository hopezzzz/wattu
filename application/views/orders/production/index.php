<?php // pr($records);die;?>
<div class="row" >
  <div class="col-md-12">
    <section class="sale_panel">
      <?php $this->load->view('orders/searchBar'); ?>
      <section class="Production_tabs_panel" id="tableData">
        <header class="panel-heading">
          <ul class="nav nav-tabs">
            <li class="active">
              <a data-toggle="tab" href="#Production "><span><?php echo ($total_rows) ?></span>Production Queue </a>
            </li>
            <li class="">
              <a data-toggle="tab" href="#Recently_Completed"><span><?php echo $totalCompleteOrder = ($complatedOrders['total_rows'] !='') ? $complatedOrders['total_rows'] : 0; ?></span> Completed (past 7 Days)</a>
            </li>
            <div class="col-lg-3 col-md-6 pull-right">
              <div class="display">Display:
                <select id="fileterByNum" rel="production-processing">
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>             ￼
                </select>
                per page
              </div>
            </div>
          </ul>
        </header>

        <div class="tab-content" id="allRecords">
          <div id="Production" class="tab-pane active">
            <div class="panel-body">
              <div class="table-responsive">
                <table id="sort2" class="grid table table-striped table-sortable">
                  <thead>
                    <tr>
                      <td class="thPriority"><b>Priority</b></td>
                      <td class="thOrder"><b>Order</b></td>
                      <td colspan="4" style="padding:0 !important;">
                        <table style="width:100%;" class="main_inner_table">
                          <tr>
                            <td class="thEstReady"><b>Est.Ready</b></td>
                            <td class="thBlockType"><b>Block Type</b></td>
                            <td class="thItems"><b>Items</b></td>
                            <td class="thBlocks"><b>#Blocks</b></td>
                          </tr>
                        </table>
                      </td>
                      <td class="thStatus"><b>Change Status</b></td>
                    </tr>
                  </thead>
                  <tbody class="ui-sortable">
                    <?php if (!empty($records)) {
                      $currentday 					= productionDays(date('D'));
                      $currentDate 					= date('d-m-Y');
                      $readyAsstimatedDate          = '';
                      $totalBlocksAvailbleToday     = 0;
                      $orderCount   				= 0;
                      $tr 							= 1;
                      $production   				= getCurrrentProductionOutput($currentday);
                     // pr($production);
                        foreach ($records as $orderKey => $value) {
                          if ($value->orderStatus == 'inProduction' || $value->orderStatus == 'queued' ) {?>
                            <tr sort="<?php echo $tr; $tr++;?>" ref="<?php  echo $value->orderRef;?>" class="sortNo" id="order_<?php  echo $value->orderRef;?>" style="display: table-row;">
                              <td><b><i class="fa fa-arrows" aria-hidden="true"></i></b> <?php $orderKey++; echo $orderKey  ?></td>
                              <td><?php echo $value->businessName.' # '.$value->orderNo; ?></td>
                              <td colspan="4">
                              <table class="order-item-table" style="width:100%;">
                                <tbody>
                                  <?php
                                  $orderItemsRefIds  = '';
                                  $orderItemsEstDate = '';
                                  if (!empty($value->orderItems)): ?>
                                    <?php
                                    $checkCount = 0;
                                    $orderItemsRefIds  = '';
                                    $orderItemsEstDate = '';
                                    foreach ($value->orderItems as $key => $orderItems) {
                                      // pr($orderItems);;
                                      if (!empty($orderItems->variants))
                                      {
                                        $total = 0;
                                        $numItems = count($orderItems->variants);
                                        $io = 0;

                                        $itmesblockper = blockPercentage($orderItems->variants);
                                        for ($i=0; $i < count($orderItems->variants); $i++) {
                                          // echo $i; echo '<br>';
                                        $valuevariants =  ($orderItems->variants[$i]);
                                        // pr($valuevariants);;
                                        $blockqty       = $valuevariants->qty;
                                        $blockPercetage = $valuevariants->blockPercentage;
                                        $total  +=  ( $valuevariants->blockPercentage * $valuevariants->qty) / 100;
                                      //  echo "production======> ".$production.'<br>';
                                      //  echo "blockPercentage======> ".$valuevariants->blockPercentage.'<br>';
                                      //  echo "$production >= $valuevariants->blockPercentage <br>";

                                        if (intval($production ) >= intval( $valuevariants->blockPercentage ) )
                                        {
                                          // echo "stringIF";
                                          // $total = ( $valuevariants->blockPercentage * $valuevariants->qty  ) / 100;
                                          $readyAsstimatedDate  = date('d-m-Y',strtotime(''.nextDays($currentday).''));
                                          $production           =  $production - $valuevariants->blockPercentage;
                                          ?>
                                          <tr class="<?php if($i == 0) echo 'firstTr' ?>">
                                            <td class="est-date">
                                              <?php
                                              $orderItemsRefIds  .= $valuevariants->variant_id.',';
                                              $orderItemsEstDate .= $currentDate.',';
                                              ?>
                                              <?php if ($currentDate !='' )  echo $currentDate;?>
                                            </td>
                                            <td class="block_type"><?php echo $valuevariants->blockType;?></td>
                                            <td class="items"><?php echo ucwords($orderItems->itemName) ?>: <br>
                                              <?php
                                              print($valuevariants->length."x".$valuevariants->width."x".$valuevariants->height." ".$valuevariants->design." ".$valuevariants->color. '('. $valuevariants->qty . ' ) '. $orderItems->baseUOM ."</br>");//echo "</br>";
                                            ?> </td>
                                              <?php if ($i == 0 ): ?>
                                                  <td class="blockNo"><?php echo $itmesblockper; ?></td>
                                              <?php else: ?>
                                                  <td></td>
                                              <?php endif; ?>


                                          </tr>
                                      <?php }else
                                      {
                                        // echo "stringIELSE";

                                          $currentday++;
                                          $currentDate         = date('d-m-Y',strtotime($currentDate . "+1 day"));
                                          if($currentday > 6)
                                          {
                                            $currentday = 0;
                                          }
                                          $production           =  getCurrrentProductionOutput($currentday);
                                          if ($checkCount >= 200) {
                                            ?>
                                            <tr>
                                              <td class="est-date">  <?php echo "N/A";?></td>
                                              <td class="block_type"><?php echo $valuevariants->blockType;?></td>
                                              <td class="items"><?php echo ucwords($orderItems->itemName) ?>: <br>
                                                <?php
                                                print($valuevariants->design."x".$valuevariants->width."x".$valuevariants->length." ".$valuevariants->height." ".$valuevariants->color. '('. $valuevariants->qty . ' ) '. $orderItems->baseUOM ."</br>");//echo "</br>";
                                              ?> </td>
                                              <?php if ($i == 0 ): ?>
                                                  <td class="blockNo"><?php echo $itmesblockper; ?></td>
                                              <?php else: ?>
                                                  <td></td>
                                              <?php endif; ?>
                                            </tr>
                                          <?php
                                        }else { /*?>
                                          <tr>
                                            <td class="est-date">  <?php echo "N/A";?></td>
                                            <td class="block_type"><?php echo $valuevariants->blockType;?></td>
                                            <td class="items"><?php echo ucwords($orderItems->itemName) ?>: <br>
                                              <?php
                                              print($valuevariants->height."x".$valuevariants->width."x".$valuevariants->length." ".$valuevariants->design." ".$valuevariants->color. '('. $valuevariants->qty . ' ) '. $orderItems->baseUOM ."</br>");//echo "</br>";
                                            ?> </td>
                                            <?php if (++$i === $numItems): ?>
                                              <td class="blockNo"><?php echo $total; ?></td>

                                            <?php else:?>
                                              <td class="blockNo"><?php echo $total; ?></td>
                                            <?php endif; ?>
                                          </tr>
                                          <?php*/
                                          $i = $i -1;
                                         }
                                      }
                                      $checkCount++;
                                      }
                                    }

                                  }
                                   ?>
                                  <?php endif; ?>
                              </tbody>
                            </table>
                              </td>
                              <td class="buttonTd">
                                <div class="dropdown">
                                  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <?php echo orderStatus($value->orderStatus);?>
                                  </a>
                                  <div class="dropdown-menu col-sm-12" aria-labelledby="dropdownMenuLink">
                                    <ul class="">
                                      <li><a class="dropdown-item changeOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-reload="true" data-orderno="<?php echo $value->orderNo;?>" data-to="queued" data-pipline="3" data-production="<?php echo $value->orderInProduction;?>" href="javascript:void(0)">Queued</a></li>
                                      <li><a class="dropdown-item changeOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-reload="true" data-orderno="<?php echo $value->orderNo;?>" data-to="inProduction" data-pipline="3" data-production="<?php echo $value->orderInProduction;?>" href="javascript:void(0)">In Production</a></li>
                                      <li><a class="dropdown-item changeOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-reload="true" data-orderno="<?php echo $value->orderNo;?>" data-to="onHold"  data-pipline="3" data-production="<?php echo $value->orderInProduction;?>" href="javascript:void(0)">On Hold</a></li>
                                      <li><a data-itemRefIds="<?php echo $orderItemsRefIds;?>" data-estDates="<?php echo $orderItemsEstDate;?>" class="dropdown-item changeOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-reload="true" data-orderno="<?php echo $value->orderNo;?>" data-to="pending" data-pipline="4" data-production="<?php echo $value->orderInProduction;?>" href="javascript:void(0)"> Complete</a></li>
                                    </ul>
                                  </div>
                                </div>
                                <a target="_blank" class="btn btn-primary view" href="<?php echo base_url().'order-details/'.$value->orderRef;?>" role="button">View Detail</a>
                              </td>
                            </tr>
                        <?php  } else {
                        ?>
                          <tr  sort="<?php echo $tr; $tr++;?>" ref="<?php  echo $value->orderRef;?>" class="sortNo" id="order_<?php  echo $value->orderRef;?>" style="display: table-row;">
                            <td><b><i class="fa fa-arrows" aria-hidden="true"></i></b> <?php $orderKey++; echo $orderKey  ?></td>
                            <td><?php echo $value->businessName.' # '.$value->orderNo; ?></td>
                            <td colspan="4">
                            <table class="order-item-table" style="width:100%;">
                              <tbody>
                                <?php
                                if (!empty($value->orderItems)): ?>
                                  <?php
                                  //pr($records);die;
                                  $checkCount = 0;

                                  // for ($i; $i < count($value->orderItems); $i++)
                                  foreach ($value->orderItems as $key => $orderItems)
                                  {


                                    $total = 0;
                                    $numItems = count($orderItems->variants);
                                    $io = 0;
                                    $itmesblockper = blockPercentage($orderItems->variants);

                                    if (!empty($orderItems->variants))
                                    {
                                      foreach ($orderItems->variants as $key => $valuevariants)
                                      {

                                        $blockqty       = $valuevariants->qty;
                                        $blockPercetage = $valuevariants->blockPercentage;

                                        $total  +=  ( $valuevariants->blockPercentage * $valuevariants->qty) / 100;
                                      ?>
                                      <tr class="<?php if($key == 0) echo 'firstTr'; ?>">
                                        <td class="est-date"> <?php echo orderStatus($value->orderStatus);?> </td>
                                        <td class="block_type"><?php echo $valuevariants->blockType ;?></td>
                                        <td class="items"><?php echo ucwords($orderItems->itemName) ?>: <br><?php
                                        print($valuevariants->length."x".$valuevariants->width."x".$valuevariants->height." ".$valuevariants->design." ".$valuevariants->color. '('. $valuevariants->qty . ' ) '. $orderItems->baseUOM ."</br>");//echo "</br>";
                                        ?> </td>
                                          <?php if ($key == 0): ?>
                                            <td class="blockNo"><?php echo $itmesblockper; ?></td>

                                          <?php else: ?>
                                            <td></td>
                                          <?php endif; ?>


                                      </tr>
                                    <?php
                                      $checkCount++;
                                      }
                                    }
                                  }
                                 ?>
                                <?php endif; ?>
                            </tbody>
                          </table>
                            </td>
                            <td class="buttonTd">
                              <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo orderStatus($value->orderStatus);?>
                                </a>
                                <div class="dropdown-menu col-sm-12" aria-labelledby="dropdownMenuLink">
                                  <ul class="">
                                    <li><a class="dropdown-item changeOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-reload="true" data-orderno="<?php echo $value->orderNo;?>" data-to="queued" data-pipline="3" data-production="<?php echo $value->orderInProduction;?>" href="javascript:void(0)">Queued</a></li>
                                    <li><a class="dropdown-item changeOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-reload="true" data-orderno="<?php echo $value->orderNo;?>" data-to="inProduction" data-pipline="3" data-production="<?php echo $value->orderInProduction;?>" href="javascript:void(0)">In Production</a></li>
                                    <li><a class="dropdown-item changeOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-reload="true" data-orderno="<?php echo $value->orderNo;?>" data-to="onHold"  data-pipline="3" data-production="<?php echo $value->orderInProduction;?>" href="javascript:void(0)">On Hold</a></li>
                                    <li><a class="dropdown-item changeOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-reload="true" data-orderno="<?php echo $value->orderNo;?>" data-to="pending" data-pipline="4" data-production="<?php echo $value->orderInProduction;?>" href="javascript:void(0)"> Complete</a></li>
                                  </ul>
                                </div>
                              </div>
                              <a class="btn btn-primary view" href="<?php echo base_url().'order-details/'.$value->orderRef;?>" role="button">View Detail</a>
                            </td>
                          </tr>
                        <?php
                        }

                          $orderCount++;
                         }
                    }

                    else
                    { ?>
                        <tr><td align="center" colspan="13">No Order Found.</td></tr>
                      <?php   } ?>
                  </tbody>
                </table>
                <!-- <div class="">
                  <?php //echo $paginationLinks; ?>
                </div> -->
              </div>
            </div>
          </div>

          <div id="Recently_Completed" class="tab-pane">
            <div class="panel-body">
              <div class="table-responsive">
                <table id="" class="grid table table-striped">
                  <thead>
                    <tr>
                      <td class="thPriority"><b>Sr.No.</b></td>
                      <td class="thOrder"><b>Order</b></td>
                      <td colspan="5" style="padding:0 !important;">
                        <table style="width:100%;" class="main_inner_table">
                          <tr>
                            <td class="thEstReady"><b>Completed Date</b></td>
                            <td class="thBlockType"><b>Block Type</b></td>
                            <td class="thItems"><b>Items</b></td>
                            <td class="thBlocks"><b>#Blocks</b></td>
                          </tr>
                        </table>
                      </td>
                      <td class="thStatus"><b>Status</b></td>
                    </tr>
                  </thead>

                  <tbody class="" style="">
                    <?php if (!empty($complatedOrders)) {
                      $currentday = productionDays(date('D'));
                      $currentDate = date('d-m-Y');
                      // echo $currentday;die;
                      $currentDayProductionCount    = 0;
                      $readyAsstimatedDate          = '';
                      $totalBlocksAvailbleToday     = 0;
                      $orderCount                   = 0;
                      $tr                           = 1;
                      $production   = getCurrrentProductionOutput($currentday);
                        foreach ($complatedOrders['result'] as $orderKey => $value) { ?>
                            <tr  sort="<?php echo $tr; $tr++;?>" ref="<?php  echo $value->orderRef;?>" class="sortNo" id="order_<?php  echo $value->orderRef;?>" style="display: table-row;">
                              <td class="tdCounter"> <?php $orderKey++; echo $orderKey  ?></td>
                              <td><a href="<?php echo base_url().'order-details/'.$value->orderRef ?>"> <?php echo $value->businessName.' # '.$value->orderNo; ?> </a> </td>
                              <td colspan="5">
                              <table class="order-item-table" style="width:100%;">
                                <tbody>
                                  <?php
                                  if (!empty($value->orderItems)): ?>
                                    <?php
                                    $checkCount = 0;

                                    for ($i=0; $i < count($value->orderItems); $i++) {
                                        $orderItems = $value->orderItems[$i];
                                        // pr($orderItems);die;
                                        $total = 0;
                                        $numItems = count($orderItems->variants);
                                        $io = 0;

                                        if (!empty($orderItems->variants))
                                        {
                                            $itmesblockper = blockPercentage($orderItems->variants);
                                          foreach ($orderItems->variants as $key => $valuevariants)
                                          {
                                            $blockqty       = $valuevariants->qty;
                                            $blockPercetage = $valuevariants->blockPercentage;

                                            $total  +=  ( $valuevariants->blockPercentage * $valuevariants->qty) / 100;
                                        ?>
                                        <tr class="<?php if($i == 0) echo 'firstTr'; ?>">
                                          <?php

                                           ?>
                                          <td class="est-date">
                                            <?php
                                                  if($valuevariants->readyEstDate == '01-01-1970' || $valuevariants->readyEstDate == '0000-00-00' || $valuevariants->readyEstDate == '')
                                                       echo date('d-m-Y',strtotime('now') );
                                                  else
                                                       echo date('d-m-Y',strtotime($valuevariants->readyEstDate) );
                                            ?>
                                          </td>
                                          <td class="block_type"><?php echo $valuevariants->blockType;?></td>
                                          <td class="items"><?php echo ucwords($orderItems->itemName) ?>: <br><?php

                                                print($valuevariants->length."x".$valuevariants->width."x".$valuevariants->length." ".$valuevariants->height." ".$valuevariants->color. '('. $valuevariants->qty . ' )</br>');//echo "</br>";

                                          ?> </td>
                                          <?php if ($key == 0): ?>
                                            <td class="blockNo"><?php echo $itmesblockper; ?></td>

                                          <?php else:?>
                                            <td class="blockNo"></td>
                                          <?php endif; ?>
                                        </tr>
                                      <?php
                                    }
                                  }
                                        $checkCount++;
                                  }
                                   ?>
                                  <?php endif; ?>
                              </tbody>
                            </table>
                              </td>
                              <td>Completed</td>
                              </tr>
                        <?php

                          $orderCount++;
                         }
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

        </div>

      </section>
      <div id="orderSearchRecords" style="display:none"></div>
    </section>
     <input type="hidden" name="page_order_list" id="page_order_list" />
   </div>

</div>
