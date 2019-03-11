<div class="row">
   <div class="col-md-12">
      <section class="sale_panel">
    <?php $this->load->view('orders/searchBar'); ?>
               <section class="Production_tabs_panel" id="tableData">
                  <header class="panel-heading">
                     <ul class="nav nav-tabs">
                        <li class="active">
                           <a data-toggle="tab" class="getToLoadOrder" data-to="pending" href="#Pending_dispatch"><span class="unLoadCount"><?php  echo getUnLoadCount(); ?></span>Pending</a>
                        </li>
                        <li class="">
                           <a data-toggle="tab" class="getToLoadOrder" data-to="toLoad" href="#Toload_dispatch"><span class="toLoadCount"><?php echo getToLoadCount(); ?></span>To Load </a>
                        </li>
                        <li class="">
                          <!-- <a data-toggle="tab" class="getToLoadOrder" data-to="toLoad" href="#Toload_dispatch"><span class="toLoadCount"><?php echo getToLoadCount(); ?></span>To Load </a> -->
                           <a data-toggle="tab" class="getToLoadOrder" data-to="dispached" href="#Past_dispatch"><span class="toDispatchCount"><?php echo getDispatchCount(); ?></span>Dispatched ( Past 7 days )</a>
                        </li>
                     </ul>
                  </header>
                  <div class="tab-content" >
                     <div id="Pending_dispatch" class="tab-pane active">
                       <div  id="tableData" class="panel-body">
                         <div class="table-responsive">
                           <table class="table table-striped">
                             <thead>
                               <tr>
                                 <th>#</th>
                                 <th>Order #</th>
                                 <th>Date & Time</th>
                                 <th>Order Status</th>
                                 <th>Business Name</th>
                                 <th>City</th>
                                 <th>Net Amount</th>
                                 <th>No. of Items</th>
                               </tr>
                             </thead>
                             <tbody>
                               <?php if (!empty($records)) {
                                   foreach ($records as $key => $value) { ?>
                                       <tr id="order_<?php echo $value->orderRef;?>" >
                                           <td>
                                             <input <?php if($value->toLoad == 1) echo "checked"; ?> type="checkbox" class="orders" data-ref="<?php echo $value->orderRef;?>"> <?php //echo $key + 1; ?></td>
                                             <td>
                                               <a data-toggle="collapse" data-target="#inner-item-detail_<?php echo $value->orderRef;?>" href="javascript:void(0)" class="clickable btn btn-plus extendsOrder" data-ref="<?php echo $value->orderRef;?>">+
                                                 <?php echo $value->orderNo; ?>
                                               </a>
                                             </td>
                                             <td><?php echo date('d-m-Y H:i:s', strtotime($value->addedOn)) ;?></td>
                                             <td><?php echo orderStatus($value->orderStatus);?></td>
                                             <td><?php echo ucwords($value->businessName);?></td>
                                             <td><?php echo ucwords($value->cityName);?></td>
                                             <td>KES <?php echo amountFormat($value->orderPrice - $value->orderDiscount);?></td>
                                             <td><?php echo ucwords($value->orderQty);?></td>
                                             <td><a href="<?php echo base_url().'order-details/'.$value->orderRef;?>" class="btn btn-primary">View</a></td>
                                        </tr>
                                        <?php   if (!empty($value->orderItems['data'])):  ?>
                                          <tr class="<?php echo $value->orderRef?>">
                                            <td colspan="9" style="background: #fff;">
                                              <div class="collapse inner-item-det" id="inner-item-detail_<?php echo $value->orderRef;?>">
                                                <h3 class="text-center"><u>Item Chart</u></h3>

                                                <table class="table" style="width:100%;">
                                                  <tbody>
                                                    <tr>
                                                      <th><b>Sr No</b></th>
                                                      <th><b>Item Name</b></th>

                                                            <th><b>Dimensions</b></th>
                                                            <th><b>Color</b></th>
                                                            <th><b>Design</b></th>
                                                            <th><b>Block Type</b></th>
                                                            <th><b>Quantity</b></th>
                                                            <th><b>Sale UOM</b></th>
                                                            <th><b>Total Dispatched</b></th>
                                                            <th><b>Pending Dispatch</b></th>
                                                            <th><b>Fullfilment</b></th>
                                                            <!-- <th><b>Price</b></th> -->


                                                    </tr>
                                                    <?php $itemId = ''; foreach ($value->orderItems['data'] as $orderItemKey => $orderItems): ?>
                                                      <?php foreach ($orderItems->variants as $vkey => $variants): ?>
                                                      <tr>
                                                        <?php $itemId .= $orderItems->itemRefId.','; if ($vkey == 0): ?>
                                                          <td><?php  echo $orderItemKey+1 ?></td>
                                                          <td><?php echo $orderItems->itemName ?></td>
                                                        <?php else : ?>
                                                          <td></td>
                                                          <td></td>
                                                        <?php endif; ?>
                                                                    <td>
                                                                      <?php echo  getDiamentions($variants->length,$variants->width,$variants->height) ?>
                                                                    </td>
                                                                    <td><?php echo $variants->color ?></td>
                                                                    <td><?php echo $variants->design ?></td>

                                                                    <td>
                                                                      <?php
                                                                      //echo "<pre>";print_r($orderItems->blockType);
                                                                      echo $variants->blockType;?>
                                                                    </td>
                                                                    <td>
                                                                      <?php echo $variants->qty ?>
                                                                    </td>
                                                                    <td><?php  echo get_ItemUOM($value->orderRef , $orderItems->itemRefId)->saleUOM;?></td>
                                                                    <td><?php
                                                                    $totalDispatch = 0;
                                                                    $totalDispatch =  getTotalDispatchQty($value->orderRef,$orderItems->itemRefId)->totalDipatchQty;
                                                                    echo ($totalDispatch !='') ? $totalDispatch : 0 ;
                                                                    ?></td>
                                                                    <td><?php echo $variants->qty - $totalDispatch ?></td>
                                                                    <td><?php if ($totalDispatch == $variants->qty ) echo "Full";  else echo "Part";  ?></td>
                                                                    <!-- <td>
                                                                    KES  <?php echo amountFormat($variants->price) ?>
                                                                    </td> -->
                                                        <?php echo '<input type="hidden" value="'.$itemId.'" id="ids'.$value->orderRef.'">' ?>
                                                      </tr>
                                                    <?php endforeach; ?>
                                                    <?php endforeach; ?>

                                                    <tr>
                                                      <td colspan="11" class="text-right">
                                                        <table style="width:100%;">
                                                          <tr>
                                                            <td>Total Items : <?php echo count($value->orderItems['data']) ?></td>
                                                          </tr>
                                                          <!-- <tr>
                                                            <td>Total Amount : KES <?php echo amountFormat($value->orderPrice) ?></td>
                                                          </tr> -->
                                                        </table>
                                                        </td>
                                                    </tr>
                                                  </tbody>
                                                </table>

                                                <?php if (!empty($value->orderComment)): ?>
                                                  <h3 class="text-center"><u>Order Comments</u></h3>
                                                    <ul class="comment-section clearfix">
                                                  <?php foreach ($value->orderComment as $key => $orderComment): ?>
                                                    <li class="comment user-comment" id="orderComment_<?php echo $orderComment->commentRef;?>">
                                                      <div class="info">
                                                        <a href="javascript:void(0)"><?php echo ucwords($orderComment->userName) ?></a>
                                                        <h4 class="label label-primary">
                                                          <?php if ($orderComment->userType == 1){
                                                            echo "Super Admin";
                                                          }elseif ($orderComment->userType == 2) {
                                                            echo "Manager";
                                                          }elseif ($orderComment->userType == 3) {
                                                            echo "Salesman";
                                                          } ?>
                                                        </h4>
                                                        <br>
                                                        <span><?php echo dateDiff(date('Y-m-d H:i:s'),$orderComment->addedOn) ?></span>
                                                      </div>
                                                      <a class="avatar" href="javascript:void(0)">
                                                        <img src="<?php echo site_url('assets/images/user.png');?>" width="35" alt="Profile Avatar" title="Anie Silverston" />
                                                      </a>
                                                      <p class="realtive">
                                                        <?php echo ucfirst($orderComment->comment); ?>
                                                      </p>
                                                    </li>
                                                  <?php endforeach; ?>
                                                  </ul>
                                                <?php endif; ?>

                                              </div>
                                            </td>
                                          </tr>
                                        <?php endif; ?>
                                   <?php }
                               }
                               else
                               { ?>
                                   <tr><td align="center" colspan="13">No Pending Order Found.</td></tr>
                                 <?php   } ?>
                             </tbody>
                             </table>
                             <div class="">
                               <?php echo $paginationLinks; ?>
                             </div>
                         </div>

                       </div>
                     </div>
                     <div id="Toload_dispatch" class="tab-pane ">
                     </div>
                     <div id="Past_dispatch" class="tab-pane">
                     </div>
                  </div>
               </section>
               <div id="orderSearchRecords" style="display:none"></div>
            </section>

         </div>
      </div>
   </section>
    </section>
  </div>
</div>

<div class="modal fade" id="myModal-2" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <div class="table-responsive">
               <table id="modal-table1" class=" table table-striped table-bordered">
                  <thead>
                     <tr>
                        <th>Order</th>
                        <th>Item Name</th>
                        <th>Loaded Now (Base UOM)</th>
                        <th>Loaded Now (Sales UOM)</th>
                        <th>Not Loading (Sales UOM)</th>
                        <th>Dispatched Previously (Sales UOM)</th>
                        <th>Complete</th>
                        <th>Confirm</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td rowspan="3">Ajay’s Shop 123456</td>
                        <td>Name, Design, Color, Size</td>
                        <td>100 meters</td>
                        <td>2 rolls</td>
                        <td>1 rolls</td>
                        <td>2 rolls</td>
                        <td>Complete/Incomplete</td>
                        <td>Invoice No:</td>
                     </tr>
                     <tr>
                        <td>Name, Design, Color, Size</td>
                        <td>100 meters</td>
                        <td>2 rolls</td>
                        <td>1 rolls</td>
                        <td>0 rolls</td>
                        <td>Complete/Incomplete</td>
                        <td>[Confirm Dispatch Button]</td>
                     </tr>
                     <tr>
                        <td colspan="7">Also list items that were in the order but not loaded in case of a partial loading, but have them in grey/not editable</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <div class="table-responsive">
               <table id="modal-table" class=" table table-striped table-bordered">
                  <thead>
                     <tr>
                        <th>Item Name</th>
                        <th>Loading Now</th>
                        <th>Loading Now</th>
                        <th>Loading Now</th>
                     </tr>
                  </thead>
                  <tbody>
                     <td>Name, Design, Color, Size</td>
                     <td>2 rolls</td>
                     <td>1 rolls</td>
                     <td>2 rolls</td>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
$(document).on('click', '#loadingSheet', function(event)
{
    var val     = $( this ).find('option:selected').attr('data-ref');
    if( val == 'addNewLoadingSheet')
    {
        $('#loadingSheet').not($(this)).each(function(){
            $(this).popover('hide');
        });
        $(this).popover({
            trigger: 'manual',
            placement: 'auto right',
            container: 'body',
            content: $('#addNewSheet').html()
        }).popover('show');
        return false;
    }
});

</script>
