<div class="col-md-12">
  <div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-6">
      <div class="form-group col-md-4">
          <input type="text" class="form-control customerFollupField EpstartDate datepicker followupDate" data-ref="startDate" placeholder="Start Date">
      </div>
      <div class="form-group col-md-4">
          <input type="text" class="form-control customerFollupField EpendDate datepicker followupDate" data-ref="endDate" placeholder="End Date">
      </div>
      <div class="form-group col-md-2">
          <a href="javascript:void(0)" data-href="<?php echo base_url().'export-customer-follow-up';?>" class="customerFollupField btn btn-primary downloadFollowup " style="display:none">Download Customer Followup</a>
      </div>
    </div>
    <div class="col-md-2 pull-right text-right">
      <a href="javascript:void(0)" class="hideRecords btn btn-success ">Hide Records</a>
    </div>
  </div>
</div>
<br>
<div class="panel-body">
  <div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>SrNo #</th>
        <th>Order #</th>
        <th>Date & Time</th>
        <th>Order Pipline</th>
        <th>Order Status</th>
        <th>Customer</th>
        <!-- <th>City</th> -->
        <!-- <th>Due Date</th> -->
        <th>Total Amount</th>
        <!-- <th>No. of Items</th> -->
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($records)) {
          foreach ($records as $key => $value) { ?>
              <tr id="order_<?php echo $value->orderRef;?>">
                  <td><?php echo $start+ $key + 1; ?></td>
                    <td><?php echo $value->orderNo;?></td>
                    <td><?php echo date($value->addedOn,strtotime('d-m-Y H:i:s'));?></td>
                    <td><?php if ($value->orderPipline == 2)
                                    echo "Approval";
                                  elseif($value->orderPipline == 3)
                                    echo "Production";
                                  elseif ($value->orderPipline == 4)
                                    echo "Dispatch";
                    ?></td>
                    <td><?php echo orderStatus($value->orderStatus);?></td>
                    <td><?php echo ucwords($value->fullname);?></td>
                    <!-- <td><?php echo ucwords($value->cityName);?></td> -->
                    <!-- <td>12/01/2018</td> -->
                    <td>KES <?php echo amountFormat($value->orderPrice);?></td>
                    <!-- <td><?php echo ucwords($value->orderQty);?></td> -->
                    <td><a href="<?php echo site_url('order-details').'/'.$value->orderRef; ?>" class="btn btn-primary">View</a></td>
                  </tr>

          <?php }
      }
      else
      { ?>
          <tr><td align="center" colspan="13">No Order Found.</td></tr>
        <?php   } ?>
    </tbody>
    </table>
    <div class="">
      <?php echo $paginationLinks; ?>
    </div>
</div>
</div>
<script type="text/javascript">
$( function() {
  $( ".datepicker" ).datepicker(
    {
      dateFormat: 'dd-mm-yy',
    }
  );
} );

jQuery(document).on('click','.downloadFollowup',function(event){
  event.preventDefault();
  var _href = jQuery(this).attr('data-href');
  var isFalse = false;
  jQuery('.followupDate').each(function(event) {
    if (jQuery(this).val() == '') {
        jQuery(this).parent().addClass('has-error');
        jQuery(this).attr('placeholder','This field is required');
        isFalse = true;
    }else{
         jQuery(this).parent().removeClass('has-error');
    }
    var startDate = jQuery('.EpstartDate').val();
    var endDate = jQuery('.EpendDate').val();
    if (!isFalse) {
      var downloadUrl = _href+'/'+startDate+'/'+endDate;
      var link = document.createElement('a');
      link.href = downloadUrl;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }

  })


})
</script>
