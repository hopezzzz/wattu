<script type="text/javascript">
$(document).ready(function ()
{
    var viewuser = '<?php echo $viewuser?>';
    if( viewuser == 'viewcustomer' )
    {
        $(document).find('form').find('button[type="submit"]').remove();
        $(document).find('form').find('input, textarea, select').prop("disabled", true).attr("disabled", true);
        $(document).find('form').find('.saveBtns').remove();
        $(document).find('form').find('.savePaymentStatus').remove();
        $(document).find('form').find('#addLayer').remove();
        $(document).find('form').find('.removeLayer').remove();
    }


});
</script>
<div class="row">
  <div class="col-md-12">
    <section class="pages" id="add-user">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-12">
          <!-- <h2>Add Customer</h2> -->
        </div>
            <?php
            echo form_open('AjaxAddUpdateCustomer', array('name' => 'add-customer-form', 'method' => 'post', 'class' => 'form-horizontal', 'id' => "add-customer-form","autocomplete" => "off"));

             ?>
             <input type="hidden" value="<?php if($userDetail) echo $userDetail->customerRef;?>" name="customerRef" id="customerRef" value="">
          <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <label class="col-sm-3 control-label">Contact name</label>
                <div class="col-sm-9">
                  <input value="<?php if($userDetail) echo $userDetail->contactName;?>" name="contactName" id="contactName" type="text" class="form-control" placeholder="Enter Contact Name">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Email Id</label>
                <div class="col-sm-9">
                  <input value="<?php if($userDetail) echo $userDetail->customerEmail;?>" name="customerEmail" id="customerEmail" type="email" class="form-control" placeholder="Enter Email">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Business Name</label>
                <div class="col-sm-9">
                  <input value="<?php if($userDetail) echo $userDetail->businessName;?>" name="businessName" id="businessName" type="text" class="form-control" placeholder="Business name">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Phone No. 1.</label>
                <div class="col-sm-9">
                  <input value="<?php if($userDetail) echo $userDetail->phoneNo1;?>" name="phoneNo1" id="phoneNo1" type="text" class="form-control number" placeholder="Contact No">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Phone No. 2</label>
                <div class="col-sm-9">
                  <input value="<?php if($userDetail->phoneNo2) echo $userDetail->phoneNo2;?>" name="phoneNo2" id="phoneNo2" type="text" class="form-control" placeholder="Enter phone no">
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xs-12">
              <div class="form-group hide">
                <label class="col-sm-3 control-label">Country</label>
                <div class="col-sm-9">
                  <select class="form-control" <?php if(trim($userDetail->countryId) =='') {?> id="countriesSel" <?php } ?> value="<?php if($userDetail) echo $userDetail->countryId;?>" name="countryId">
                    <option value="">Select Country</option>
                    <?php if (!empty($countries['result'])){
                        foreach ($countries['result'] as $key => $value) {
                        ?>
                      <option <?php if($value->countryId == $userDetail->countryId) echo "selected";?> selected value="<?php echo $value->countryId;?>"><?php echo $value->countryName;?></option>
                    <?php } }; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">

                <label class="col-sm-3 control-label">Region</label>
                <div class="col-sm-9">
                  <select class="form-control" id="stateSel" value="<?php if($userDetail) echo $userDetail->stateId;?>" name="stateId">
                    <?php if ($userDetail->countryId !=''){
                          ?>
                            <option value="">Select Region</option>
                          <?php
                          $getStates =   getRegionsByCountryId($userDetail->countryId);

                          foreach ($getStates['result'] as $key => $value) { ?>
                            <option <?php if($value->stateId == $userDetail->stateId) echo "selected";?> value="<?php echo $value->stateId;?>"><?php echo $value->stateName;?></option>
                          <?php } } else { ?>
                    <option value="">Select Region</option>
                  <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Town</label>
                <div class="col-sm-9">
                  <select class="form-control" id="city" value="<?php if($userDetail) echo $userDetail->cityId;?>" name="cityId">
                    <?php if ($userDetail->countryId !=''){
                          ?>
                            <option value="">Select Town</option>
                          <?php
                          $getCity =   getCitiesByRegionId($userDetail->stateId);
                          foreach ($getCity['result'] as $key => $value) { ?>
                            <option <?php if($value->CityId == $userDetail->cityId) echo "selected";?> value="<?php echo $value->CityId;?>"><?php echo $value->cityName;?></option>
                          <?php } } else { ?>
                    <option value="">Select Town</option>
                  <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Address Line</label>
                <div class="col-sm-9">
                  <input type="text" name="addressLine" id="addressLine" class="form-control" value="<?php if($userDetail->addressLine) echo $userDetail->addressLine;?>" placeholder="Address Line">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Delivery Method</label>
                <div class="col-sm-9">
                  <select class="form-control" value="<?php if($userDetail) echo $userDetail->deliveryMethodRef;?>" name="deliveryMethodRef">
                    <?php
                      $getDeliveryMethod =   getDeliveryMethods();
                     if (!empty($getDeliveryMethod) ){ ?>
                         <option value="">Select Delivery Method</option>
                         <?php
                      foreach ($getDeliveryMethod as $key => $value)
                      { ?>
                        <option <?php if($value->deliveryMethodRef == $userDetail->deliveryMethodRef) echo "selected";?> value="<?php echo $value->deliveryMethodRef;?>"><?php echo $value->methodName;?></option>
                    <?php }
                      }
                      else { ?>
                        <option value="">No Delivery Method available</option>
                        <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group text-right">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary"><?php echo $btnValue; ?></button>
                </div>
              </div>
          </div>
        </form>
        </div>

      </div>
    </section>
  </div>
</div>
