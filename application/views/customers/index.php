<?php  $loginSessionData = $this->session->userdata('clientData');
            // echo "<pre>";print_r($loginSessionData)
?>

<div class="row">
  <div class="col-md-12">
    <section class="pages" id="user_management">
      <div class="form-group add_button_box text-right clearfix">
        <div class="col-md-3" style="padding:0">
          <div class="input-group input-group-sm col-md-12" style="padding:0">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search Customers">
            <div class="input-group-btn" style="right: 29px;">
              <button data-url="<?php echo site_url('customers-list');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <!-- <div class="form-group col-md-2">
          <label for="" class="pull-left" style="margin: 5px 0px"> <strong>Status Toggle</strong> </label>
          <label class="switch pull-rifht">
            <input type="checkbox" checked id="statusBox" data-toggle="switch">
            <span class="slider round"></span>
          </label>
        </div>-->
        <div class="col-md-9">
              <form method="post" method="post" id="importCsv" action="<?php echo base_url() ?>import-customers" enctype="multipart/form-data">
                  <input type="file" name="csv_file" id="csv_file" required accept=".csv" style="clear: both;display: inline;">
                 <?php if($loginSessionData['CustomerManagerWrite'] == 1)
                  echo '<input type="submit" id="importCsv_btn" name="submit" value="UPLOAD" class="btn btn-primary">';
                  else echo '<input type="button" value="UPLOAD" class="btn btn-primary noAccess">';
                  ?>
            </form>
            <a href="<?php echo site_url('export-customers');?>" class="btn btn-success">Export Customers</a>
           <?php if ($loginSessionData['CustomerManagerWrite'] == 1)
            echo '<a href="'.base_url().'add-customer " class="btn btn-primary">Add Customer+</a>';
            else
              echo '<a href="javascript:void(0)" class="btn btn-primary noAccess">Add Customer+</a>';
            ?>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="col-md-5 text-left no-padding">
          <label class="containerCheckbox">All
            <input type="checkbox" class="filterCheckBox" value="" checked="checked">
            <span class="checkmark"></span>
          </label>
          <label class="containerCheckbox">Active
            <input type="checkbox" class="filterCheckBox" value="1">
            <span class="checkmark"></span>
          </label>
          <label class="containerCheckbox">Inactive
            <input type="checkbox" class="filterCheckBox" value="0">
            <span class="checkmark"></span>
          </label>
        </div>


      </div>

      <div id="tableData" class="panel-body">
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
                          <td class="srNum"><?php echo $key + 1; ?></td>
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
      </div>
