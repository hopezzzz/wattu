<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Sr No</th>
        <th>Item Name</th>
        <!-- <th>Region</th> -->
        <th>Transport Method</th>
        <th>Pricing Mode</th>
        <!-- <th>Price / Value</th> -->
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($records)) {
        $status = array('Inactive','Active');
          foreach ($records as $key => $value) { ?>
              <tr id="transportCharges_<?php echo $value->transportRef;?>">
                  <td><?php echo $key + 1; ?></td>
                  <td><a href="javascript:void(0)"  data-ref="<?php echo  ($value->itemRefId);?>" class="updateTransport" data-url="<?php echo base_url() .'update-transport-charges/'.$value->itemRefId ?>" data-name="<?php echo $value->itemName;?>"><?php echo ucfirst($value->itemName); ?></a>
                  <!-- <td class="itemName"><?php echo $value->region; ?></td> -->
                  <td class="region"><?php echo $value->deliveryMethod;  ?></td>
                  <td class="transportMethod"><?php echo $value->pricingMode;  ?></td>
                  <!-- <td class="price"><?php echo amountFormat($value->price);  ?></td> -->
                  <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>
                  <td>
                    <a href="javascript:void(0)"  data-ref="<?php echo  ($value->itemRefId);?>" class="updateTransport" data-url="<?php echo base_url() .'update-transport-charges/'.$value->itemRefId ?>" data-name="<?php echo $value->itemName;?>" class="updateRegion"> <i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                    <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->itemName);?>" data-type="transportCharges" data-ref="<?php echo ($value->transportRef);?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                  </td>
              </tr>
          <?php }
      }
      else
      { ?>
          <tr><td align="center" colspan="13">No transport charges found.</td></tr>
        <?php   } ?>
    </tbody>
  </table>
  <div class="">
    <?php echo $paginationLinks; ?>
  </div>
</div>
