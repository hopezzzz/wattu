<?php  $loginSessionData = $this->session->userdata('clientData');
// echo "<pre>";print_r($loginSessionData)
?>
<div class="row">
  <div class="col-md-12">

    <section class="pages" id="items">
      <div class="form-group add_button_box text-right clearfix">
        <div class="col-md-4" style="padding-left:0">
          <div class="input-group input-group-sm col-md-12" style="padding-left:0">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search items">
            <div class="input-group-btn">
              <button data-url="<?php echo site_url('items');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <!-- <form method="post" method="post" id="import_csv" action="<?php echo base_url() ?>/import-items" enctype="multipart/form-data">
          <input type="file" name="csv_file" id="csv_file" required accept=".csv" style="clear: both;display: inline;">
          <?php if($loginSessionData['ItemManagerWrite'] == 1)
          echo '<input type="submit" id="import_csv_btn" name="submit" value="UPLOAD" class="btn btn-primary">';
          else echo '<input type="button" value="UPLOAD" class="btn btn-primary noAccess">';
          ?>


        </form> -->
        <a href="<?php echo site_url('export-items');?>" class="btn btn-success">Export Items In CSV</a>
        <?php if($loginSessionData['ItemManagerWrite'] == 1)
        echo '<button type="button" class="btn btn-primary addNewModal">Add Item+</button>';
        else  echo '<button type="button" class="btn btn-primary noAccess" >Add Item+</button>'
        ?>
      </div>
    </div>

    <div class="col-md-5 text-left no-padding">
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
    <div class="clearfix">

    </div>
    <div id="tableData" class="panel-body">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Sr No</th>
              <th>Item Name</th>
              <th>Product ID</th>
              <th>Category Name</th>
              <th>Block Type</th>
              <th>Price</th>
              <!-- <th>Color</th> -->
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($records)) {
              foreach ($records as $key => $value) { ?>
                <tr id="items_<?php echo $value->productRef;?>">
                  <td class="srNum">
                    <?php echo $key + 1; ?>
                  </td>
                  <td><a href="javascript:void(0)" class="updateItem" data-ref="<?php echo ($value->productRef); ?>" data-name="<?php echo ucfirst($value->itemName); ?>"><?php echo ucfirst($value->itemName); ?></a></td>
                  <td><?php echo $value->productRef; ?></td>
                  <td class="TdcatRef">
                    <?php echo getCategoryByRef($value->catRef); ?>
                  </td>
                  <td class="TdinStock">
                    <?php echo(getblockType($value->blockType));  ?>
                  </td>
                  <td class="TditemCost">
                    <?php echo amountFormat($value->itemCost);  ?>
                  </td>
                  <!-- <td class="Tdcolor">
                  <?php echo $value->color;  ?>
                </td> -->
                <td class="statusTd">
                  <?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>

                  <td>
                    <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->itemName);?>" data-type="items" data-ref="<?php echo $value->productRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                    <!-- &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="deleteRecord" data-name="<?php echo ucfirst($value->userName);?>" data-type="users" data-ref="<?php echo $value->productRef;?>"><i class="fa fa-times" aria-hidden="true"></i></a> -->
                  </td>
                </tr>
              <?php }
            }
            else
            { ?>
              <tr>
                <td align="center" colspan="13">No Items found.</td>
              </tr>
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
        <button type="button" data-ref="" class="btn btn-primary" id="saveUOM">Save UOM</button>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
</div>


<div id="add-new-block-type" class="hide">
  <div class="col-md-12">
      <div class="form-group">
        <label>Block Type</label>
          <div class="form-group">
            <input name="block_type" id="block_type" autofocus class="form-control" placeholder="Enter Block Type" type="text">
          </div>
          <button type="button" data-ref="" class="btn btn-primary" id="saveblockType">Save Block Type</button>
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
            <input type="hidden" name="frontEnd" value="1">
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
                  <option value="<?php echo $value->catRef;?>">
                    <?php echo $value->categoryName;?>
                  </option>
                <?php } } else { ?>
                  <option value="1">No Category added yet</option>
                <?php  } ?>
              </select>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 form-group">
              <label class="form-label">  Sub Category Name </label>
              <div class="form-group">
                <input type="hidden" name="frontEnd" value="1">
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

<table>
  <tbody>
    <tr id="nextLine" class="hide">
      <td class="serialNumber">1</td>
      <td>
        <input type="text" class="form-control length validNumber" placeholder="Length" id="length[]" name="length[]">
      </td>
      <td>
        <input type="text" class="form-control width validNumber" placeholder="Width" id="width[]" name="width[]">
      </td>
      <td>
        <input type="text" class="form-control height validNumber" placeholder="Height" id="height[]" name="height[]">
      </td>
      <td class="addMins"> <i class="addSizes fa fa-plus faIcon"></i> </td>
    </tr>
  </tbody>
</table>
<table>
  <tbody>
    <tr id="nextLineDesign" class="hide">
      <td>
        <input type="text" class="form-control design" placeholder="Enter Design" id="design" value="" name="design[]">
      </td>
      <td class="addMins"> <i class="addDesign fa fa-plus faIcon"></i> </td>
    </tr>
  </tbody>
</table>
<table>
  <tbody>
    <tr id="nextLineColor" class="hide">
      <td>
        <input type="text" class="form-control color" placeholder="Enter Color" id="color" value="" name="color[]">
      </td>
      <td class="addMins"> <i class="addColor fa fa-plus faIcon"></i> </td>
    </tr>
  </tbody>
</table>
<script>
jQuery(document).ready(function() {
  jQuery('#import_csv').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
      url: "<?php echo base_url(); ?>import-items",
      method: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function() {
        jQuery('#import_csv_btn').html('Importing...');
      },
      success: function(data) {
        jQuery('#import_csv')[0].reset();
        jQuery('#import_csv_btn').attr('disabled', false);
        jQuery('#import_csv_btn').html('Import Done');
        iziToast.success({
          timeout: 4000,
          title: 'Success',
          message: 'CSV File imported successfully.',
          position: 'bottomRight',
        })
      }
    })
  });
  var copied = '';
  jQuery(document).on('click', '.fa-clipboard', function() {
    var isError = false;
    jQuery(".dealPack").each(function() {
      var trIsEmpty = true;
      var tr = jQuery(this);
      tr.find("td:not(.serialNumberr)").each(function() {
        td = jQuery(this).find('input');
        if (isEmpty(td) === false)  {
          trIsEmpty = false;
          isError = false;
        }
      });

      if (trIsEmpty == true) {
        iziToast.destroy();
        iziToast.warning({title: 'Info',message: 'You cannot copy empty row'});
        isError = true;
      }

    });

    if (isError) {
      return false;
    }
    copied = jQuery(this).closest('tr').clone(true);

    iziToast.info({title: 'Info',message: 'Record copied successfully'});
  })

  function isEmpty(td) {
    if ($.trim(td.val()) == '') {
      return true;
    }
    return false;
  }


});
</script>

<script type="text/javascript">
$(document).on('click', '.selectUOM', function(event)
{
  var val     = $( this ).attr('id');
  if (jQuery(this).val() == 'addNewUOM')
  {
    jQuery('#saveUOM').attr('data-ref',val);
    jQuery('.remove-label').remove();
    jQuery('.form-group').removeClass('has-error');
    $('.selectUOM').not($(this)).each(function(){
      $(this).popover('hide');
    });
    $(this).popover({
      trigger: 'manual',
      placement: 'auto right',
      container: 'body',
      content: $('#add-new-uom').html()
    }).popover('show');
    return false;
  }
});

</script>
