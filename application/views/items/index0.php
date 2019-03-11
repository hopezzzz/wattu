<?php  $loginSessionData = $this->session->userdata('clientData');
            // echo "<pre>";print_r($loginSessionData)
?>
<div class="row">
  <div class="col-md-12">
    <section class="pages" id="items">
      <div class="form-group add_button_box text-right clearfix">
        <div class="col-md-4">
          <div class="input-group input-group-sm col-md-12">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search items">
            <div class="input-group-btn">
              <button data-url="<?php echo site_url('items');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <form method="post" method="post" id="import_csv" action="<?php echo base_url() ?>/import-items" enctype="multipart/form-data">
          <input type="file" name="csv_file" id="csv_file" required accept=".csv" style="clear: both;display: inline;">
         <?php if($loginSessionData['ItemManagerWrite'] == 1)
          echo '<input type="submit" id="import_csv_btn" name="submit" value="UPLOAD" class="btn btn-primary">';
          else echo '<input type="button" value="UPLOAD" class="btn btn-primary noAccess">';
         ?>
        <a href="<?php echo site_url('export-items');?>" class="btn btn-success">Export Items In CSV</a>
        <?php if($loginSessionData['ItemManagerWrite'] == 1)
        echo '<button type="button" class="btn btn-primary"  data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#AddItem">Add Item+</button>';
        else  echo '<button type="button" class="btn btn-primary noAccess" >Add Item+</button>'
        ?>
      </form>
      </div>
      </div>
      <div  id="tableData" class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped">
                <thead>
                      <tr>
                          <th>Sr No</th>
                          <th>Item Name</th>
                          <th>Category Name</th>
                          <th>Block Type</th>
                          <!-- <th>Size</th> -->
                          <th>Price</th>
                          <th>Color</th>
                          <th>Status</th>
                          <th>Actions</th>
                      </tr>
                </thead>
            <tbody>
              <?php if (!empty($records)) {
                  foreach ($records as $key => $value) { ?>
                      <tr id="items_<?php echo $value->productRef;?>">
                          <td class="srNum"><?php echo $key + 1; ?></td>
                          <td><a href="javascript:void(0)" class="updateItem" data-ref="<?php echo ($value->productRef); ?>" data-name="<?php echo ucfirst($value->itemName); ?>"><?php echo ucfirst($value->itemName); ?></a></td>
                          <td class="TdcatRef"><?php echo getCategoryByRef($value->catRef); ?></td>
                          <td class="TdinStock"><?php if($value->blockType == 1) echo "Recon";else echo "​​Comfort";  ?></td>
                          <td class="TditemCost"><?php echo amountFormat($value->itemCost);  ?></td>
                          <td class="Tdcolor"><?php echo $value->color;  ?></td>
                            <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>

                            <td>
                              <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->itemName);?>" data-type="items" data-ref="<?php echo $value->productRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                              <!-- &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="deleteRecord" data-name="<?php echo ucfirst($value->userName);?>" data-type="users" data-ref="<?php echo $value->productRef;?>"><i class="fa fa-times" aria-hidden="true"></i></a> -->
                            </td>
                      </tr>
                  <?php }
              }
              else
              { ?>
                  <tr><td align="center" colspan="13">No Items found.</td></tr>
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
<div id="AddItem" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Item</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open('ajaxAddUpdateItem', array('name' => 'login', 'method' => 'post', 'class' => 'add-item-form', 'id' => "add-item-form","autocomplete" => "off")); ?>

        <div class="col-sm-6">
            <input type="hidden" name="productRef" id="productRef" value="">
            <div class="form-group clearfix">
              <label class="col-md-4 col-xs-12">Item Name</label>
              <div class="col-md-8 col-xs-12">
                <input type="text" class="form-control" placeholder="Enter Item Name" id="itemName" name="itemName">
              </div>
            </div>
            <div class="form-group clearfix count sizeDiv">
              <label class="col-md-4 col-xs-12">Size</label>
              <div class="col-md-7 col-xs-12 ">
                  <div class="row">
                    <div class="col-sm-3" style="padding-right:0">
                          <input type="text" class="form-control length validNumber" placeholder="Length" id="length" name="length[]">
                   </div>
                   <div class="col-sm-3" style="padding-right:0">
                      <input type="text" class="form-control width validNumber" placeholder="Width" id="width" name="width[]">
                   </div>
                   <div class="col-sm-3" style="padding-right:0">
                     <input type="text" class="form-control height validNumber" placeholder="Height" id="height" name="height[]">
                   </div>
                   <div class="col-sm-3" style="padding-right:0">
                     <label class="switch pull-rifht">
                        <input checked="" class="attrbute" data-toggle="switch" type="checkbox">
                        <span class="slider round"></span>
                      </label>
                     </a>
                   </div>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
                <div class="col-md-12 text-right">
                  <div class="form-group">
                    <a class="btn btn-primary addSize" href="javascript:void(0)">
                      <i class="fa fa-plus"></i>
                    </a>
                  </div>
                </div>
                <div class="clearfix"></div>

              <div class="form-group clearfix">
                <label class="col-md-4 col-xs-12">Item Category</label>
                <div class="col-md-8 col-xs-12">
                  <select class="form-control" id="catRef" name="catRef">
                    <option value="">Select Category</option>
                    <option value="">Add New Category</option>
                    <?php if (!empty($categories)){
                      foreach ($categories as $key => $value) {
                    ?>
                    <option value="<?php echo $value->catRef;?>"><?php echo $value->categoryName;?></option>
                  <?php } } else { ?>
                    <option value="1">No Category added yet</option>
                  <?php  } ?>
                  </select>
                </div>
              </div>
              <div class="form-group clearfix">
                <label class="col-md-4 col-xs-12">Item Sub Category</label>
                <div class="col-md-8 col-xs-12">
                  <select class="form-control" id="subCat" name="subCat">
                    <option value="">Select Category</option>
                    <option value="">Add New Category</option>

                    <option value="1">No Category added yet</option>

                  </select>
                </div>
              </div>
              <div class="form-group clearfix designDiv">
                <label class="col-md-4 col-xs-12">Design</label>
                <div class="col-md-5 col-xs-12" style="padding-right:0">
                  <input type="text" class="form-control" placeholder="Enter Design" id="design" name="design[]">
                </div>
                <div class="col-sm-3" style="padding-right:0">
                  <label class="switch pull-rifht">
                     <input checked="" class="attrbute" data-toggle="switch" type="checkbox">
                     <span class="slider round"></span>
                   </label>
                </div>
              </div>
              <div class="clearfix"></div>
                <div class="col-md-12 text-right">
                  <div class="form-group">
                    <a class="btn btn-primary addDesignInput" href="javascript:void(0)">
                      <i class="fa fa-plus"></i>
                    </a>
                  </div>
                </div>
                <div class="clearfix"></div>
               <div class="form-group clearfix colorDiv">
                <label class="col-md-4 col-xs-12">Color</label>
                <div class="col-md-5 col-xs-12" style="padding-right:0">
                  <input type="text" class="form-control" placeholder="Enter Color" id="color" name="color[]">
                </div>
                <div class="col-sm-3" style="padding-right:0">
                  <label class="switch pull-rifht">
                     <input checked="" class="attrbute" data-toggle="switch" type="checkbox">
                     <span class="slider round"></span>
                   </label>
                  </a>
                </div>
              </div>
              <div class="clearfix"></div>
                <div class="col-md-12 text-right">
                  <div class="form-group">
                    <a class="btn btn-primary addColorInput" href="javascript:void(0)">
                      <i class="fa fa-plus"></i>
                    </a>
                  </div>
                </div>
                <div class="clearfix"></div>
              <div class="form-group clearfix">
                  <label class="col-md-4 col-xs-12">UOM Type</label>
                  <div class="col-md-8 col-xs-12">
                    <select class="form-control uomType" id="uomType" name="uomType">
                      <option value="">Select UOM Type</option>
                        <option value="1">Fixed</option>
                        <option value="2">Variable</option>
                    </select>
                  </div>
              </div>
              <div class="form-group clearfix">
                  <label class="col-md-4 col-xs-12">BASE UOM</label>
                  <div class="col-md-8 col-xs-12">
                    <select class="form-control selectUOM" id="baseUOM" name="baseUOM" data-html="true">
                      <option value="">Select UOM</option>
                      <option value="addNewUOM">Add New UOM</option>
                      <?php
                      $getUOM = getUOM();
                      if (!empty($getUOM)) {
                        foreach ($getUOM as $key => $value)
                        { ?>
                          <option value="<?= $value->unitRef;?>"><?php echo $value->unitName; ?></option>
                        <?php }
                      }
                       ?>
                    </select>
                  </div>
              </div>
              <div class="form-group clearfix preventDefault" style="display:none">
                <label class="col-md-4 col-xs-12">Coversion Qty / Length</label>
                <div class="col-md-8 col-xs-12" style="padding:0">
                  <div class="col-sm-6">
                        <input type="text" class="form-control" value="1" readonly placeholder="Roll Qty" id="baseConvQty" name="baseConvQty"> <span> = Roll</span>
                 </div>
                 <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Roll Length" id="baseConvLength" name="baseConvLength"> <span> = Meter</span>
                 </div>
               </div>
              </div>
        </div>
        <div class="col-sm-6">
              <div class="form-group clearfix">
                  <label class="col-md-4 col-xs-12">Sales UOM</label>
                  <div class="col-md-8 col-xs-12">
                    <select class="form-control selectUOM" id="saleUOM" name="saleUOM" data-html="true">
                      <option value="">Select UOM</option>
                      <option value="addNewUOM">Add New UOM</option>
                      <?php
                      $getUOM = getUOM();
                      if (!empty($getUOM)) {
                        foreach ($getUOM as $key => $value)
                        { ?>
                          <option value="<?= $value->unitRef;?>"><?php echo $value->unitName; ?></option>
                        <?php }
                      }
                       ?>
                    </select>
                  </div>
              </div>
              <div class="form-group clearfix preventDefault" style="display:none">
                <label class="col-md-4 col-xs-12">Coversion Qty / Length</label>
                <div class="col-md-8 col-xs-12" style="padding:0">
                  <div class="col-sm-6">
                        <input type="text" class="form-control" value="1" readonly placeholder="Roll Qty" id="saleConvQty" name="saleConvQty">  <span> = Roll</span>
                 </div>
                 <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Roll Length" id="saleConvLength" name="saleConvLength"> <span> = Meter</span>
                 </div>
               </div>
              </div>
          <div class="form-group clearfix">
            <label class="col-md-4 col-xs-12">Production on Demand</label>
            <div class="col-md-8 col-xs-12">
              <select class="form-control" id="productionOnDemand" name="productionOnDemand">
                <option value="">Select status</option>
                <option value="0">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-md-4 col-xs-12">Transport Cost</label>
            <div class="col-md-8 col-xs-12">
              <input type="text" class="form-control validNumber" placeholder="Enter Transport Cost" id="transportCost" name="transportCost">
            </div>
          </div>

          <div class="form-group clearfix">
            <label class="col-md-4 col-xs-12">Item  Minimum Cost</label>
            <div class="col-md-8 col-xs-12">
              <input type="text" class="form-control validNumber" placeholder="Enter Item minimum Cost" id="minimumCost" name="minimumCost">
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-md-4 col-xs-12">Item Default Price</label>
            <div class="col-md-8 col-xs-12">
              <input type="text" class="form-control validNumber" placeholder="Enter Item Default Price" id="itemCost" name="itemCost">
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-md-4 col-xs-12">Block Type</label>
            <div class="col-md-8 col-xs-12">
              <select id="blockType" name="blockType" class="form-control">
                <option value=""> Select Block Type</option>
                <option value="1">Recon</option>
                <option value="2">​​Comfort</option>
              </select>
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-md-4 col-xs-12">Block Percentage</label>
            <div class="col-md-8 col-xs-12">
              <input type="text" class="form-control validNumber" placeholder="Block Percentage" id="blockPercentage" name="blockPercentage">
            </div>
          </div>
        </div>
          <div class="clearfix"></div>
          <div class="form-group clearfix">
            <div class="col-sm-12 text-right">
              <button type="submit" class="btn btn-primary">Add</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>


<div id="add-new-uom" class="hide">
  <div class="col-md-12">
            <div class="form-group">
              <label class="col-sm-12">Unit of measurement</label>
              <div class="col-sm-12">
                <div class="form-group">
                  <input name="unitName" id="unitName" autofocus class="form-control unitName" placeholder="Enter UOM Name" type="text">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <button type="button" class="btn btn-primary" id="saveUOM">Save UOM</button>
              </div>
            </div>
  </div>
  <div class="clearfix"></div>

</div>

<!-- Add Category Popup -->
<div id="AddCategory" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add New Category</h4>
      </div>
      <div class="modal-body">

        <?php echo form_open('ajaxAddUpdateCategory', array('name' => 'login', 'method' => 'post', 'id' => 'add-category-form', 'id' => "add-category-form","autocomplete" => "off")); ?>
        <input type="hidden" id="catRef" name="catRef" name="" value="">
        <div class="form-group">
          <label class="col-sm-12">Name</label>
          <div class="col-sm-12">
            <input type="text" name="categoryName" id="categoryName" class="form-control" placeholder="Enter Category Name">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
        </div>
      </form>
    </div>
  </div>
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
                        <option value="">Add New Category</option>
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
                        <label class="form-label"> Category Name</label>
                          <div class="form-group">
                              <input type="text" name="categoryName" value="" class="form-control" placeholder="Category Name">
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

<div id="cloneDivSize" class="form-group clearfix hide">
  <label class="col-md-4 col-xs-12"></label>
  <div class="col-md-7 col-xs-12 ">
      <div class="row">
        <div class="col-sm-3" style="padding-right:0">
              <input type="text" class="form-control length validNumber" placeholder="Length" id="length" name="length[]">
       </div>
       <div class="col-sm-3" style="padding-right:0">
          <input type="text" class="form-control width validNumber" placeholder="Width" id="width" name="width[]">
       </div>
       <div class="col-sm-3" style="padding-right:0">
         <input type="text" class="form-control height validNumber" placeholder="Height" id="height" name="height[]">
       </div>
       <div class="col-sm-3" style="padding-right:0">
         <label class="switch pull-rifht">
            <input checked="" class="attrbute" data-toggle="switch" type="checkbox">
            <span class="slider round"></span>
          </label>
         </a>
       </div>
      </div>
    </div>
    <a href="javascript:void(0)" class="remove_btn">X</a>
</div>

<div id="cloneDivColor" class="form-group clearfix hide">

    <label class="col-md-4 col-xs-12"></label>
    <div class="col-md-5 col-xs-12" style="padding-right:0">
      <input type="text" class="form-control" placeholder="Enter Color" id="color" name="color[]">
    </div>
    <div class="col-sm-3" style="padding-right:0">
      <label class="switch pull-rifht">
         <input checked="" class="attrbute" data-toggle="switch" type="checkbox">
         <span class="slider round"></span>
       </label>
       <a href="javascript:void(0)" class="remove_btn">X</a>
    </div>


</div>

<div id="cloneDivDesign" class="form-group clearfix hide">
    <label class="col-md-4 col-xs-12"></label>
    <div class="col-md-5 col-xs-12" style="padding-right:0">
      <input type="text" class="form-control" placeholder="Enter Design" id="design" name="design[]">
    </div>
    <div class="col-sm-3" style="padding-right:0">
      <label class="switch pull-rifht">
         <input checked="" class="attrbute" data-toggle="switch" type="checkbox">
         <span class="slider round"></span>
       </label>
       <a href="javascript:void(0)" class="remove_btn">X</a>
    </div>
</div>

<script>

$(document).ready(function(){
 $('#import_csv').on('submit', function(event){
  event.preventDefault();
  $.ajax({
   url:"<?php echo base_url(); ?>import-items",
   method:"POST",
   data:new FormData(this),
   contentType:false,
   cache:false,
   processData:false,
   beforeSend:function(){
    $('#import_csv_btn').html('Importing...');
   },
   success:function(data)
   {
    $('#import_csv')[0].reset();
    $('#import_csv_btn').attr('disabled', false);
    $('#import_csv_btn').html('Import Done');
    iziToast.success({
      timeout: 4000,
      title: 'Success',
      message: 'CSV File imported successfully.',
      position: 'bottomRight',
    })
   }
  })
 });

 $(document).on('click','.addSize',function(){
        var block = jQuery("#cloneDivSize").clone();
        block.removeAttr('id').addClass('count').removeClass('hide').addClass('hasRemovedElement sizeDiv');
        var countt = jQuery('.count').length;
        countt++;
        block.find("#color").addClass('color');
        // block.find("#length").addClass('length').attr('name','length[' + countt + ']');
        // block.find("#width").addClass('width').attr('name','width[' + countt + ']');
        // block.find("#height").addClass('height').attr('name','height[' + countt + ']');
        block.insertAfter(".sizeDiv:last");
    callToEnhanceValidate();
  });
 $(document).on('click','.addColorInput',function(){
   console.log('asdfasd');
        var block = jQuery("#cloneDivColor").clone();
        block.removeAttr('id').addClass('count').removeClass('hide').addClass('hasRemovedElement colorDiv');
        var countt = jQuery('.count').length;
        countt++;
        block.find("#length").addClass('length');
        block.find("#width").addClass('width');
        block.find("#height").addClass('height');
        block.insertAfter(".colorDiv:last");
  });
 $(document).on('click','.addDesignInput',function(){
   console.log('asdfasd');
        var block = jQuery("#cloneDivDesign").clone();
        block.removeAttr('id').addClass('count').removeClass('hide').addClass('hasRemovedElement designDiv');
        var countt = jQuery('.count').length;
        countt++;
        block.find("#length").addClass('length');
        block.find("#width").addClass('width');
        block.find("#height").addClass('height');
        block.insertAfter(".designDiv:last");
  });
  var callToEnhanceValidate=function()
  {
    $(".length").each(function()
    {
        $(this).rules('remove');
        $(this).rules('add', {
                required: true,
      messages: {
        required: "required."
      },
         });
    });

    $(".width").each(function()
    {
        $(this).rules('remove');
        $(this).rules('add', {
                required: true,
      messages: {
        required: "required."
      },
         });
    });

    $(".height").each(function()
    {
        $(this).rules('remove');
        $(this).rules('add', {
                required: true,
                noSpace: true,
      messages: {
        required: "required."
      },
         });
    });

  }
});

</script>
