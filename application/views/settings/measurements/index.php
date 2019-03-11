<div class="row">
  <div class="col-md-12">
    <section class="pages" id="category">
      <div class="row form-group add_button_box text-right clearfix">
        <div class="col-md-4">
          <div class="input-group input-group-sm col-md-12 padding-left">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search measurements">
            <div class="input-group-btn">
              <button data-url="<?php echo site_url('unit-of-measurement');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#addUnitOfMeasurement">Add New UOM+</button>
      </div>
    </div>
    <?php $this->load->view('commonFiles/statusBoxs'); ?>
      <div class="panel-body">
        <div id="tableData">
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
        </div>
      </div>
    </section>
  </div>
</div>
