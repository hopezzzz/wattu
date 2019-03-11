<div class="row">
  <div class="col-md-12">
    <section class="pages" id="category">
      <div class="row form-group add_button_box text-right clearfix">
        <div class="col-md-4">
          <div class="input-group input-group-sm col-md-12 padding-left">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search Pricing Method">
            <div class="input-group-btn">
              <button data-url="<?php echo site_url('pricing-method');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddPricingMethod">Add Payment Method+</button>
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
                          <td class="srNum"><?php echo $key + 1; ?></td>
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

        </div>
      </div>
    </section>
  </div>
</div>
      <!-- Add Category Popup -->
    <div id="AddPricingMethod" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <?php echo form_open('addUpdatePricingMethod', array('name' => 'addUpdateDeliveryMethod', 'method' => 'post', 'class' => 'add-category-form', 'id' => "pricingMethod-form","autocomplete" => "off")); ?>
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Payment Method</h4>
          </div>
          <div class="modal-body">
            <input type="hidden" id="pricingRef" name="pricingRef" value="">
            <div class="form-group">
              <label class="col-sm-12">Name</label>
              <div class="col-sm-12">
                <input type="text" name="payementMethod" id="payementMethod" class="form-control" placeholder="Enter payement method Name">
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
