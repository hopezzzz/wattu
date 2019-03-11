<div class="row">
  <div class="col-md-12">
    <section class="pages" id="profile-details">
      <div class="panel-body">
        <div class="col-md-6">
          <div class="row">
            <div class="panel-body bio-graph-info" style="min-height:unset">
              <h1>Bio Graph</h1>
              <div class="row">
                <div class="bio-row">
                  <p><span> Name </span>: <?php if(isset($userDetail->userName)) echo ucwords($userDetail->userName); ?> </p>
                </div>
                <div class="bio-row">
                  <p><span>Role </span>: <?php if($userDetail->userType == 3) echo ucwords('salesman'); elseif($userDetail->userType == 2) echo 'Manager'; else echo 'Superadmin' ?> </p>
                </div>
                <div class="bio-row">
                  <p><span>Email </span>: <?php if(isset($userDetail->userEmail)) echo $userDetail->userEmail; ?> </p>
                </div>
                <div class="bio-row">
                  <p><span>Mobile </span>: <?php if(isset($userDetail->mobileNo)) echo $userDetail->mobileNo; ?> </p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 ">
                  <div class="form-group">
                    <a data-href="#EditProfile" data-toggle1="collapse" class="btn btn-primary my-show">Edit Profile</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div id="profile">  </div>
        </div>
        <div class="clearfix"></div>

        <!-- edit-profile -->
        <div id="EditProfile" style="display:none;">
          <section class="panel">

            <?php echo form_open('updateUserDetails', array('name' => 'login', 'method' => 'post', 'class' => 'form-horizontal', 'id' => "update-user-form","autocomplete" => "off"));?>
            <input type="hidden" name="userRef" value="<?php if(isset($userDetail->userRef)) echo ($userDetail->userRef);?>">
            <div class="panel-body bio-graph-info">
              <h1> Profile Info</h1>
                <div class="form-group">
                  <label class="col-lg-2 control-label"> Name</label>
                  <div class="col-lg-6">
                    <input type="text" disabled class="form-control" name="userName" value="<?php if(isset($userDetail->userName)) echo ucwords($userDetail->userName);?>" id="f-name" placeholder=" ">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 control-label">Mobile</label>
                  <div class="col-lg-6">
                    <input type="text" class="form-control" name="mobileNo" value="<?php if(isset($userDetail->mobileNo)) echo ucwords($userDetail->mobileNo);?>" placeholder=" ">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 control-label">Old Password</label>
                  <div class="col-lg-6">
                    <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="Enter old password">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 control-label">New Password</label>
                  <div class="col-lg-6">
                    <input type="password" class="form-control" name="newPassword"  id="newPassword" placeholder=" New password ">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 control-label">Confirm Password</label>
                  <div class="col-lg-6">
                    <input type="password" class="form-control" name="confirmPassword" placeholder=" confirm new password">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger">Cancel</button>
                  </div>
                </div>
                </div>
                <div class="clearfix">

                </div>
              </form>
          </section>
        </div>
      </section>


<script>
$(".my-show").click(function(){

  var hrf=$(this).attr('data-href');

  $(hrf).toggle();

});
</script>
