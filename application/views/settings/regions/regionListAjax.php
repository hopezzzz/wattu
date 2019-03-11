<table class="table table-striped">
  <thead>
    <tr>
      <th>Sr No</th>
      <th>Region Name</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($records)) {
        foreach ($records as $key => $value) { ?>
            <tr id="region_<?php echo ($value->id);?>" class="region_<?php echo ($value->id);?>">
                <td><?php echo $key + $start + 1; ?></td>
                <td><a href="<?php echo base_url().'cities/'. base64_encode($value->id); ?>" class="updateRegion" data-ref="<?php echo base64_encode($value->id);?>" data-name="<?php echo $value->name;?>" ><?php echo ucfirst($value->name); ?></a></td>
                <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>
                  <td>
                    <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->name);?>" data-type="region" data-ref="<?php echo ($value->id);?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                  </td>
            </tr>
        <?php }
    }
    else
    { ?>
        <tr class="noRecord"><td align="center" colspan="13">No Region found.</td></tr>
      <?php   } ?>
  </tbody>
  </table>
  <div class="">
    <?php echo $paginationLinks; ?>
  </div>
