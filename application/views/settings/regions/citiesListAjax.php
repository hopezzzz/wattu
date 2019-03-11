<table class="table table-striped">
  <thead>
    <tr>
      <th>Sr No</th>
      <th>Town </th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($records)) {
      foreach ($records as $key => $value) { ?>
        <tr id="cities_<?php echo ($value->id);?>">
          <td><?php echo $start + $key + 1; ?></td>
          <td><a href="javascript:void(0)" data-parent="<?php echo base64_decode($this->uri->segment(2)) ?>" class="updateCity" data-ref="<?php echo ($value->id);?>" data-name="<?php echo $value->name;?>" ><?php echo ucfirst($value->name); ?></a></td>
          <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>
          <td>
            <a href="javascript:void(0)" data-parent="<?php echo base64_decode($this->uri->segment(2)) ?>" data-ref="<?php echo ($value->id);?>" class="updateCity"> <i class="fa fa-edit"></i></a> &nbsp;&nbsp;
            <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->name);?>" data-type="cities" data-ref="<?php echo ($value->id);?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
          </td>
        </tr>
      <?php }
    }
    else
    { ?>
      <tr class="noRecord"><td align="center" colspan="13">No Town found.</td></tr>
    <?php   } ?>
  </tbody>
</table>
<div class="">
  <?php echo $paginationLinks; ?>
</div>
