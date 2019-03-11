<div class="detailDrow">

</div>
<div class="defaultRow row">
  <div class="col-md-12">
    <section class="pages" id="category">
      <div class="row form-group add_button_box text-right clearfix">
        <div class="col-md-4">
          <div class="input-group input-group-sm col-md-12 padding-left">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search Regions">
            <div class="input-group-btn">
              <button data-url="<?php echo site_url('regions');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNewRegion">Add New Region+</button>
      </div>
      <div class="clearfix">

      </div>
      <div class="row form-group add_button_box text-right clearfix">

          <div class="col-md-5 text-left no-padding" style="margin-left: 23px;margin-top: 12px;">
            <label class="containerCheckbox">All
              <input type="checkbox" class="filterCheckBox" value="" checked="checked">
              <span class="checkmark"></span>
            </label>
            <label class="containerCheckbox">Active
              <input type="checkbox" class="filterCheckBox" value="1">
              <span class="checkmark"></span>
            </label>
            <label class="containerCheckbox">Inactive
              <input type="checkbox" class="filterCheckBox" value="0">
              <span class="checkmark"></span>
            </label>
          </div>
        </div>


    </div>



      <div class="panel-body">

        <div id="tableData" class="table-responsive">
          <!-- <h3>Already Added Method</h3> -->
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
                          <td><?php echo $key + 1; ?></td>
                          <td><a data-url="<?php echo base_url().'cities/'. base64_encode($value->id); ?>" href="javascript:void(0)" class="viewDetails" data-ref="<?php echo ($value->id);?>" data-name="<?php echo $value->name;?>" ><?php echo ucfirst($value->name); ?></a></td>
                          <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>
                            <td>
                              <a href="javascript:void(0)"  data-ref="<?php echo ($value->id);?>" data-name="<?php echo $value->name;?>" class="updateRegion"> <i class="fa fa-edit"></i></a> &nbsp;&nbsp;
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
        </div>
      </div>
    </section>
  </div>
</div>

      <!-- Add Category Popup -->
    <div id="addNewRegion" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <?php echo form_open('addNewRegion', array('name' => 'addNewRegion', 'method' => 'post', 'id' => "add-update-region","autocomplete" => "off")); ?>
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Region</h4>
          </div>
          <div class="modal-body">
            <input type="hidden" id="stateId" name="stateId" value="">
            <div class="form-group">
              <label class="col-sm-12">Name</label>
              <div class="col-sm-12">
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Region Name">
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
