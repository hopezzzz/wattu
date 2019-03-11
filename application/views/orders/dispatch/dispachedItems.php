
<?php

 if ($type == 'saveDispatch'): ?>
  <?php $items = 0 ; $i = 0; if (!empty($orderDetails)): ?>
  <?php foreach ($orderDetails as $key => $value): ?>
    <?php
    // echo '<pre>'; print_r($value);die;
    $items++;
     // echo '<pre>';print_r($value);
     $count = count($value->orderItems)

     ?>
    <?php foreach ($value->orderItems as $ItemsKey => $Items): ?>


      <?php



      $lastDispatched = getLastDispatchedItem($value->orderRef,$Items->itemRefId,$Items->variant_id);
      $variants  = getOrderItemVariants(null,$Items->variant_id);

      $dispachedQty   = $Items->qtyLoaded;
      $orderQty       = $variants->qty;
      $lastDispatched = $lastDispatched->lastDispatchedQty;

      if(!empty($lastDispatched)){ //&& $lastDispatched != "undefined"){
        $lastDisp = $lastDispatched;
      }else{
        $lastDisp = 0;
      }
      $dispachedQty	= $dispachedQty + $lastDisp;


      $OrderQtyItems = getOrderQtyItemsVariants($value->orderRef,$Items->itemRefId,$Items->variant_id);
      $data          = getDispatchedItems($value->orderRef,$Items->itemRefId,$variants->variant_id);
      $disabled= '';
      if ($data->dispatchedQty >= $OrderQtyItems->orderQty) {
        $disabled = 'disabled';
      }
       ?>
      <tr class="parentTr<?php echo $items; ?>">
        <?php

         if ($key == $i && $ItemsKey == 0 ): ?>
          <td rowspan="<?php echo $count;?>"><b><?php echo $value->orderAddress[0]->businessName ?></b>
            <br> Order #<?php echo $value->orderNo; ?>
          </td>

        <?php endif; ?>
          <td>
            <input type="hidden" name="orderQty" value="<?php echo $variants->qty; ?>" class="orderQty">
            <input type="hidden" <?php echo $disabled ?> name="orderRef[<?php echo $value->orderRef?>][]" value="<?php echo $value->orderRef;?>">
            <input type="hidden" <?php echo $disabled ?> name="orderRef[<?php echo $value->orderRef?>][dispatchNo][]" value="<?php echo $Items->dispatchRef;?>">
            <input type="hidden" <?php echo $disabled ?> name="orderRef[<?php echo $value->orderRef?>][sheetRef][]" value="<?php echo $Items->sheetRef;?>">
            <input type="hidden" <?php echo $disabled ?> name="orderRef[<?php echo $value->orderRef?>][orderItem][]" value="<?php echo $Items->itemRefId;?>">
            <input type="hidden" <?php echo $disabled ?> name="orderRef[<?php echo $value->orderRef?>][variant_id][]" value="<?php echo $variants->variant_id;?>">
            <?php echo $Items->itemName?>
          </td>
          <td><?php echo getDiamentions($variants->length,$variants->width,$variants->length,$variants->height) ?> <?php echo ucfirst($variants->color) ?> <?php echo ucfirst($variants->design) ?></td>
            <td><?php echo $OrderQtyItems->orderQty; ?></td>

          <td <?php if($disabled !== 'disabled') echo ' class="ignore"'  ?>>
            <div class="input-group">
              <input <?php echo $disabled ?> class="form-control validNumber <?php if(get_ItemUOM($value->orderRef , $Items->itemRefId)->saleUOM == get_ItemUOM($value->orderRef , $Items->itemRefId)->baseUOM ) echo 'baseMatched'; ?> baseUOMQty <?php echo $disabled ?>" name="orderRef[<?php echo $value->orderRef?>][baseUOM][]" type="text" value='<?php if($Items->uomType == 1){ echo $Items->saleConvLength * $Items->qtyLoaded; } ?>'> <span class="input-group-addon"> <?php echo get_ItemUOM($value->orderRef , $Items->itemRefId)->baseUOM; ?>
               </span> </div>
            </td>
            <td>
              <div class="input-group">
                <input data-ref="<?php echo $Items->saleConvLength;?>" <?php echo $disabled ?> data-target="<?php echo $value->orderRef;?>" class="fullfillment validNumber dispachedQty <?php if($Items->uomType == 1){?> uomTypeFixed <?php } ?> form-control <?php echo $disabled ?>" name="orderRef[<?php echo $value->orderRef?>][qtyLoaded][]" type="text" value="<?php echo $Items->qtyLoaded?>">
                <span class="input-group-addon"> <?php echo get_ItemUOM($value->orderRef , $Items->itemRefId)->saleUOM ?> </span> </div>
              </td>
              <td>
                <div class="input-group">
                  <input <?php echo $disabled ?> class="peindingQty form-control <?php echo $disabled ?>" readonly name="orderRef[<?php echo $value->orderRef?>][qtyNotLoaded][]" type="text" value="0">
                 </div>
                </td>
                <td> <?php

                    if (!empty($lastDispatched))
                    {

                        if (trim($lastDispatched) !='') {
                          echo "<p>" . $lastDispatched .'&nbsp'. get_ItemUOM($value->orderRef , $Items->itemRefId)->saleUOM . "</p>";
                          echo "<input class='dispatched' type='hidden' value='".$lastDispatched."'>";
                        }else {
                          echo "<p class='notDispatched'>No Dispatched yet</p>";
                        }
                    } else {
                      echo "<p class='notDispatched'>No Dispatched yet</p>";
                    }

                 ?> </td>
                <?php if ( $ItemsKey == 0 ): ?>
                <td rowspan="<?php echo $count;?>">
                  <label class="switch">
                    <input id="togBtn" class="saveDispatchFullfillment" name="orderRef[<?php echo $value->orderRef?>][orderfullfillment][]" <?php if($dispachedQty >= $orderQty) echo 'checked'; ?> type="checkbox"  >
                    <div class="slider round"></div>
                  </label>
                </td>
                <td rowspan="<?php echo $count;?>">
                  <input name="orderRef[<?php echo $value->orderRef?>][invoiceNo][]" type="text" class="form-control validNumber" placeholder="invoice no">
                </td>
                <?php endif; ?>
              </tr>
    <?php  endforeach; ?>
  <?php $i++;  endforeach; ?>
  <tr>
<td style="display:none">
  <input type="hidden" id="totalItem" value="<?php echo $items;?>">
</td>
  </tr>
  <?php endif; ?>

<?php else:
  // pr($orderItems);die;
  foreach ($orderItems as $key => $value):?>
          <?php
          $i = 0;
      foreach ($value->variants as $skey => $itemVariant) { ?>
        <?php
              $data =  getDispatchedItems($orderDetails->orderRef,$value->itemRefId,$itemVariant->variant_id);
              $disabled= '';
              if ($data->dispatchedQty >= $itemVariant->qty) {
                $disabled = 'disabled';
              }
              $getToDispatchQty = getToDispatchQty($orderDetails->orderRef,$value->itemRefId,$itemVariant->variant_id);
              // pr ($getToDispatchQty);
              if (isset($getToDispatchQty->toDispatchQty)) {
                $toLoadQty  =  $getToDispatchQty->toDispatchQty;
              }else{
                $toLoadQty = $itemVariant->qty;
              }
              $getPendingDispatchQty = getPendingDispatchQty($orderDetails->orderRef,$value->itemRefId,$itemVariant->variant_id);
              // pr($getPendingDispatchQty);die;
              if (isset($getPendingDispatchQty->pendingDispatchQty)) {
                $pendingQty = $getPendingDispatchQty->pendingDispatchQty;
              }else{
                $pendingQty = 0;
              }
              ?>

        <?php if($i != 0){ ?>
          <td></td>
          <td></td>
          <td><?php echo $itemVariant->qty ?></td>
        <?php }else{?>
          <tr class="<?php echo $disabled ?>">
             <td><?php echo $key+1; ?></td>
             <td><?php echo $value->itemName; ?></td>
             <td><?php echo $itemVariant->qty ?></td>
        <?php } ?>
           <td><?php echo getDiamentions($itemVariant->length,$itemVariant->width,$itemVariant->length,$itemVariant->height) ?> <?php echo ucfirst($itemVariant->color) ?> <?php echo ucfirst($itemVariant->design) ?></td>
           <td>
              <div class="input-group">
                <input type="hidden" name="orderQty" value="<?php echo $itemVariant->qty; ?>" class="orderQty">
                <input <?php echo $disabled ?> class="form-control <?php echo $disabled ?>" name="load_item[itemRefId][]" value="<?php echo $value->itemRefId?>" type="hidden">
                <input <?php echo $disabled ?> class="form-control <?php echo $disabled ?>" name="load_item[itemName][]" value="<?php echo $value->itemName?>" type="hidden">
                <input <?php echo $disabled ?> class="form-control <?php echo $disabled ?>" name="load_item[height][]" value="<?php echo $itemVariant->height?>" type="hidden">
                <input <?php echo $disabled ?> class="form-control <?php echo $disabled ?>" name="load_item[width][]" value="<?php echo $itemVariant->width?>" type="hidden">
                <input <?php echo $disabled ?> class="form-control <?php echo $disabled ?>" name="load_item[length][]" value="<?php echo $itemVariant->length?>" type="hidden">
                <input <?php echo $disabled ?> class="form-control <?php echo $disabled ?>" name="load_item[variant_id][]" value="<?php echo $itemVariant->variant_id?>" type="hidden">
                <input <?php echo $disabled ?> class="form-control validNumber dispachedQty fullfillment  <?php echo $disabled ?>" name="load_item[saleQty][]" value="<?php if($disabled !='') echo '0'; else echo $toLoadQty; ?>" type="text">
                <span class="input-group-addon"><?php echo get_ItemUOM($value->orderRef , $value->itemRefId)->saleUOM ; ?></span>
              </div>
           </td>
           <td>
              <div class="input-group">
                <input readonly <?php echo $disabled ?> class="form-control peindingQty <?php echo $disabled ?>" name="load_item[pendingQty][]" value="<?php echo $pendingQty;?>" type="text">
                <span class="input-group-addon"><?php echo get_ItemUOM($value->orderRef , $value->itemRefId)->saleUOM; ?></span>
              </div>
           </td>
           <td><?php

               $lastDispatched = getLastDispatchedItem($value->orderRef,$value->itemRefId,$itemVariant->variant_id);
               if (!empty($lastDispatched))
               {
                   if (trim($lastDispatched->lastDispatchedQty) !='') {
                     echo "<p>" . $lastDispatched->lastDispatched .'&nbsp'. get_ItemUOM($value->orderRef , $value->itemRefId)->saleUOM . "</p>";

                     echo "<input class='dispatched' type='hidden' value='".$lastDispatched->lastDispatchedQty."'>";
                   }else {
                     echo "<p class='notDispatched'>No Dispatched yet</p>";
                   }
               } else {
                 echo "<p class='notDispatched'>No Dispatched yet</p>";
               }

            ?> </td>
        </tr>
      <?php
      $i++;
      }
    ?>
  <?php endforeach; ?>
<?php endif; ?>
