<?php echo form_open('ajaxAddUpdateItem', array('name' => 'login', 'method' => 'post', 'class' => 'add-item-form', 'id' => "add-item-form","autocomplete" => "off")); ?>

<div class="col-sm-6">
  <input type="hidden" name="productRef" id="productRef" value="">
  <div class="form-group clearfix">
    <label class="col-md-4 col-xs-12">Item Name</label>
    <div class="col-md-8 col-xs-12">
      <input type="text" class="form-control" placeholder="Enter Item Name" id="itemName" name="itemName">
    </div>
  </div>
  <div class="form-group clearfix">
    <label class="col-md-4 col-xs-12">Item Category</label>
    <div class="col-md-8 col-xs-12">
      <select class="form-control" id="catRef" name="catRef">
        <option value="">Select Category</option>
        <option value="">Add New Category</option>
        <?php if (!empty($categories)){
          foreach ($categories as $key => $value) {
            ?>
            <option value="<?php echo $value->catRef;?>">
              <?php echo $value->categoryName;?>
            </option>
          <?php } } else { ?>
            <option value="">No Category added yet</option>
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

          <option value="0">No Category added yet</option>

        </select>
      </div>
    </div>
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
      <label class="col-md-4 col-xs-12">Block Type</label>
      <div class="col-md-8 col-xs-12">
        <select id="blockType" name="blockType" class="form-control">
          <?php $blockTypes = blockTypes(); ?>
          <option value=""> Select Block Type </option>
          <option value="addNew">Add New</option>
          <?php if (!empty($blockTypes)){ ?>
            <?php foreach ($blockTypes as $key => $value): ?>
              <option value="<?php echo $value->id;?>"><?php echo $value->blockType ?></option>
            <?php endforeach; ?>
          <?php } else{ ?>
              <option value="">No Record Found</option>
           <?php }; ?>

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
              <option value="<?= $value->unitRef;?>">
                <?php echo $value->unitName; ?>
              </option>
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
          <input type="text" class="form-control" value="1" readonly placeholder="Qty" id="baseConvQty" name="baseConvQty"> <!--span> = Roll</span-->
        </div>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="baseConvLength" name="baseConvLength"> <!--span> = Meter</span-->
        </div>
      </div>
    </div>
    <div class="clearfix"></div>

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
              <option value="<?= $value->unitRef;?>">
                <?php echo $value->unitName; ?>
              </option>
            <?php }
          }
          ?>
        </select>
      </div>
    </div>
    <div class=" clearfix preventDefault" style="display:none">
      <label class="col-md-4 col-xs-12">Conversion Sales=Base</label>
      <div class="col-md-8 col-xs-12" style="padding:0">
        <div class="col-sm-6">
          <input type="text" class="form-control" value="1" readonly placeholder="Qty" id="saleConvQty" name="saleConvQty"> <!--span> = Roll</span-->
        </div>
        <div class="col-sm-6 form-group">
          <input type="text" class="form-control toFixed" id="saleConvLength" name="saleConvLength"> <!--span> = Meter</span-->
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
    <!-- <div class="form-group clearfix">
    <label class="col-md-4 col-xs-12">Transport Cost</label>
    <div class="col-md-8 col-xs-12">
    <input type="text" class="form-control validNumber" placeholder="Enter Transport Cost" id="transportCost" name="transportCost">
  </div>
</div> -->

<div class="form-group clearfix">
  <label class="col-md-4 col-xs-12"> Item Minimum Price</label>
  <div class="col-md-8 col-xs-12">
    <input type="text" data-refer="attrMinPrice" class="form-control cloneInput toFixed validNumber" placeholder="Enter Item minimum Cost" id="minimumCost" name="minimumCost">
  </div>
</div>
<div class="form-group clearfix">
  <label class="col-md-4 col-xs-12">Item Default Price</label>
  <div class="col-md-8 col-xs-12">
    <input type="text" data-refer="attrDefaultPrice" class="form-control cloneInput toFixed validNumber" placeholder="Enter Item Default Price" id="itemCost" name="itemCost">
  </div>
</div>

<div class="form-group BlockPercentage hide clearfix">
  <label class="col-md-4 col-xs-12">Block Percentage</label>
  <div class="col-md-8 col-xs-12">
    <input  type="text" data-refer="attrBlockPer" class="form-control cloneInput toFixed number" placeholder="Block Percentage" id="blockPercentage" name="blockPercentage" value="0">
  </div>
</div>
</div>
<div class="clearfix"></div>
<div class="col-md-12">
<div class="row">
  <h4>Variations</h4>
</div>
</div>
<div class="col-md-6">
<div class="table-responsive">
  <table class="add-sizes table table-striped table-hover">
    <thead>
      <th>#</th>
      <th>Length</th>
      <th>Width</th>
      <th>Height</th>
      <!-- <th>Color</th>
      <th>Design</th>-->
      <th></th>
    </thead>
    <tbody>
      <tr class="dealPack addTableRow">
        <td class="serialNumberr">1</td>
        <td>
          <input type="text" class="form-control length validNumber" placeholder="Length" id="length[0]" name="length[0]">
        </td>
        <td>
          <input type="text" class="form-control width validNumber" placeholder="Width" id="width[0]" name="width[0]">
        </td>
        <td>
          <input type="text" class="form-control height validNumber" placeholder="Height" id="height[0]" name="height[0]">
        </td>
        <td class="addMins"> <i class="addSizes faIcon fa fa-plus"></i> </td>
      </tr>
    </tbody>
  </table>

</div>
</div>
<div class="col-md-3">
<div class="table-responsive">
  <table class="add-color table table-striped table-hover">
    <thead>
      <th>Color</th>
      <th></th>
    </thead>
    <tbody>
      <tr class="colorPack addTableRow">


        <td>
          <input type="text" class="form-control color" placeholder="Enter Color" id="color" value="" name="color[0]">
        </td>

        <td class="addMins"> <i class="addColor faIcon fa fa-plus"></i> </td>
      </tr>
    </tbody>
  </table>

</div>
</div>
<div class="col-md-3">
<div class="table-responsive">
  <table class="add-design table table-striped table-hover">
    <thead>
      <th>Design</th>
      <th></th>
    </thead>
    <tbody>
      <tr class="designPack addTableRow">
        <td>
          <input type="text" class="form-control design" placeholder="Enter Design" id="design" value="" name="design[0]">
        </td>
        <td class="addMins"> <i class="addDesign faIcon fa fa-plus"></i> </td>


      </tr>
    </tbody>
  </table>

</div>
</div>
<div class="clearfix"></div>
<div class="col-md-12 text-left">
<div class="row">
  <h4>Attributes</h4>
  <div class="atttibutes">

  </div>
</div>
</div>

<div class="form-group clearfix">
<div class="col-sm-6 text-left">
  <!-- <a class="btn btn-primary addColorInput" id="addLayer" href="javascript:void(0)"><i class="fa fa-eye"></i> Get Attributes</a> -->
</div>
<div class="col-sm-6 text-right">
  <button type="submit" class="btn btn-primary">Save Item</button>
</div>
</div>
</form>
