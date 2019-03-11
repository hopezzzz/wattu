<?php $logindata = $this->session->userdata('clientData');?>
    <div class="row">
        <div class="col-md-12">
            <section class="pages" id="order-details">
                <div class="panel-body">
                    <div class="order-details-panel">
                        <form class="form-horizontal" method="">
                            <div class="col-md-4">
                                <div class="customer_details_panel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Customer Detail</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-12">Customer Name</label>
                                                <div class="col-sm-12">
                                                    <?php if(isset($orderAddress[0])) echo $orderAddress[0]->customerName; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-12">Bussiness Name</label>
                                                <div class="col-sm-12">
                                                    <?php if(isset($orderAddress[0])) echo $orderAddress[0]->businessName; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-12">Phone</label>
                                                <div class="col-sm-12">
                                                    <?php if(isset($orderAddress[0])) echo $orderAddress[0]->customerPhone; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-12">Address</label>
                                                <div class="col-sm-12">
                                                    <?php if(isset($orderAddress[0])) echo $orderAddress[0]->addressLine.' , '.$orderAddress[0]->city.' , '.$orderAddress[0]->state.' , '.$orderAddress[0]->country; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>

                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="customer_details_panel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Order Detail</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Order Number</label>
                                                <div class="col-sm-12">
                                                    <?php echo $orderDetails->orderNo; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Order Status</label>
                                                <div class="col-sm-12">
                                                    <?php echo orderStatus($orderDetails->orderStatus); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Sales Person</label>
                                                <div class="col-sm-12">
                                                    <?php echo $orderDetails->salesman; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Transport Price</label>
                                                <div class="col-sm-12">
                                                    KES <?php echo amountFormat($orderDetails->transportCharge); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Order Price</label>
                                                <div class="col-sm-12">
                                                    KES <?php echo amountFormat($orderDetails->orderPrice); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Total Amount</label>
                                                <div class="col-sm-12">
                                                    KES <?php $totalPrice = ($orderDetails->orderPrice + $orderDetails->transportCharge) - ($orderDetails->orderDiscount) ; echo amountFormat($totalPrice); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <label class="col-sm-12">Order Discount</label>
                                              <div class="col-sm-12">
                                                  <?php echo amountFormat($orderDetails->orderDiscount); ?>
                                              </div>
                                          </div>
                                      </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Payment Method</label>
                                                <div class="col-sm-12">
                                                    <?php echo ($orderDetails->paymentMethod); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (trim($orderDetails->dueDays) !=''): ?>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-sm-12">Due Days</label>
                                                    <div class="col-sm-12">
                                                        <?php echo ($orderDetails->dueDays); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                                <?php if (trim($orderDetails->dueAmount) !=''): ?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="col-sm-12">Due Amount</label>
                                                            <div class="col-sm-12">
                                                              KES  <?php echo number_format($orderDetails->dueAmount,2); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Preauthorize</label>
                                                <div class="col-sm-12">
                                                    <?php echo ($orderDetails->preAuthorization); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Delivery Method</label>
                                                <div class="col-sm-12">
                                                    <?php echo ($orderDetails->deliveryMethod); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-12">Remarks</label>
                                                <div class="col-sm-12">
                                                    <?php echo ($orderDetails->orderRemarks); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="item_chart">
                        <h3 class="text-center">Item Chart</h3>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Item Name</th>
                                        <th>Dimensions</th>
                                        <th>Color</th>
                                        <th>Design</th>
                                        <th>Quantity</th>
                                        <th>Sale UOM</th>
                                        <th>Transport Charge</th>
                                        <th>Production</th>
                                        <th>Discount Type</th>
                                        <th>Discount(<strong>%</strong>)</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $totalPrice = 0;
                                    $transportCharge = 0;
                 foreach ($orderItems as $key => $value): ?>
                 <?php $key = $key + 1;

                 ?>
                 <?php $qty =''; foreach ($value->variants as $keyd => $variantsValue): ?>
                                        <tr>
                                          <?php   if ($keyd == 0): ?>
                                          <td>

                                            <?php echo $key ?>
                                            </td>
                                            <td>
                                                <?php echo ucwords($value->itemName) ?>
                                            </td>
                                          <?php else :?>
                                            <td></td>
                                            <td></td>
                                           <?php endif; ?>
                                            <td>


                                                  <p style="font-weight:normal" class="default">
                                                  <span>
                                                  <?php echo  getDiamentions($variantsValue->length,$variantsValue->width, $variantsValue->height) ?>
                                                  </span>

                                                  &nbsp;
                                                  <!-- <span><?php echo $variantsValue->qty ?></span> -->
                                                </p>


                                            </td>
                                            <td><?php echo $variantsValue->color ?></td>
                                            <td><?php echo $variantsValue->design ?></td>
                                            <td> <?php echo $variantsValue->qty ?>    </td>
                                            <td> <?php echo $value->saleUOM ?>    </td>
                                            <td>KES <?php echo amountFormat($variantsValue->transportCharge) ?>    </td>
                                            <?php $transportCharge  += $variantsValue->transportCharge* $variantsValue->qty; ?>
                                            <td>
                                                <?php
                                                  if($variantsValue->readyEstDate == ''){
                                                    if ($value->productionOnDemand == 1)
                                                    echo "In Production";
                                                    else
                                                    echo "No Production Needed";
                                                  }else{
                                                    echo "Production Completed";
                                                  }
                                                 ?>
                                            </td>
                                            <td><?php $arr =array('Price','Percentage');  echo  ($arr[$value->discountType]) ? $arr[$value->discountType] : '' ?></td>
                                            <td><?php echo number_format($value->discount,2) ?></td>
                                            <td>KES  <?php
                                            $price = ($variantsValue->price) * ($variantsValue->qty);

                                            $disValue = 0;
                                            if ($value->discount != 0) {
                                              if ($value->discountType == 1 )
                                              {
                                                    $disValue           =   $price * $value->discount / 100;
                                              }else
                                              {
                                                  	$disValue     =  $price - $value->discount;
                                              }
                                            }
                                            $price = $price - $disValue;
                                            $price = $price * $value->saleConvLength;
                                            $totalPrice += $price;
                                            echo amountFormat($price)
                                            ?> </td>
                                        </tr>
                                          <?php endforeach; //die;?>
                                        <?php endforeach; ?>

                                            <tr>
                                                <td class="text-left" colspan="2"> <strong>Total Items</strong>  </td>
                                                <td>
                                                    <?php echo count($orderItems) ?>
                                                </td>
                                                <td  colspan="4" class="text-right"><strong>Total Charge</strong> </td>
                                                <td>KES
                                                    <?php echo amountFormat($transportCharge) ?>
                                                </td>
                                                <td colspan="3" class="text-right"><strong>Total Amount</strong>  </td>
                                                <td>KES
                                                    <?php echo amountFormat($totalPrice); ?>
                                                </td>
                                            </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    if (!empty($orderDispatches['result']) && $orderDetails->orderStatus == "Closed") {
                      ?>
                        <div class=" active" style="margin-top:40px">
                            <div class="text-center">
                                <h3>Order Dispatchs</h3>
                            </div>
                            <table id="sort2" class="grid table table-striped table- table-bordered toLoadOrders">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Order No</th>
                                        <th>Dispatch Date</th>
                                        <th>Dispatch No.</th>
                                        <th>Loading Sheet</th>
                                        <th>Fulfillment</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($orderDispatches['result'] as $key => $value): ?>
                                      <tr class="active <?php echo $value->dispatchNo?>" id="active<?php echo $value->dispatchNo?>">
                                          <td>
                                              <a data-parent="#active<?php echo $value->orderRef?>" data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="clickable btn btn-plus extendsOrder" data-ref="<?php echo $value->orderRef;?>">+
                                                <?php echo ucwords($value->fullname) .' ( '.$value->businessName . ' ) ' ?>
                                              </a>
                                          </td>
                                          <td>
                                              <?php echo $value->orderNo ?>
                                          </td>
                                          <td>
                                              <?php echo $value->dispatchedDate; ?>
                                          </td>
                                          <td>
                                              <?php echo $value->dispatchNo ?>
                                          </td>
                                          <!-- <td></td> -->
                                          <td>
                                              <?php $loadingSheets = getLoadingSheets(); if (!empty($loadingSheets['loadingSheets']) ): ?>
                                                  <?php foreach ($loadingSheets['loadingSheets'] as $loadingSheetKey => $loadingSheetvalue): ?>
                                                      <?php if($value->sheetRef == $loadingSheetvalue->sheetRef) {
                                                          echo $loadingSheetvalue->refName;
                                                        } ?>
                                                          <?php endforeach; ?>
                                                              <?php endif; ?>
                                          </td>
                                          <td>
                                              <?php $array = array('','Incomplete','Complete');
                                      echo $array[$value->fullfilment];
                                     ?>
                                          </td>

                                      </tr>
                                      <?php    if (!empty($value->dispatchtems)):  ?>
                                        <tr class=" <?php echo $value->dispatchNo?>">
                                          <td colspan="7" style="background: #fff;">

                                            <div class="collapse inner-item-det" id="inner-item-detail_<?php echo $value->dispatchNo;?>">
                                              <h3 class="text-center"><u>Item Chart</u></h3>
                                              <table class="table" style="width:100%;">
                                                <thead>
                                                  <tr>
                                                    <th>Sr No</th>
                                                    <th>Item Name</th>
                                                    <th>Dimensions</th>
                                                    <th>Color</th>
                                                    <th>Design</th>
                                                    <th>Block Type</th>
                                                    <th>Quantity</th>
                                                    <th>Sale UOM</th>
                                                    <th>Price</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                  <?php $orderPrice = 0; foreach ($value->dispatchtems as $orderItemKey => $orderItems): ?>
                                                    <tr>
                                                      <td>
                                                        <?php echo $orderItemKey+1  ?>
                                                      </td>
                                                      <td>
                                                        <?php echo $orderItems->itemName ?>
                                                      </td>
                                                      <td><?php  $variants  = getOrderItemVariants(null,$orderItems->variant_id);//echo "<pre>";print_r($variants);die; ?><?php echo getDiamentions($variants->length,$variants->width,$variants->height); ?></td>
                                                      <td><?php echo ucfirst($variants->color) ?> </td>
                                                      <td><?php echo ucfirst($variants->design) ?> </td>
                                                      <td><?php  echo $variants->blockType;?> </td>
                                                      <td><?php  echo $orderItems->qtyLoaded;?></td>
                                                      <td><?php  echo get_ItemUOM($orderItems->orderRefId , $orderItems->itemRefId)->saleUOM;?></td>
                                                      <td><?php $price = ($orderItems->baseConvLength > 0 && $orderItems->baseConvLength !='' ) ? $orderItems->baseConvLength * $variants->price : $variants->price; echo amountFormat($price);?></td>
                                                      <?php $orderPrice +=$price  ?>
                                                    </tr>
                                                  <?php endforeach; ?>

                                                  <tr>
                                                    <td colspan="8" class="text-right"><strong>Total Items :</strong> </td>
                                                    <td>
                                                      <?php echo count($value->dispatchtems) ?>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="8" class="text-right"><strong>Total Items :</strong> </td>
                                                    <td>KES
                                                      <?php echo amountFormat($orderPrice) ?>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>


                                            </div>
                                          </td>
                                        </tr>
                                            <?php endif; ?>
                                                <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>
                        <?php
                  }
                  ?>
                </div>

            </section>
            <?php if ($orderDetails->orderStatus != "Closed"): ?>

                <ul class="comment-section clearfix">

                    <?php if (!empty($orderComments)): ?>
                        <?php foreach ($orderComments as $key => $value): ?>
                            <li class="comment user-comment" id="orderComment_<?php echo $value->commentRef;?>">
                                <div class="info">
                                    <a href="javascript:void(0)"><?php echo ucwords($value->userName) ?></a>
                                    <h4 class="label label-primary">
                  <?php if ($value->userType == 1){
                    echo "Super Admin";
                  }elseif ($value->userType == 2) {
                    echo "Manager";
                  }elseif ($value->userType == 3) {
                    echo "Salesman";
                  } ?>
                </h4>
                                    <br>
                                    <span><?php echo dateDiff(date('Y-m-d H:i:s'),$value->addedOn) ?></span>
                                </div>
                                <a class="avatar" href="#">
                <img src="<?php echo site_url('assets/images/user.png');?>" width="35" alt="Profile Avatar" title="Anie Silverston" />
              </a>
                                <p class="realtive">
                                    <?php echo ucfirst($value->comment); ?>
                                        <?php if ($value->addedBy == $logindata['userRef']): ?>
                                            <a href="javascript:void(0);" class="deleteRecord" data-name="this comment" data-type="orderComment" data-ref="<?php echo $value->commentRef;?>"><i class="fa fa-times"></i></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="javascript:void" data-ref="<?php echo $value->commentRef;?>" class="editComment"><i class="fa fa-edit"></i></a>
                                            <?php endif; ?>
                                </p>
                            </li>
                            <?php endforeach; ?>
                                <?php endif; ?>

                                    <li class="write-new">
                                        <?php
          echo form_open('addComment', array('name' => 'comment-order', 'method' => 'post', 'id' => "comment-order","autocomplete" => "off"));
          ?>
                                            <input type="hidden" name="orderRef" value="<?php echo $orderDetails->orderRef;?>">
                                            <div class="form-group">
                                                <label for="">Order Comment</label>
                                                <textarea placeholder="Write your comment here" rows="5" id="commentArea" name="comment"></textarea>
                                            </div>
                                            <div>
                                                <img src="<?php echo site_url('assets/images/user.png');?>" width="35" alt="Profile of Bradley Jones" title="Bradley Jones" />
                                                <button type="submit">Submit</button>
                                            </div>
                                            </form>
                                    </li>

                </ul>
                <?php endif; ?>
        </div>
        </section>
    </div>
    </div>
