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
                <td><?php echo $key + $start; ?></td>
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
          <tr><td align="center" colspan="13">No users found.</td></tr>
<?php   } ?>
    </tbody>
    </table>
    <div class="">
      <?php echo $paginationLinks; ?>
    </div>
  </div>
