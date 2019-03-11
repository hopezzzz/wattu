<script type="text/javascript">
$(document).ready(function ()
{
    var viewuser = '<?php echo $viewuser?>';
    if( viewuser == 'view-user' )
    {
        $("#register-user-form").find('input, textarea, select').prop("disabled", true).attr("disabled", true);
        $("#register-user-form").find('button[type="submit"]').remove();
        $(document).find('form').find('button[type="submit"]').remove();
        $(document).find('form').find('input, textarea, select').prop("disabled", true).attr("disabled", true);
        $(document).find('form').find('.saveBtns').remove();
        $(document).find('form').find('.savePaymentStatus').remove();
        $(document).find('form').find('#addLayer').remove();
        $(document).find('form').find('.removeLayer').remove();
        $(document).find('#updatePassword').remove();
    }


});
</script>

    <div class="row">
      <div class="col-md-12">
        <section class="pages" id="add-user">
          <div class="panel-body">
            <div class="row">
              <?php if (trim($userDetail->userRef) !=''){
                  echo form_open('updateUserAjax', array('name' => 'register-user', 'method' => 'post', 'class' => 'form-horizontal', 'id' => "register-user-form","autocomplete" => "off"));
             }else{
                echo form_open('register-user', array('name' => 'register-user', 'method' => 'post', 'class' => 'form-horizontal', 'id' => "register-user-form","autocomplete" => "off"));?>
              <?php }; ?>
              <input type="hidden"  name="userRef" value="<?php echo $userDetail->userRef; ?>">
              <div class="col-md-12 col-xs-12">
                <h2 class="text-center">User Information</h2>
                  <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                      <input type="text" value="<?php echo ucwords($userDetail->userName);?>"  name="userName" id="userName" class="form-control" placeholder="Enter Name">
                    </div>
                  </div>
                </div>
                  <div class="col-md-6">
                  <div class="form-group hide">
                    <label class="col-sm-2 control-label">Address</label>
                    <div class="col-sm-10">
                      <input type="text" value"<?php echo $userDetail->userAddress; ?>" name="userAddress" id="userAddress" class="form-control" placeholder="#33">
                    </div>
                  </div>
                </div>
                  <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Email Id</label>
                    <div class="col-sm-10">
                      <input type="email" name="userEmail" id="userEmail" class="form-control" placeholder="Enter Email" value="<?php echo $userDetail->userEmail; ?>">
                    </div>
                  </div>
                </div>
                <div class="clearfix"></div>
                  <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Phone No</label>
                    <div class="col-sm-10">
                      <input type="text" name="mobileNo" id="mobileNo" class="form-control validNumber" placeholder="Enter Phone Number"  value="<?php echo $userDetail->mobileNo; ?>">
                    </div>
                  </div>
                </div>
                  <div class="col-md-6">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Role</label>
                    <div class="col-sm-10">

                      <select class="form-control" name="userType" id="userType">
                        <option value="">Please Select user role</option>
                        <option <?php if ($userDetail->userType == 2) echo "selected";?> value="2">Manager</option>
                        <option <?php if ($userDetail->userType == 3) echo "selected";?> value="3">Salesman</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-xs-12">
                <h2 class="text-center">Alloted Permissions</h2>
                <?php foreach ($permissions as $key => $value): ?>
                  <div class="switch-btn">
                  <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label"><?=$value->permissionName;?></label>

                      <label class="switch">
                        <?php
                          if(isset($userDetail->userPermissions)){
                            $userPermissionData = explode(',',$userDetail->userPermissions);
                            $checked = (in_array($value->permissionId,$userPermissionData)) ? 'checked' : '';
                          }else{
                            $checked = '';
                          }
                         ?>
                        <input <?php echo $checked ?> type="checkbox"  name="userPermission[<?php echo urlencode(encode_url_ci(strtolower($value->permissionName)));?>]" data-toggle="switch" />
                       <span class="slider round"></span>
                      </label>

                  </div>
                  </div>
                  </div>
                <?php endforeach; ?>
    <?php
            /*
                  <div class="form-group">
                    <label class="col-sm-5 control-label">Production Pipeline Write</label>
                    <div class="col-sm-7">
                      <label class="switch">
                        <input type="checkbox" <?php  if(trim($userDetail->productionAccess) =='' || $userDetail->productionAccess == 0 ) echo ""; else echo "checked"; ?> name="productionAccess" data-toggle="switch" />
                       <span class="slider round"></span>
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-5 control-label">Finance Pipeline Write</label>
                    <div class="col-sm-7">
                      <label class="switch">
                       <input type="checkbox" <?php  if(trim($userDetail->financialAccess) =='' || $userDetail->financialAccess == 0 ) echo ""; else echo "checked"; ?> name="financialAccess" data-toggle="switch" />
                       <span class="slider round"></span>
                      </label>

                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-5 control-label">Dispatch Pipeline Write</label>
                    <div class="col-sm-7">
                      <label class="switch">
                       <input type="checkbox" <?php  if(trim($userDetail->dispatchAccess) =='' || $userDetail->dispatchAccess == 0 ) echo "";else echo "checked";?> name="dispatchAccess" data-toggle="switch" />
                       <span class="slider round"></span>
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-5 control-label">Re-Assigned Orders Write</label>
                    <div class="col-sm-7">
                      <label class="switch">
                       <input type="checkbox" <?php  if(trim($userDetail->reAssigned) =='' || $userDetail->reAssigned == 0 ) echo ""; else echo "checked"; ?> name="reAssigned" data-toggle="switch" />
                       <span class="slider round"></span>
                      </label>

                    </div>
                  </div>

                   <div class="form-group">
                    <label class="col-sm-5 control-label">Item Manager Write</label>
                    <div class="col-sm-7">
                      <label class="switch">
                        <input type="checkbox" <?php  if(trim($userDetail->itemManagement) =='' || $userDetail->itemManagement == 0 ) echo ""; else echo "checked"; ?> name="itemManagement" data-toggle="switch" />
                       <span class="slider round"></span>
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-5 control-label">Customer Manager Write</label>
                    <div class="col-sm-7">
                      <label class="switch">
                        <input type="checkbox" <?php  if(trim($userDetail->userManagement) =='' || $userDetail->userManagement == 0 ) echo ""; else echo "checked"; ?> name="userManagement" data-toggle="switch" />
                       <span class="slider round"></span>
                      </label>
                    </div>
                  </div>


          */
          ?>
            <div class="switch-btn">
              <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label">Login App</label>
                  <label class="switch">
                    <input type="checkbox" <?php  if(trim($userDetail->userActive) =='' || $userDetail->userActive == 0 ) echo ""; else echo "checked"; ?> name="userActive" data-toggle="switch" />
                   <span class="slider round"></span>
                  </label>

              </div>
              </div>
            </div>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-12">
                <div class="form-group  col-md-4 pull-right text-right">
                    <?php if (trim($userDetail->userRef) !=''){ ?>
                    <button data-ref="<?php echo $userDetail->userRef;?>" type="button" id="updatePassword" class="btn btn-primary">Change Password</button> &nbsp;
                  <?php } ?>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
              </form>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>

  <?php if (trim($userDetail->userRef) !=''){ ?>
    <div class="modal fade" id="change-password-modal">
      <div class="modal-dialog modal-md">

          <?php  echo form_open('update-user-password', array('name' => 'register-user', 'method' => 'post', 'class' => '', 'id' => "change-password","autocomplete" => "off")); ?>


          <input type="hidden" name="userRef" id="change-userRef-password" value="">

          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Update Password</h4>
            </div>
              <div class="modal-body">
                  <div class="input-area">
                        <div class="form-group">
                              <label for="old_password">Old Password</label>
                              <input type="password" autocomplete="off" placeholder="Enter old password" id="old_password" name="old_password" class="form-control">
                        </div>
                        <div class="form-group">
                              <label for="old_password">New Password</label>
                              <input type="password" autocomplete="off" placeholder="Enter new password" id="new_password" name="new_password" class="form-control">
                        </div>
                        <div class="form-group">
                              <label for="old_password">Confirm New Password</label>
                              <input type="password" autocomplete="off" placeholder="Enter confirm new password" id="confirm_password" name="confirm_password" class="form-control">
                        </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary cancel-btn pull-left" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary no-border-radius changePassword">Update Password</button>
              </div>
          </div>
          </form>
      </div>
  </div>
  <?php } ?>
