<?php $loginSessionData = $this->session->userdata('clientData');?>
<?php //pr($complatedOrders);die; ?>
<style media="screen">
.Production_tabs_panel .tab-pane span {
  font-size: inherit;
  text-align: left;
  min-height:unset !important;
  float: left;
}
</style>
<div class="row" id="rowCustomers">
  <div class="col-md-12">
    <section class="sale_panel">
      <?php  $this->load->view('orders/searchBar'); ?>
      <section class="Production_tabs_panel" id="tableData">
        <header class="panel-heading">
          <ul class="nav nav-tabs">
            <li class="active">
              <a data-toggle="tab" href="#pendingFollowup "><span><?php echo ($total_rows) ?></span>Pending Followups </a>
            </li>
            <li class="">
              <a data-toggle="tab" href="#Recently_Completed"><span><?php echo count($complatedOrders) ?></span> Completed (past 7 Days)</a>
            </li>
            <div class="col-lg-3 col-md-6 pull-right">
              <div class="display">Display:
                <select id="fileterByNum" rel="customer-follow-up-orders">
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>             ￼
                  <option value="200">200</option>             ￼
                </select>
                per page
              </div>
            </div>
          </ul>
        </header>
        <div class="tab-content" id="allRecords">
          <div id="pendingFollowup" class="tab-pane active">
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="customerFollowupTable">
                  <thead>
                    <tr>
                      <th>Sr.No</th>
                      <th>Dispatch</th>
                      <th>Business Name</th>
                      <th>Date</th>
                      <th>Invoice No.</th>
                      <th>Customer Contact</th>
                      <th>Town</th>
                      <th>Region</th>
                      <th>Received</th>
                      <th>Return</th>
                      <th>Error</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php  if (!empty($records))
                    {
                      $status = array('Inactive','Active');
                      foreach ($records as $key => $value)
                      {
                        $orderPrice = 0;
                        ?>
                        <tr>
                            <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo $key+$start+1; ?> </td>
                            <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo '#'.$value->dispatchNo ?> </td>
                            <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo $value->businessName ?></td>
                            <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo date('d-m-Y', strtotime($value->dispatchedDate)) ?></td>
                            <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php $invoiceNo = (trim($value->invoiceNo) !='') ? $value->invoiceNo : 'Not Avialable' ;echo $invoiceNo ; ?> </td>
                            <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo $value->customerName. ' ' .$value->customerPhone ?> </td>
                            <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo ucwords($value->cityName); ?> </td>
                            <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo ucwords($value->state) ?> </td>
                            <td class="receivedStatus<?php echo $value->dispatchNo;?>"><span class="label label-default"><?php $receivedStatus = ($value->receivedStatus != '' ) ? $value->receivedStatus.' Received' : 'Not Received'; echo $receivedStatus;?></span></td>
                            <td class="returnStatus<?php echo $value->dispatchNo;?>"><span class="label label-default"><?php $returnStatus   = ($value->returnStatus != '' ) ? $value->returnStatus .' Return' : 'No Return'; echo $returnStatus;?></span></td>
                            <td class="errorStatus<?php echo $value->dispatchNo;?>"><span class="label label-default"><?php $errorStatus    = ($value->errorStatus != '' ) ? 'Error' : 'No Error'; echo $errorStatus;?></span></td>
                            <td><?php
                                      if($loginSessionData['CustomerFollowUpWrite'] == 1){
                                        $CustomerFollowUpWrite = '<a data-ref="'.$value->dispatchNo.'" href="javascript:void(0)" class="btn btn-primary updateCustomerFollowup"> Update Status</a>';
                                      }else{
                                        $CustomerFollowUpWrite = '<a href="javascript:void(0)" class="btn btn-primary noAccess" > Update Status</a>';
                                      }
                                      echo $CustomerFollowUpWrite;
                                ?>
                            </td>
                      </tr>
                      <?php foreach ($value->dispatchtems as $dispatchtemskey => $dispatchtems): ?>

                        <?php
                        $variants  = getOrderItemVariants(null,$dispatchtems->variant_id);
                        // pr($variants);die;
                         if ($dispatchtemskey == 0): ?>
                          <tr style="display:none">
                            <td colspan="12" style="background:#F3F3F3">
                              <h4 class="text-left"><b>Order Dispatch </b>Items</h4>
                              <div class="inner-item-det" id="inner-item-detail_<?php echo $value->dispatchNo;?>" style="display:none">
                                <table class="table table-bordered" style="width:100%">
                                  <tr>
                                    <th style="width:70px;">Sr No</th>
                                    <th class="width16">Item Name</th>
                                    <th>Dimensions</th>
                                    <th>Color</th>
                                    <th>Design</th>
                                    <th class="width16">Block Type</th>
                                    <th class="width16">Quantity</th>
                                    <th class="width16">Sale UOM</th>
                                    <th class="width16">Price</th>
                                    <th class="width40">Reason</th>
                                  </tr>
                                <?php endif; ?>
                                <tr>
                                <td style="width:10px;"><?php echo $dispatchtemskey+1 ?></td>
                                <td class="width16"><?php echo $dispatchtems->itemName ?></td>
                                  <td class="width16"><?php echo getDiamentions($variants->length,$variants->width,$variants->height); ?></td>
                                  <td class="width16"><?php echo $variants->color; ?> </td>
                                  <td class="width16"><?php echo $variants->design  ?> </td>
                                  <td class="width16"> <?php echo(getblockType($dispatchtems->blockType));?></td>
                                  <td class="width16"> <?php  echo $dispatchtems->qtyLoaded;  ?></td>
                                  <td class="width16"> <?php  echo get_ItemUOM($dispatchtems->orderRefId , $dispatchtems->itemRefId)->saleUOM;  ?></td>
                                  <td class="width16"><?php $price = ($dispatchtems->baseConvLength > 0 && $dispatchtems->baseConvLength !='' ) ? $dispatchtems->baseConvLength * $variants->price : $variants->price; echo amountFormat($price); ?></td>
                                  <td><?php $orderPrice += $price; $itemReason = ($dispatchtems->itemReason !='') ? $dispatchtems->itemReason : '---' ; echo $itemReason;?></td>
                                </tr>
                                <?php if ($dispatchtemskey == count($value->dispatchtems) - 1): ?>
                                  <tr>
                                    <td colspan="8" class="text-right">Total Price </td>
                                    <td colspan="3" class="">KES <?php echo amountFormat($orderPrice) ?></td>
                                  </tr>
                                        </table>
                                      </div>
                                    </td>
                                  </tr>
                               <?php endif; ?>

                      <?php endforeach; ?>
                    <?php }
                  }
                  else
                  { ?>
                    <tr><td align="center" colspan="13">No follow up found.</td></tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="">
          <?php // echo $paginationLinks; ?>
        </div>
      </div>
    </div>
  </div>
  <div id="Recently_Completed" class="tab-pane">
    <div class="panel-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="customerFollowupTable">
          <thead>
            <tr>
              <th>Sr.No</th>
              <th>Dispatch</th>
              <th>Business Name</th>
              <th>Date</th>
              <th>Invoice No.</th>
              <th>Customer Contact</th>
              <th>Town</th>
              <th>Region</th>
              <th>Received</th>
              <th>Return</th>
              <th>Error</th>
              <!-- <th></th> -->
            </tr>
          </thead>
          <tbody>
            <?php  if (!empty($complatedOrders))
            {
              $status = array('Inactive','Active');
              foreach ($complatedOrders as $key => $value)
              {
                // echo "<pre>";
                // print_r($value);

                ?>
                <tr>
                    <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo $key+$start+1; ?> </td>
                    <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo '#'.$value->dispatchNo ?> </td>
                    <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo $value->businessName ?></td>
                    <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo $value->dispatchedDate ?></td>
                    <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php $invoiceNo = (trim($value->invoiceNo) !='') ? $value->invoiceNo : 'Not Avialable' ;echo $invoiceNo ; ?> </td>
                    <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo $value->customerName. ' ' .$value->customerPhone ?> </td>
                    <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo ucwords($value->cityName); ?> </td>
                    <td data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="subParentAccrodion" data-ref="<?php echo $value->dispatchNo;?>"> <?php echo ucwords($value->state) ?> </td>
                    <td><span class="label label-default"><?php $receivedStatus = ($value->receivedStatus != '' ) ? $value->receivedStatus.' Received' : 'Not Received'; echo $receivedStatus;?></span></td>
                    <td><span class="label label-default"><?php $returnStatus   = ($value->returnStatus != '' ) ? $value->returnStatus.' Return' : 'No Return'; echo $returnStatus;?></span></td>
                    <td><span class="label label-default"><?php $errorStatus    = ($value->errorStatus != '' ) ? 'Error' : 'No Error'; echo $errorStatus;?></span> <?php if($value->errorStatus !='') {?> <span class="fetchErrors pull-right" data-url="<?php echo base_url().'/get-follow-up-error/'.$value->dispatchNo?>"> <i class="fa fa-eye"></i> </span> <?php } ?>  </td>
                    <!-- <td><?php
                              // if($loginSessionData['CustomerFollowUpWrite'] == 1){
                              //   $CustomerFollowUpWrite = '';//'<a data-ref="'.$value->dispatchNo.'" href="javascript:void(0)" class="btn btn-primary updateCustomerFollowup"> Update Statusee</a>';
                              // }else{
                              //   $CustomerFollowUpWrite = '<a href="javascript:void(0)" class="btn btn-primary noAccess" > Update Status</a>';
                              // }
                              // echo $CustomerFollowUpWrite;
                        ?>
                    </td> -->
              </tr>
              <?php  $orderPrice = 0; foreach ($value->dispatchtems as $dispatchtemskey => $dispatchtems): ?>
                <?php  $variants  = getOrderItemVariants(null,$dispatchtems->variant_id); ?>
                <?php if ($dispatchtemskey == 0): ?>
                  <tr style="display:none">
                    <td colspan="12" style="background:#F3F3F3">
                      <h4 class="text-left"><b>Order Dispatch </b>Items</h4>
                      <div class="inner-item-det" id="inner-item-detail_<?php echo $value->dispatchNo;?>" style="display:none">
                        <table class="table table-bordered" style="width:100%">
                          <tr>
                            <th style="width:70px;">Sr No</th>
                            <th class="width16">Item Name</th>
                            <th>Dimensions</th>
                            <th>Color</th>
                            <th>Design</th>
                            <th class="width16">Block Type</th>
                            <th class="width16">Quantity</th>
                            <th class="width16">Sale UOM</th>
                            <th class="width16">Price</th>
                            <th class="width40">Reason</th>
                          </tr>
                        <?php endif; ?>
                        <tr>
                        <td style="width:10px;">
                            <?php echo $dispatchtemskey+1 ?>
                          </td>
                        <td class="width16">
                            <?php echo $dispatchtems->itemName ?>
                          </td>
                          <td class="width16"><?php echo getDiamentions($variants->length,$variants->width,$variants->height); ?></td>
                          <td class="width16"><?php echo $variants->color; ?> </td>
                          <td class="width16"><?php echo $variants->design  ?> </td>

                          <td class="width16">
                            <?php echo(getblockType($dispatchtems->blockType));?>
                          </td>
                          <td class="width16">
                            <?php  echo $dispatchtems->qtyLoaded;
                            // echo (getOrderQtyItems($value->orderRef, $dispatchtems->itemRefId)->orderQty) ? getOrderQtyItems($value->orderRef, $dispatchtems->itemRefId)->orderQty : '';
                            ?>
                          </td>
                          <td class="width16"> <?php  echo get_ItemUOM($dispatchtems->orderRefId , $dispatchtems->itemRefId)->saleUOM;  ?></td>
                          <td class="width16">
                            <?php
                            $price = ($dispatchtems->baseConvLength > 0 && $dispatchtems->baseConvLength !='' ) ? $dispatchtems->baseConvLength * $variants->price : $variants->price;

                            $orderPrice +=$price
                            ?>
                            KES <?php echo amountFormat($price); ?>
                          </td>
                          <td><?php $itemReason = ($dispatchtems->itemReason !='') ? $dispatchtems->itemReason : '---' ; echo $itemReason;?></td>
                        </tr>
                        <?php if ($dispatchtemskey == count($value->dispatchtems) - 1): ?>

                          <tr>
                            <td colspan="8" class="text-right">Total Price </td>
                            <td colspan="3" class="">KES <?php echo amountFormat($orderPrice) ?></td>
                          </tr>
                                </table>
                              </div>
                            </td>

                          </tr>

                       <?php endif; ?>

              <?php endforeach; ?>

            <?php }
          }
          else
          { ?>
            <tr><td align="center" colspan="13">No follow up found.</td></tr>
    <?php } ?>
          </tbody>
        </table>

<div class="">
  <?php // echo $pagination; ?>
</div>
</div>
</div>
</div>
</div>

</section>
<div id="orderSearchRecords" style="display:none"></div>
<div class="modal fade " id="confirm-update-customer-follow-up-modal" style="display:">
  <div class="modal-dialog modal-lg" style="width:980px">
    <form class="saveCustomerFollowupStatus" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Status</h4>
        </div>
        <div class="modal-body">
          <div class="col-md-10 col-md-offset-1 text-center">
            <div class="col-sm-4">
              <p>Received</p>
              <label class="switch-case"><input name="recevedStatus" class="customerFollowUpToggle notReceived" data-ref="notReceived" type="checkbox"><div class="slider-case  slider-error round"></div></label>
              <br>
              <label class="switch-case"><input name="recevedStatusVal" class="childSlider" data-parent="notReceived" data-ref="notReceived" type="checkbox"><div class="slider-case round"></div></label>
            </div>
            <div class="col-sm-4">
              <p>Return</p>
              <label class="switch-case"><input name="returnStatus" class="customerFollowUpToggle noReturn" data-ref="noReturn" type="checkbox"><div class="slider-case  slider-error round"></div></label>
              <br>
              <label class="switch-case"><input name="returnStatusVal" class="childSlider" data-parent="noReturn" data-ref="noReturn" type="checkbox"><div class="slider-case round"></div></label>
            </div>
            <div class="col-sm-4">
              <p>Error</p>
              <label class="switch-case"><input name="errorStatus"  class="customerFollowUpToggle" data-ref="noError" type="checkbox"><div class="slider-case slider-error round"></div></label>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="queries hide" id="noReturn">
            <table class="table" style="width:100%;">
              <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Item Name</th>
                  <th>Variations</th>
                  <th>Total Quantity</th>
                  <th>Quantity Returned</th>
                  <th>Reason</th>
                </tr>
              </thead>
              <tbody id="dispatchtems">

              </tbody>
            </table>
            <div class="text-left col-md-4 no-padding">
              <div class="form-group">
                <label for="creditNote">Credit Note No.</label>
                <input name="creditNote" type="text" class="form-control" >
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="queries hide" id="noError">
            <hr>
            <h4><b>Tick all errors that occurred:</b></h4>
            <ul class="list-style">
              <?php
              $errors = getFollowupErrors();
              // echo pr($errors);die;
              if (!empty($errors)) {
                foreach ($errors as $key => $value) {
                  echo '<li>  <label class="verticalAlignTop"><input class="errors-checkbox" type="checkbox" name="errors['.$value->id.']" value="'.$value->id.'"> &nbsp;&nbsp;'.$value->error.' </label></li>';
                }
              } else {
                echo "<li> No error type found</li>";
              }
              ?>
            </ul>
            <div class="text-left">
              <div class="form-group">
                <label for="commentErrors">Comment on Errors</label>
                <textarea name="commentErrors" rows="2" class="form-control" ></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 text-left">
          <div class="form-group">
            <label for="comment">Comment on Dispatch</label>
            <textarea name="comment" rows="4" class="form-control" ></textarea>
          </div>
        </div>
        <div class="modal-footer">
            <div class="col-md-4 pull-right text-right">
                <div class="row">
                  <button type="button" class="btn btn-default submitCustomerFollowUp" data-ref="saveNopen">Save</button>
                  <button type="button" class="btn btn-default submitCustomerFollowUp" data-ref="saveNclose">Save & Complete Order</button>

                </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
