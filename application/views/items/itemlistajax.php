<div class="table-responsive">
          <table class="table table-striped">
                <thead>
                      <tr>
                          <th>Sr No</th>
                          <th>Item Name</th>
                          <th>Product ID</th>

                          <th>Category Name</th>
                          <th>Block Type</th>
                          <!-- <th>Size</th> -->
                          <th>Price</th>
                          <!-- <th>Color</th> -->
                          <th>Status</th>
                          <th>Actions</th>
                      </tr>
                </thead>
            <tbody>
              <?php if (!empty($records)) {
                  foreach ($records as $key => $value) { ?>
                      <tr id="items_<?php echo $value->productRef;?>">
                          <td class="srNum"><?php echo $start + $key + 1; ?></td>
                          <td><a href="javascript:void(0)" class="updateItem" data-ref="<?php echo ($value->productRef); ?>" data-name="<?php echo ucfirst($value->itemName); ?>"><?php echo ucfirst($value->itemName); ?></a></td>
                          <td><?php echo $value->productRef; ?></td>

                          <td class="TdcatRef"><?php echo getCategoryByRef($value->catRef); ?></td>
                        <?php echo(getblockType($value->blockType));  ?>
                          <td class="TditemCost"><?php echo amountFormat($value->itemCost);  ?></td>
                          <!-- <td class="Tdcolor"><?php echo $value->color;  ?></td> -->
                            <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>

                            <td>
                              <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->itemName);?>" data-type="items" data-ref="<?php echo $value->productRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                              <!-- &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="deleteRecord" data-name="<?php echo ucfirst($value->userName);?>" data-type="users" data-ref="<?php echo $value->productRef;?>"><i class="fa fa-times" aria-hidden="true"></i></a> -->
                            </td>
                      </tr>
                  <?php }
              }
              else
              { ?>
                  <tr><td align="center" colspan="13">No Items found.</td></tr>
                <?php   } ?>
            </tbody>
            </table>
            <div class="">
              <?php echo $paginationLinks; ?>
            </div>
          </div>
        </div>
