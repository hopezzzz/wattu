<?php  $loginSessionData = $this->session->userdata('clientData');
            // echo "<pre>";print_r($loginSessionData)
?>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Sr No</th>
        <th>Business Name</th>

        <th>Contact Name</th>
        <th>Email ID</th>
        <th>Contact No</th>
        <th>Town</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($records)) {
        $status = array('Inactive','Active');
          foreach ($records as $key => $value) { ?>
              <tr id="customers_<?php echo $value->customerRef;?>">
                  <td><?php echo $key + $start + 1; ?></td>
                  <td class="businessName"><?php echo $value->businessName; ?></td>

                  <td>
                   <?php if ($loginSessionData['CustomerManagerWrite'] == 1){ ?>
                    <a href="<?php echo base_url() .'update-customer/'.$value->customerRef; ?>"  class="updateCustomer" data-ref="<?php echo ($value->customerRef); ?>" data-name="<?php echo ucfirst($value->fullName); ?>"><?php echo ucfirst($value->fullName); ?></a>
                    <?php } else{ ?>
                     <a href="javascript:void(0)"  class="noAccess" data-ref="<?php echo ($value->customerRef); ?>" data-name="<?php echo ucfirst($value->fullName); ?>"><?php echo ucfirst($value->fullName); ?></a>
                    <?php } ?></td>
                  <td class="fullName"><?php echo $value->customerEmail; ?></td>
                  <td class="customerEmail"><?php echo $value->phoneNo1;  ?></td>
                  <td class="cityName"><?php echo $value->cityName;  ?></td>
                  <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>
                    <td>
                      <?php if ($loginSessionData['CustomerManagerWrite'] == 1){ ?>
                      <a href="<?php echo base_url() .'update-customer/'.$value->customerRef; ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;&nbsp;
                      <a href="<?php echo base_url() .'view-customer/'.$value->customerRef; ?>"><i class="fa fa-eye"></i></a> &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo trim(ucfirst($value->fullName));?>" data-type="customers" data-ref="<?php echo $value->customerRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>

                      <?php } else { ?>
                        <a href="javascript:void(0)" class="noAccess"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;&nbsp;
                      <a href="<?php echo base_url() .'view-customer/'.$value->customerRef; ?>"><i class="fa fa-eye"></i></a> &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="noAccess" data-name="<?php echo trim(ucfirst($value->fullName));?>" data-type="customers" data-ref="<?php echo $value->customerRef;?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                      <?php } ?>

                    </td>
              </tr>
          <?php }
      }
      else
      { ?>
          <tr><td align="center" colspan="13">No customers found.</td></tr>
        <?php   } ?>
    </tbody>
  </table>
  <div class="">
    <?php echo $paginationLinks; ?>
  </div>
</div>
