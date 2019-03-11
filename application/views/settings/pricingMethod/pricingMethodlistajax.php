<table class="table table-striped">
  <thead>
    <tr>
      <th>Sr No</th>
      <th>Payement Method</th>
      <th>Added on</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($records)) {
        foreach ($records as $key => $value) { ?>
            <tr id="pricingMethod_<?php echo $value->pricingRef;?>">
                <td class="srNum"><?php echo $start + $key + 1; ?></td>
                <td><a href="javascript:void(0)" class="updatePricingMethod" data-ref="<?php echo $value->pricingRef;?>" data-name="<?php echo $value->payementMethod;?>"><?php echo ucfirst($value->payementMethod); ?></a></td>
                <td><?php echo date('d-M-Y',strtotime($value->addedOn)); ?></td>
                  <td class="statusTd"><?php if ($value->status == 0) {
                        echo '<span class="label label-warning">Inactive</span>';
                    } else {
                        echo '<span class="label label-success">Active</span>';
                    } ?></td>
                  <td>
                    <!-- <a href="<?php  echo site_url('view-user/' . $value->pricingRef); ?>" ><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp; -->
                    <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->payementMethod);?>" data-type="pricingMethod" data-ref="<?php echo $value->pricingRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                  </td>
            </tr>
        <?php }
    }
    else
    { ?>
        <tr class="noRecord"><td align="center" colspan="13">No Pricing Method found.</td></tr>
      <?php   } ?>
  </tbody>
  </table>
  <div class="">
    <?php echo $paginationLinks; ?>
  </div>
