<div class="row">
  <div class="col-md-12">
    <section class="sale_panel">
      <div class="search_order">
        <div class="col-md-12">
          <h4>Search</h4>
        </div>
        <div class="row">
          <div class="col-md-2">
            <label class="col-md-5">Order ID</label>
            <div class="col-md-7"><input class="form-control"></div>
          </div>
          <div class="col-md-3">
            <label class="col-md-5">Order Status</label>
            <div class="col-md-7">
              <select class="form-control">
                <option>Re-Assign</option>
                <option>Approved</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <label class="col-md-5">Order Date From</label>
            <div class="col-md-7">
              <input class="form-control">
            </div>
          </div>
          <div class="col-md-3">
            <label class="col-md-6">Order Date Through</label>
            <div class="col-md-6">
              <input class="form-control">
            </div>
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-primary">Search</button>
          </div>
        </div>
      </div>
      <div class="panel-body">

        <?php if (!empty($records)) {
          $trRow6         = $trRow12 = $trRow18 = $trRow24 = $elsetrRow = $SevenDays ='';
          $currentDate    = date('Y-m-d H:i:s');
          $currentDated   = date('Y-m-d H:i:s',strtotime('- 6 hours'));
          $currentDated12 = date('Y-m-d H:i:s',strtotime('- 12 hours'));
          $currentDated18 = date('Y-m-d H:i:s',strtotime('- 18 hours'));
          $currentDated24 = date('Y-m-d H:i:s',strtotime('- 24 hours'));
          $past7Days      = date('Y-m-d H:i:s',strtotime('- 7 days'));
          foreach ($records as $key => $value) {
            // echo "<pre>";print_r($value);
            // echo "$currentDated18 >=  $value->addedOn  && $currentDated24 <= $value->addedOn) <br>";;
            // echo "$past7Days >=  $value->addedOn  && $past7Days <= $value->addedOn)";die;
            // echo "$currentDated >=  $value->addedOn  && $currentDatedd <= $value->addedOn)";
            if ($currentDate >=  $value->addedOn  && $currentDated <= $value->addedOn ) {
              $trRow6 .= '<tr>
              <td>'.$value->orderNo.'</td>
              <td>'.$value->addedOn.'</td>';
              if ($value->orderStatus == 'MAN') {
                  $trRow6 .=  '<td>Manager Approval Needed</td>';
              }else {
                $trRow6 .=  '<td>'.ucwords($value->orderStatus).'</td>';
              }
              $trRow6 .= '<td>'.ucwords($value->fullname).'</td>
              <td>'.amountFormat($value->orderPrice).'</td>
              <td><a href="" class="btn btn-primary">Approve</a> <a href="" class="btn btn-primary">Re-Assign</a> <a href="" class="btn btn-primary">Delete</a> <a href="" class="btn btn-primary">Comment</a></td>
              </tr>';
            }elseif ( $currentDated >=  $value->addedOn  && $currentDated12 <= $value->addedOn) {
              $trRow12 .= '<tr>
              <td>'.$value->orderNo.'</td>
              <td>'.$value->addedOn.'</td>';
              if ($value->orderStatus == 'MAN') {
                  $trRow12 .=  '<td>Manager Approval Needed</td>';
              }else {
                $trRow12 .=  '<td>'.ucwords($value->orderStatus).'</td>';
              }
              $trRow12 .= '<td>'.ucwords($value->fullname).'</td>
              <td>'.amountFormat($value->orderPrice).'</td>
              <td><a href="" class="btn btn-primary">Approve</a> <a href="" class="btn btn-primary">Re-Assign</a> <a href="" class="btn btn-primary">Delete</a> <a href="" class="btn btn-primary">Comment</a></td>
              </tr>';
            }
            elseif ( $currentDated12 >=  $value->addedOn  && $currentDated18 <= $value->addedOn) {
              $trRow18 .= '<tr>
              <td>'.$value->orderNo.'</td>
              <td>'.$value->addedOn.'</td>';
              if ($value->orderStatus == 'MAN') {
                  $trRow18 .=  '<td>Manager Approval Needed</td>';
              }else {
                $trRow18 .=  '<td>'.ucwords($value->orderStatus).'</td>';
              }
              $trRow18 .= '<td>'.ucwords($value->fullname).'</td>
              <td>'.amountFormat($value->orderPrice).'</td>
              <td><a href="" class="btn btn-primary">Approve</a> <a href="" class="btn btn-primary">Re-Assign</a> <a href="" class="btn btn-primary">Delete</a> <a href="" class="btn btn-primary">Comment</a></td>
              </tr>';
            }
            elseif ( $currentDated18 >=  $value->addedOn  && $currentDated24 <= $value->addedOn) {
              $trRow24 .= '<tr>
              <td>'.$value->orderNo.'</td>
              <td>'.$value->addedOn.'</td>';
              if ($value->orderStatus == 'MAN') {
                  $trRow24 .=  '<td>Manager Approval Needed</td>';
              }else {
                $trRow24 .=  '<td>'.ucwords($value->orderStatus).'</td>';
              }
              $trRow24 .= '<td>'.ucwords($value->fullname).'</td>
              <td>'.amountFormat($value->orderPrice).'</td>
              <td><a href="" class="btn btn-primary">Approve</a> <a href="" class="btn btn-primary">Re-Assign</a> <a href="" class="btn btn-primary">Delete</a> <a href="" class="btn btn-primary">Comment</a></td>
              </tr>';
            }elseif ( $currentDated24 >=  $value->addedOn  && $past7Days <= $value->addedOn) {
              $SevenDays .= '<tr>
              <td>'.$value->orderNo.'</td>
              <td>'.$value->addedOn.'</td>';
              if ($value->orderStatus == 'MAN') {
                  $SevenDays .=  '<td>Manager Approval Needed</td>';
              }else {
                $SevenDays .=  '<td>'.ucwords($value->orderStatus).'</td>';
              }
              $SevenDays .= '<td>'.ucwords($value->fullname).'</td>
              <td>'.amountFormat($value->orderPrice).'</td>
              <td><a href="" class="btn btn-primary">Approve</a> <a href="" class="btn btn-primary">Re-Assign</a> <a href="" class="btn btn-primary">Delete</a> <a href="" class="btn btn-primary">Comment</a></td>
              </tr>';
            }else {
              $elsetrRow .= '<tr>
              <td>'.$value->orderNo.'</td>
              <td>'.$value->addedOn.'</td>';
              if ($value->orderStatus == 'MAN') {
                  $elsetrRow .=  '<td>Manager Approval Needed</td>';
              }else {
                $elsetrRow .=  '<td>'.ucwords($value->orderStatus).'</td>';
              }
              $elsetrRow .= '<td>'.ucwords($value->fullname).'</td>
              <td>'.amountFormat($value->orderPrice).'</td>
              <td><a href="" class="btn btn-primary">Approve</a> <a href="" class="btn btn-primary">Re-Assign</a> <a href="" class="btn btn-primary">Delete</a> <a href="" class="btn btn-primary">Comment</a></td>
              </tr>';
            }
          }
        }
        ?>

        <?php if ($trRow6 !=''){ ?>
          <h4>Past 6 Hours</h4>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Date &amp; Time</th>
                  <th>Order Status</th>
                  <th>Customer</th>
                  <th>Total Amount</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $trRow6; ?>
              </tbody>
            </table>
          </div>
        <?php }; ?>

        <?php if ($trRow12 !=''): ?>
          <h4>Past 12 Hours</h4>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Date &amp; Time</th>
                  <th>Order Status</th>
                  <th>Customer</th>
                  <th>Total Amount</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $trRow12; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

        <?php if ($trRow18 !=''): ?>
          <h4>Past 18 Hours</h4>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Date &amp; Time</th>
                  <th>Order Status</th>
                  <th>Customer</th>
                  <th>Total Amount</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $trRow18; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

        <?php if ($trRow24 !=''): ?>
          <h4>Past 24 Hours</h4>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Date &amp; Time</th>
                  <th>Order Status</th>
                  <th>Customer</th>
                  <th>Total Amount</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $trRow24; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
        <?php if ($SevenDays !=''): ?>
          <h4>Past 7 Days</h4>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Date &amp; Time</th>
                  <th>Order Status</th>
                  <th>Customer</th>
                  <th>Total Amount</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $SevenDays; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
        <?php if (trim($elsetrRow)!=''): ?>

          <h4>Past Days</h4>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Order #</th>
                  <th>Date &amp; Time</th>
                  <th>Order Status</th>
                  <th>Customer</th>
                  <th>Total Amount</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $elsetrRow; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>


      </div>
    </section>
  </div>
</div>
