$(document).ready(function() {
//$(".ajax_form").submit(function(event){
$(document).on("submit", ".ajax_form_new", function (event)
{
$(".se-pre-con").fadeOut("slow");
var posturl=$(this).attr('action');
var callbackFunction=$(this).attr('data-callback_function');
if(callbackFunction)
{
	if(callbackForm() == false)
	{
		return false;
	}

}
var formid='#'+$(this).attr('id');
var isError = false;
$(formid+" .required").each(function ()
{
	var checkRequired = $(this).attr('checkRequired');
	if(checkRequired != 'no')
	{
		if ($.trim($(this).val()) == '') {
			isError = true;
			$(this).parents('.form-group').addClass('has-error');
			$(this).attr('placeholder', 'This Field is Required');
		}
		else{
			$(this).parents('.form-group').removeClass('has-error');
			$(this).attr('placeholder', '');
		}
	}

});
if(isError)
		return false;
$(this).ajaxSubmit({
				url: posturl,
				dataType: 'json',
				beforeSend: function(){
				 //$("input[type=submit]").attr("disabled", "disabled");
				 $(".se-pre-con").fadeOut("slow");
				 $('#wait').show();
				},
				success: function(response){
					$("input[type=submit]").removeAttr("disabled");
					$(".se-pre-con").fadeOut("slow");
					 $('#wait').hide();
					 if(response.messageNot)
						{
						   $(formid).find('.ajax_report').removeClass('alert-success').removeClass('alert-danger').fadeOut(100);
						}
					else
						{
							$(formid).find('.ajax_report').removeClass('alert-success').removeClass('alert-danger').fadeIn(200);
						}
						$.toast().reset('all');
					if(response.success)
					 {
							$.toast({
	 				           heading: 'Success',
	 				           text: response.success_message,
	 				           loader: true,
	 				           loaderBg: '#fff',
	 				           showHideTransition: 'fade',
	 				           icon: 'success',
	 				           hideAfter: 2000,
	 				           position : 'top-right'
	 				         });

						$(formid).find('.ajax_report').addClass('alert-success').children('.ajax_message').html(response.success_message);
					 }
					 else
					 {
					   $.toast({
				           heading: 'Error',
				           text: response.error_message,
				           loader: true,
				           loaderBg: '#fff',
				           showHideTransition: 'fade',
				           icon: 'error',
				           hideAfter: 2000,
				           position : 'top-right'
				         });

							$(formid).find('.ajax_report').addClass('alert-danger').children('.ajax_message').html(response.error_message);
					 }

					if(response.resetform)
					$(formid).resetForm();
					if(response.url)
					{
						if(response.delayTime)
							setTimeout(function() { window.location.href=response.url;}, response.delayTime);
						else
							window.location.href=response.url;
					}
					if(response.parentUrl)
					window.top.location.href = response.parentUrl;
					if(response.selfReload)
					{
						setTimeout(function() {
							window.location.reload();
						},2000);
					}

					if(response.parentReload)
					parent.location.reload();
					if(response.slideToThisDiv)
					slideToDiv(response.divId);
					if(response.slideToTop)
					slideToTop();
					if(response.slideToThisForm)
					slideToElement(formid);
					if(response.ajaxPageCallBack)
					{
						response.formid = formid;
						ajaxPageCallBack(response);
					}
					if(response.ajaxPageCallBackData)
					{
						response.formid = formid;
						ajaxPageCallBackData(response);
					}
					if(response.ajaxPageCallBackFunction)
					{
						response.formid = formid;
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackOperation' )
							ajaxPageCallBackOperation(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackPortResources' )
							ajaxPageCallBackPortResources(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackBussinessCustomer' )
							ajaxPageCallBackBussinessCustomer(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackAddPort' )
							ajaxPageCallBackAddPort(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackInventoryPort' )
							ajaxPageCallBackInventoryPort(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackInventoryBusinessCustomer' )
							ajaxPageCallBackInventoryBusinessCustomer(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackWorklog' )
							ajaxPageCallBackWorklog(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallDescription' )
							ajaxPageCallDescription(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackratesIncludes' )
							ajaxPageCallBackratesIncludes(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackLabourOperation' )
							ajaxPageCallBackLabourOperation(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackManning' )
							ajaxPageCallBackManning(response);
						if( response.ajaxPageCallBackFunctionName == 'ajaxPageCallBackProjectgang' )
							ajaxPageCallBackProjectgang(response);

					}
					if(response.modal)
					{
						setTimeout(function() {
							$('#'+response.modalId).modal('hide');
						},3000);
					}

					if(response.popup)
					{
						parent.$.fancybox.update();
					}
				setTimeout(function() {
						$(formid).find('.ajax_report').fadeOut(2000);
						if(response.popup)
						{
							parent.$.fancybox.update();
						}
					}, 7000);
				setTimeout(function() {
						if(response.popup)
						{
							parent.$.fancybox.update();
						}
					}, 8100);
				},
				error:function(response){alert( 'Connection error')}
			});
return false;
});
});
function slideToElement(element)
{
	$("html, body").animate({scrollTop: $(element).offset().top-150 }, 1000);
}
function slideToDiv(element)
{
	$("html, body").animate({scrollTop: $(element).offset().top-50 }, 1000);
}
function slideToTop()
{
	$("html, body").animate({scrollTop: 50}, 1000);

}
$(document).ready(function(e) {

    $(document).on("click", ".alert-message .close", function (event) {
        $(this).closest(".alert-message").hide();
    });
});
