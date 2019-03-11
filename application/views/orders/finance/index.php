<?php  $loginSessionData = $this->session->userdata('clientData');
            // echo "<pre>";print_r($loginSessionData);
?>

<div class="row">
  <div class="col-md-12">
    <section class="sale_panel">
      <?php $this->load->view('orders/searchBar'); ?>
      <div class="panel-body" id="tableData">

        <?php
        $trRow6         = $trRow12 = $trRow18 = $trRow24 = $elsetrRow = $SevenDays ='';
        // $orderPrice6 = $orderPrice12 = $orderPrice18 = $orderPrice24 = $orderPriceElse = $ordePriceSevenDays ='';
        if (!empty($records)) {
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

            if ($value->managerApprove == 1) {
                $managerApprove = 'danger';
              }else {
                $managerApprove ='';
              }
              if ($value->orderStatus == 'reAssign') {
                $reAssingOrderA = '';
              }else{
                if($loginSessionData['ReAssignedOrdersWrite'] == 1){
                  $reAssingOrderA = '<a href="javascript:void(0)" data-sales="'.$value->salesRef.'" class="btn btn-primary changeOrderStatus" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="re-assigned" data-pipline="2" data-production="'.$value->orderInProduction.'">Re-Assign</a>';
                }else{
                  $reAssingOrderA = '<a href="javascript:void(0)" data-sales="'.$value->salesRef.'" class="btn btn-primary noAccess" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="re-assigned" data-pipline="2" data-production="'.$value->orderInProduction.'">Re-Assign</a>';
                }
              }
            if ($loginSessionData['FinancePipelineWrite'] == 1) {
              if ($managerApprove !='') {
                $trRowData ='<td><a href="javascript:void(0)" class="btn btn-primary changeOrderStatus" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="approved" data-pipline="2" data-production="'.$value->orderInProduction.'" data-approve="'.$value->managerApprove.'">Approve</a>
                '.$reAssingOrderA.'
                <a href="javascript:void(0);" class="changeOrderStatus btn btn-primary" data-name="'.$value->orderNo.'" data-type="orders" data-ref="'.$value->orderRef.'" data-to="cancelled" data-pipline="2" data-orderNo="'.$value->orderNo.'">Cancel</a> </td></tr>';
              } else {
                $trRowData ='<td><a href="javascript:void(0)" class="btn btn-primary changeOrderStatus" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="approved" data-pipline="2" data-production="'.$value->orderInProduction.'" data-approve="'.$value->managerApprove.'">Approve</a>
                '.$reAssingOrderA.'
                    <a href="javascript:void(0);" class="changeOrderStatus btn btn-primary" data-name="'.$value->orderNo.'" data-type="orders" data-ref="'.$value->orderRef.'" data-to="cancelled" data-pipline="2" data-orderNo="'.$value->orderNo.'">Cancel</a> &nbsp;';
                   $trRowData .='<a href="javascript:void(0)"  class="btn btn-primary  changeOrderStatus" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="managerApprove" data-pipline="2" data-production="'.$value->orderInProduction.'">Escalate</a></td></tr>';
              }
            } else {
             if ($managerApprove !='') {

                $trRowData ='<td><a href="javascript:void(0)" class="btn btn-primary noAccess" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="approved" data-pipline="2" data-production="'.$value->orderInProduction.'" data-approve="'.$value->managerApprove.'">Approve</a>
                <a href="javascript:void(0)" data-sales="'.$value->salesRef.'" class="btn btn-primary noAccess" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="re-assigned" data-pipline="2" data-production="'.$value->orderInProduction.'">Re-Assign</a>
                <a href="javascript:void(0);" class="noAccess btn btn-primary" data-name="'.$value->orderNo.'" data-type="orders" data-ref="'.$value->orderRef.'">Delete</a> </td></tr>';
              } else {
                $trRowData ='<td><a href="javascript:void(0)" class="btn btn-primary noAccess" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="approved" data-pipline="2" data-production="'.$value->orderInProduction.'" data-approve="'.$value->managerApprove.'">Approve</a>
                <a href="javascript:void(0)" data-sales="'.$value->salesRef.'" class="btn btn-primary noAccess" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="re-assigned" data-pipline="2" data-production="'.$value->orderInProduction.'">Re-Assign</a>
                <a href="javascript:void(0);" class="noAccess btn btn-primary" data-name="'.$value->orderNo.'" data-type="orders" data-ref="'.$value->orderRef.'">Delete</a> &nbsp;';

                   $trRowData .='<a href="javascript:void(0)"  class="btn btn-primary  changeOrderStatus" data-ref="'.$value->orderRef.'" data-orderNo="'.$value->orderNo.'" data-to="managerApprove" data-pipline="2" data-production="'.$value->orderInProduction.'">Escalate</a></td></tr>';

              }
            }


            if ($currentDate >=  $value->addedOn  && $currentDated <= $value->addedOn ) {

              if ($value->managerApprove == 1) {
                $managerApprove = 'danger';
              }else {
                $managerApprove ='';
              }
              $totalPrice = ($value->orderPrice + $value->transportCharge) - ($value->orderDiscount);
              $trRow6 .= '<tr class="'.$managerApprove.'" id="orders_'.$value->orderRef.'">
              <td><a href="'.base_url()."order-details/".$value->orderRef.'">'.$value->orderNo.'</td>
              <td>'.date('d-m-Y H:i:s', strtotime($value->addedOn)).'</td>
              <td class="tdStatus">'.orderStatus($value->orderStatus).'</td>
              <td>'.ucwords($value->fullname).'</td>
              <td>KES '.amountFormat($totalPrice).'</td>';
              if(trim($trRow6) !=''){

              $trRow6 .= $trRowData;
              }

            }elseif ( $currentDated >=  $value->addedOn  && $currentDated12 <= $value->addedOn) {
              if ($value->managerApprove == 1) {
                $managerApprove = 'danger';
              }else {
                $managerApprove ='';
              }
              $totalPrice = ($value->orderPrice + $value->transportCharge) - ($value->orderDiscount);
              $trRow12 .= '<tr class="'.$managerApprove.'" id="orders_'.$value->orderRef.'">
              <td><a href="'.base_url()."order-details/".$value->orderRef.'">'.$value->orderNo.'</td>
              <td>'.date('d-m-Y H:i:s', strtotime($value->addedOn)).'</td>
              <td class="tdStatus">'.orderStatus($value->orderStatus).'</td>
              <td>'.ucwords($value->fullname).'</td>
              <td>KES '.amountFormat($totalPrice).'</td>';
              if(trim($trRow12) !=''){
              $trRow12 .= $trRowData;
              }
            }
            elseif ( $currentDated12 >=  $value->addedOn  && $currentDated18 <= $value->addedOn) {
              if ($value->managerApprove == 1) {
                $managerApprove = 'danger';
              }else {
                $managerApprove ='';
              }
              $totalPrice = ($value->orderPrice + $value->transportCharge) - ($value->orderDiscount);
              $trRow18 .= '<tr class="'.$managerApprove.'" id="orders_'.$value->orderRef.'">
              <td><a href="'.base_url()."order-details/".$value->orderRef.'">'.$value->orderNo.'</td>
              <td>'.date('d-m-Y H:i:s', strtotime($value->addedOn)).'</td>
              <td class="tdStatus">'.orderStatus($value->orderStatus).'</td>
              <td>'.ucwords($value->fullname).'</td>
              <td>KES '.amountFormat($totalPrice).'</td>';
              if(trim($trRow18) !=''){

              $trRow18 .= $trRowData;
              }
            }
            elseif ( $currentDated18 >=  $value->addedOn  && $currentDated24 <= $value->addedOn) {
              if ($value->managerApprove == 1) {
                $managerApprove = 'danger';
              }else {
                $managerApprove ='';
              }
              $totalPrice = ($value->orderPrice + $value->transportCharge) - ($value->orderDiscount);
              $trRow24 .= '<tr class="'.$managerApprove.'" id="orders_'.$value->orderRef.'">
              <td><a href="'.base_url()."order-details/".$value->orderRef.'">'.$value->orderNo.'</td>
              <td>'.date('d-m-Y H:i:s', strtotime($value->addedOn)).'</td>
              <td class="tdStatus">'.orderStatus($value->orderStatus).'</td>
              <td>'.ucwords($value->fullname).'</td>
              <td>KES '.amountFormat($totalPrice).'</td>';
              if(trim($trRow24) !=''){

              $trRow24 .= $trRowData;
              }
            }elseif ( $currentDated24 >=  $value->addedOn  && $past7Days <= $value->addedOn) {
              if ($value->managerApprove == 1) {
                $managerApprove = 'danger';
              }else {
                $managerApprove ='';
              }
              $totalPrice = ($value->orderPrice + $value->transportCharge) - ($value->orderDiscount);
              $SevenDays .= '<tr class="'.$managerApprove.'"id="orders_'.$value->orderRef.'">
              <td><a href="'.base_url()."order-details/".$value->orderRef.'">'.$value->orderNo.'</td>
              <td>'.date('d-m-Y H:i:s', strtotime($value->addedOn)).'</td>
              <td class="tdStatus">'.orderStatus($value->orderStatus).'</td>
              <td>'.ucwords($value->fullname).'</td>
              <td>KES '.amountFormat($totalPrice).'</td>';
              if(trim($SevenDays) !=''){

              $SevenDays .= $trRowData;
              }
            }else {
              if ($value->managerApprove == 1) {
                $managerApprove = 'danger';
              }else {
                $managerApprove ='';
              }
              $totalPrice = ($value->orderPrice + $value->transportCharge) - ($value->orderDiscount);
              $elsetrRow .= '<tr class="'.$managerApprove.'"id="orders_'.$value->orderRef.'">
              <td><a href="'.base_url()."order-details/".$value->orderRef.'">'.$value->orderNo.'</td>
              <td>'.date('d-m-Y H:i:s', strtotime($value->addedOn)).'</td>
              <td class="tdStatus">'.orderStatus($value->orderStatus).'</td>
              <td>'.ucwords($value->fullname).'</td>
              <td>KES '.amountFormat($totalPrice).'</td>';
              if(trim($elsetrRow) !=''){

              $elsetrRow .= $trRowData;
              }
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
                  <th class="col">Order #</th>
                  <th class="col">Date &amp; Time</th>
                  <th class="col">Order Status</th>
                  <th class="col">Customer</th>
                  <th class="col">Total Amount</th>
                  <th class="col">Actions</th>
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
                  <th class="col">Order #</th>
                  <th class="col">Date &amp; Time</th>
                  <th class="col">Order Status</th>
                  <th class="col">Customer</th>
                  <th class="col">Total Amount</th>
                  <th class="col">Actions</th>
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
                  <th class="col">Order #</th>
                  <th class="col">Date &amp; Time</th>
                  <th class="col">Order Status</th>
                  <th class="col">Customer</th>
                  <th class="col">Total Amount</th>
                  <th class="col">Actions</th>
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
                  <th class="col">Order #</th>
                  <th class="col">Date &amp; Time</th>
                  <th class="col">Order Status</th>
                  <th class="col">Customer</th>
                  <th class="col">Total Amount</th>
                  <th class="col">Actions</th>
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
                  <th class="col">Order #</th>
                  <th class="col">Date &amp; Time</th>
                  <th class="col">Order Status</th>
                  <th class="col">Customer</th>
                  <th class="col">Total Amount</th>
                  <th class="col">Actions</th>
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
                  <th class="col">Order #</th>
                  <th class="col">Date &amp; Time</th>
                  <th class="col">Order Status</th>
                  <th class="col">Customer</th>
                  <th class="col">Total Amount</th>
                  <th class="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $elsetrRow; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

    <?php if ($trRow6  =='' && $trRow12  =='' && $trRow18  =='' && $trRow24  =='' && $SevenDays  =='' && trim($elsetrRow) ==''): ?>
      <table>
        <tbody>
            <tr><td align="right" colspan="13">No Order Found.</td></tr>
        </tbody>

      </table>
    <?php endif; ?>

      </div>
      <div id="orderSearchRecords" style="display:none"></div>
    </section>
  </div>
</div>
