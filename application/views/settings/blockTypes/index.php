<div class="row">
  <div class="col-md-12">
    <section class="pages" id="category">
      <div class="row form-group add_button_box text-right clearfix">
        <div class="col-md-4">
          <div class="input-group input-group-sm col-md-12 padding-left">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search Block Type">
            <div class="input-group-btn">
              <button data-url="<?php echo site_url('block-types');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#blockTypeModal">Add Block Type+</button>
      </div>
    </div>
    <?php $this->load->view('commonFiles/statusBoxs'); ?>
      <div class="panel-body">

        <div id="tableData" class="table-responsive">
          <!-- <h3>Already Added Method</h3> -->
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
                          <td class="srNum"><?php echo $key + 1; ?></td>
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

        </div>
      </div>
    </section>
  </div>
</div>

      <!-- Add Category Popup -->
    <div id="blockTypeModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <?php echo form_open('addUpdateblockTypes', array('name' => 'addUpdateblockTypes', 'method' => 'post', 'class' => 'add-block-type-form', 'id' => "add-block-type-form","autocomplete" => "off")); ?>
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Block Type</h4>
          </div>
          <div class="modal-body">
            <input type="hidden" id="id" name="id" value="">
            <div class="form-group">
              <label class="col-sm-12">Name</label>
              <div class="col-sm-12">
                <input type="text" name="blockType" id="blockType" class="form-control" placeholder="Enter Block Type">
              </div>
            </div>

        </div>
        <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                   <input type="submit" value="Save" class="btn btn-success pull-right">
        </div>
        </div>
      </form>

      </div>
    </div>
