<div class="row">
  <div class="col-md-12">
    <section class="pages" id="category">
      <div class="row form-group add_button_box text-right clearfix">
        <div class="col-md-4">
        <div class="input-group input-group-sm col-md-12 padding-left">
          <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search categories">
          <div class="input-group-btn">
            <button data-url="<?php echo site_url('categories');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </div>
        <div class="col-md-8">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddCategory">Add New Category+</button>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-Category-modal">Add New Sub Category+</button>
      </div>
      </div>
      <?php $this->load->view('commonFiles/statusBoxs'); ?>
      <div  id="tableData" class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped">
                <thead>
                      <tr>
                          <th>Sr No</th>
                          <th>Category Name</th>
                          <th>Category Type</th>
                          <th>Parent Category</th>
                          <th>Status</th>
                          <th>Actions</th>
                      </tr>
                </thead>
            <tbody>
              <?php if (!empty($records)) {
                  foreach ($records as $key => $value) { ?>
                      <tr id="category_<?php echo $value->catRef;?>">
                          <td class="srNum"><?php echo $key + 1; ?></td>
                          <?php if (trim($value->parentCatRef) !=''): ?>
                            <td><a href="javascript:void(0)" class="updateSubCategory" data-parentRef="<?php echo ($value->parentCatRef); ?>" data-ref="<?php echo ($value->catRef); ?>" data-name="<?php echo ucfirst($value->categoryName); ?>"><?php echo ucfirst($value->categoryName); ?></a></td>
                          <?php else: ?>
                            <td><a href="javascript:void(0)" class="updateCategory" data-ref="<?php echo ($value->catRef); ?>" data-name="<?php echo ucfirst($value->categoryName); ?>"><?php echo ucfirst($value->categoryName); ?></a></td>
                          <?php endif; ?>

                         <td>
                           <?php if (trim($value->parentCatRef) !=''){
                             $parentCategory  = getParentCategory($value->parentCatRef);
                             echo '<span class="label label-info">Sub Category</span>';
                           }
                           else
                                echo '<span class="label label-info">Parent Category</span>';
                           ?>
                        </td>
                        <td>
                          <?php
                          if (trim($value->parentCatRef) !='') {
                            echo $parentCategory->categoryName;
                          }
                           ?>
                        </td>
                        <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>


                            <td>
                              <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->categoryName);?>" data-type="category" data-ref="<?php echo $value->catRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                              <!-- &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="deleteRecord" data-name="<?php echo ucfirst($value->userName);?>" data-type="users" data-ref="<?php echo $value->catRef;?>"><i class="fa fa-times" aria-hidden="true"></i></a> -->
                            </td>
                      </tr>
                  <?php }
              }
              else
              { ?>
                  <tr><td align="center" colspan="13">No Categories found.</td></tr>
        <?php   } ?>
            </tbody>
            </table>
            <div class="">
              <?php echo $paginationLinks; ?>
            </div>
          </div>
        </div>
        <!-- Add Category Popup -->
        <div id="AddCategory" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <?php echo form_open('ajaxAddUpdateCategory', array('name' => 'login', 'method' => 'post', 'class' => 'add-category-form', 'id' => "add-category-form","autocomplete" => "off")); ?>
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Category</h4>
              </div>
              <div class="modal-body">
                <input type="hidden" id="catRef" name="catRef" name="" value="">
                <div class="form-group">
                  <label class="col-sm-12">Name</label>
                  <div class="col-sm-12">
                    <input type="text" name="categoryName" id="categoryName" class="form-control" placeholder="Enter Category Name">
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
      <div class="modal fade" id="add-Category-modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Sub Category</h4>
              </div>
                <div class="modal-body">
                    <div class="col-lg-12 col-md-12" id="">
                      <?php echo form_open('ajaxAddUpdateCategory', array('id' => 'add-sub-category-form', 'autocomplete' => 'off')); ?>
                          <div class="col-md-12 form-group">
                          <select class="form-control" id="parentCatRef" name="parentCatRef">
                            <option value="">Select Category</option>
                            <!-- <option value="">Add New Category</option> -->
                            <?php if (!empty($categories)){
                              foreach ($categories as $key => $value) {
                            ?>
                            <option value="<?php echo $value->catRef;?>"><?php echo $value->categoryName;?></option>
                          <?php } } else { ?>
                            <option value="1">No Category added yet</option>
                          <?php  } ?>
                          </select>
                        </div>
                          <div class="clearfix"></div>
                          <div class="col-md-12 form-group">
                            <label class="form-label">Sub Category Name</label>
                              <div class="form-group">
                                  <input type="text" name="categoryName" value="" class="form-control" placeholder="Sub Category Name">
                              </div>
                          </div>
                    </div>
                  <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                  <input type="submit" value="Save" class="btn btn-success pull-right">
                </div>
                </form>
            </div>
        </div>
    </div>
