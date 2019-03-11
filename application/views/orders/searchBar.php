

<div  class="search_order">
  <div class="col-md-12">
     <button data-toggle="collapse" class="btn btn-primary text-right" data-target="#searchBarToggle">Search</button>
  </div>

   <div id="searchBarToggle" class="row  collapse">
      <div class="col-md-12">
         <div class="row">
            <div class="form-group clearfix">
               <label class="col-md-2">Order ID</label>
               <div class="col-md-10"><input class="form-control" id="orderId" placeholder="Order Id"></div>
            </div>
         </div>
         <div class="row">
            <div class="form-group clearfix">
               <label class="col-md-2">Order State</label>
               <div class="col-md-10">
                  <select class="form-control" id="orderPipline">
                     <option value="">Select pipline</option>
                     <option data-target="#production_chkbx" value="3">Production Processing</option>
                     <option data-target="#approval_chkbx" value="2">Approval Status</option>
                     <option data-target="#dispatch_chkbx" value="4">Dispatch Processing</option>
                     <option data-target="" value="cancelled">Cancelled Orders</option>
                     <option data-target="#Customer_chkbx" value="4">Customer Follow-Up Status</option>
                  </select>
                  <!-- 'submitted','approved','pending','MAN','NA','queued','inProduction','open','Open Partially Filled','Closed Filled','Closed Partially Filled','Closed','reAssign' -->
                  <div id="approval_chkbx" class="checkbox_panel hide">
                     <div class="checkbox">
                        <label>
                        <input value="open" class="orderStatus" type="checkbox">
                        Approved
                        </label>
                     </div>
                     <div class="checkbox">
                        <label>
                        <input value="pending" class="orderStatus" type="checkbox">
                        Pending
                        </label>
                     </div>
                     <div class="checkbox">
                        <label>
                        <input value="reAssign" class="orderStatus" type="checkbox">
                        Re-assigned
                        </label>
                     </div>
                     <div class="checkbox">
                        <label>
                        <input value="MAN" class="orderStatus" type="checkbox">
                        Approval Needed
                        </label>
                     </div>
                  </div>
                  <div id="production_chkbx" class="checkbox_panel hide">
                     <!-- <div class="checkbox">
                        <label>
                          <input value="NA" class="orderStatus" type="checkbox">
                          NA
                        </label>
                        </div> -->
                     <div class="checkbox">
                        <label>
                        <input value="queued" class="orderStatus" type="checkbox">
                        Queued
                        </label>
                     </div>
                     <div class="checkbox">
                        <label>
                        <input value="inProduction" class="orderStatus" type="checkbox">
                        In Production
                        </label>
                     </div>
                     <div class="checkbox">
                        <label>
                        <input value="complete" class="orderStatus" type="checkbox">
                        Complete
                        </label>
                     </div>
                  </div>
                  <div id="dispatch_chkbx" class="checkbox_panel hide">
                     <div class="checkbox">
                        <label>
                        <input value="open" class="orderStatus" type="checkbox">
                        Open
                        </label>
                     </div>
                     <div class="checkbox">
                        <label>
                        <input value="OpenPartiallyFilled" class="orderStatus" type="checkbox">
                        Open Partially Filled
                        </label>
                     </div>
                     <div class="checkbox">
                        <label>
                        <input value="ClosedFilled" class="orderStatus" type="checkbox">
                        Closed Filled
                        </label>
                     </div>
                     <div class="checkbox">
                        <label>
                        <input value="Closed" class="orderStatus" type="checkbox">
                        Closed
                        </label>
                     </div>
                  </div>
                  <div id="Customer_chkbx" class="checkbox_panel hide">
                     <div class="checkbox">
                        <label>
                        <input value="OpenPartiallyFilled,ClosedFilled" class="orderStatus" type="checkbox">
                        In Queue
                        </label>
                     </div>
                     <div class="checkbox">
                        <label>
                        <input value="Closed" class="orderStatus" type="checkbox">
                        Completed Orders
                        </label>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="form-group clearfix">
               <label class="col-md-2" for="customerSearch">Customer</label>
               <div class="col-md-10">
                  <input class="form-control checkValue" id="customerSearch">
                  <input class="form-control empty" id="customerRef" type="hidden">
               </div>
            </div>
         </div>
         <div class="row">
            <div class="form-group clearfix">
               <label class="col-md-2">Salesperson</label>
               <div class="col-md-10">
                  <input class="form-control checkValue" id="salesmanSearch">
                  <input class="form-control empty" type="hidden" id="salesRef">
               </div>
            </div>
         </div>
         <div class="row">
            <div class="form-group clearfix">
               <label class="col-md-2">Date Placed</label>
               <div class="col-md-10">
                  <input class="form-control datepicker" id="date">
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <div class="col-md-12">
            <div class="row text-right">
               <button type="reset" id="reset" class="btn btn-primary">Reset</button>
               <button type="button" id="searchOrdersGlobal" class="btn btn-primary">Search</button>
            </div>
         </div>
      </div>
   </div>
</div>
