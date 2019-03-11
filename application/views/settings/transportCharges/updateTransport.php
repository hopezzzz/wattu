

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
          <div class="col-md-12 col-xs-12">
            <div class="form-group clearfix col-md-7">
              <label class="col-sm-2 control-label">Item Name</label>
              <div class="col-sm-10">
                <input id="searchItemName" value="<?php if(isset($transport->transportDetails->itemName)) echo $transport->transportDetails->itemName;?>" type="text" class="form-control" placeholder="Search items by name , color , design">
                <input type="hidden" name="itemRefId" id="itemRefId" value="<?php if(isset($transport->transportDetails->itemRefId)) echo $transport->transportDetails->itemRefId;?>">
              </div>
            </div>
            <div class="form-group clearfix col-md-7">
              <label class="col-sm-2 control-label">Pricing Method</label>
              <div class="col-sm-10">
                <select class="form-control" name="pricingMode">
                  <option value="">Select Pricing Method</option>
                  <option <?php if ($transport->transportDetails->pricingMode == "Fixed") echo "selected"; ?>>Fixed</option>
                  <option <?php if ($transport->transportDetails->pricingMode == "Percentage") echo "selected"; ?>>Percentage</option>
                </select>
              </div>
            </div>
            <div class="clearfix"> </div>

            <div class="form">
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
                        $data = $transport->details;
                        ?>
                        <tr id="">
                          <td><?php echo $value->stateName;?></td>
                          <input type="hidden" name="region_id[<?php echo $value->stateId;?>][]" value="<?php if(isset($data->{$value->stateId}[0]['region_id'])) echo $data->{$value->stateId}[0]['region_id']; ?>">
                          <?php
                          $getDeliveryMethod =   getDeliveryMethods();
                          if (!empty($getDeliveryMethod) ){
                            $i = 0;
                            foreach ($getDeliveryMethod as $key => $deliveryMethod)
                            { ?>
                              <input type="hidden" name="region_id[<?php echo $value->stateId;?>][transportRef][]" value="<?php if(isset($data->{$value->stateId}[$i]['transportRef'])) echo $data->{$value->stateId}[$i]['transportRef']; ?>">
                              <input type="hidden" name="region_id[<?php echo $value->stateId;?>][deliveryMethodRef][]" value="<?php if(isset($data->{$value->stateId}[$i]['deliveryMethodRef'])) echo $data->{$value->stateId}[$i]['deliveryMethodRef']; ?>">
                              <td><input class="price toFixed form-control" name="region_id[<?php echo $value->stateId;?>][price][]" id="price" type="text"  placeholder="Enter Price" value="<?php if(isset($data->{$value->stateId}[$i]['price'])) echo $data->{$value->stateId}[$i]['price']; ?>">
                              </td>
                            <?php $i++;
                          } } ?>
                          </tr>
                        <?php }
                      ?>
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
