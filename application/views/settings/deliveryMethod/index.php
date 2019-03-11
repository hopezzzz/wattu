<div class="row">
  <div class="col-md-12">
    <section class="pages" id="category">
      <div class="row form-group add_button_box text-right clearfix">
        <div class="col-md-4">
          <div class="input-group input-group-sm col-md-12 padding-left">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search Delivery Method">
            <div class="input-group-btn">
              <button data-url="<?php echo site_url('delivery-method');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddDeliveryMethod">Add Delivery Method+</button>
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
                          <td class="srNum"><?php echo $key + 1; ?></td>
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

        </div>
      </div>
    </section>
  </div>
</div>

      <!-- Add Category Popup -->
    <div id="AddDeliveryMethod" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <?php echo form_open('addUpdateDeliveryMethod', array('name' => 'addUpdateDeliveryMethod', 'method' => 'post', 'class' => 'add-category-form', 'id' => "deliveryMethod-form","autocomplete" => "off")); ?>
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Delivery Method</h4>
          </div>
          <div class="modal-body">
            <input type="hidden" id="deliveryMethodRef" name="deliveryMethodRef" value="">
            <div class="form-group">
              <label class="col-sm-12">Name</label>
              <div class="col-sm-12">
                <input type="text" name="methodName" id="methodName" class="form-control" placeholder="Enter delivery method Name">
              </div>
            </div>
            <div class="form-group hide">
              <label class="col-sm-12">Area</label>
              <div class="col-sm-12">
                <input type="text" name="area" id="area" class="form-control" placeholder="Enter area">
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
