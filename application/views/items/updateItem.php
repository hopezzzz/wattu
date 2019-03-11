
<div class="modal-body">
    <?php echo form_open('ajaxAddUpdateItem', array('name' => 'login', 'method' => 'post', 'class' => 'add-item-form', 'id' => "add-item-form","autocomplete" => "off")); ?>

        <div class="col-sm-6">
            <input type="hidden" name="productRef" id="productRef" value="<?php echo $record['itemDetails']->productRef;?>">
            <div class="form-group clearfix">
                <label class="col-md-4 col-xs-12">Item Name</label>
                <div class="col-md-8 col-xs-12">
                  <input type="text" class="form-control" placeholder="Enter Item Name" id="itemName" name="itemName" value="<?php echo $record['itemDetails']->itemName;?>">
                </div>
            </div>
            <div class="form-group clearfix">
              <label class="col-md-4 col-xs-12">Item Category</label>
              <div class="col-md-8 col-xs-12">
                <select class="form-control" id="catRef" name="catRef">
                  <option value="">Select Category</option>
                  <option value="">Add New Category</option>
                  <?php $categories = getCategories(); if (!empty($categories)){
                    foreach ($categories as $key => $value) {
                  ?>
                  <option <?php if($record['itemDetails']->catRef == $value->catRef) echo "selected"; ?> value="<?php echo $value->catRef;?>"><?php echo $value->categoryName;?></option>
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
                  <?php $subCategories = getSubCategories($record['itemDetails']->catRef); if (!empty($subCategories)){
                    foreach ($subCategories as $key => $value) {
                    ?>
                    <option <?php if($record['itemDetails']->subCat == $value->catRef) echo "selected"; ?> value="<?php echo $value->catRef;?>"><?php echo $value->categoryName;?></option>
                  <?php } } else { ?>
                    <option value="1">No Category added yet</option>
                  <?php  } ?>
                </select>
              </div>
            </div>
            <div class="form-group clearfix">
                <label class="col-md-4 col-xs-12">UOM Type</label>
                <div class="col-md-8 col-xs-12">
                  <select class="form-control uomType" id="uomType" name="uomType">
                    <option value="">Select UOM Type</option>
                      <option <?php if($record['itemDetails']->uomType == 1) echo "selected"; ?> value="1">Fixed</option>
                      <option <?php if($record['itemDetails']->uomType == 2) echo "selected"; ?> value="2">Variable</option>
                  </select>
                </div>
            </div>
            <div class="form-group clearfix">
              <label class="col-md-4 col-xs-12">Block Type</label>
              <div class="col-md-8 col-xs-12">
                <select id="blockType" name="blockType" class="form-control">
                  <

                  <?php $blockTypes = blockTypes(); ?>
                  <option value=""> Select Block Type </option>
                  <option value="addNew">Add New</option>
                  <?php if (!empty($blockTypes)){ ?>
                    <?php foreach ($blockTypes as $key => $value): ?>
                      <option <?php if($record['itemDetails']->blockType == $value->id ) echo "selected"; ?> value="<?php echo $value->id;?>"><?php echo $value->blockType ?></option>
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
                        <option <?php if($record['itemDetails']->baseUOM == $value->unitRef) echo "selected"; ?> value="<?= $value->unitRef;?>"><?php echo $value->unitName; ?></option>
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
                        <input type="text" class="form-control" value="<?php echo $record['itemDetails']->baseConvLength;?>"   id="baseConvLength" name="baseConvLength"> <!--span> = Meter</span-->
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
                      <option <?php if($record['itemDetails']->saleUOM == $value->unitRef) echo "selected"; ?> value="<?= $value->unitRef;?>"><?php echo $value->unitName; ?></option>
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
                        <input type="text" class="form-control toFixed" value="<?php echo $record['itemDetails']->saleConvLength;?>"  id="saleConvLength" name="saleConvLength"> <!--span> = Meter</span-->
                    </div>
                </div>
            </div>


            <div class="form-group clearfix">
              <label class="col-md-4 col-xs-12">Production on Demand</label>
              <div class="col-md-8 col-xs-12">
                <select class="form-control" id="productionOnDemand" name="productionOnDemand">
                  <option value="">Select status</option>
                  <option <?php if($record['itemDetails']->productionOnDemand == 0) echo "selected"; ?>  value="0">No</option>
                  <option <?php if($record['itemDetails']->productionOnDemand == 1) echo "selected"; ?>  value="1">Yes</option>
                </select>
              </div>
            </div>

            <div class="form-group clearfix">
              <label class="col-md-4 col-xs-12"> Item Minimum Price</label>
              <div class="col-md-8 col-xs-12">
                <input type="text" class="form-control toFixed cloneInput validNumber" data-refer="attrMinPrice"  placeholder="Enter Item minimum Cost" id="minimumCost" value="<?php echo amountFormat($record['itemDetails']->minimumCost); ?>" name="minimumCost">
              </div>
            </div>
            <div class="form-group clearfix">
              <label class="col-md-4 col-xs-12">Item Default Price</label>
              <div class="col-md-8 col-xs-12">
                <input type="text" class="form-control toFixed cloneInput validNumber" data-refer="attrDefaultPrice"  placeholder="Enter Item Default Price" id="itemCost" value="<?php echo amountFormat($record['itemDetails']->itemCost); ?>" name="itemCost">
              </div>
            </div>
            <div class="form-group BlockPercentage <?php if($record['itemDetails']->productionOnDemand == 0) echo "hide"; ?> clearfix">
              <label class="col-md-4 col-xs-12">Block Percentage</label>
              <div class="col-md-8 col-xs-12">
                <input type="text" class="form-control cloneInput toFixed number" data-refer="attrBlockPer" placeholder="Block Percentage" id="blockPercentage" name="blockPercentage" value="<?php $blockPercentage = ( trim( $record['itemDetails']->blockPercentage ) !=''  ) ? $record['itemDetails']->blockPercentage : 0 ;  echo number_format($blockPercentage , 2) ; ?>" name="blockPercentage">
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
                    <?php if (!empty($record[0]['variantsSizes'])): ?>
                      <?php $total = count($record[0]['variantsSizes']); foreach ($record[0]['variantsSizes'] as $Skey => $variantsSizes): ?>
                        <tr class="dealPack addTableRow <?php if($Skey != 0) echo 'hasRemovedElement';?>">
                            <td class="serialNumberr"><?php echo $Skey+1 ?></td>
                            <td>
                                <input type="hidden" class="sasa variantsSizeId" name="variantsSizeId[<?php echo $Skey;?>]" value="<?php echo $variantsSizes->id; ?>">
                                <input type="text" class="form-control length validNumber" placeholder="Length" id="length[<?php echo $Skey;?>]" name="length[<?php echo $Skey;?>]" value="<?php echo $variantsSizes->length ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control width validNumber" placeholder="Width" id="width[<?php echo $Skey;?>]" name="width[<?php echo $Skey;?>]" value="<?php echo $variantsSizes->width ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control height validNumber" placeholder="Height" id="height[<?php echo $Skey;?>]" name="height[<?php echo $Skey;?>]" value="<?php echo $variantsSizes->height ?>">
                              </td>
                              <td class="addMins">
                                  <?php if ($total > 1): ?>
                                  <span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span>
                                <?php endif; ?>
                                 <?php if ($Skey == $total - 1): ?>
                                  <i class="addSizes faIcon fa fa-plus"></i> </td>
                              <?php endif; ?>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
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
                    <?php endif; ?>

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
                    <?php if (!empty($record[0]['variantsColor'])): ?>
                        <?php $totalC = count($record[0]['variantsColor']); foreach ($record[0]['variantsColor'] as $Ckey => $variantsColor): ?>
                          <tr class="colorPack addTableRow <?php if($Ckey != 0) echo 'hasRemovedElement';?>">
                              <td>
                                  <input type="hidden" class="variationColorId" name="" value="<?php echo $variantsColor->id; ?>">
                                  <input type="hidden" name="colorId[<?php echo $Ckey;?>]" value="<?php echo $variantsColor->id; ?>">
                                  <input type="text" class="form-control color" placeholder="Enter Color" id="color[<?php echo $Ckey;?>]" value="<?php echo $variantsColor->productColor;?>" name="color[<?php echo $Ckey;?>]">
                              </td>
                              <td class="addMins">
                                <?php if ($totalC > 1): ?>
                                  <span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span>
                                <?php endif; ?>
                                 <?php if ($Ckey == $totalC - 1): ?>
                                  <i class="addColor faIcon fa fa-plus"></i> </td>
                              <?php endif; ?> </td>
                          </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                          <tr class="colorPack addTableRow">
                              <td>

                                  <input type="text" class="form-control color" placeholder="Enter Color" id="color[0]" value="" name="color[0]">
                              </td>

                              <td class="addMins"> <i class="addColor faIcon fa fa-plus"></i> </td>
                          </tr>
                    <?php endif; ?>

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
                      <?php if (!empty($record[0]['variantsDesign'])): ?>
                          <?php $totalV = count($record[0]['variantsDesign']); foreach ($record[0]['variantsDesign'] as $Vkey => $variantsDesign): ?>
                            <tr class="designPack addTableRow <?php if($Vkey != 0) echo 'hasRemovedElement';?>">
                                <td>
                                    <input type="hidden" class="variationDesignId" name="" value="<?php echo $variantsDesign->id; ?>">
                                    <input type="hidden" name="designId[<?php echo $Vkey;?>]" value="<?php echo $variantsDesign->id; ?>">
                                    <input type="text" class="form-control design" placeholder="Enter Design" id="design[<?php echo $Vkey ?>]" value="<?php echo $variantsDesign->productDesign; ?>" name="design[<?php echo $Vkey ?>]">
                                </td>
                                <td class="addMins">
                                    <?php if ($totalV > 1): ?>
                                    <span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span>
                                  <?php endif; ?>
                                   <?php if ($Vkey == $totalV - 1): ?>
                                    <i class="addDesign faIcon fa fa-plus"></i> </td>
                                <?php endif; ?> </td>
                            </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                            <tr class="designPack addTableRow">
                                <td>
                                    <input type="text" class="form-control design" placeholder="Enter Design" id="design" value="" name="design[0]">
                                </td>
                                <td class="addMins"> <i class="addDesign faIcon fa fa-plus"></i> </td>
                            </tr>
                      <?php endif; ?>

                  </tbody>
              </table>

          </div>
        </div>
        <div class="clearfix">

        </div>
        <div class="col-md-12 text-left">
          <div class="row">
              <h4>Attributes</h4>
              <div class="atttibutes">
                <table class="table">
                  <table class="table">
                    <thead class="attrTable">
                      <tr><th>#</th>
                      <th>Diamentions</th>
                      <th>Color</th>
                      <th>Design</th>
                      <th>Minimum Price</th>
                      <th>Default Price</th>
                      <th <?php if($record['itemDetails']->productionOnDemand == 0) echo "style='display:none'"; ?> >Block Percentage</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                    <tbody>
                      <?php if (!empty($record[0]['getVariants'])): ?>
                          <?php foreach ($record[0]['getVariants'] as $gKey => $getVariants): ?>
                            <tr id="<?php echo $getVariants->length.'X'.$getVariants->width.'X'.$getVariants->height.'X'.$getVariants->color.'X'.$getVariants->design;?>" class="updateTableRows">
                                <td><i class="fa fa-circle"></i></td>
                                <input type="hidden" name="attrLength[<?php echo $gKey;?>]" value="<?php echo $getVariants->length;?>">
                                <input type="hidden" name="attrWidth[<?php echo $gKey;?>]" value="<?php echo $getVariants->width;?>">
                                <input type="hidden" name="attrHeight[<?php echo $gKey;?>]" value="<?php echo $getVariants->height;?>">
                                <input type="hidden" name="attrColor[<?php echo $gKey;?>]" value="<?php echo $getVariants->color;?>">
                                <input type="hidden" name="attrDesign[<?php echo $gKey;?>]" value="<?php echo $getVariants->design;?>">
                                <td><?php echo $getVariants->length;?>X<?php echo $getVariants->width;?>X<?php echo $getVariants->height;?></td>
                                <td><?php echo $getVariants->color ?></td>
                                <td><?php echo $getVariants->design ?></td>
                                <?php if($record['itemDetails']->productionOnDemand == 0){ ?>
                                <td><input type="text"  class="form-control toFixed attrMinPrice" name="attrMinPrice[]" value="<?php echo amountFormat($getVariants->minPrice) ?>"></td>
    													 <td><input type="text"  class="form-control toFixed attrDefaultPrice" name="attrDefaultPrice[]" value="<?php echo amountFormat($getVariants->price) ?>"><input type="hidden"  class="form-control attrBlockPer" name="attrBlockPer[]" value="<?php echo $getVariants->blockPercentage ?>" ></td>
                             <?php } else {?>
                               <td><input type="text"  class="form-control toFixed attrMinPrice" name="attrMinPrice[]" value="<?php echo amountFormat($getVariants->minPrice) ?>"></td>
                              <td><input type="text"  class="form-control toFixed attrDefaultPrice" name="attrDefaultPrice[]" value="<?php echo amountFormat($getVariants->price) ?>"></td>
                              <td><input type="text"  class="form-control toFixed attrBlockPer" name="attrBlockPer[]" value="<?php echo number_format($getVariants->blockPercentage , 2)  ?>" ></td>
                             <?php }?>
                                <td>
                                    <label class="switch">
                                        <input attrId="<?php echo $getVariants->length.'X'.$getVariants->width.'X'.$getVariants->height.'X'.$getVariants->color.'X'.$getVariants->design;?>" class="check-attr" type="checkbox" <?php if($getVariants->status == 1) { ?>  checked="" <?php } ?> name="attributeStatus[<?php echo $gKey ?>]" data-toggle="switch"> <span class="slider round"></span> </label>
                                </td>
                            </tr>
                          <?php endforeach; ?>
                      <?php endif; ?>

                    </tbody>
                </table>
            </div>
          </div>
        </div>
        <div class="form-group clearfix">
            <div class="col-sm-6 text-left">
              <!-- <a class="btn btn-primary addColorInput" id="addLayer" href="javascript:void(0)"><i class="fa fa-plus"></i> Add Row</a> -->
            </div>
            <div class="col-sm-6 text-right">
                <input type="hidden" name="variationRefIds" class="variationRefIds" value="">
                <input type="hidden" name="variationColorIds" class="variationColorIds" value="">
                <input type="hidden" name="variationDesignIds" class="variationDesignIds" value="">
                <button type="submit" class="btn btn-primary">Save Item</button>
            </div>
        </div>
        </form>
</div>
