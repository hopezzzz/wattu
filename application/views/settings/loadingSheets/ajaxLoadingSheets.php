<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Sr No</th>
        <th>Sheet Name</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($records)) {
        foreach ($records as $key => $value) { ?>
          <tr id="loadingSheet_<?php echo $value->sheetRef;?>">
            <td class="srNum"><?php echo $key + 1; ?></td>
            <td><a href="javascript:void(0)" class="updateSheet" data-ref="<?php echo $value->sheetRef; ?>" data-name="<?php echo ucfirst($value->refName); ?>"><?php echo ucfirst($value->refName); ?></a></td>
            <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>
            <td>
              <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->refName);?>" data-type="loadingSheet" data-ref="<?php echo $value->sheetRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
            </td>
          </tr>
        <?php }
      }
      else
      { ?>
        <tr><td align="center" colspan="13">No Loading sheet found.</td></tr>
      <?php   } ?>
    </tbody>
  </table>
  <div class="">
    <?php echo $paginationLinks; ?>
  </div>
</div>
