<div class="row">
  <div class="col-md-12">
    <section class="pages" id="add-transport">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
            <!-- <h2>Add Customer</h2> -->
          </div>
          <?php
          echo form_open('add-new-transport-charge', array('name' => 'add-transport-form', 'method' => 'post', 'class' => 'form-horizontal', 'id' => "add-transport-form","autocomplete" => "off"));
          ?>
          <input type="hidden" value="<?php if(isset($details)) echo $details->transportRef;?>" name="transportRef" id="transportRef" value="">
          <div class="col-md-12 col-xs-12">
            <div class="form-group clearfix col-md-7">
              <label class="col-sm-2 control-label">Item Name</label>
              <div class="col-sm-10">
                <input id="searchItemName" value="<?php if(isset($details->itemName)) echo $details->itemName;?>" type="text" class="form-control" placeholder="Search items by name">
                <input type="hidden" name="itemRefId" id="itemRefId" value="<?php if(isset($details)) echo $details->itemRefId;?>">
              </div>
            </div>
            <div class="form-group clearfix col-md-7">
              <label class="col-sm-2 control-label">Pricing Method</label>
              <div class="col-sm-10">
                <select class="form-control" name="pricingMode">
                  <option value="">Select Pricing Method</option>
                  <option <?php if (isset($details->pricingMode) && $details->pricingMode == "Fixed") echo "selected"; ?>>Fixed</option>
                  <option <?php if (isset($details->pricingMode) && $details->pricingMode == "Percentage") echo "selected"; ?>>Percentage</option>
                </select>
              </div>
            </div>
            <div class="clearfix"> </div>
            <div class="form clearfix">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Region</th>
                      <?php
                      $getDeliveryMethod =   getDeliveryMethods();
                      if (!empty($getDeliveryMethod) ){
                        foreach ($getDeliveryMethod as $key => $value)
                        { ?>
                          <th>
                            <?php echo $value->methodName;?>
                          </th>
                        <?php } } ?>

                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $getStates =   getRegionsByCountryId(49);
                      foreach ($getStates['result'] as $key => $value) {
                        ?>
                        <tr id="">
                          <td><?php echo $value->stateName;?></td>
                          <input type="hidden" name="region_id[<?php echo $value->stateId;?>][transportRef][]">
                          <input type="hidden" name="region_id[<?php echo $value->stateId;?>][]" value="<?php echo $value->stateId;?>">
                          <?php
                          $getDeliveryMethod =   getDeliveryMethods();
                          if (!empty($getDeliveryMethod) ){
                            foreach ($getDeliveryMethod as $key => $deliveryMethod)
                            { ?>
                                <input type="hidden" name="region_id[<?php echo $value->stateId;?>][deliveryMethodRef][]" value="<?php echo $deliveryMethod->deliveryMethodRef;?>">
                                <td><input class="price validNumber toFixed form-control" name="region_id[<?php echo $value->stateId;?>][price][]" value="<?php if(isset($details)) echo $details->price;?>" id="price" type="text"  placeholder="Enter Price"></td>
                            <?php } } ?>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                    </div>
                    <div class="form-group text-right">
                      <div class="col-md-12">
                        <button type="submit" class="btn btn-primary"><?php echo "Save"; ?></button>
                      </div>
                    </div>
                  </form>
                </div>

              </div>
            </div>



          </section>
        </div>
      </div>
      <script src="<?php echo $this->config->item('assets_path');?>js/common.js"></script>
      <script src="<?php echo $this->config->item('assets_path');?>js/form-validate.js"></script>
