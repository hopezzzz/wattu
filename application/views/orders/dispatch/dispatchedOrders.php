<table id="sort2" class="grid table table-striped table- table-bordered toLoadOrders">
  <thead>
    <tr>
      <th>Business Name</th>
      <th>Invoice No.</th>
      <th>Dispatch No.</th>
      <th>Loading Sheet</th>
      <th>Fulfillment</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (!empty($records)) {
      // pr($records);die;
      ?>

    <?php foreach ($records as $key => $value): ?>

      <tr class="active <?php echo $value->dispatchNo?>" id="active<?php echo $value->dispatchNo?>">
        <td>
          <a data-parent="#active<?php echo $value->orderRef?>" data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->dispatchNo;?>" href="javascript:void(0)" class="clickable btn btn-plus extendsOrder" data-ref="<?php echo $value->orderRef;?>">+
            <!-- <?php echo ucwords($value->fullname) .' ( '.$value->businessName . ' ) ' ?> -->
              <?php echo ucwords($value->businessName .' ( #'.$value->orderNo . ' ) '); ?>
          </a>
        </td>
        <td>
          <?php echo $value->invoiceNo ?>
        </td>
        <td>
          <?php echo $value->dispatchNo ?>
        </td>
        <!-- <td></td> -->
        <td><?php $loadingSheets = getLoadingSheet($value->sheetRef);  ?>

             <?php
                echo $loadingSheets;
              ?>

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
                    <td colspan="8" class="text-right">Total Items : </td>
                    <td>
                      <?php echo count($value->dispatchtems) ?>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="8" class="text-right">Total Amount : </td>
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
    <?php
  }else {?>
    <tr>
      <th colspan="7">No Orders found</th>
    </tr>
  <?php }
  ?>
  </tbody>
</table>
