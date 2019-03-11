<div class="text-right">
<div class="credits">
<div class="col-md-12 text-center">
      <p>Designed by <a href="http://1wayit.com/" target="_blank">1WayIT Solutions</a></p>
  </div>
</div>
</div>
</section>
<!--main content end-->
</section>
<!-- container section start -->
<!-- javascripts -->
<input type="hidden" id="site_url" value="<?php echo base_url();?>">
<script type="text/javascript">
  var site_url = $('#site_url').val();
</script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.9.1.min.js"></script>

<script type="text/javascript" src="https://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script src="<?php echo $this->config->item('assets_path'); ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="<?php echo $this->config->item('assets_path');?>js/jquery.form.js"></script>
<script src="<?php echo $this->config->item('assets_path');?>js/jquery.validate.js"></script>
<script src="<?php echo $this->config->item('assets_path'); ?>js/custom.js"></script>
<script src="<?php echo $this->config->item('assets_path');?>js/form-validate.js"></script>
<script src="<?php echo $this->config->item('assets_path');?>js1/jquery-ui-1.10.4.min.js"></script>


<script type="text/javascript">
$(document).ready(function() {
  $('.loader_div').show();
      $(window).on('load', function () {
      setTimeout(()=> {$('.loader_div').fadeOut();},1200)
   });
});
var keys = {37: 1, 38: 1, 39: 1, 40: 1};
function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
      e.preventDefault();
  e.returnValue = false; type="text/javascript"
}
function preventDefaultForScrollKeys(e) {
    if (keys[e.keyCode]) {
        preventDefault(e);
        return false;
    }
}
function disableScroll() {
  if (window.addEventListener) // older FF
      window.addEventListener('DOMMouseScroll', preventDefault, false);
  window.onwheel = preventDefault; // modern standard
  window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
  window.ontouchmove  = preventDefault; // mobile
  document.onkeydown  = preventDefaultForScrollKeys;
}
function enableScroll() {
    if (window.removeEventListener)
        window.removeEventListener('DOMMouseScroll', preventDefault, false);
    window.onmousewheel = document.onmousewheel = null;
    window.onwheel = null;
    window.ontouchmove = null;
    document.onkeydown = null;
}

</script>

<!--

<script src="<?php echo $this->config->item('assets_path');?>js/jquery.validate.min.js"></script>




<script src="<?php echo $this->config->item('assets_path');?>js1/jquery.nicescroll.js" type="text/javascript"></script>

-->
<script src="<?php echo $this->config->item('assets_path'); ?>js/common.js"></script>


<!-- <script type="text/javascript" src="<?php echo $this->config->item('assets_path');?>js1/jquery-ui-1.9.2.custom.min.js"></script> -->


<!--script for this page only-->
<!--custome script for all page-->
<script src="<?php echo $this->config->item('assets_path');?>js1/scripts.js"></script>
<!-- custom script for this page-->
<?php if (current_url() == base_url().'production-processing') {?>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<?php } ?>

<?php
  $session = $this->session->all_userdata();;
  $session = $session['clientData'];

  if (!empty($session)) {
    if ($session['userType'] == 1) {
      ?>
      <script type="text/javascript">
        jQuery('.breadcrumb li > a').first().remove().html('Home');
        jQuery('.breadcrumb li').first().html('Home');
      </script>
    <?php } } ?>
<script type="text/javascript">
$(document).ready(function() {
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });
        return $helper;
    };
    $(".table-sortable tbody ").sortable({
        helper: fixHelperModified,
        placeholder : "ui-state-highlight",
        update  : function(event, ui)
        {
         var page_id_array = new Array();
         var srNo = 1;
         $('.sortNo').each(function(index){
            jQuery(this).find('td:first-child').not( ".est-date" ).html('<b><i class="fa fa-arrows" aria-hidden="true"></i></b>'+srNo)
            // jQuery(this).find('td:first-child').html('<b>O</b>'+srNo)
            page_id_array.push({
                                priorityNo:srNo,
                                orderRef:$(this).attr("ref")
                              });
          srNo++
         });
         $.ajax({
          url:site_url + "re-arrage-orders",
          method:"POST",
          dataType:"json",
          data:{orderByNo:page_id_array},
          success:function(response)
          {
            iziToast.destroy();
            var delayTime = 2500;
            if (response.success)
            {
              iziToast.success({
                timeout: delayTime,
                title: 'Success',
                message: response.success_message,
                position: 'bottomRight',
              });
              setTimeout(function(){  location.reload(); }, delayTime);

            }else{
              iziToast.error({
                timeout: delayTime,
                title: 'Error',
                message: response.error_message,
                position: 'bottomRight',
              });
            }
         },
          error: function (error) {
            iziToast.destroy();
            iziToast.error({
              timeout: 2500,
              title: 'Error',
              message: 'Connection Error',
              position: 'bottomRight',
            });
          }
         });
        }
    }).disableSelection();
    $(".table-sortable thead").disableSelection();
    $('.order-item-table tr').bind('mousedown',false);
    $('.order-item-table').bind('mousedown',false);
});
</script>

<?php if( $this->session->flashdata('error_message') ){?>
    <script type="text/javascript">
        iziToast.destroy();
        iziToast.error({
          timeout: 2500,
          title: 'Error',
          message: '<?php echo $this->session->flashdata('error_message');?>',
          position: 'bottomRight',
        });
    </script>
<?php } ?>
<?php if( $this->session->flashdata('success_message') ){?>
    <script type="text/javascript">
        iziToast.destroy();
        iziToast.success({
          timeout: 2500,
          title: 'Success',
          message: '<?php echo $this->session->flashdata('success_message');?>',
          position: 'bottomRight',
        });
    </script>
<?php } ?>


<div class="modal fade" id="get-followup-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="myModalLabel">Customer Follow Errors</h4>
        </div>
            <div class="modal-body">
                <table class="table table-striped">
                  <thead>
                    <th>Error</th>
                    <th>Status</th>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
            </div>
            <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirm-delete-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <p align="center"><img src="<?php echo site_url('assets/images/cross.png');?>" /></p>
                <p class="hide orderLine" align="center">Are you sure you want to delete this order? </p>
                <p align="center" class="hide userLine">Are you sure you want to delete this user? This action can not be modified. </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-success deleteRecordBtn">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-status-update-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <p align="center"><img src="<?php echo site_url('assets/images/info.png');?>" /></p>
                <p align="center"> Are you sure you want to make <span class="statusLabel"></span> ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-success updateRecordStatusBtn">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-action" style="z-index:9999">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <p align="center"><img src="<?php echo site_url('assets/images/info.png');?>" /></p>
                <p align="center">You are re-assigning to a different person than who placed the order – are you sure ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default reassingProcessClose pull-left" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-success reassingProcess">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-action-modal" style="z-index:9999">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <p align="center"><img src="<?php echo site_url('assets/images/info.png');?>" /></p>
                <p align="center">Are you sure you want to mark this order complete?  Be sure all details are correct and all customer follow up is successfully concluded</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default confirmActionProcessClose pull-left" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-success confirmAction">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-order-status-update-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <p align="center"><img src="<?php echo site_url('assets/images/info.png');?>" /></p>
                <p align="center"> Are you sure you want to make <span class="statusLabel"></span> ?</p>
                <br>
                <div class="form-group">
                  <label for="">Escalate</label>
                  <input id="approvedBy" name="approvedBy" class="form-control" type="text" placeholder="Enter Escalate Name">
                </div>

                <div class="form-group">
                  <label for="">Select Salesman to Re-Assign Order</label>
                  <select class="form-control" id="salesRefSel">
                  <option value="">Select Salesman to re-assign order</option>
                  <?php
                  $getSalesman = getSalesman();
                  if (!empty($getSalesman)){?>
                    <?php foreach ($getSalesman as $key => $value): ?>
                      <option value="<?= $value->userRef?>"><?php echo $value->userName; ?></option>
                    <?php endforeach; ?>
                  <?php } else {?>
                    <option value=""> no salesman found</option>
                <?php }; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="">Order Comment</label>
                  <textarea id="comment" name="comment" class="form-control" rows="8" cols="80"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-success updateOrderStatusData">Yes</button>
            </div>
        </div>
    </div>
</div>

      <!-- Add New Unit of Measurement-->
    <div id="addUnitOfMeasurement" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <?php echo form_open('addUnitOfMeasurement', array('name' => 'addUnitOfMeasurement', 'method' => 'post', 'class' => 'addUnitOfMeasurement', 'id' => "deliveryMethod-form","autocomplete" => "off")); ?>
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Unit of Measurement</h4>
          </div>
          <div class="modal-body">
            <input type="hidden" id="unitRef" name="unitRef" value="">
            <div class="form-group">
              <label class="col-sm-12">Unit of measurement</label>
              <div class="col-sm-12">
                <input type="text" name="unitName" id="unitName" class="form-control" placeholder="Enter UOM Name">
              </div>
            </div>

        </div>
        <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                   <input type="submit" value="Save" class="btn btn-success pull-right">
        </div>
        </div>

      </form>
      </div>
    </div>

    <div class="modal fade" id="add-new-comment-modal">
        <div class="modal-dialog modal-md">
          <?php
          echo form_open('addComment', array('name' => 'comment-order', 'method' => 'post', 'id' => "comment-order","autocomplete" => "off"));
          ?>
            <div class="modal-content">
              <div class="modal-body">
                  <p align="center"><img src="<?php echo site_url('assets/images/info.png');?>" /></p>
                  <p align="center"> Are you sure you want to make comment on <span class="order-no"></span> ?</p>

                  <input type="hidden" name="orderRef" id="commentOrderRef" value="">
                  <input type="hidden" name="orderPipline" id="orderPipline" value="">
                  <div class="form-group">
                    <label for="">Order Comment</label>
                    <textarea class="form-control" placeholder="Write your comment here" rows="5" id="commentArea" name="comment"></textarea>
                  </div>
              </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success">Yes</button>
                </div>
            </div>
          </form>
        </div>
    </div>


</body>
</html>
