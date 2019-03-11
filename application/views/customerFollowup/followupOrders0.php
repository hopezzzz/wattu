<?php  $loginSessionData = $this->session->userdata('clientData');
            // echo "<pre>";print_r($loginSessionData)
?>
<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
          <th>SrNo #</th>
          <th>Order #</th>
          <th>Date &amp; Time</th>
          <th>Order Status</th>
          <th>Customer</th>
          <th>City</th>
          <!-- <th>Due Date</th> -->
          <th>Total Amount</th>
          <th>No. of Items</th>
        </tr>
    </thead>
    <tbody>
      <?php  if (!empty($records))
      {
        $status = array('Inactive','Active');
          foreach ($records as $key => $value)
          {

            ?>
            <tr id="order_<?php echo $value->orderRef;?>" data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable parentAccrodion" data-ref="<?php echo $value->orderRef;?>">
                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable  parentAccrodion btn btn-primary" data-ref="<?php echo $value->orderRef;?>"> + <?php echo $key + 1; ?></td>
                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable parentAccrodion " data-ref="<?php echo $value->orderRef;?>"><a href="javascript:void(0)"><?php echo $value->orderNo;?></a></td>
                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable parentAccrodion " data-ref="<?php echo $value->orderRef;?>"><?php echo date($value->addedOn,strtotime('d-m-Y H:i:s'));?></td>
                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable parentAccrodion " data-ref="<?php echo $value->orderRef;?>"><?php echo orderStatus($value->orderStatus);?></td>
                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable parentAccrodion " data-ref="<?php echo $value->orderRef;?>"></td>
                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable parentAccrodion " data-ref="<?php echo $value->orderRef;?>"></td>
                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable parentAccrodion " data-ref="<?php echo $value->orderRef;?>"><?php echo amountFormat($value->orderPrice);?></td>
                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable parentAccrodion " data-ref="<?php echo $value->orderRef;?>"></td>
                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable parentAccrodion " data-ref="<?php echo $value->orderRef;?>"><a href="<?php echo base_url().'order-details/'.$value->orderRef;?>" class="btn btn-primary">View</a></td>
                </tr>
                <?php foreach ($value->dispatchOrderDetails  as $dispatchKey => $dispatchOrderDetails): ?>

                          <?php if ($dispatchKey == 0): ?>
                            <tr style="display:none">
                              <td colspan="9">
                                <div class="collapse inner-item-det" id="inner-item-detail_<?php echo $value->orderRef;?>">
                                <table class="table table-bordered" style="width:100%;margin:0" >
                                      <tr>
                                        <th data-toggle="collapse" data-target="#inner-item-detail_<?php echo $dispatchOrderDetails->dispatchNo;?>" href="javascript:void(0)" class="clickable width25" data-ref="<?php echo $dispatchOrderDetails->dispatchNo;?>">Customer Name</th>
                                        <th data-toggle="collapse" data-target="#inner-item-detail_<?php echo $dispatchOrderDetails->dispatchNo;?>" href="javascript:void(0)" class="clickable width25" data-ref="<?php echo $dispatchOrderDetails->dispatchNo;?>">Dispatch No.</th>
                                        <th data-toggle="collapse" data-target="#inner-item-detail_<?php echo $dispatchOrderDetails->dispatchNo;?>" href="javascript:void(0)" class="clickable width25" data-ref="<?php echo $dispatchOrderDetails->dispatchNo;?>">Loading Sheet</th>
                                        <th data-toggle="collapse" data-target="#inner-item-detail_<?php echo $dispatchOrderDetails->dispatchNo;?>" href="javascript:void(0)" class="clickable width25" data-ref="<?php echo $dispatchOrderDetails->dispatchNo;?>">Fulfillment</th>
                                      </tr>
                          <?php endif; ?>
                              <tr>
                                <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $dispatchOrderDetails->dispatchNo;?>" href="javascript:void(0)" class="clickable width25" data-ref="<?php echo $dispatchOrderDetails->dispatchNo;?>"> <?php echo $dispatchOrderDetails->fullname ?> </td>
                                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $dispatchOrderDetails->dispatchNo;?>" href="javascript:void(0)" class="clickable width25" data-ref="<?php echo $dispatchOrderDetails->dispatchNo;?>"> <?php echo $dispatchOrderDetails->dispatchNo ?></td>
                                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $dispatchOrderDetails->dispatchNo;?>" href="javascript:void(0)" class="clickable width25" data-ref="<?php echo $dispatchOrderDetails->dispatchNo;?>"><?php $loadingSheets = getLoadingSheets(); if (!empty($loadingSheets['loadingSheets']) ): ?>
                                    <?php foreach ($loadingSheets['loadingSheets'] as $loadingSheetKey => $loadingSheetvalue): ?>
                                       <?php if($dispatchOrderDetails->sheetRef == $loadingSheetvalue->sheetRef) {
                                          echo $loadingSheetvalue->refName;
                                        } ?>
                                    <?php endforeach; ?>
                                  <?php endif; ?></td>
                                  <td data-toggle="collapse" data-target="#inner-item-detail_<?php echo $dispatchOrderDetails->dispatchNo;?>" href="javascript:void(0)" class="clickable width25" data-ref="<?php echo $dispatchOrderDetails->dispatchNo;?>">
                                    <?php $array = array('','Incomplete','Complete');
                                      echo $array[$dispatchOrderDetails->fullfilment];
                                     ?>
                                   </td>
                              </tr>
                            <?php if ($dispatchKey == count($value->dispatchOrderDetails) - 1): ?>
                            </table>
                            </div>
                          </td>
                        </tr>
                            <?php endif; ?>
                          <?php foreach ($dispatchOrderDetails->dispatchtems as $dispatchtemskey => $dispatchtems): ?>
                              <?php if ($dispatchtemskey == 0): ?>
                                <tr style="display:none">
                                  <td colspan="9">
                                    <div class="collapse inner-item-det" id="inner-item-detail_<?php echo $dispatchOrderDetails->dispatchNo;?>">
                                    <table class="table table-bordered" style="width:100%">
                                <tr>
                                  <th class="width16">Sr No</th>
                                  <th class="width16">Item Name</th>
                                  <th class="width16">Size</th>
                                  <th class="width16">Block Type</th>
                                  <th class="width16">Quantity</th>
                                  <th class="width16">Price</th>
                                </tr>
                              <?php endif; ?>
                              <tr>
                                <td class="width16">
                                  <?php echo $dispatchtemskey+1 ?>
                                </td>
                                <td class="width16">
                                  <?php echo $dispatchtems->itemName ?>
                                </td>
                                <td class="width16">
                                  <?php echo  getDiamentions($dispatchtems->height,$dispatchtems->width,$dispatchtems->length,$dispatchtems->length) ?>
                                </td>
                                <td class="width16">
                                  <?php if($dispatchtems->blockType !='') $blockType = array('NA','Recon','Comfort'); echo $blockType[$dispatchtems->blockType];?>
                                </td>
                                <td class="width16">
                                  <?php  echo $dispatchtems->qtyLoaded;
                                  // echo (getOrderQtyItems($value->orderRef, $dispatchtems->itemRefId)->orderQty) ? getOrderQtyItems($value->orderRef, $dispatchtems->itemRefId)->orderQty : '';
                                  ?>
                                </td>
                                <td class="width16">
                                  <?php
                                  $orderPrice = getOrderQtyItems($value->orderRef, $dispatchtems->itemRefId)->price;
                                  $orderQty = getOrderQtyItems($value->orderRef, $dispatchtems->itemRefId)->orderQty;
                                  $orderPrice += $orderPrice * $orderQty;
                                  ?>
                                  <?php echo amountFormat($orderPrice); ?>
                                </td>
                                <!-- <td></td> -->
                              </tr>
                              <?php if ($dispatchtemskey == count($dispatchOrderDetails->dispatchtems) - 1): ?>
                              </table>
                            </div>
                            </td>
                          </tr>
                          <tr>
                            <td></td>
                          </tr>
                              <?php endif; ?>

                          <?php endforeach; ?>
                <?php endforeach; ?>
    <?php }

      }
      else
      { ?>
          <tr><td align="center" colspan="13">No customers found.</td></tr>
    <?php   }  ?>
    </tbody>
  </table>
  <div class="">
    <?php echo $paginationLinks; ?>
  </div>


</div>
