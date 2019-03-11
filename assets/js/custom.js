$(document).ready(function () {
  $("#forget-pass").hide();
  $("#kl").click(function(){
      $("#login-form").hide();
      $("#forget-pass").show();
  });
  $("#backToLogin").click(function(){
      $("#login-form").show();
      $("#forget-pass").hide();
  });

  // $("li").click(function(){
  //   if(!$('#noRecordToLoad').is(':visible'))
  //   {
  //     $('.saveDispatch .pull-right .btn .btn-success').css('display','none');
  //   }
  // });

  $(document).on('keyup','.uomTypeFixed',function(){
  var salesRef = $(this).attr('data-ref');
  var sale = salesRef * $(this).val();
  jQuery(this).closest('td').prev('td').find('.baseUOMQty').val(sale);
});

    (function ($) {
        $.fn.extend({
            donetyping: function (callback, timeout) {
                timeout = timeout || 1e3; // 1 second default timeout
                var timeoutReference,
                        doneTyping = function (el) {
                            if (!timeoutReference)
                                return;
                            timeoutReference = null;
                            callback.call(el);
                        };
                return this.each(function (i, el) {
                    var $el = $(el);
                    // Chrome Fix (Use keyup over keypress to detect backspace)
                    // thank you @palerdot
                    $el.is(':input') && $el.on('keyup keypress paste', function (e) {
                        // This catches the backspace button in chrome, but also prevents
                        // the event from triggering too preemptively. Without this line,
                        // using tab/shift+tab will make the focused element fire the callback.
                        if (e.type == 'keyup' && e.keyCode != 8)
                            return;
                        // Check if timeout has been set. If it has, "reset" the clock and
                        // start over again.
                        if (timeoutReference)
                            clearTimeout(timeoutReference);
                        timeoutReference = setTimeout(function () {
                            // if we made it here, our timeout has elapsed. Fire the
                            // callback
                            doneTyping(el);
                        }, timeout);
                    }).on('blur', function () {
                        // If we can, fire the event since we're leaving the field
                        doneTyping(el);
                    });
                });
            }
        });
    })(jQuery);

    // var site_url = $('#site_url').val();

    //chek old Password
    $('#oldPassword').donetyping(function () {
        $.ajax({
            type: "POST",
            url: site_url + "checkOldPassword",
            data: {
                'oldpassword': $(this).val()
            },
            dataType: 'json',
            success: function (msg) {
                //alert(msg);
                jQuery('#message').remove();
                if (!msg.success) {

                    $('#oldPassword').css('border', '1px solid #b92c28');
                    $('.form-control-feedback').css("color", "red");
                     $("<span id='message' style='color:red'>Password not matched!</span>").insertAfter("#oldPassword");
                    $('#oldPassword').focus();
                    //return false
                } else {
                    $("#successMsg").css("display", "none");
                    $('.form-control-feedback').removeClass('glyphicon glyphicon-remove');
                     $("<span id='message' style='color:green'>Password matched!</span>").insertAfter("#oldPassword");
                    $('.form-control-feedback').css("color", "green");
                    $('#oldPassword').css('border', '1px solid green');
                }
            }
        });

    });

    //*********************PRODUCTION PROCESSING & CUSTOMER FOLLOW UP****************//
    // $("#fileterByNum").change(function() {
    //   $('#tableData li:first').addClass('active');
    //   $('#tableData li:first').next().removeClass('active');
    //   $('#pendingFollowup').addClass('active');
    //   $('#Recently_Completed').removeClass('active');
    // });
    //*********************PRODUCTION PROCESSING & CUSTOMER FOLLOW UP****************//

    $(document).on('keypress', '.validNumber', function (eve) {
        if (eve.which == 0) {
            return true;
        } else {
            if (eve.which == '.') {
                eve.preventDefault();
            }
            if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57)) {
                if (eve.which != 8)
                {
                    eve.preventDefault();
                }
            }

            $('.validNumber').keyup(function (eve) {
                if ($(this).val().indexOf('.') == 0) {
                    $(this).val($(this).val().substring(1));
                }
            });
        }
    });

    $(document).on('change', '#countriesSel', function (event)
    {

        var id = $(this).val();
        if (id > 0)
        {
            $.ajax({
                type: "post",
                url: site_url + 'getStates',
                beforeSend: function () {
                    $('.loader_div').show();
                },
                complete: function () {
                    $('.loader_div').hide();
                },
                cache: false,
                data: {id: id},
                dataType: "json",
                success: function (response)
                {
                    if (response.success)
                    {
                        var optHtml = '<option value="">Select Region</option>';
                        $(response.cities).each(function (i, obj) {
                            optHtml += '<option value="' + obj.stateId + '">' + obj.stateName + '</option>';
                        });
                        $('#stateSel').html(optHtml);
                    } else
                    {
                        $('#stateSel').html('<option value="">No region found.</option>');
                    }
                },
                error: function ()
                {
                    alert('Error while request..');
                }
            });
        }
    });
    jQuery('#countriesSel').trigger('change');
    $(document).on('change', '#stateSel', function (event)
    {
        var id = $(this).val();
        if (id > 0)
        {
            $.ajax({
                type: "post",
                url: site_url + 'getCities',
                beforeSend: function () {
                    $('.loader_div').show();
                },
                complete: function () {
                    $('.loader_div').hide();
                },
                cache: false,
                data: {id: id},
                dataType: "json",
                success: function (response)
                {
                    if (response.success)
                    {
                        var optHtml = '<option value="">Select Town</option>';
                        $(response.cities).each(function (i, obj) {
                            optHtml += '<option value="' + obj.CityId + '">' + obj.cityName + '</option>';
                        });
                        $('#city').html(optHtml);
                    } else
                    {
                        $('#city').html('<option value="">No town found.</option>');
                    }
                },
                error: function ()
                {
                    alert('Error while request..');
                }
            });
        }
    });

    jQuery(document).on('change click','#catRef',function(){
        var selvalue = $.trim($(this).find("option:selected").text()) ;
        if(selvalue == 'Add New Category'){
          jQuery('#AddCategory').modal('show');
          jQuery('#catRef').val(' ');
        }
    });
    jQuery(document).on('click','.updateSubCategory',function(){
          jQuery('#add-Category-modal').find('#catRef').remove();
          var parentCategory = jQuery(this).attr('data-parentref');
          var dataRef        = jQuery(this).attr('data-ref');
          var dataName       = jQuery(this).attr('data-name');
          jQuery('#parentCatRef').val(parentCategory);
          jQuery('#add-Category-modal').find('form').append('<input type="hidden" name="catRef"  id="catRef" value="'+dataRef+'">');
          jQuery('#add-Category-modal').find('input[name="categoryName"]').val(dataName);
          jQuery('#add-Category-modal').modal('show');

    });
    jQuery(document).on('change click','#subCat',function(){
        var parentRef = $('#catRef').val();
        jQuery('#parentCatRef').val(parentRef);
        var selvalue = $(this).find("option:selected").text();
        if(selvalue == 'Add New Category'){

          jQuery('#add-Category-modal').modal('show');
          jQuery('#subCat').val('');
        }
    });
    jQuery(document).on('change click','#UOM',function(){
        var selvalue = $(this).find("option:selected").text();
        if(selvalue == 'Add New UOM')
        {
          $('#addUnitOfMeasurement').modal({backdrop: 'static', keyboard: false})
        }
    });

    jQuery(document).on('change click','.updateUOM',function(){
        var dataRef   = jQuery(this).attr('data-ref');
        var dataName  = jQuery(this).attr('data-name');
        if (dataRef == '' || dataName == '') {
          iziToast.error({
              timeout: 3000,
              title: 'Error',
              message: 'Something went wrong please try again.',
              position: 'bottomRight',
          });
        }else {
          $('#addUnitOfMeasurement').find('#unitRef').val(dataRef);
          $('#addUnitOfMeasurement').find('#unitName').val(dataName);
          $('#addUnitOfMeasurement').find('button[type="submit"]').html('Update');
          $('#addUnitOfMeasurement').modal({backdrop: 'static', keyboard: false})
        }
    });

jQuery(document).on('click','.updateCity',function(){
    var dataName = jQuery.trim(jQuery(this).attr('data-name'));
    var dataRef = jQuery.trim(jQuery(this).attr('data-ref'));
    jQuery('#addUpdateCity').find('#name').val(dataName);
    jQuery('#addUpdateCity').find('#cityId').val(dataRef);
    jQuery('#addUpdateCity').modal('show');
})
jQuery(document).on('click','.viewDetails',function(e){
  e.preventDefault();
    var url = jQuery.trim(jQuery(this).attr('data-url'));
    $.ajax({
        type: "GET",
        url: url,
        beforeSend: function () {
            $('.loader_div').show();
        },
        complete: function () {
            $('.loader_div').hide();
        },
        cache: false,
        data: {ajax: 1},
        dataType: "html",
        success: function (response)
        {
          jQuery('.detailDrow').html(response);
          jQuery('.defaultRow').hide();
        },
        error: function ()
        {
            alert('Error while request..');
        }
    });

})
jQuery(document).on('click','.updateRegion',function(){
    var dataName = jQuery.trim(jQuery(this).attr('data-name'));
    var dataRef = jQuery.trim(jQuery(this).attr('data-ref'));
    jQuery('#addNewRegion').find('#name').val(dataName);
    jQuery('#addNewRegion').find('#stateId').val(dataRef);
    jQuery('#addNewRegion').modal('show');
})
$(document).on('click', '.backBtn', function(event)
{
  jQuery('.detailDrow').html('');
  jQuery('.defaultRow').show();
  jQuery(this).hide();
});

jQuery(document).on('change','.searchNotification',function(e){
  e.preventDefault();
	var filterType = $.trim(jQuery(this).val());
	var customerValue = $.trim(jQuery('.customerSearch').val());
	$.ajax({
			type: "POST",
			url: site_url+'getNotifications',
			beforeSend: function () {
					$('.loader_div').show();
			},
			complete: function () {
					$('.loader_div').hide();
			},
			cache: false,
			data: {filterType: filterType,customerName:customerValue},
			dataType: "html",
			success: function (response)
			{
				jQuery('.notify_panel').html(response);
			},
			error: function ()
			{
					alert('Error while request..');
			}
	});
})


jQuery(document).on('keyup keydown','.checkValue',function (e) {
  jQuery(this).next('.empty').val('');
});
jQuery(document).on('click','.readStatus',function (e) {
  var notificationRef  = jQuery(this).attr('data-ref');
  var dataHref = jQuery(this).attr('data-href');
  $.ajax({
			type: "POST",
			url: site_url+'updateNotificationStatus',
			beforeSend: function () {
					$('.loader_div').show();
			},
			complete: function () {
					$('.loader_div').hide();
			},
			cache: false,
			data: {notificationRef: notificationRef},
			dataType: "json",
			success: function (response)
			{
        window.location.href = dataHref;
			},
			error: function ()
			{
					alert('Error while request..');
			}
	});
});

jQuery(document).on('keyup keydown','.productionCount', function() {
  callToEnhanceValidate();
})
var callToEnhanceValidate=function()
{
    $(".productionCount").each(function()
    {
        $(this).rules('remove');
        $(this).rules('add', {
                required: true,
      messages: {
              required: "This field is required."
          },
         });
    });
}
jQuery(document).on('click','.add-new-comment', function() {
  var ref     = jQuery(this).attr('data-ref');
  var orderNo = jQuery(this).attr('data-orderno');
  var pipline = jQuery(this).attr('data-pipline');
  jQuery('#add-new-comment-modal').find('#commentOrderRef').val(ref);
  jQuery('#add-new-comment-modal').find('#orderPipline').val(pipline);
  jQuery('#add-new-comment-modal').find('.order-no').html('#'+orderNo);
  jQuery('#add-new-comment-modal').modal('show');
})
jQuery(document).on('click','.modifyLoading', function() {
  iziToast.destroy();
  var ref          = jQuery(this).attr('data-ref');
  var dispatch     = jQuery(this).attr('data-dispatch');
  dispatch = (dispatch == '') ? '': dispatch;
  var sheetRef     = jQuery(this).closest('tr').find('.prependToDiv').val();

  if (sheetRef == '' || ref == '') {
    iziToast.info({ timeout: 3000,title: 'Success', message: 'Please load order to loading sheet first..',position: 'bottomRight',});
    return false;
  }
  jQuery('#modifyLoading-modal').find('#dispatchNum').val(dispatch);
  jQuery('#modifyLoading-modal').modal('show');
  var itemIds = jQuery('#ids'+ref).val();
  var data = {
    orderRef : ref,
    sheetRef : sheetRef,
    dispatch : dispatch,
    orderRef : ref,
    itemIds : itemIds,
  }
  $.ajax({
			type: "POST",
			url: site_url+'get-dispached-order-details/',
			beforeSend: function () {
					$('.loader_div').show();
			},
			complete: function () {
					$('.loader_div').hide();
			},
      data : data,
			cache: false,
			dataType: "json",
			success: function (response)
			{
        jQuery('#orderRefId').val(ref);
        jQuery('#sheetRefId').val(sheetRef);
        jQuery('#modifyLoadingItems').html(response.html);
			},
			error: function ()
			{
					alert('Error while request..');
			}
	});
})

jQuery(document).on('submit','.loadingForm',function (event) {
  event.preventDefault();
  iziToast.destroy();
  var formdata = $(this).serializeArray();
  $.ajax({
      type: "POST",
      url: $.trim($(this).attr('action')),
      beforeSend: function () {
          $('.loader_div').show();
      },
      complete: function () {
          $('.loader_div').hide();
      },
      data : formdata,
      cache: false,
      dataType: "json",
      success: function (response)
      {
        var modelhide = localStorage.getItem("modelhide");
        console.log('modelhide'+modelhide);
        if(modelhide != undefined && modelhide !="" && modelhide !=null)
        {
          jQuery('#'+modelhide).modal('hide');
          localStorage.removeItem('modelhide');
        }

        if (response.success) {
          jQuery('#dispatchNum').val(response.dispatchRef);
          $('.'+response.orderRef).find('.modifyLoading').attr('data-dispatch',response.dispatchRef)
          iziToast.success({ timeout: 3000,title: 'Success', message: response.success_message,position: 'bottomRight',});
        }else {
          iziToast.error({ timeout: 3000,title: 'Error', message: response.error_message,position: 'bottomRight',});
        }
      },
      error: function ()
      {
        iziToast.error({
            timeout: 3000,
            title: 'Error',
            message: 'Connection Error.',
            position: 'bottomRight',
        });
      }
  });
})


jQuery(document).on('click','.parentAccrodion',function(event) {
      event.preventDefault();
      var dataTarget = jQuery(this).attr('data-target');
      var chilTarget = jQuery(dataTarget).find('table tbody tr').find('th:first-child').attr('data-target');
      if (jQuery(dataTarget).hasClass('shown')) {
            if (jQuery(chilTarget).hasClass('child-shown')) {
                jQuery(chilTarget).closest('tr').css('display','none');
                jQuery(chilTarget).hide().removeClass('child-shown');
            }
            jQuery(dataTarget).closest('tr').css('display','none');
            jQuery(dataTarget).hide().removeClass('shown');
            return false;
      } else {
            jQuery(dataTarget).closest('tr').css('display','');
            jQuery(dataTarget).show().addClass('shown');
            return false;
      }
      return false;
})
  jQuery(document).on('click','.subParentAccrodion',function() {
      var dataTarget = jQuery(this).attr('data-target');
      if (jQuery(dataTarget).hasClass('child-shown')) {
            jQuery(dataTarget).closest('tr').css('display','none');
            jQuery(dataTarget).hide().removeClass('child-shown');
            return false;
      } else {
            jQuery(dataTarget).closest('tr').css('display','');
            jQuery(dataTarget).show().addClass('child-shown');
            return false;
      }
      return false;
  })

  jQuery(document).on('click','.childSlider',function(){
    var parent = jQuery(this).attr('data-parent');
    if(!jQuery('.'+parent).is(':checked')){
        jQuery(this).prop('checked', false);
         alert('Please checked first check box first');
    }
    if (jQuery(this).is(':checked') && parent == 'noReturn') {
        jQuery('#'+parent).find('.itemDisabled').attr('readonly','readonly');
        jQuery('.itemDisabled').each(function() {
            var closedtQty = jQuery.trim(jQuery(this).closest('tr').find('.dispatchQty').text());
            jQuery(this).val(closedtQty);
            jQuery(this).parent().removeClass('has-error');

        })
    }else{
        jQuery('#'+parent).find('.itemDisabled').removeAttr('readonly');
    }

  })
    jQuery(document).on('keyup','.itemDisabled',function(){
    var closedtQty = jQuery.trim(jQuery(this).closest('tr').find('.dispatchQty').text());
    var currentVal = jQuery.trim(jQuery(this).val());

    closedtQty  = parseInt(closedtQty);
    currentVal  = (currentVal == NaN) ? 0 : parseInt(currentVal);
    jQuery('.submitCustomerFollowUp').prop('disabled',false);
    if (currentVal > closedtQty) {
      iziToast.destroy();
      iziToast.warning({title: 'Warning',message: 'Return qunatity can not more then Dispatched qunatity so please enter correct entries.'});
      jQuery('.submitCustomerFollowUp').prop('disabled',true);
    }
  })
  jQuery(document).on('change','.childSlider',function(){
    var parent = jQuery(this).attr('data-parent');
    if(!jQuery('.'+parent).is(':checked')){
        jQuery(this).prop('checked', false);
    }
    if (jQuery(this).is(':checked') && parent == 'noReturn') {
          jQuery('#'+parent).find('.itemDisabled').attr('readonly','readonly');
          jQuery('#'+parent).find('.qtyReturn').removeClass('has-error');
    }else{
          jQuery('#'+parent).find('.itemDisabled').removeAttr('readonly');
    }
  })
  // jQuery('.noReturn').on('change', function () {
  //      $('.noReturn').not(this).prop('checked', false);
  //  })
  // jQuery('.notReceived').on('change', function () {
  //      $('.notReceived').not(this).prop('checked', false);
  // })
  jQuery('.customerFollowUpToggle').on('change', function () {
       // $('.customerFollowUpToggle').not(this).prop('checked', false);
       var dataTarget = jQuery(this).attr('data-ref');
       if(jQuery(this).is(':checked')){
         // jQuery('.queries').addClass('hide');
         jQuery('.queries').each(function(){
           jQuery('#'+dataTarget).removeClass('hide');
         })
       }else{
         jQuery('.childSlider').trigger('change');
         jQuery('#'+dataTarget).addClass('hide');
       }
      return false;

   });


  jQuery(document).on('click','.updateCustomerFollowup',function() {
        var dataRef = jQuery.trim(jQuery(this).attr('data-ref'));

        if (dataRef !='') {
          $.ajax({
              type: "GET",
              url: site_url+'get-dispatch-items/'+dataRef,
              beforeSend: function () {
                  $('.loader_div').show();
              },
              complete: function () {
                  $('.loader_div').hide();
              },
              cache: false,
              dataType: "json",
              success: function (response)
              {
                  if (response.success) {
                      jQuery('#dispatchtems').html(response.html);

                      if (response.dispatchStatus) {
                              jQuery('.customerFollowUpToggle').each(function(){
                                if (response.dispatchStatus.receivedStatus) {
                                  jQuery('input[name="recevedStatus"]').prop('checked', true);
                                    if (response.dispatchStatus.receivedStatus == 'Full') {
                                          jQuery('input[name="recevedStatusVal"]').prop('checked', true);
                                    }
                                }
                                if ( response.dispatchStatus.errorStatus != null && response.dispatchStatus.errorStatus != '' ) {
                                  jQuery('input[name="errorStatus"]').prop('checked', true);
                                  var errorss = (response.dispatchStatus.errorStatus !=null) ? response.dispatchStatus.errorStatus.split(',') : '';;
                                    jQuery(errorss).each(function(index,value){
                                          jQuery('.errors-checkbox[name="errors['+value+']"]').prop('checked', true);
                                    });
                                }
                                if (response.dispatchStatus.returnStatus && response.dispatchStatus.returnStatus != '' ) {
                                  jQuery('input[name="returnStatus"]').prop('checked', true);
                                    if (response.dispatchStatus.returnStatus == 'Full') {
                                      jQuery('input[name="returnStatusVal"]').prop('checked', true);
                                      jQuery('input[name="returnStatusVal"]').trigger('change');
                                    }
                                }

                              })

                      }
                      if (response.orderNotes) {
                          jQuery(response.orderNotes).each(function(index,value){
                            if(value.type == 'error'){
                              jQuery('textarea[name="commentErrors"]').val(value.comment);

                            }
                            if(value.type == 'dispatch'){
                              jQuery('textarea[name="comment"]').val(value.comment);
                            }
                            if(value.type == 'creditNote'){
                              jQuery('input[name="creditNote"]').val(value.comment);

                            }
                          });
                      }

                      jQuery('.customerFollowUpToggle').trigger('change')
                  }else{
                    jQuery('#dispatchtems').html('<tr><td colspan="4" align="center">No items Found.</td></tr>')
                  }
              },
              error: function ()
              {
                iziToast.error({
                    timeout: 3000,
                    title: 'Error',
                    message: 'Connection Error.',
                    position: 'bottomRight',
                });
              }
          });
        } else {
            alert('Something went wrong please try again')
        }
        jQuery('#confirm-update-customer-follow-up-modal').modal('show');
        return false;
  })

    var confirmAction = false;
    jQuery(document).on('click','.confirmAction',function () {
  		confirmAction = true;
  		jQuery('.confirmActionProcessClose').click();

      jQuery('.submitCustomerFollowUp[data-ref="saveNclose"]').trigger('click');
  		// jQuery('.submitCustomerFollowUp').click();
  	});

    jQuery(document).on('click','.submitCustomerFollowUp',function() {
          iziToast.destroy();
          var dataRef = jQuery(this).attr('data-ref');
          var formData = jQuery('.saveCustomerFollowupStatus').serialize();
          formData += '&data-ref='+dataRef;

          var recevedStatus = false;
          var returnStatus  = false;
          var errorStatus   = false;
          jQuery('.customerFollowUpToggle').each(function(){
            if (jQuery(this).attr('data-ref') == 'notReceived' && jQuery(this).is(':checked')) {
                recevedStatus = true;
            }
            if (jQuery(this).attr('data-ref') == 'noReturn' && jQuery(this).is(':checked')) {
                returnStatus = true;
            }
            if (jQuery(this).attr('data-ref') == 'noError' && jQuery(this).is(':checked')) {
                errorStatus = true;
            }
          });

          var childReturnStatus =  false;
          jQuery('.childSlider').each(function(){
            if (jQuery(this).attr('data-ref') == 'noReturn' && jQuery(this).is(':checked')) {
                childReturnStatus = true;
            }
          });
          // console.log('recevedStatus ===> '+recevedStatus);
          // console.log('returnStatus ===> '+returnStatus);
          if (dataRef == 'saveNclose') {
             if ( (recevedStatus == false) && (returnStatus == false  ) ) {
                 let error_message = "Received Status is No you cannnot close this order if Return is not Part or Full!";
                 iziToast.info({title: 'info',message: error_message});
                 return false;
             }
             if (!confirmAction) {
               jQuery('#confirm-action-modal').modal();
               return false
             }else{
               formData += '&data-ref=saveNclose';
               confirmAction = false;
             }
          }

          var errorCount = 0;
          var closedReturn = '';
          if (!jQuery('#noReturn').hasClass('hide'))
          {
          jQuery('.qtyReturn').each(function() {
              if (returnStatus && jQuery(this).val() == '' ) {
                 jQuery(this).attr('placeholder','This field is required').parent().addClass('has-error');
                 errorCount++;
              }else{
                jQuery(this).parent().removeClass('has-error');
              }

          })
          jQuery('.itemReason').each(function() {
              closedReturn = jQuery(this).closest('tr').find('.qtyReturn').val();
              if(jQuery(this).val() == '' && (closedReturn != 0 && closedReturn != '') )
              {
                   jQuery(this).attr('placeholder','This field is required').parent().addClass('has-error');
                   errorCount++;
              }
              else{
                   jQuery(this).parent().removeClass('has-error');
              }
          });
          }
          if (!jQuery('#noError').hasClass('hide'))
          {
              if ($(".errors-checkbox:checkbox:checked").length == 0)
              {
        				 iziToast.info({title: 'info',message: 'Please select one error!'});
        			   return false;
        			}
          }

          if (errorCount == 0) {
            $.ajax({
              type: "POST",
              url: site_url+'save-customer-follow-up',
              beforeSend: function () {
                  $('.loader_div').show();
                  // jQuery('.submitCustomerFollowUp').prop('disabled',true);
              },
              complete: function () {
                  $('.loader_div').hide();
              },
              cache: false,
              dataType: "json",
              data : formData,
              success: function (response)
              {
                if (response.success) {
                  if (dataRef !="saveNopen" ) {
                      setTimeout(function(){  location.reload(); }, 1000);jQuery('#confirm-update-customer-follow-up-modal').modal('hide');
                  }
                  $('.receivedStatus'+response.dispatchRef).find('span').html( response.data.receivedStatus + ' Received' );
                  $('.returnStatus'+response.dispatchRef).find('span').html( response.data.returnStatus !='' ? response.data.returnStatus + ' Return' : 'No Return' );
                  $('.errorStatus'+response.dispatchRef).find('span').html( response.data.errorStatus !='' ? ' Error ' : 'No' + ' Error' );
                  iziToast.success({timeout: 3000,title: 'Success',message: response.success_message,position: 'bottomRight'});
                }else{
                  iziToast.error({timeout: 3000,title: 'Error',message: response.error_message,position: 'bottomRight'});
                }
              }
          });
          }
    })


    jQuery(document).on('keyup','.productionCount',function() {
      var value = 0;
      if (jQuery(this).val().length == 0 ) {
         jQuery(this).val(value);
      }
      value = parseInt(jQuery(this).val());
      jQuery(this).val(value);
    })


    jQuery(document).on('click','.updateSheet',function() {
      var sheetRef = jQuery.trim(jQuery(this).attr('data-ref'));
      var refName = jQuery.trim(jQuery(this).attr('data-name'));
      jQuery('#addNewLoadingSheet').find('#sheetRef').val(sheetRef);
      jQuery('#addNewLoadingSheet').find('#refName').val(refName);
      jQuery('#addNewLoadingSheet').modal('show');
    })

jQuery(document).on('click','.saveAndClose', function () {
    localStorage.setItem("modelhide", "modifyLoading-modal");
})
jQuery(document).on('click','#reset', function () {
    jQuery('.search_order').find(':input').not(':input[type="checkbox"]').val('');
    jQuery('.search_order').find('.orderStatus').prop('checked',false);
})

jQuery(document).on('click','#updatePassword',function() {
      var ref = jQuery(this).attr('data-ref');
      if (ref != '' || ref != undefined)
      {
        jQuery('#change-password-modal').modal('show').find('#change-userRef-password').val(ref);
      }else{
        iziToast.error({timeout: 3000,title: 'Error',message: 'Invalid Request',position: 'bottomRight'});
      }
})

$(document).on('keyup','.number',function(event) {
    var $this = $(this);

    if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
       ((event.which < 48 || event.which > 57) &&
       (event.which != 0 && event.which != 8))) {
           event.preventDefault();
    }

    var text = $(this).val();

    if (text > 100) {
      $(this).val(100)
    }

    if ((event.which == 46) && (text.indexOf('.') == -1)) {
        setTimeout(function() {
            if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
            }
        }, 1);
    }

    if ((text.indexOf('.') != -1) &&
        (text.substring(text.indexOf('.')).length > 2) &&
        (event.which != 0 && event.which != 8) &&
        ($(this)[0].selectionStart >= text.length - 2)) {
            event.preventDefault();
    }
});





// jQuery
jQuery(document).on('keyup', '.baseMatched' ,function(event){

    var $this = $(this);
    if($(this).val().length == 0){
         $this.val(parseInt(0));
    }else{
        $this.val(parseInt($this.val() ) );
    }

    $this.closest('tr').find('.dispachedQty').val(parseInt($this.val()));
    jQuery('.dispachedQty').trigger('keyup');
})

jQuery(document).on('keyup', '.baseUOMQty' ,function(event){

    var $this = $(this);
    if($(this).val().length == 0){
         $this.val(parseInt(0));
    }else{
        $this.val(parseInt($this.val() ) );
    }

})

});
