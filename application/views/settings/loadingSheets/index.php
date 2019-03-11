<?php  $loginSessionData = $this->session->userdata('clientData');
            // echo "<pre>";print_r($loginSessionData)
?>
<div class="row">
  <div class="col-md-12">
    <section class="pages" id="items">
      <div class="form-group add_button_box text-right clearfix">
        <div class="col-md-4 padding-left">
          <div class="input-group input-group-sm col-md-12 padding-left">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search loading sheet">
            <div class="input-group-btn">
              <button data-url="<?php echo site_url('loading-sheets');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <a href="javascript:void(0)" class="btn btn-success"  data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#addNewLoadingSheet">Add Loading Sheet+</a>
        </div>
      </div>
      <?php $this->load->view('commonFiles/statusBoxs'); ?>
      <div  id="tableData" class="panel-body">
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
      </div>
    </section>
  </div>
</div>
</section>

<!-- Add Category Popup -->
<div id="addNewLoadingSheet" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <?php echo form_open('add-loading-sheet', array('name' => 'login', 'method' => 'post', 'id' => 'addNewLoadingSheet-form',"autocomplete" => "off")); ?>
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Loading Sheet</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" id="sheetRef" name="sheetRef" name="" value="">
          <div class="form-group">
            <label class="col-sm-12">Sheet Name</label>
            <div class="col-sm-12">
              <input type="text" name="refName" id="refName" class="form-control" placeholder="Enter Sheet Name">
            </div>
          </div>

      </div>
      <div class="modal-footer">
                  <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                 <input type="submit" value="Save" class="btn btn-success pull-right">
      </div>
    </div>
  </form>
    <!-- Modal content-->
</div>
</div>
