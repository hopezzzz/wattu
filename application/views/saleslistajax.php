<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>SrNo #</th>
        <th>Order #</th>
        <th>Date & Time</th>
        <th>Order Status</th>
        <th>Business Name</th>
        <th>City</th>
        <!-- <th>Due Date</th> -->
        <th>Total Amount</th>
        <th>No. of Items</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($records)) {
          foreach ($records as $key => $value) { ?>
              <tr id="order_<?php echo $value->orderRef;?>">
                  <td><?php echo $start+ $key + 1; ?></td>
                  <td><a href="<?php echo base_url().'order-details/'.$value->orderRef;?>">
                    <?php echo $value->orderNo;?></a> </td>
                    <td><?php echo date('d-m-Y H:i:s', strtotime($value->addedOn)) ;?></td>
                    <td><?php echo orderStatus($value->orderStatus);?></td>
                      <td><?php echo ucwords($value->businessName);?></td>

                    <td><?php echo ucwords($value->cityName);?></td>
                    <!-- <td>12/01/2018</td> -->
                    <td>KES <?php $totalPrice = ($value->orderPrice + $value->transportCharge) - ($value->orderDiscount) ; echo amountFormat($totalPrice); ?></td>
                    <td><?php echo ucwords($value->orderQty);?></td>
                    <td><a href="#" class="btn btn-primary">View</a></td>
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
