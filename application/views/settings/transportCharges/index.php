<?php  $loginSessionData = $this->session->userdata('clientData');
            // echo "<pre>";print_r($loginSessionData)
?>

<div class="row">
  <div class="col-md-12">
    <section class="pages" id="user_management">
      <div class="row form-group add_button_box text-right clearfix">
        <div class="col-md-4">
          <div class="input-group input-group-sm col-md-12 padding-left">
            <input type="text" id="searchKey" name="searchKey" class="form-control pull-right" placeholder="Search by transport charges">
            <div class="input-group-btn">
              <button data-url="<?php echo site_url('transport-charges');?>" type="button" id="tableSearchBtn" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <form method="post" method="post" id="importCsv" action="<?php echo base_url() ?>import-transport" enctype="multipart/form-data">
          <input type="file" name="csv_file" id="csv_file" required accept=".csv" style="clear: both;display: inline;">
         <?php if($loginSessionData['ItemManagerWrite'] == 1)
          echo '<input type="submit" id="import_xls_btn" name="submit" value="UPLOAD" class="btn btn-primary">';
          else echo '<input type="button" value="UPLOAD" class="btn btn-primary noAccess">';
          ?>
          <a href="<?php echo site_url('exportExcel');?>" class="btn btn-success">Export To CSV</a>
          <a href="<?php echo base_url().'add-new-transport-charge';?>" class="btn btn-primary">Add Transport Charge+</a>
        </div>
      </div>

      
      <div class="clearfix"></div>

      <div class="col-md-4 pull-right text-right">
        <button type="button" class="btn btn-primary backBtn" style="display:none"><i class="fa fa-arrow-left"></i> Back</button>
      </div>
      <div class="clearfix"></div>
      <br>
      <div class="detailDrow">

      </div>
      <div class="defaultRow">
      <div id="tableData" class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Sr No</th>
                <th>Item Name</th>
                <!-- <th>Region</th> -->

                <th>Pricing Mode</th>
                <!-- <th>Price / Value</th> -->
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($records)) {
                $status = array('Inactive','Active');
                  foreach ($records as $key => $value) { ?>
                      <tr id="transportCharges_<?php echo $value->transportRef;?>">
                          <td><?php echo $key + 1; ?></td>
                          <td><a href="javascript:void(0)"  data-ref="<?php echo  ($value->itemRefId);?>" class="updateTransport" data-url="<?php echo base_url() .'update-transport-charges/'.$value->itemRefId ?>" data-name="<?php echo $value->itemName;?>"><?php echo ucfirst($value->itemName); ?></a>
                          <!-- <td class="itemName"><?php echo $value->region; ?></td>
                          <td class="region"><?php echo $value->deliveryMethod;  ?></td>-->
                          <td class="transportMethod"><?php echo $value->pricingMode;  ?></td>
                          <!-- <td class="price"><?php echo amountFormat($value->price);  ?></td> -->
                          <td class="statusTd"><?php echo '<span class="label '.ActiveClass($value->status).'">'.status($value->status).'</span>';?></td>
                          <td>
                            <a href="javascript:void(0)"  data-ref="<?php echo  ($value->itemRefId);?>" class="updateTransport" data-url="<?php echo base_url() .'update-transport-charges/'.$value->itemRefId ?>" data-name="<?php echo $value->itemName;?>"> <i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                            <a href="javascript:void(0);" data-status="<?php echo $value->status;?>" class="updateStatus" data-name="<?php echo ucfirst($value->itemName);?>" data-type="transportCharges" data-ref="<?php echo ($value->transportRef);?>">Make <?php if( $value->status == 0 ){?>Active<?php } else{?>Inactive<?php } ?> </a>
                          </td>
                      </tr>
                  <?php }
              }
              else
              { ?>
                  <tr><td align="center" colspan="13">No transport charges found.</td></tr>
                <?php   } ?>
            </tbody>
          </table>
          <div class="">
            <?php echo $paginationLinks; ?>
          </div>
        </div>
      </div>
    </div>
    <script>
    $(document).ready(function(){
     $('#import_xls').on('submit', function(event){
      event.preventDefault();
      $.ajax({
       url:"<?php echo base_url(); ?>import-transport",
       method:"POST",
       data:new FormData(this),
       contentType:false,
       cache:false,
       processData:false,
       beforeSend:function(){
        $('#import_xls_btn').html('Importing...');
       },
       success:function(data)
       {
        $('#import_xls')[0].reset();
        $('#import_xls_btn').attr('disabled', false);
        $('#import_xls_btn').html('Import Done');
        iziToast.success({
          timeout: 4000,
          title: 'Success',
          message: 'CSV File imported successfully.',
          position: 'bottomRight',
        })
       }
      })
     });

    });
    </script>
