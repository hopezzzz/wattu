<table class="table table-striped">
  <thead>
    <tr>
      <th>Sr No</th>
      <th>Block Type</th>
      <th>Created</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($records)) {
        foreach ($records as $key => $value) { ?>
            <tr id="blockTypes_<?php echo $value->id;?>">
                <td class="srNum"><?php echo $start + $key + 1; ?></td>
                <td><a href="javascript:void(0)" class="updateBlockTypeMethod" data-ref="<?php echo $value->id;?>" data-name="<?php echo $value->blockType;?>"><?php echo ucfirst($value->blockType); ?></a></td>
                  <td><?php echo date('d-m-Y',strtotime($value->addedOn)); ?></td>
                    <td class="statusTd"><?php if ($value->status == 0) {
                      echo '<span class="label label-warning">Inactive</span>';
                    } else {
                      echo '<span class="label label-success">Active</span>';
                    } ?></td>
                <td><a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->blockType);?>" data-type="blockTypes" data-ref="<?php echo $value->id;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a></td>
            </tr>
        <?php }
    }
    else
    { ?>
        <tr class="noRecord"><td align="center" colspan="13">No Block Type Found.</td></tr>
      <?php   } ?>
  </tbody>
  </table>
  <div class="">
    <?php echo $paginationLinks; ?>
  </div>
