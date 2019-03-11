
    <div class="row">
      <div class="col-md-12">
        <section class="pages" id="user_management">
          <div class="form-group add_button_box text-right clearfix">
            <div class="col-md-4 padding-left">
              <div class="input-group input-group-sm col-md-12 padding-left">
                <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search users">
                <div class="input-group-btn">
                  <button data-url="<?php echo site_url('users');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <a href="<?php echo base_url();?>add-user" class="btn btn-primary">Add User+</a>
            </div>
          </div>
      <?php $this->load->view('commonFiles/statusBoxs'); ?>

          <div  id="tableData" class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped">
                    <thead>
                          <tr>
                              <th>Sr No</th>
                              <th>User Name</th>
                              <th>Email ID</th>
                              <th>Mobile No</th>
                              <th>Permissions</th>
                              <th>User Type</th>
                              <th>Status</th>
                              <th>Actions</th>
                          </tr>
                    </thead>
                <tbody>
                  <?php if (!empty($records)) {
                      foreach ($records as $key => $value) { ?>
                          <tr id="users_<?php echo $value->userRef;?>">
                              <td><?php echo $key + 1; ?></td>
                              <td><a href="<?php  echo site_url('update-user/' . $value->userRef); ?>"><?php echo ucfirst($value->userName); ?></a></td>
                              <td><?php echo ($value->userEmail); ?></td>
                              <td><?php echo ($value->mobileNo); ?></td>
                              <td><a href="<?php  echo site_url('update-user/' . $value->userRef); ?>" class="btn btn-primary">Modify</a></td>
                              <td><?php if ($value->userType == 2) {
                                    echo '<kbd>Manager<kbd>';
                                } else {
                                    echo '<kbd>Salesman</kbd>';
                                } ?></td>

                                <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>

                                <td>
                                  <a href="<?php  echo site_url('view-user/' . $value->userRef); ?>" ><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;
                                  <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->userName);?>" data-type="users" data-ref="<?php echo $value->userRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                                  &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="deleteRecord" data-name="<?php echo ucfirst($value->userName);?>" data-type="users" data-ref="<?php echo $value->userRef;?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </td>
                          </tr>
                      <?php }
                  }
                  else
                  { ?>
                      <tr><td align="center" colspan="13">No User found.</td></tr>
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
