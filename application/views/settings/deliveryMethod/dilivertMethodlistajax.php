<table class="table table-striped">
  <thead>
    <tr>
      <th>Sr No</th>
      <th>Method</th>
      <!-- <th>Area</th> -->
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($records)) {
        foreach ($records as $key => $value) { ?>
            <tr id="deliveryMethod_<?php echo $value->deliveryMethodRef;?>">
                <td class="srNum"><?php echo $start + $key + 1; ?></td>
                <td><a href="javascript:void(0)" class="updateDeliveryMethod" data-ref="<?php echo $value->deliveryMethodRef;?>" data-name="<?php echo $value->methodName;?>" data-area="<?php echo $value->area;?>"><?php echo ucfirst($value->methodName); ?></a></td>
                <!-- <td class="area"><?php echo ($value->area); ?></td> -->
                  <td class="statusTd"><?php if ($value->status == 0) {
                        echo '<span class="label label-warning">Inactive</span>';
                    } else {
                        echo '<span class="label label-success">Active</span>';
                    } ?></td>
                  <td>
                    <!-- <a href="<?php  echo site_url('view-user/' . $value->deliveryMethodRef); ?>" ><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp; -->
                    <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->methodName);?>" data-type="deliveryMethod" data-ref="<?php echo $value->deliveryMethodRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                  </td>
            </tr>
        <?php }
    }
    else
    { ?>
        <tr class="noRecord"><td align="center" colspan="13">No Delivery Method found.</td></tr>
      <?php   } ?>
  </tbody>
  </table>
  <div class="">
    <?php echo $paginationLinks; ?>
  </div>
