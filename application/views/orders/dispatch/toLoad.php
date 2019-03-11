<?php
// echo '<pre>'; print_r($records);die;
?>
<div class="panel-body">
  <div class="col-md-12 loading-sheet no-padd">
    <div class="col-md-2 no-padd">
      <div class="form-group">
        <input type="text" value="<?php echo date('d-m-Y');?>" name="loadingDate" id="loadingDate" class="datepicker form-control" placeholder="Date">
      </div>
    </div>
    <div class="col-md-6">
      <label class="down_loading_sheet">
        <select class="form-control downloadSheet" name="loadingSheet" id="loadingSheet" data-html="true">
          <option value="">Select Loading Sheet </option>
          <option data-ref="addNewLoadingSheet" value=""> Add New Loading Sheet </option>
          <?php $loadingSheets = getLoadingSheets(); if (!empty($loadingSheets['loadingSheets']) ): ?>
            <?php foreach ($loadingSheets['loadingSheets'] as $key => $value): ?>
              <option  value="<?php echo base_url().'download-sheet/'.$value->sheetRef;?>">
                <?php echo $value->refName;?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
        Download Loading Sheet
      </label>
</div>

  </div>
  <select class="form-control pull-right" name="prepareLoadingSheet" id="prepareLoadingSheet" data-html="true">
    <option value="0" selected="selected">Today</option>
    <option value="1" >Prepare for next day</option>
</select>
  <div class="clearfix"></div>
  <div class="table-responsive noLoadingRecords">

    <table id="sort2" class="grid table table-striped table- table-bordered toLoadOrders">
      <thead>
        <tr>
          <th style="background:#eee" colspan="7">No Loading Sheet</th>
        </tr>
        <tr>
          <th>Business Name</th>
          <th>Order No</th>
          <th>Loading Sheet</th>
          <th>Fulfillment</th>
          <th></th>
          <th></th>
          <th>Confirm Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (!empty($records['loadedOrders'])) {
          // print_r($records['loadedOrders']);die;
          ?>
          <p id="noRecordToLoad" style="display: none;"></p>
          <?php foreach ($records['loadedOrders'] as $key => $value): ?>
            <tr class="active <?php echo $value->orderRef?>">
              <td>
                <a data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable btn btn-plus extendsOrder" data-ref="<?php echo $value->orderRef;?>">+
                  <?php // echo $value->fullname .' ( '.$value->businessName . ' ) ' ?>
                  <?php echo ucwords($value->businessName); ?>
                </a>
              </td>
              <td>
                <a class="alert-link" target="_blank" href="<?php echo base_url().'order-details/'.$value->orderRef?>"> #<?php echo $value->orderNo;?></a></td>
              </td>
              <td>
                <div class="col-md-12">
                  <select class="form-control prependToDiv loadingSheet" data-ref="<?php echo $value->orderRef?>"  data-current="<?php echo  '';?>">
                    <option value="">Select Loading Sheet </option>
                    <?php $loadingSheets = getLoadingSheets(); if (!empty($loadingSheets['loadingSheets']) ): ?>
                      <?php foreach ($loadingSheets['loadingSheets'] as $loadingSheetKey => $loadingSheetvalue): ?>
                        <option value="<?php echo $loadingSheetvalue->sheetRef;?>">
                          <?php echo $loadingSheetvalue->refName;?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </td>
              <td>
                <label class="switch-case">
                  <input type="checkbox" id="togBtn">
                  <div class="slider-case round"></div>
                </label>
              </td>
              <td><a href="javascript:void(0)" class="modifyLoading btn btn-primary" data-sheet="<?php echo $value->sheetRef;?>" data-ref="<?php echo $value->orderRef;?>" data-orderNo="<?php echo $value->orderNo;?>" data-dispatch="<?php echo $value->dispatchNo;?>">Modify Loading</a></td>
              <td><a href="javascript:void(0)" class="btn btn-primary add-new-comment" data-ref="<?php echo $value->orderRef;?>" data-orderNo="<?php echo $value->orderNo;?>" data-to="Comment" data-pipline="4">Comment</a></td>
              <td>
                <div class="col-md-12">
                  <!-- <a type="button" class="select-1" data-toggle="modal" data-target="#myModal-2"> -->
                  <select class="form-control toLoadOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-to="cancelled" data-pipline="4" data-orderNo="<?php echo $value->orderNo;?>">
                    <option value=""> No Action (Default) </option>
                    <option value="returnToPending">Return to Pending </option>
                    <option value="dispatched" >Dispatched </option>
                    <option value="cancelOrder">Cancel Order </option>
                  </select>
                  <!-- </a> -->
                </div>
              </td>
            </tr>
            <?php   if (!empty($value->orderItems)):  ?>
              <tr class="<?php echo $value->orderRef?>">
                <td colspan="8" style="background: #fff;">
                  <div class="collapse inner-item-det" id="inner-item-detail_<?php echo $value->orderRef;?>">
                    <h3 class="text-center"><u>Item Chart</u></h3>
                    <table class="table" style="width:100%;">
                      <thead>
                        <tr>
                                <th><b>Sr No</b></th>
                                <th><b>Item Name</b></th>
                                <th><b>Dimensions</b></th>
                                <th><b>Color</b></th>
                                <th><b>Design</b></th>
                                <th><b>Block Type</b></th>
                                <th><b>Quantity</b></th>
                                <th><b>Sale UOM</b></th>
                                <th><b>Total Dispatched</b></th>
                                <th><b>Pending Dispatch</b></th>
                                <th><b>Fullfilment</b></th>
                                <!-- <th><b>Price</b></th> -->
                        </tr>
                      </thead>

                          <tbody>
                        <?php $itemId = ''; foreach ($value->orderItems as $orderItemKey => $orderItems): ?>
                          <?php $orderItemKey = $orderItemKey+1; ?>
                          <?php foreach ($orderItems->variants as $vkey => $variants): ?>
                          <tr>
                            <?php $itemId .= $orderItems->itemRefId.','; if ($vkey == 0): ?>
                              <td><?php  echo $orderItemKey ?></td>
                              <td><?php echo $orderItems->itemName ?></td>
                            <?php else : ?>
                              <td></td>
                              <td></td>
                            <?php endif; ?>

                                        <td>
                                          <?php echo  getDiamentions($variants->length,$variants->width,$variants->height) ?>
                                        </td>
                                        <td><?php echo $variants->color ?> </td>
                                        <td><?php echo $variants->design ?></td>
                                        <td>
                                          <?php  echo $variants->blockType ;?>
                                        </td>
                                        <td>
                                          <?php echo $variants->qty ?>
                                        </td>
                                        <td><?php  echo get_ItemUOM($value->orderRef , $orderItems->itemRefId)->saleUOM;?></td>
                                        <td><?php
                                        $totalDispatch = 0;
                                        $totalDispatch =  getTotalDispatchQty($value->orderRef,$orderItems->itemRefId, $variants->variant_id)->totalDipatchQty;
                                        echo ($totalDispatch !='') ? $totalDispatch : 0 ;
                                        ?></td>
                                        <td><?php echo $variants->qty - $totalDispatch ?></td>
                                        <td><?php if ($totalDispatch == $variants->qty ) echo "Full";  else echo "Part";  ?></td>
                                        <!-- <td>
                                        KES  <?php echo amountFormat($variants->price) ?>
                                        </td> -->

                            <?php echo '<input type="hidden" value="'.$itemId.'" id="ids'.$value->orderRef.'">' ?>
                          </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>

                        <tr>
                          <td colspan="11" class="text-right">
                            <table style="width:100%;">
                              <tr>
                                <td>Total Items : <?php echo count($value->orderItems) ?></td>
                              </tr>
                              <!-- <tr>
                                <td>Total Amount : KES <?php echo amountFormat($value->orderPrice) ?></td>
                              </tr> -->
                            </table>
                            </td>
                        </tr>
                      </tbody>
                    </table>

                    <?php if (!empty($value->orderComment)): ?>
                      <h3 class="text-center"><u>Order Comments</u></h3>
                        <ul class="comment-section clearfix">
                      <?php foreach ($value->orderComment as $key => $orderComment): ?>
                        <li class="comment user-comment" id="orderComment_<?php echo $orderComment->commentRef;?>">
                          <div class="info">
                            <a href="javascript:void(0)"><?php echo ucwords($orderComment->userName) ?></a>
                            <h4 class="label label-primary">
                              <?php if ($orderComment->userType == 1){
                                echo "Super Admin";
                              }elseif ($orderComment->userType == 2) {
                                echo "Manager";
                              }elseif ($orderComment->userType == 3) {
                                echo "Salesman";
                              } ?>
                            </h4>
                            <br>
                            <span><?php echo dateDiff(date('Y-m-d H:i:s'),$orderComment->addedOn) ?></span>
                          </div>
                          <a class="avatar" href="javascript:void(0)">
                            <img src="<?php echo site_url('assets/images/user.png');?>" width="35" alt="Profile Avatar" title="Anie Silverston" />
                          </a>
                          <p class="realtive">
                            <?php echo ucfirst($orderComment->comment); ?>
                          </p>
                        </li>
                      <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>


          <?php
        }else { ?>

        <?php }
        ?>
      </tbody>
    </table>


    <div class="loadingSheetsRecord">
      <?php
      $sheet = '';
      if (!empty($records['loadingSheets'])) {
        for ($i=0; $i < count($records['loadingSheets']); $i++) {
          // $sheetRef = getLoadingSheetByRef(array_keys($records['loadingSheets'][$i]));
          $data = array_keys($records['loadingSheets']);
          $sheetRef = getLoadingSheetByRef($data[$i]);
          foreach ($records['loadingSheets'][$sheetRef->sheetRef] as $key => $value) : ?>
          <?php if ($sheetRef->sheetRef != $sheet) {?>
            <div class="loadingSheetGroups">
              <table class="grid table table-striped table- table-bordered" <?php echo 'id="'.trim($value->sheetRef).'"' ?> >
                <thead>
                  <tr>
                  </tr>
                  <h4 class="active">
                    <?php $sheetData = getLoadingSheetByRef($value->sheetRef);
                    if (!empty($sheetData)) {
                      echo $sheetData->refName;
                    }else echo 'Loading Sheet Data Not Found.';
                    ?>
                  </h4>
                  <tr>
                    <th>Business Name</th>
                    <th>Order No</th>
                    <th>Loading Sheet</th>
                    <th>Fulfillment</th>
                    <th></th>
                    <th></th>
                    <th>Confirm Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php } ?>
                <tr class="active <?php echo $value->orderRef?>">
                  <td>
                    <a data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable btn btn-plus extendsOrder" data-ref="<?php echo $value->orderRef;?>">+
                      <!-- <?php echo $value->fullname .' ( '.$value->businessName . ' ) ' ?> -->
                      <?php echo ucwords($value->businessName); ?>
                    </a>
                  </td>
                  <td>
                    <a class="alert-link" href="<?php echo base_url().'order-details/'.$value->orderRef?>"> #<?php echo $value->orderNo;?></a></td>
                  </td>
                  <td>
                    <div class="col-md-12">
                      <select class="form-control prependToDiv loadingSheet" data-ref="<?php echo $value->orderRef?>" data-current="<?php echo $value->sheetRef;?>">
                        <option value="">Select Loading Sheet </option>
                        <?php $loadingSheets = getLoadingSheets(); if (!empty($loadingSheets['loadingSheets']) ): ?>
                          <?php foreach ($loadingSheets['loadingSheets'] as $loadingSheetKey => $loadingSheetvalue): ?>
                            <option <?php if($value->sheetRef == $loadingSheetvalue->sheetRef) {?> selected <?php } ?> value="<?php echo $loadingSheetvalue->sheetRef;?>">
                              <?php echo $loadingSheetvalue->refName;?>
                            </option>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>
                  </td>
                  <td>
                    <label class="switch-case">
                      <input type="checkbox" id="togBtn">
                      <div class="slider-case round"></div>
                    </label>
                  </td>
                  <td><a href="javascript:void(0)" class="btn btn-primary modifyLoading" data-sheet="<?php echo $value->sheetRef;?>" data-ref="<?php echo $value->orderRef;?>" data-orderNo="<?php echo $value->orderNo;?>" data-dispatch="<?php echo $value->dispatchNo;?>">Modify Loading</a></td>
                  <td><a href="javascript:void(0)" class="btn btn-primary add-new-comment" data-ref="<?php echo $value->orderRef;?>" data-orderNo="<?php echo $value->orderNo;?>" data-to="Comment" data-pipline="4">Comment</a></td>
                  <td>
                    <div class="col-md-12">
                      <!-- <a type="button" class="select-1" data-toggle="modal" data-target="#myModal-2"> -->
                      <select class="form-control toLoadOrderStatus" data-ref="<?php echo $value->orderRef;?>" data-to="Cancelled" data-pipline="4" data-orderNo="<?php echo $value->orderNo;?>">
                        <option value=""> No Action (Default) </option>
                        <option value="returnToPending">Return to Pending </option>
                        <option value="dispatched" data-to="dispatched">Dispatched </option>
                        <option value="cancelOrder">Cancel Order </option>
                      </select>
                      <!-- </a> -->
                    </div>
                  </td>
                </tr>
                <?php   if (!empty($value->orderItems)):  ?>
                  <tr class=" <?php echo $value->orderRef?>">
                    <td colspan="8" style="background: #fff;">

                      <div class="collapse inner-item-det" id="inner-item-detail_<?php echo $value->orderRef;?>">
                        <h3 class="text-center"><u>Item Chart</u></h3>
                        <table class="table" style="width:100%;">
                          <thead>
                            <tr>
                                    <th><b>Sr No</b></th>
                                    <th><b>Item Name</b></th>
                                    <th><b>Dimensions</b></th>
                                    <th><b>Color</b></th>
                                    <th><b>Design</b></th>
                                    <th><b>Block Type</b></th>
                                    <th><b>Quantity</b></th>
                                    <th><b>Total Dispatched</b></th>
                                    <th><b>Pending Dispatch</b></th>
                                    <th><b>Fullfilment</b></th>
                                    <!-- <th><b>Price</b></th> -->
                            </tr>
                          </thead>

                              <tbody>
                            <?php $itemId = ''; foreach ($value->orderItems as $orderItemKey => $orderItems): ?>
                              <?php $orderItemKey = $orderItemKey+1; ?>
                              <?php foreach ($orderItems->variants as $vkey => $variants): ?>
                              <tr>
                                 <?php $itemId .= $orderItems->itemRefId.','; if ($vkey == 0): ?>
                                   <td><?php  echo $orderItemKey?></td>
                                   <td><?php echo $orderItems->itemName ?></td>
                                 <?php else : ?>
                                   <td></td>
                                   <td></td>
                                 <?php endif; ?>

                                            <td>
                                              <?php echo  getDiamentions($variants->length,$variants->width,$variants->height) ?>
                                            </td>
                                            <td><?php echo $variants->color ?> </td>
                                            <td><?php echo $variants->design ?></td>
                                            <td>
                                              <?php  echo $variants->blockType ;?>
                                            </td>
                                            <td>
                                              <?php echo $variants->qty ?>
                                            </td>
                                            <td><?php
                                            $totalDispatch = 0;
                                            $totalDispatch =  getTotalDispatchQty($value->orderRef,$orderItems->itemRefId)->totalDipatchQty;
                                            echo ($totalDispatch !='') ? $totalDispatch : 0 ;
                                            ?></td>
                                            <td><?php echo $variants->qty - $totalDispatch ?></td>
                                            <td><?php if ($totalDispatch == $variants->qty ) echo "Full";  else echo "Part";  ?></td>
                                            <!-- <td>
                                            KES  <?php echo amountFormat($variants->price) ?>
                                            </td> -->

                                <?php echo '<input type="hidden" value="'.$itemId.'" id="ids'.$value->orderRef.'">' ?>
                              </tr>
                            <?php endforeach; ?>
                            <?php endforeach; ?>

                            <tr>
                              <td colspan="11" class="text-right">
                                <table style="width:100%;">
                                  <tr>
                                    <td>Total Items : <?php echo count($value->orderItems) ?></td>
                                  </tr>
                                  <!-- <tr>
                                    <td>Total Amount : KES <?php echo amountFormat($value->orderPrice) ?></td>
                                  </tr> -->
                                </table>
                                </td>
                            </tr>
                          </tbody>
                        </table>

                        <?php if (!empty($value->orderComment)): ?>
                          <h3 class="text-center"><u>Order Comments</u></h3>
                            <ul class="comment-section clearfix">
                          <?php foreach ($value->orderComment as $key => $orderComment): ?>
                            <li class="comment user-comment" id="orderComment_<?php echo $orderComment->commentRef;?>">
                              <div class="info">
                                <a href="javascript:void(0)"><?php echo ucwords($orderComment->userName) ?></a>
                                <h4 class="label label-primary">
                                  <?php if ($orderComment->userType == 1){
                                    echo "Super Admin";
                                  }elseif ($orderComment->userType == 2) {
                                    echo "Manager";
                                  }elseif ($orderComment->userType == 3) {
                                    echo "Salesman";
                                  } ?>
                                </h4>
                                <br>
                                <span><?php echo dateDiff(date('Y-m-d H:i:s'),$orderComment->addedOn) ?></span>
                              </div>
                              <a class="avatar" href="javascript:void(0)">
                                <img src="<?php echo site_url('assets/images/user.png');?>" width="35" alt="Profile Avatar" title="Anie Silverston" />
                              </a>
                              <p class="realtive">
                                <?php echo ucfirst($orderComment->comment); ?>
                              </p>
                            </li>
                          <?php endforeach; ?>
                          </ul>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
                <?php $sheet= $value->sheetRef; endforeach;
                ?></tbody>
              </table>
            </div>
          <?php } } ?>
        </div>

        <button type="button" name="button" class="saveDispatch pull-right btn btn-success">Save Dispatch</button>
      </div>
    </div>

    <div id="addNewSheet" class="hide">
      <div class="col-md-12">
        <div class="form-group">
          <label for="refName">Loading Sheet Name:</label>
          <input type="text" name="refName" id="refName" class="refName form-control" class="form-control input-md">
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-12" style="margin: 10px 0">
        <button type="button" id="addNewSheetbtn" class="btn btn-primary " data-loading-text="Sending info.."><em class="icon-ok"></em> Save</button>
      </div>
    </div>

    <div class="modal fade in" id="prepareLoadingSheetPopup" role="dialog" aria-hidden="false">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <p align="center"><img src="<?php echo site_url('assets/images/info.png');?>"></p>
                <p align="center"> Are you sure you want to prepare loading sheet for tomorrow?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-success prepareForTomorrow">Yes</button>
            </div>
        </div>
      </div>
    </div>
    <div class="modal fade in" id="dispatchlistModal" role="dialog" aria-hidden="false">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <form class="save-dispatch" action="<?php echo base_url()?>save-dispatch" method="post">
            <div class="modal-header">
              <h4 class="modalH4">Save Dispatch </h4>
              <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table id="modal-table1" class="table table-bordered ">
                  <thead>
                    <tr>
                      <th>Order</th>
                      <th>Item Name</th>
                      <th>Variations</th>
                      <th>Qty.</th>
                      <th>Loaded Now (Base UOM)</th>
                      <th>Loaded Now (Sales UOM)</th>
                      <th>Not Loading (Sales UOM)</th>
                      <th>Dispatched Previously (Sales UOM)</th>
                      <th>Order Full Fulfillment</th>
                      <th>Invoice No.</th>
                    </tr>
                  </thead>
                  <tbody id="orderItems">
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade in" id="modifyLoading-modal" role="dialog" aria-hidden="false">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modalH4"> Modify Loading </h4>
            <button type="button" class="close" data-dismiss="modal">×</button>
          </div>
          <form class="loadingForm" action="<?php echo base_url()?>modify-load-orders-sheet" method="post">
            <input type="hidden" name="orderRefId" id="orderRefId" value="">
            <input type="hidden" name="sheetRefId" id="sheetRefId" value="">
            <input type="hidden" name="dispatchNum" id="dispatchNum" value="">
            <div class="modal-body">
              <div class="table-responsive">
                <table class=" table table-bordered">
                  <thead>
                    <tr>
                      <th>Sr No.</th>
                      <th>Item Name</th>
                      <!-- <th>Color</th>
                      <th>Size</th> -->
                      <th>Qty</th>
                      <th>Variants</th>
                      <!-- <th>Variants Quantity</th> -->
                      <!-- <th>Color</th> -->
                      <th>Loading Now</th>
                      <th>Not Loading</th>
                      <th>Dispatched Previously</th>
                    </tr>
                  </thead>
                  <tbody id="modifyLoadingItems">

                  </tbody>
                </table>
              </div>

            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save</button>
              <button type="submit" class="btn btn-primary saveAndClose">Save & Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--  usefull scripts -->
    <script type="text/javascript">
    $(document).on('click', '#loadingDate', function(event) {
       //alert("ok");
     jQuery('#loadingSheet').val('');

    })
    var myFuncCalls = 0;


    $(document).off('change').on('change', '.downloadSheet', function(event) {
      event.preventDefault();
      $('.loadingSheetGroups').sort(function(a, b) {
      if (a.textContent < b.textContent) {
        return -1;
      } else {
        return 1;
      }
      }).appendTo('.loadingSheetsRecord');

      iziToast.destroy();
      var val = $(this).find('option:selected').attr('data-ref');
      var dataval = $(this).val();
      var loadingDate = $('#loadingDate').val();

        if (dataval != '') {
          if (loadingDate == '') {
            iziToast.warning({timeout: 2000,title: 'Alert',message: 'Please select Date First to download loading sheet',position: 'bottomRight'});
            return false;
          }
          $.ajax({
            type: "POST",
            url: dataval+'/'+loadingDate,
            data: {'sheetRef': dataval},
            dataType: "json",
            success: function(response) {
                iziToast.destroy();
              if (!response.success) {
                iziToast.info({timeout: response.delayTime,title: 'Success',message: response.error_message,position: 'bottomRight'});
              } else {
                setTimeout(function () {
                  var link = document.createElement('a');
                  link.href = response.fileUrl;
                  document.body.appendChild(link);
                  link.click();
                  document.body.removeChild(link);
                },
                response.delayTime
              );
              jQuery("#loadingSheet").val($("#loadingSheet option:first").val());
              iziToast.success({timeout: response.delayTime,title: 'Success',message: response.success_message,position: 'bottomRight'});
            }
          }
      });
    }

    return false;
});

jQuery(document).on('click', '#addNewSheetbtn', function() {
  jQuery('.popover .remove-label').html('');
  jQuery('.form-group').removeClass('has-error');
  var refName = $.trim(jQuery('.popover .refName').val());
  if (refName != "") {
    $.ajax({
      type: "POST",
      url: site_url + 'addNewLoadingSheet',
      data: {
        'refName': refName,
        'status': 1
      },
      dataType: "json",
      success: function(response) {
        var delayTime = 3000;
        if (response.success) {

          iziToast.destroy();
          iziToast.success({
            timeout: 2500,
            title: 'Success',
            message: response.success_message,
            position: 'bottomRight',
          });
          $('#loadingSheet').popover('hide');
          var toAppend = '';
          toAppend += '<option value="' + response.data.sheetRef + '">' + response.data.refName + '</option>';
          $('.loadingSheet').append(toAppend);
          $('.downloadSheet').append(toAppend);
          $('#loadingSheet').val(response.data.sheetRef)

        } else {
          if (response.formErrors) {
            $.each(response.errors, function(index, value) {
              $("input[name='" + index + "']").parents('.form-group').addClass('has-error');
              $("input[name='" + index + "']").after('<label id="' + index + '-error" class="has-error remove-label" for="' + index + '">' + value + '</label>');
            });
          } else {
            iziToast.destroy();
            iziToast.error({
              timeout: 2500,
              title: 'Success',
              message: response.error_message,
              position: 'bottomRight',
            });
          }
        }
      }
    });
  } else {
    if (refName == '') {
      $(".popover .refName").parents('.form-group').addClass('has-error');
      jQuery('.popover .refName').after("<label class='remove-label'>This field is required</label>");
    } else {
      jQuery('.popover .refName').css('border', '1px solid #ccc');
    }
  }
});
jQuery('.modal').on('hidden.bs.modal', function (e) {
  jQuery('.form-control').removeClass('has-error');
  jQuery('.form-group.has-error').removeClass('has-error');
  jQuery('label.has-error').remove();
  var  id  = jQuery(this).attr('id');
  jQuery('.shipingMessageElement').hide();
  jQuery('#'+id).find('input[type="text"]').val('');
  jQuery('#'+id).find('input[type="hidden"]').val('');
  jQuery('#'+id).find('input[type="button"]').removeAttr('disabled');
  jQuery('#'+id).find('input[type="submit"]').removeAttr('disabled');
  jQuery('#'+id).find('button[type="button"]').removeAttr('disabled');
  jQuery('#'+id).find('button[type="submit"]').removeAttr('disabled');
  jQuery('#'+id).find('select').val('');
});
$( function() {
  $( ".datepicker" ).datepicker(
    {
      dateFormat: 'dd-mm-yy',
    }
  );
} );



$('.loadingSheetGroups').sort(function(a, b) {
if (a.textContent < b.textContent) {
  return -1;
} else {
  return 1;
}
}).appendTo('.loadingSheetsRecord');

</script>
<script src="<?php echo $this->config->item('assets_path');?>js/form-validate.js"></script>


<style>
.main_inner_table td{border:none !important; }
.main_inner_table td, .order-item-table td { width: 100px;}
</style>
