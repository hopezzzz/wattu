<?php  $loginSessionData = $this->session->userdata('clientData');
             // echo "<pre>";print_r($loginSessionData);die;
?>

<div class="row">
  <div class="col-md-12">
    <section class="pages" id="user_management">
      <?php $this->load->view('orders/searchBar'); ?>

      <div id="tableData" class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Sr No</th>
                <th>Contact Name</th>
                <th>Email ID</th>
                <th>Contact No</th>
                <th>Country</th>
                <th>State</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($records))
              {
                $status = array('Inactive','Active');
                  foreach ($records as $key => $value)
                  { ?>
                      <tr id="customers_<?php echo $value->customerRef;?>">
                          <td><?php echo $key + 1; ?></td>
                          <td>
                            <a target="_blank" href="<?php echo base_url()?>customer-follow-up-orders/<?php echo $value->customerRef ?>"  class="customerFollowup" data-ref="<?php echo ($value->customerRef); ?>" data-name="<?php echo ucfirst($value->fullName); ?>"><?php echo ucfirst($value->fullName); ?></a>
                            </td>
                          <td class="fullName"><?php echo $value->customerEmail; ?></td>
                          <td class="phoneNo1"><?php echo $value->phoneNo1;  ?></td>
                          <td class="countryName"><?php echo $value->countryName;  ?></td>
                          <td class="stateName"><?php echo $value->stateName;  ?></td>
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
      </div>
      <div id="orderSearchRecords" style="display:none"></div>
