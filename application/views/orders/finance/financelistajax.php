<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>SrNo #</th>
        <th>Order #</th>
        <th>Date & Time</th>
        <th>Order Status</th>
        <th>Customer</th>
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
                  <td><?php echo $key + 1; ?></td>
                    <td><?php echo $value->orderNo;?></td>
                    <td><?php echo date($value->addedOn,strtotime('d-m-Y H:i:s'));?></td>
                    <td><?php echo ucwords($value->orderStatus);?></td>
                    <td><?php echo ucwords($value->fullname);?></td>

                    <td><?php echo ucwords($value->cityName);?></td>
                    <!-- <td>12/01/2018</td> -->
                    <td><?php echo amountFormat($value->orderPrice);?></td>
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
