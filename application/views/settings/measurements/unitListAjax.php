<div class="box-body table-responsive no-padding">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Unit of measurement</th>
                <th>Created Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($records)) {
                foreach ($records as $key => $value) { ?>
                    <tr id="measurements_<?php echo $value->unitRef;?>">
                        <td class="srNum"><?php echo $key + 1; ?></td>
                        <td><a href="javascript:void(0)" class="updateUOM" data-ref="<?php echo $value->unitRef ?>" data-name="<?php echo ucwords($value->unitName); ?>"><?php echo ucfirst($value->unitName); ?></a>
                        </td>
                        <td><?php echo  date('d M Y', strtotime($value->createdDate));?></td>
                        <td class="statusTd"><?php if ($value->status == 0) {
                              echo '<span class="label label-warning">Inactive</span>';
                          } else {
                              echo '<span class="label label-success">Active</span>';
                          } ?></td>
                        <td>
                            <a href="javascript:;" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->unitName);?>" data-type="measurements" data-ref="<?php echo $value->unitRef;?>" >Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a></li>
                        </td>
                    </tr>
                <?php }
            }
            else
            { ?>
                <tr><td align="center" colspan="13">No unit of measurement found.</td></tr>
    <?php   } ?>
        </tbody>
    </table>
</div>
<div class="box-footer clearfix">
    <?php echo $paginationLinks; ?>
</div>
