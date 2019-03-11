<?php $i = 0; if (!empty($orderDetails)): ?>
<?php foreach ($orderDetails as $key => $value): ?>
  <?php $count = count($value->orderItems) ?>
  <?php foreach ($value->orderItems as $ItemsKey => $Items): ?>
    <tr class="">
      <?php if ($key == $i && $ItemsKey == 0 ): ?>
        <td rowspan="<?php echo $count;?>"><b><?php echo $value->orderAddress[0]->businessName ?></b><b> ( </b> <?php echo $value->orderAddress[0]->customerName ?><b> )</b>
          <br> Order #<?php echo $value->orderNo; ?>
        </td>
      <?php endif; ?>
        <td>
          <input type="hidden" name="orderRef[<?php echo $value->orderRef?>][]" value="<?php echo $value->orderRef;?>">
          <input type="hidden" name="orderRef[<?php echo $value->orderRef?>][dispatchNo][]" value="<?php echo $Items->dispatchRef;?>">
          <input type="hidden" name="orderRef[<?php echo $value->orderRef?>][sheetRef][]" value="<?php echo $Items->sheetRef;?>">
          <input type="hidden" name="orderRef[<?php echo $value->orderRef?>][orderItem][]" value="<?php echo $Items->itemRefId;?>">
          <?php echo $Items->itemName?></td>
        <td>
          <div class="input-group">
            <input class="form-control" name="orderRef[<?php echo $value->orderRef?>][baseUOM][]" type="text" value=""> <span class="input-group-addon">Meters</span> </div>
          </td>
          <td>
            <div class="input-group">
              <input class="form-control" name="orderRef[<?php echo $value->orderRef?>][qtyLoaded][]" type="text" value="<?php echo $Items->qtyLoaded ?>"> <span class="input-group-addon">Roll</span> </div>
            </td>
            <td>
              <div class="input-group">
                <input class="form-control" name="orderRef[<?php echo $value->orderRef?>][qtyNotLoaded][]" type="text" value="<?php echo $Items->qtyNotLoaded ?>"> <span class="input-group-addon">Roll</span> </div>
              </td>
              <td> <input type="text" readonly style="width:100px"  name="" value="" class="form-control"> </td>
              <?php if ( $ItemsKey == 0 ): ?>
              <td>
                <label class="switch">
                  <input id="togBtn" name="orderRef[<?php echo $value->orderRef?>][orderfullfillment][]" <?php if($Items->qtyNotLoaded == 0 ) {?> checked <?php } ?> type="checkbox">
                  <div class="slider round"></div>
                </label>
              </td>
              <?php endif; ?>
            </tr>
  <?php endforeach; ?>
<?php $i++; endforeach; ?>
<?php endif; ?>
