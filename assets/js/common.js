$(document).ready(function ()
{
	var checkStatus = [];
	var delayTime = 3000;
	var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	$(document).on('keypress keyup', '#searchKey,#fromDate,#toDate', function(event)
	{
		var dataContainer = $('#pageType').attr('data-container');
		if(event.which == 13)
		{
			if( dataContainer != '' && dataContainer != undefined )
			var searchKey 	  = $(dataContainer).find('#tableSearchBtn').trigger('click');
			else
			$('#tableSearchBtn').trigger('click');
		}
		else if(event.which == 8)
		{
			var searchKey = $(this).val();
			if( searchKey == '' )
			{
				if( dataContainer != '' && dataContainer != undefined )
				var searchKey 	  = $(dataContainer).find('#tableSearchBtn').trigger('click');
				else
				$('#tableSearchBtn').trigger('click');
			}
		}
	});
	$(document).on('change', '#statusBox', function(event){
			$('#tableSearchBtn').trigger('click');
	})
	$(document).on('click', '.filterCheckBox', function(event){
			$('.filterCheckBox').not(this).prop('checked', false);
			if (!$(document).find(".filterCheckBox").is(':checked')) {
				$('.filterCheckBox:first').prop('checked', true);
			}
			$('#tableSearchBtn').trigger('click');
	})

	$(document).on('click', '#tableSearchBtn', function(event)
	{
		var dataContainer  = $('#pageType').attr('data-container');
		var url		  	  	 = $(this).attr('data-url');
		if( dataContainer != '' && dataContainer != undefined )
		var searchKey 	   = $(dataContainer).find("#searchKey").val();
		else
		var searchKey 	   = $('#searchKey').val();
		var statusBox 	   = $(document).find(".filterCheckBox").is(':checked');
		if (statusBox)
				statusBox 		 = $(document).find(".filterCheckBox:checked").val();
		 else
			  statusBox = '';

		var fromDate   = $('#fromDate').val();
		var toDate 	   = $('#toDate').val();

		searchKey 	  = $.trim(searchKey);
		fromDate 	  = $.trim(fromDate);
		toDate 	  	  = $.trim(toDate);
		statusBox 	  	  = $.trim(statusBox);

		if( url != '')
		{
			$.ajax({
				type	 : "POST",
				dataType : "json",
				data	 : {'searchKey':searchKey,'page':1,'fromDate':fromDate,'toDate':toDate,'statusBox':statusBox},
				url		 : url,
				beforeSend  : function () {
					$(".loader_div").show();
				},
				complete: function () {
					$(".loader_div").hide();
				},
				success: function(response)
				{
					if( dataContainer != '' && dataContainer != undefined )
					$(dataContainer).find("#tableData").html(response.html);
					else
					$("#tableData").html(response.html);
				},
				error:function(response){
					iziToast.error({
						title: 'Error',
						message: 'Something is missing. Please try again!',
					});
				}
			});
		}
	});

	$(document).on('click', '.ajax_pagingUL a', function(event)
	{
		event.preventDefault();
		var dataContainer = $('#pageType').attr('data-container');
		var searchKey 	  = jQuery('#searchKey').val();
		if( $(this).parent('li').hasClass('active') )
		return true;
		var url  = $(this).attr("href");
		var page = $(this).attr("data-ci-pagination-page");
		if (searchKey == undefined && jQuery(document).find('#orderSearchData').val() != undefined) {
			var globalSearch = true;
			var input_data_json = jQuery(document).find('#orderSearchData').val();
		}else {
			var globalSearch = false;
			searchKey 	      = $.trim(searchKey);
			var statusBox 	   = $(document).find(".filterCheckBox").is(':checked');
			if (statusBox)
					statusBox 		 = $(document).find(".filterCheckBox:checked").val();
			 else
				  statusBox = '';

			var input_data_json = JSON.stringify({'searchKey':searchKey,'page':page,'statusBox':statusBox});
		}
		input_data_json = JSON.parse(input_data_json);
		// replace old page no with new page no.
		input_data_json.page = page;
		$.ajax({
			type	 : "POST",
			dataType : "json",
			data	 : input_data_json,
			url		 : url,
			beforeSend  : function () {
				$(".loader_div").show();
			},
			complete: function () {
				$(".loader_div").hide();
			},
			success: function(response)
			{
				if (globalSearch) {
					jQuery('#orderSearchRecords').html(response.html);
				}else{
					if( dataContainer != '' && dataContainer != undefined )
					$(dataContainer).find("#tableData").html(response.html);
					else
					$("#tableData").html(response.html);
				}
			},
			error:function(response){
				iziToast.error({
					title: 'Error',
					message: 'Something is missing. Please try again!',
				});
			}
		});
		return false;
	});
	$(document).on('click', '.ajax_pagingsearc4Html a', function(event)
	{
		event.preventDefault();
		var dataContainer = $('#pageType').attr('data-container');
		var searchKey 	  = jQuery('#searchKey').val();
		searchKey 	      = $.trim(searchKey);
		if( $(this).parent('li').hasClass('active') )
		return true;
		var url  = $(this).attr("href");
		var page = $(this).attr("data-ci-pagination-page");
		$.ajax({
			type	 : "POST",
			dataType : "html",
			data	 : {'searchKey':searchKey,'page':page},
			url		 : url,
			beforeSend  : function () {
				$(".loader_div").show();
			},
			complete: function () {
				$(".loader_div").hide();
			},
			success: function(response)
			{
				if( dataContainer != '' && dataContainer != undefined )
				$(dataContainer).find("#tableData").html(response);
				else
				$("#tableData").html(response);
			},
			error:function(response){
				iziToast.error({
					title: 'Error',
					message: 'Something is missing. Please try again!',
				});
			}
		});
		return false;
	});

	$(document).on('click', '.deleteRecord', function(event)
	{
		var name = $(this).attr('data-name');
		var ref  = $(this).attr('data-ref');
		var type = $(this).attr('data-type');
		if (type == 'users') {
			jQuery('.userLine').removeClass('hide');
			jQuery('.orderLine').removeClass('hide').addClass('hide');
		}else{
			jQuery('.orderLine').removeClass('hide');
			jQuery('.userLine').removeClass('hide').addClass('hide');
		}
		localStorage.setItem('DeleteRecordLabel',name);
		localStorage.setItem('DeleteRecordRef',ref);
		localStorage.setItem('DeleteRecordType',type);
		if (name == 'this comment') {
			$('.orderLine').html('Are you sure you want to delete this comment?');
		}
		$('#confirm-delete-modal').modal('show');
	});


	$(document).on('click', '.deleteRecordBtn', function(event)
	{
		iziToast.destroy();
		var DeleteRecordLabel 	= localStorage.getItem('DeleteRecordLabel');
		var DeleteRecordRef 	= localStorage.getItem('DeleteRecordRef');
		var DeleteRecordType 	= localStorage.getItem('DeleteRecordType');
		if( DeleteRecordRef == '' || DeleteRecordType == '' )
		{
			iziToast.error({
				title: 'Error',
				message: 'Something is missing. Please try again!',
			});

		}
		else
		{
			$.ajax({
				url         : site_url+'delete-record',
				type        : "post",
				data        : { 'type':DeleteRecordType,'ref':DeleteRecordRef },
				dataType    : "json",
				beforeSend  : function ()
				{
					$(".loader_div").show();
				},
				complete: function ()
				{
					$(".loader_div").hide();
				},
				success: function (response)
				{
					$(".loader_div").hide();
					if (response.success)
					{

						iziToast.success({
							title: 'Success',
							message: response.success_message,
						});
						$('#'+DeleteRecordType+'_'+DeleteRecordRef).remove();
						var tableTrCount = $('#tableData').find('table tbody tr').length;
						if( tableTrCount <= 0 )
						{
							var page = $('#ajax_pagingsearc1').find('li.active').find('a').text();
							if( page == 1 )
							$('#ajax_pagingsearc1').find('li').eq(1).find('a').trigger('click');
							else if( page > 1 )
							{
								page = parseInt(page) - 1 ;
								$('#ajax_pagingsearc1').find('li').eq(page).find('a').trigger('click');
							}
						}
						$('#confirm-delete-modal').modal('hide');
						localStorage.setItem('DeleteRecordLabel','');
						localStorage.setItem('DeleteRecordRef','');
						localStorage.setItem('DeleteRecordType','');
					}
					else
					{
						iziToast.error({
							title: 'Error',
							message: response.error_message,
						});
						$('#confirm-delete-modal').modal('hide');
					}
					if(response.ajaxPageCallBack)
					{
						ajaxPageCallBack(response);
					}
					if(response.url)
					{
						if(response.delayTime)
						setTimeout(function() { window.location.href=response.url;}, response.delayTime);
						else
						window.location.href=response.url;
					}
				},
				error:function(response){
					iziToast.error({
						title: 'Error',
						message: 'Something went wrong cannot connect to server. please try again.',
					});
				}
			});
		}
	});


	$(document).on('click', '.updateStatus', function(event)
	{
		var name 		= $(this).attr('data-name');
		var ref  		= $(this).attr('data-ref');
		var type 		= $(this).attr('data-type');
		var status 	= $(this).attr('data-status');
		var dataNoti 	= $(this).attr('data-noti');

		localStorage.setItem('RecordLabel',name);
		localStorage.setItem('RecordStatus',status);
		localStorage.setItem('RecordRef',ref);
		localStorage.setItem('RecordType',type);
		if (dataNoti == undefined) {
			var dataNoti 	= '';
		}
		localStorage.setItem('dataNoti',dataNoti);
		$('#confirm-status-update-modal').modal('show');
		if (dataNoti != '' && dataNoti != undefined) {
			if( status == 1)
			var status = 'as Normal';
			else
			var status = 'as Starred';
			$('#confirm-status-update-modal').find('.modal-body').find('.statusLabel').html('<strong>'+name+'</strong> '+status);
		}else{
			if( status == 1)
			var status = 'Inactive';
			else
			var status = 'Active';
			$('#confirm-status-update-modal').find('.modal-body').find('.statusLabel').html('<strong>'+name+'</strong> '+status);
		}
	});

	$(document).on('click', '.updateRecordStatusBtn', function(event)
	{
		iziToast.destroy();
		var name 			= localStorage.getItem('RecordLabel');
		var status 		= localStorage.getItem('RecordStatus');
		var ref  			= localStorage.getItem('RecordRef');
		var type 			= localStorage.getItem('RecordType');
		var dataNoti 	= localStorage.getItem('dataNoti');
		if( ref == '' || type == '' || status == '' )
		{
			iziToast.error({
				title: 'Error',
				message: 'Something is missing. Please try again!',
			});
		}
		else
		{
			$.ajax({
				url         : site_url+'update-status',
				type        : "post",
				data        : { 'type':type,'ref':ref, 'status':status },
				dataType    : "json",
				beforeSend  : function ()
				{
					$(".loader_div").show();
				},
				complete: function ()
				{
					$(".loader_div").hide();
				},
				success: function (response)
				{
					$(".loader_div").hide();
					if (response.success)
					{
						iziToast.success({
							timeout: delayTime,
							title: 'Success',
							message: response.success_message,
						});
						if (dataNoti !='' && dataNoti != undefined) {
							if( response.status == 1 )
							{
								$('#'+type+'_'+ref).find('.updateNotification').html('<i class="fa fa-star pull-right"></i>').attr('data-status',response.status);
							}
							else
							{
								$('#'+type+'_'+ref).find('.updateNotification').html('<i class="fa fa-star-o pull-right"></i>').attr('data-status',response.status);
							}
						}
						else{
							if( response.status == 1 )
							{
								$('#'+type+'_'+ref).find('.statusTd').html('<span class="label label-success">Active</span>');
								$('#'+type+'_'+ref).find('.updateStatus').html('Make Inactive').attr('data-status',response.status);
							}
							else
							{
								$('#'+type+'_'+ref).find('.statusTd').html('<span class="label label-warning">Inactive</span>');
								$('#'+type+'_'+ref).find('.updateStatus').html('Make Active').attr('data-status',response.status);
							}
						}


						$('#confirm-status-update-modal').modal('hide');

						localStorage.setItem('RecordLabel','');
						localStorage.setItem('RecordStatus','');
						localStorage.setItem('RecordRef','');
						localStorage.setItem('RecordType','');
						localStorage.setItem('dataNoti','');
					}
					else
					{
						iziToast.error()({
							timeout: delayTime,
							title: 'Error',
							message: response.error_message,
						});

						$('#confirm-status-update-modal').modal('hide');
					}
					if(response.ajaxPageCallBack)
					{
						ajaxPageCallBack(response);
					}
					if(response.url)
					{
						if(response.delayTime)
						setTimeout(function() { window.location.href=response.url;}, response.delayTime);
						else
						window.location.href=response.url;
					}
				},
				error:function(response){
					iziToast.error()({
						timeout: 4000,
						title: 'Error',
						message: 'Something went wrong please try again.',
					});
				}
			});
		}
	});
	jQuery('.modal').on('hidden.bs.modal', function (e) {

					jQuery('.form-control').removeClass('has-error');
					jQuery('.form-group.has-error').removeClass('has-error');
					jQuery('label.has-error').remove();
					var  id  = jQuery(this).attr('id');
					//console.log(id);
					if(id == 'AddItem'){
						jQuery('.hasRemovedElement').remove();
						jQuery('.variationId').remove();
						jQuery('.atttibutes').html('');
					}
					jQuery('.shipingMessageElement').hide();
					jQuery('#'+id).find('input[type="text"]').val('');
					jQuery('#'+id).find('input[type="checkbox"]').prop('checked',false);
					jQuery('#'+id).find('input[type="hidden"]').val('');
					jQuery('#'+id).find('input[type="password"]').val('');
					jQuery('#'+id).find('input[type="checkbox"]').prop('checked', false);
					jQuery('#'+id).find('textarea').val('');
					jQuery('#'+id).find('input[type="button"]').removeAttr('disabled');
					jQuery('#'+id).find('input[type="submit"]').removeAttr('disabled');
					jQuery('#'+id).find('button[type="button"]').removeAttr('disabled');
					jQuery('#'+id).find('button[type="submit"]').removeAttr('disabled');
					jQuery('#'+id).find('select').val('');
					jQuery('#'+id).find('#subCat').find('option').remove().end().append('<option value="">Select Category</option>').val('')
	});


	jQuery(document).on('click','.updateCategory',function(){
		var catRef  			=  jQuery.trim(jQuery(this).attr('data-ref'));
		var categoryName  =  jQuery.trim(jQuery(this).attr('data-name'));
		jQuery('#catRef').val(catRef);
		jQuery('#categoryName').val(categoryName);
		jQuery('#AddCategory').modal('show');
	})
	jQuery(document).on('click','.updateBlockTypeMethod',function(){
		var id  			=  jQuery.trim(jQuery(this).attr('data-ref'));
		var blockType  =  jQuery.trim(jQuery(this).attr('data-name'));
		jQuery('#id').val(id);
		jQuery('#blockType').val(blockType);
		jQuery('#blockTypeModal').modal('show');
	})
	jQuery(document).on('click','.updateDeliveryMethod',function(){
		var methodRef  			=  jQuery.trim(jQuery(this).attr('data-ref'));
		var methodName  		=  jQuery.trim(jQuery(this).attr('data-name'));
		var dataArea  			=  jQuery.trim(jQuery(this).attr('data-area'));
		jQuery('#deliveryMethodRef').val(methodRef);
		jQuery('#area').val(dataArea);
		jQuery('#methodName').val(methodName);
		jQuery('#AddDeliveryMethod').modal('show');
	})

	jQuery(document).on('click','.updatePricingMethod',function(){
		var pricingRef  				=  jQuery.trim(jQuery(this).attr('data-ref'));
		var payementMethod  		=  jQuery.trim(jQuery(this).attr('data-name'));
		jQuery('#pricingRef').val(pricingRef);
		jQuery('#payementMethod').val(payementMethod);
		jQuery('#AddPricingMethod').modal('show');
	})
	var subCat = '';

	function tags() {
		// var sizeTags = [];
		// sizeTags['height']  = [];
		// sizeTags['width']   = [];
		// sizeTags['len']  = [];
		// jQuery(document).find('.diamentionsTagsLength').each(function (index) {
		//
		// 		var length = (jQuery(this).val() != '' ) ? jQuery(this).val() : 0;
		// 		// sizeTags['length'][index] = length;
		// 		sizeTags['len'].push(length);
		// })
		// jQuery(document).find('.diamentionsTagsWidth').each(function (index) {
		// 		var width  = (jQuery(this).val() != '' )  ? jQuery(this).val() : 0;
		// 		// sizeTags['width'][index] = width;
		// 		sizeTags['width'].push(width);
		//
		// })
		// jQuery(document).find('.diamentionsTagsHeight').each(function (index) {
		// 		var height = (jQuery(this).val() != '' ) ? jQuery(this).val() : 0;;
		// 		// sizeTags['height'][index] = height;
		// 		sizeTags['height'].push(height);
		//
		//
		// })
		//
		// var Tags = '<div class="clearfix col-md-12">';
		// for (var i = 0; i < sizeTags['height'].length; i++) {
		// 	var length =  sizeTags['len'][i];
		// 	var width  =  sizeTags['width'][i];
		// 	var height =  sizeTags['height'][i];
		// 	Tags +='<span class="label label-success">'+height+'X'+width+'X'+length+'</span> &nbsp;&nbsp';
		// }
		// Tags += '</div>';
		// jQuery('#add-item-form').append(Tags);
	}

	jQuery(document).on('click','.updateItem',function(){
		var productRef  			=  jQuery.trim(jQuery(this).attr('data-ref'));
		if (productRef !='') {
			$.ajax({
				type: "POST",
				url: site_url + "getItemDetails",
				data: {
					'productRef': $.trim(productRef)
				},
				dataType: 'json',
				success: function (msg) {

					if (!msg.success) {
						iziToast.error({
							timeout : 4000,
							title		: 'Error',
							message	: 'Something went wrong please try again.',
						});

					} else {
						jQuery('#AddItem').find('.modal-body').html(msg.html);
						$.getScript(site_url+"assets/js/form-validate.js");

						checkStatus=[];
						jQuery('.check-attr').each(function () {
							if(jQuery(this).is(':checked')){
								checkStatus[jQuery(this).attr('attrId')] = 'checked';
							}else{
								checkStatus[jQuery(this).attr('attrId')] = '';
							}
						});

						addRows();
						jQuery('.selectUOM').trigger('click');
					}
				}
			});
		}
		jQuery('#AddItem').modal('show');
	})

	jQuery(document).on('change click blur','#catRef',function() {

		if(jQuery.trim(jQuery(this).val()) !=''){
			$.ajax({
				type: "POST",
				url: site_url + "getSubCategories",
				data: {
					'parentCatRef': $.trim(jQuery(this).val())
				},
				dataType: 'html',
				success: function (msg) {
					jQuery('#subCat').html(msg);
					jQuery('#subCat').val(subCat)
				}
			});
		}
	})
	jQuery(document).on('click','.changeOrderStatus', function(){
		var text	 				  =  jQuery(this).text();
		var orderRef 				=  jQuery(this).attr('data-ref');
		var dataTo 					=  jQuery(this).attr('data-to');
		var dataPipline 		=  jQuery(this).attr('data-pipline');
		var dataProduction 	=  jQuery(this).attr('data-production');
		var orderNo 				=  jQuery(this).attr('data-orderNo');
		var salesRef 				=  jQuery(this).attr('data-sales');
		var dataApprove 	  =  jQuery(this).attr('data-approve');
		var reload 	  			=  jQuery(this).attr('data-reload');

		var itemRefIds			=  jQuery(this).attr('data-itemrefids');
		var estDates 	  		=  jQuery(this).attr('data-estdates');

		if (reload === undefined) {
			reload = false;
		}else {
			reload = true;
		}
		if (dataTo == 're-assigned') {
			jQuery(this).addClass('reAssignHide');
		}
		localStorage.setItem('orderRef',orderRef);
		localStorage.setItem('dataTo',dataTo);
		localStorage.setItem('dataPipline',dataPipline);
		localStorage.setItem('dataProduction',dataProduction);
		localStorage.setItem('orderNo',orderNo);
		localStorage.setItem('text',text);
		localStorage.setItem('salesRef',salesRef);
		localStorage.setItem('dataApprove',dataApprove);
		localStorage.setItem('reload',reload);
		localStorage.setItem('itemRefIds',itemRefIds);
		localStorage.setItem('estDates',estDates);

		if (dataTo == 'managerApprove') {
			jQuery('#approvedBy').parent().show();
		}else{
			jQuery('#approvedBy').parent().hide();
		}
		if (salesRef == '' || salesRef === undefined) {
			jQuery('#salesRefSel').parent().hide();
		}else {
			jQuery('#salesRefSel').parent().show();
			jQuery('#salesRefSel').val(salesRef).attr('data-ref',salesRef);
		}

		$('#confirm-order-status-update-modal').modal('show');
		if( status == 1)
		var status = 'Inactive';
		else
		var status = 'Active';
		$('#confirm-order-status-update-modal').find('.modal-body').find('.statusLabel').html('<strong>Order No. '+orderNo+'</strong> '+text);

	});
	var reassingAction = false;
	jQuery(document).on('click','.reassingProcess',function () {
		reassingAction = true;
		jQuery('.reassingProcessClose').click();
		jQuery('.updateOrderStatusData').click();

	});
	// function to update order sttaus and move order to next pipline
	jQuery(document).on('click','.updateOrderStatusData', function(){
		var text	 					=  localStorage.getItem('text');
		var orderRef 				=	 localStorage.getItem('orderRef');;
		var dataTo 					=  localStorage.getItem('dataTo');;
		var dataPipline 		=  localStorage.getItem('dataPipline');;
		var dataProduction 	=  localStorage.getItem('dataProduction');;
		var orderNo 				=  localStorage.getItem('orderNo');;
		var salesRef 				=  localStorage.getItem('salesRef');
		var dataApprove 		=  localStorage.getItem('dataApprove');
		var reload 					=  localStorage.getItem('reload');
		var itemRefIds 			=  localStorage.getItem('itemRefIds');
		var estDates 				=  localStorage.getItem('estDates');


		// console.log(text);		// console.log(orderRef);		// console.log(dataTo);		// console.log(dataPipline);		// console.log(dataProduction);		// console.log(orderNo);
		if( orderRef == '' || dataTo == '' || dataPipline == '' || dataProduction =='' || orderNo == '')
		{
			iziToast.error({
				title: 'Error',
				message: 'Something is missing. Please try again!',
			});
		}else {
			var comment = $.trim(jQuery('#comment').val());
			var approvedBy = $.trim(jQuery('#approvedBy').val());
			if ($.trim(jQuery('#salesRefSel').val()) != '') {
				var dataSale = $.trim(jQuery('#salesRefSel').val())
			}else {
				var dataSale = '';
			}

			if (dataTo == 're-assigned') {
					if (dataSale != jQuery('#salesRefSel').attr('data-ref')) {
							if (!reassingAction) {
								jQuery('#confirm-action').modal();
								return false
							}
					}else{
							reassingAction = false;
					}
			}
			$.ajax({
				url         : site_url+'chage-order-status',
				type        : "post",
				data        : {'reload':reload,'dataApprove':dataApprove,'approvedBy':approvedBy,'salesRef':dataSale,'orderRef':orderRef,'dataTo':dataTo, 'dataPipline':dataPipline ,'dataProduction' : dataProduction,'comment':comment , 'itemRefIds':itemRefIds,'estDates':estDates},
				dataType    : "json",
				beforeSend  : function ()
				{
					$(".loader_div").show();
				},
				complete: function ()
				{
					$(".loader_div").hide();
				},
				success: function (response)
				{
					$(".loader_div").hide();
					if (response.success)
					{
						iziToast.success({
							timeout: delayTime,
							title: 'Success',
							message: response.success_message,
						});

						jQuery('#orders_'+orderRef).find('.tdStatus').html(response.status);
						if (dataTo == 'managerApprove') {
							jQuery('#orders_'+orderRef).addClass('danger');
							jQuery('#orders_'+orderRef).find('a[data-to="managerApprove"]').hide();
						}
						else if(dataTo == 're-assigned')
						{
							jQuery('#orders_'+orderRef).focus();
							jQuery('#orders_'+orderRef).find('.reAssignHide').hide();
						}
						else
						{
							jQuery('#orders_'+orderRef).hide();
						}
						if (response.reload) {
							setTimeout(function(){  location.reload(); }, delayTime)
						}
						$('#confirm-order-status-update-modal').modal('hide');

					}
					else
					{

						iziToast.error()({
							timeout: delayTime,
							title: 'Error',
							message: response.error_message,
						});
						$('#confirm-order-status-update-modal').modal('hide');
					}
				},
				error:function(response){
					iziToast.error({
						timeout: 4000,
						title: 'Error',
						message: 'Something went wrong please try again.',
					});
				}
			});
		}

	})

	jQuery(document).on('click','.editComment',function () {

		jQuery('.commentReset').remove();
		jQuery('#comment-order').find('input[name="commentRef"]').remove();
		var dataTo 		 = jQuery(this).attr('data-ref');
		var dataUpdate = $.trim(jQuery(this).closest('.realtive').text());
		if( dataUpdate == '' || dataTo == '')
		{
			iziToast.error({
				title: 'Error',
				message: 'Something is missing. Please try again!',
			});
		}
		else {
			jQuery('#comment-order').append('<input type="hidden" name="commentRef" value="'+dataTo+'">');
			jQuery('#comment-order').find('button[type="submit"]').before('<button type="button" class="commentReset">Add New</button>');
			jQuery('#commentArea').val(dataUpdate);
			$('html, body').animate({
				scrollTop: $("#comment-order").offset().top + 10
			}, 2000);
		}

	})

	jQuery(document).on('click','.commentReset',function(){
		$(this).hide();
		$(this).closest('form').find('input,textarea').filter(':input').not('input[name^="orderRef"]').val('');
		// $(this).closest('form').find("input[type=hidden], textarea").val("");
	})


	$("#customerSearch").autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {

			jQuery.ajax({
				url:      site_url+"searchCustomer",
				data:    {searchKey : request.term},
				dataType: "json",
				success: function(data)
				{

					response(data);
				}
			})
		},
		select:  function(e, ui)
		{
			var countryId = ui.item.id;
			$("#customerRef").val(countryId);
			$("#customerSearch").val(ui.item.value);
			jQuery('.searchNotification').trigger('change');
		},
		focus:function(e,ui) {
			return false;
		}
	});
	$("#salesmanSearch").autocomplete({
		minLength: 1,
		delay : 400,
		source: function(request, response) {

			jQuery.ajax({
				url:      site_url+"searchSalesman",
				data:    {searchKey : request.term},
				dataType: "json",
				success: function(data)
				{

					response(data);
				}
			})
		},
		select:  function(e, ui)
		{
			var countryId = ui.item.id;
			$("#salesRef").val(countryId);
		},
		focus:function(e,ui) {
			return false;
		}
	});

	jQuery(document).on('click','#searchOrdersGlobal',function () {
		var orderId 	        = $.trim($('#orderId').val());
		var orderPipline 	    = $.trim($('#orderPipline').val());
		var customerRef 	    = $.trim($('#customerRef').val());
		var salesRef 	      	= $.trim($('#salesRef').val());
		var date 	            = $.trim($('#date').val());
		if (orderId == '' &&  orderPipline == '' && customerRef == '' && salesRef == '' && date == '' ) {
			iziToast.destroy();
			iziToast.info({
				title: 'info',
				message: 'Please select at least one filter and try again!',
			});
		}
		else
		{
			var sList 				  = "";
			var anyBoxesChecked = '';
			if (orderPipline !='' && orderPipline != 'cancelled') {
				if ($(".orderStatus:checkbox:checked").length == 0){
					iziToast.destroy();
					iziToast.info({
						title: 'info',
						message: 'Please select one order status!',
					});
					return false;
				}
			}else{
				if (orderPipline == 'cancelled') {
					orderPipline = null;
					anyBoxesChecked  = 'cancelled';
				}
			}
			var Closed = false;
			jQuery('.orderStatus').each(function(){
				if ($(this).is(":checked"))
				{
					if ($(this).val() == 'Closed') {
						Closed = true;
					}
					anyBoxesChecked += $(this).val()+',';
				}
			});
			var data = {'orderId':orderId,'page':1,'orderPipline':orderPipline,'customerRef':customerRef,'salesRef':salesRef,'orderStatus':anyBoxesChecked,'date':date};
			jQuery('#orderSearchData').remove();
			jQuery('#main-content').append("<input type='hidden' id='orderSearchData' value='"+JSON.stringify(data)+"'>");
			$.ajax({
				type	 : "POST",
				dataType : "json",
				data	 : {'orderId':orderId,'page':1,'orderPipline':orderPipline,'customerRef':customerRef,'salesRef':salesRef,'orderStatus':anyBoxesChecked,'date':date},
				url		 : site_url+'orderSearch',
				beforeSend  : function () {
					$(".loader_div").show();
				},
				complete: function () {
					$(".loader_div").hide();
				},
				success: function(response)
				{

					$('html, body').animate({
						scrollTop: $("#tableData").offset().top - 70
					}, 1000);

					$('#tableData').hide();
					$("#orderSearchRecords").show().html(response.html);

					if (Closed) {
							jQuery('.customerFollupField').show();
					}else {
							jQuery('.customerFollupField').hide();
					}
				},
				error:function(response){
					iziToast.error({
						title: 'Error',
						message: 'Something is missing. Please try again!',
					});
				}
			});
		}
	})

	jQuery(document).on('click change', '#orderPipline', function(){
		jQuery('.checkbox_panel').addClass('hide');
		jQuery('.orderStatus').prop('checked',false);
		var option = $('option:selected', this).attr('data-target');
		jQuery(option).removeClass('hide');


	})

	$('.datepicker').datepicker({
		inline: true,
		//nextText: '&rarr;',
		//prevText: '&larr;',
		showOtherMonths: true,
		dateFormat: 'dd-mm-yy',
		dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		//showOn: "button",
		//buttonImage: "img/calendar-blue.png",
		//buttonImageOnly: true,
	});



jQuery('.noAccess').on('click',function (event) {
	iziToast.destroy();
	iziToast.warning({
		title: 'Warning',
		position: 'bottomCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
		message: 'you have no permission to perform this action!',
		pauseOnHover: false,
		animateInside: true,
		transitionIn: 'fadeInUp',
		transitionOut: 'fadeOutDown',
	});
})

$("#searchItemName").autocomplete({
	minLength: 1,
	delay : 400,
	source: function(request, response) {
		jQuery('#itemRefId').val('');
		jQuery.ajax({
			url:      site_url+"searchItemsbyName",
			data:    {searchKey : request.term},
			dataType: "json",
			success: function(data){
				response(data);
			}
		})
	},
	select:  function(e, ui)
	{
		$('.loader_div').show();
		var itemRefId = ui.item.id;
		var item = ui.item.value;
		var charge = ui.item.charge;

		var url = site_url+'update-transport-charges/'+itemRefId;
		$.ajax({
			type: "POST",
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
				jQuery('#add-transport').html(response);
				jQuery('#add-transport-form').validate();
				jQuery('#add-transport-form').valid();
				var validator = $("#add-transport-form").validate();
				setTimeout(function()
				{
				jQuery('.toFixed').each(function () {
					var num = parseFloat($(this).val());
					var cleanNum = num.toFixed(2);
					if (!isNaN(cleanNum)) {
						$(this).val(cleanNum);
					}else{
						$(this).val(0.00);
					}
				})
			},100);
			$("#itemRefId").val(itemRefId);
			$("#searchItemName").val(item);
			},
			error: function ()
			{
				alert('Error while request..');
			}
		});
	},
	focus:function(e,ui) {
		return false;
	}
});

jQuery('.updateTransport').on('click',function(e){
	e.preventDefault();
	var url     = jQuery(this).attr('data-url');
	var dataRef = jQuery(this).attr('data-ref');
	if (dataRef == '' && url == '') {
		iziToast.destroy();
		iziToast.error({
			title: 'error',
			position: 'bottomCenter', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
			message: 'Something Went wrong please try again!',
			pauseOnHover: false,
			animateInside: true,
			transitionIn: 'fadeInUp',
			transitionOut: 'fadeOutDown',
		});
	}else{

		var url = jQuery.trim(jQuery(this).attr('data-url'));
		$.ajax({
			type: "POST",
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
				jQuery('.backBtn').show();
				jQuery('.detailDrow').html(response);
				jQuery('.defaultRow').hide();
				jQuery('#add-transport-form').validate();
				jQuery('#add-transport-form').valid();
				var validator = $("#add-transport-form").validate();
				jQuery('.toFixed').trigger('blur');

			},
			error: function ()
			{
				alert('Error while request..');
			}
		});
	}
})

jQuery(document).on('click change', '.selectUOM', function (e) {
	e.preventDefault();
	var selectedText = $.trim( $(this).find("option:selected").text() ) ;
	selectedText = selectedText.toLowerCase();
	var baseUOM = jQuery.trim( jQuery('#baseUOM').val() );
	var saleUOM = jQuery.trim(jQuery('#saleUOM').val() );
	if (baseUOM != saleUOM && saleUOM !='') { jQuery('#saleUOM').parents('.col-sm-6').find('.preventDefault').show();}else{jQuery('#saleUOM').parents('.col-sm-6').find('.preventDefault').hide();}
})

jQuery(document).on('click', '#saveUOM', function() {
  jQuery('.popover .remove-label').html('');
  jQuery('.form-group').removeClass('has-error');
	var target = jQuery(this).attr('data-ref');
  var unitName = $.trim(jQuery('.popover .unitName').val());
  if (unitName != "") {
    $.ajax({
      type: "POST",
      url: site_url + 'addUnitOfMeasurement',
      data: {
        'unitName': unitName,
        'unitRef': ''
      },
      dataType: "json",
      success: function(response) {
        var delayTime = 3000;
        if (response.success) {

          iziToast.destroy();
          iziToast.success({
            timeout: 2500,
            title: 'Success',
            message: response.success_message,
            position: 'bottomRight',
          });
          $('.selectUOM').popover('hide');
          var toAppend = '';
          toAppend += '<option value="' + response.unitRef + '">' + response.unitName + '</option>';
          $('.selectUOM').append(toAppend);
          $('#'+target).val(response.unitRef);

        } else {
          if (response.formErrors) {
            $.each(response.errors, function(index, value) {
              $("input[name='" + index + "']").parents('.form-group').addClass('has-error');
              $("input[name='" + index + "']").after('<label id="' + index + '-error" class="has-error remove-label" for="' + index + '">' + value + '</label>');
            });
          } else {
            iziToast.destroy();
            iziToast.error({
              timeout: 2500,
              title: 'Success',
              message: response.error_message,
              position: 'bottomRight',
            });
          }
        }
      }
    });
  } else {
    if (unitName == '') {
      $(".popover .unitName").parents('.form-group').addClass('has-error');
      jQuery('.popover .unitName').after("<label class='remove-label'>This field is required</label>");
    } else {
      jQuery('.popover .unitName').css('border', '1px solid #ccc');
    }
  }
});
jQuery(document).on('click', '#saveblockType', function() {
  jQuery('.popover .remove-label').html('');
  jQuery('.form-group').removeClass('has-error');

  var block_type = $.trim(jQuery('.popover #block_type').val());

  if (block_type != "") {
    $.ajax({
      type: "POST",
      url: site_url + 'addUpdateblockTypes',
      data: {'blockType': block_type,'id': ''},
      dataType: "json",
      success: function(response) {
        var delayTime = 3000;
        if (response.success) {
          iziToast.destroy();
          iziToast.success({timeout: 2500,title: 'Success',message: response.success_message,position: 'bottomRight',});
          $('#blockType').popover('hide');
          var toAppend = '';
          toAppend += '<option value="' + response.data.id + '" selected>' + response.data.blockType + '</option>';
          $('#blockType').append(toAppend);
        } else {
          if (response.formErrors) {
            $.each(response.errors, function(index, value) {
              $("input[name='" + index + "']").parents('.form-group').addClass('has-error');
              $("input[name='" + index + "']").after('<label id="' + index + '-error" class="has-error remove-label" for="' + index + '">' + value + '</label>');
            });
          } else {
            iziToast.destroy();
            iziToast.error({timeout: 2500,title: 'Success',message: response.error_message,position: 'bottomRight',});
          }
        }
      }
    });
  } else {
    if ($.trim(block_type) == '') {
      $(".popover #block_type").parents('.form-group').addClass('has-error');
      jQuery('.popover #block_type').after("<label for='block_type' id='block_type-error' class='remove-label has-error'>This field is required</label>");
    } else {
      jQuery('.popover #block_type').css('border', '1px solid #ccc');
    }
  }
});

jQuery(document).on('click','.updateNotification', function(){
	var name 		= $(this).attr('data-name');
	var ref  		= $(this).attr('data-ref');
	var type 		= $(this).attr('data-type');
	var status 	= $(this).attr('data-status');
	var dataNoti 	= $(this).attr('data-noti');

	localStorage.setItem('RecordLabel',name);
	localStorage.setItem('RecordStatus',status);
	localStorage.setItem('RecordRef',ref);
	localStorage.setItem('RecordType',type);
	if (dataNoti == undefined) {
		var dataNoti 	= '';
	}
	localStorage.setItem('dataNoti',dataNoti);

	jQuery('.updateRecordStatusBtn').trigger('click');
})
jQuery('#importCsv').css({'display':'inline'});
$(document).on('submit','#importCsv', function(event){
	var url = jQuery(this).attr('action');
	event.preventDefault();
	$.ajax({
		url:url,
		method:"POST",
		data:new FormData(this),
		contentType:false,
		cache:false,
		processData:false,
		beforeSend:function(){
			$('#importCsv_btn').html('Importing...');
		},
		success:function(data)
		{
			// $('#importCsv')[0].reset();
			$('#importCsv_btn').attr('disabled', false);
			$('#importCsv_btn').html('Import Done');
			iziToast.success({
				timeout: 4000,
				title: 'Success',
				message: 'CSV File imported successfully.',
				position: 'bottomRight',
			})
			setTimeout(function(){  location.reload(); }, delayTime);
		}
	})
});
jQuery('body').on('change', '#fileterByNum', function () {
	var currentVal 	= jQuery(this).val();
	var pageName 		= jQuery(this).attr('rel');
	var pro = false;
	var cIndex = jQuery('.Production_tabs_panel').find('li.active').index();
	if (cIndex == 0) {
		var obj = jQuery('.Production_tabs_panel').find('li:first');
			var objTarget = jQuery('.Production_tabs_panel').find('.nav-tabs').find('li:first').find('a').attr('href');
			pro = true;
	} else {
		var obj = jQuery('.Production_tabs_panel').find('li:last');
			var objTarget = jQuery('.Production_tabs_panel').find('.nav-tabs').find('li:last').find('a').attr('href');
			pro = true;
	}

	jQuery.ajax({
		type: "post",
		url: site_url + pageName,
		beforeSend: function () {
			$('.loader_div').show();
		},
		complete: function () {
			$('.loader_div').hide();
		},
		data: {limit: currentVal,},
		dataType: 'json',
		success: function (data) {

			try {
				if (pageName == 'customer-follow-up-orders') {
					$('#rowCustomers').html(data.html)
				}else{
					jQuery("#allRecords").html(data.html);
				}
				if (pro) {
					obj.trigger('click');
					console.log(objTarget);
					$('.tab-pane').removeClass('active');
					$(objTarget).addClass('active');
				}

			} catch (e) {
				jQuery("#allRecords").html(data.html);
				//alert('Exception while request..');
			}
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
						jQuery(this).find('td:first-child').not( ".est-date" ).html('<b>O</b>'+srNo)
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
		},
		error: function () {
			// jQuery("#allRecords").html(data);
			alert('Error while request..');
		}
	});
});
jQuery(document).on('click','.orders',function(event) {
	var atrclass = jQuery(this).closest('tr').attr('id');
	var arr = atrclass.split('_');


	// return false;
	var delayTime = 3000;
	var uncheckedArray = new Array();
	var checkedArray = new Array();
	jQuery('.orders').each(function() {
		if($(this).is(':checked')) {
			checkedArray.push($(this).attr('data-ref'))
		}else{
			uncheckedArray.push($(this).attr('data-ref'))
		}
		jQuery(this).closest('tr').remove();
		jQuery("tr."+ arr[1]).remove();
	})
	$.ajax({
		url:site_url + "mark-load-orders",
		method:"POST",
		dataType:"json",
		data:{checkedArray:checkedArray,uncheckedArray:uncheckedArray},
		success:function(response)
		{
			iziToast.destroy();
			if (response.success) {
					jQuery('.toLoadCount').html(response.toLoadCount);
					jQuery('.unLoadCount').html(response.unLoadCount);
					iziToast.success({
						timeout: delayTime,
						title: 'Success',
						message: response.success_message,
						position: 'bottomRight',
					});
			}
			else
			{
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
})
jQuery(document).on('click','.getToLoadOrder',function(event) {
	var delayTime = 3000;
	var dataTo = jQuery(this).attr('data-to');
	$.ajax({
		url:site_url + "get-to-load-orders?dataTo="+dataTo,
		method:"GET",
		dataType:"json",
		beforeSend: function () {
			$('.loader_div').show();
		},
		complete: function () {
			$('.loader_div').hide();
		},
		data:{},
		success:function(response)
		{
			if (dataTo == 'toLoad') {
				jQuery('#Toload_dispatch').html(response.html);
      }
      else if (dataTo == 'pending') {
				jQuery('#Pending_dispatch > #tableData').html(response.html);
      }
      else if (dataTo == 'dispached') {
				jQuery('#Past_dispatch').html(response.html);
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
})
jQuery(document).on('change click','.prependToDiv',function(event) {
	event.preventDefault();


	var className = $(this).attr('data-ref');
	var optionRef = $(this).val();
	var lastOptionRef = $(this).attr('data-current');
	var modifyBtnDispatchVal = jQuery(this).closest('tr').find('.modifyLoading');
	var dispatchNo = jQuery(this).closest('tr').find('.modifyLoading').attr('data-dispatch');
	var prependLoading = $('#prepareLoadingSheet').val();
	var thisObj = $(this);

	if (optionRef !='' && (	optionRef != lastOptionRef ) )  {
		// console.log('if');
		var optionRefName = $(this).find('option:selected').text();
		if($("#"+optionRef).length == 0 && optionRef !='')
		{
			var data = '<div class="loadingSheetGroups"><h4 class="active">'+optionRefName+'</h4>	<table class="table table-bordered" id="'+optionRef+'"><thead><tr><th>Business Name</th><th>Order No</th><th>Loading Sheet</th>								<th>Fulfillment</th>								<th></th>								<th></th>								<th>Confirm Actions</th>						</tr>				</thead>				<tbody>				</tbody>		</table>		</div>';
			jQuery('.loadingSheetsRecord').append(data);
		}
		jQuery('.'+className).prependTo('.loadingSheetsRecord  .loadingSheetGroups #'+optionRef+' > tbody');
		var delayTime = 3000;
		$.ajax({
			url:site_url + "load-orders-to-loadingsheet",
			method:"POST",
			dataType:"json",
			beforeSend: function () {
				iziToast.destroy();
				$('.loader_div').show();
			},
			complete: function () {
				$('.loader_div').hide();
			},
			data:{orderRef:className,sheetRef:optionRef,dispatchNo:dispatchNo, prependLoading : prependLoading},
			success:function(response)
			{
				jQuery('#Toload_dispatch').html(response.html);

				if (localStorage.getItem('prepare') == 'yes') {
						//console.log('ok');
						//$('#prepareLoadingSheet option[value="1"]')
						$(document).find('#prepareLoadingSheet').val(1);
				}
				modifyBtnDispatchVal.attr('data-dispatch',response.dispatchRef)
				iziToast.success({
					timeout: 2500,
					title: 'Success',
					message: response.success_message,
					position: 'bottomRight',
				});
			},
			error: function (error) {
				iziToast.error({
					timeout: 2500,
					title: 'Error',
					message: 'Connection Error',
					position: 'bottomRight',
				});
			}
		});
	} else {
		if (optionRef != lastOptionRef) {
			// jQuery('#sort2 > tbody tr').prependTo(jQuery('.'+className));
			// var dispatch = jQuery('.'+className).find('.modifyLoading').attr('data-dispatch');
			$.ajax({
				url:site_url + "remove-loading-orders",
				method:"POST",
				dataType:"json",
				data:{orderRef:className,sheetRef:optionRef},
				success:function(response)
				{
					if (response.success) {
						thisObj.attr('data-current','');
						jQuery('.'+className).prependTo('#sort2 > tbody');
						if (localStorage.getItem('prepare') == 'yes') {
								$(document).find('.prepareLoadingSheet').val(1);
						}
						removeUnLoadOrders();
						iziToast.success({timeout: 2500,title: 'Success',message: response.success_message,	position: 'bottomRight',});
					} else {
						iziToast.info({timeout: 2500,title: 'Success',message: 'Something went wrong please try again.',	position: 'bottomRight',});
					}
				},
		})
		}

}
//  each loop to remove loading group if there is no order in group

//  each loop to remove loading group if there is no order in group




})

$(document).on('click', '#prepareLoadingSheet', function()
{
	var prepareLoadingSheet = $(this).val();

	if(prepareLoadingSheet == 1){
		$("#prepareLoadingSheetPopup").modal('show');
	}
});

jQuery(document).on('click','.prepareForTomorrow',function () {
		jQuery(this).closest('.modal').modal('hide');
		var jj = localStorage.setItem('prepare','yes');
})

function removeUnLoadOrders() {
	jQuery('.loadingSheetGroups').each(function(index,data) {
		var dataLength = jQuery(this).find('table tbody tr').length;
		if (dataLength == 0) jQuery(this).remove();
	});
}

jQuery(document).on('click change','.toLoadOrderStatus',function(e) {
			e.preventDefault();
			var text	 				  =  jQuery(this).attr('data-to');
			var thisRef 				=  jQuery(this).attr('data-ref');
			var dataTo 					=  jQuery(this).attr('data-to');
			var dataPipline 		=  jQuery(this).attr('data-pipline');
			var dataProduction 	=  jQuery(this).attr('data-production');
			var orderNo 				=  jQuery(this).attr('data-orderNo');
			var salesRef 				=  jQuery(this).attr('data-sales');
			var dataApprove 	  =  jQuery(this).attr('data-approve');
			var reload 	  			=  jQuery(this).attr('data-reload');
			var thisValue				=  jQuery.trim(jQuery(this).val());
			if (thisValue !='' && thisValue !='dispatched')
			{
					jQuery(this).val(' ');
					if (thisValue == 'returnToPending' ) {
						jQuery(this).closest('tr').next().remove();
						jQuery(this).closest('tr').remove();
						var delayTime = 3000;
						var uncheckedArray = new Array();
						var checkedArray = new Array();
						uncheckedArray.push(thisRef);
						$.ajax({
							url:site_url + "mark-load-orders",
							method:"POST",
							dataType:"json",
							data:{checkedArray:checkedArray,uncheckedArray:uncheckedArray},
							success:function(response)
							{
								iziToast.destroy();
								if (response.success) {
										jQuery('.toLoadCount').html(response.toLoadCount);
										jQuery('.unLoadCount').html(response.unLoadCount);
										iziToast.success({
											timeout: delayTime,
											title: 'Success',
											message: response.success_message,
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
					}else if (thisValue == 'cancelOrder') {
						localStorage.setItem('orderRef',thisRef);
						localStorage.setItem('dataTo',dataTo);
						localStorage.setItem('dataPipline',dataPipline);
						localStorage.setItem('dataProduction',dataProduction);
						localStorage.setItem('orderNo',orderNo);
						localStorage.setItem('text',text);
						localStorage.setItem('salesRef',salesRef);
						localStorage.setItem('dataApprove',dataApprove);
						localStorage.setItem('reload',reload);
						if (dataTo == 'managerApprove') {
							jQuery('#approvedBy').parent().show();
						}else{
							jQuery('#approvedBy').parent().hide();
						}
						if (salesRef == '' || salesRef === undefined) {
							jQuery('#salesRefSel').parent().hide();
						}else {
							jQuery('#salesRefSel').parent().show();
							jQuery('#salesRefSel').val(salesRef);
						}

						$('#confirm-order-status-update-modal').modal('show');
						if( status == 1)
						var status = 'Inactive';
						else
						var status = 'Active';
						$('#confirm-order-status-update-modal').find('.modal-body').find('.statusLabel').html('<strong>Order No. '+orderNo+'</strong> '+text);
					}
			}
			jQuery('.loadingSheetGroups').each(function(index,data) {
				var dataLength = jQuery(this).find('table tbody tr').length;
				if (dataLength == 0) jQuery(this).remove();
			});
})

jQuery(document).on('click','.saveDispatch',function(e) {
	e.preventDefault();

	var thisValue = '';
	var thisRef  = '';
	var dispatchNo = '';
	var text	 				  =  jQuery('.loadingSheetsRecord .toLoadOrderStatus').attr('data-to');
	var orderRef 				=  jQuery('.loadingSheetsRecord .toLoadOrderStatus').attr('data-ref');
	var dataTo 					=  jQuery('.loadingSheetsRecord .toLoadOrderStatus').attr('data-to');
	var dataPipline 		=  jQuery('.loadingSheetsRecord .toLoadOrderStatus').attr('data-pipline');
	var dataProduction 	=  jQuery('.loadingSheetsRecord .toLoadOrderStatus').attr('data-production');
	var orderNo 				=  jQuery('.loadingSheetsRecord .toLoadOrderStatus').attr('data-orderNo');
	var salesRef 				=  jQuery('.loadingSheetsRecord .toLoadOrderStatus').attr('data-sales');
	var dataApprove 	  =  jQuery('.loadingSheetsRecord .toLoadOrderStatus').attr('data-approve');
	var reload 	  			=  jQuery('.loadingSheetsRecord .toLoadOrderStatus').attr('data-reload');
	var dispatchCount 	=  parseInt(jQuery('.toDispatchCount').text());
	jQuery('.loadingSheetsRecord .toLoadOrderStatus').each(function () {
		if ($(this).val() == 'dispatched') {
			thisRef   			+= jQuery.trim(jQuery(this).attr('data-ref'))+',';
			dispatchNo 		 += jQuery(this).closest('tr').find('.modifyLoading').attr('data-dispatch')+',';
		}
		if ($(this).val() !='') {
			thisValue  	 		 = jQuery.trim(jQuery(this).find('option:selected').val());
		}
	});

	var thisRef = thisRef.replace(/,+$/,'');
	var dispatchNo = dispatchNo.replace(/,+$/,'');
	jQuery(this).val(' ');
	if (thisValue !='') {

		 if (thisValue == 'dispatched') {

			$.ajax({
					type: "POST",
					url: site_url+'get-dispached-order-details/',
					beforeSend: function () {
							$('.loader_div').show();
					},
					complete: function () {
							$('.loader_div').hide();
					},
					data : {orderRef:thisRef,type:'saveDispatch',dispatchNo:dispatchNo},
					cache: false,
					dataType: "json",
					success: function (response)
					{
						jQuery('.save-dispatch #totalRef').remove();
						jQuery('.save-dispatch').append('<input name="orderRefIds" type="hidden" id="totalRef" value="'+thisRef+'">');
						jQuery('#orderItems').html(response.html);

						// setTimeout(function () {
						// 	jQuery('.fullfillment').each(function() {
						// 		 jQuery('.fullfillment').trigger('click');
						// 	});
						// },1000)
						// setTimeout(function () {
						// 	jQuery('.dispachedQty').each(function() {
						// 		 jQuery(this).trigger('keyup');
						// 	});
						// },1000)
						// jQuery('.dispachedQty').trigger('keyup');
					},
					error: function ()
					{
							alert('Error while request..');
					}
			});
			jQuery('#dispatchlistModal').modal('show');
		}
	}else{
		iziToast.info({
			timeout: 4000,
			title: 'Info',
			message: 'Please confirm actions for dispatches then try again!',
			position: 'bottomRight',
		})
	}

})


$('html').on('mouseup', function(e) {
  if (!$(e.target).closest('.popover').length) {
    $('.popover').each(function() {
      $(this).popover('hide');
      if ($(this) + ':hidden') {
        $('#loadingSheet').each(function() {
          // console.log($(this).find('option:selected').attr('data-ref'));
          if ($(this).find('option:selected').attr('data-ref') == 'addNewLoadingSheet') {
            jQuery('#loadingSheet').val(' ');
          }
        })
        $('.selectUOM').each(function() {
          if ($(this).val() == 'addNewUOM') {
            jQuery('.selectUOM').val(' ');
          }
        })
      }
    });
  }
});
// $('.getToLoadOrder').each(function () {
// 	if ($(this).attr('data-to') == 'toLoad') {
// 		$(this).trigger('click');
// 	}
// })
// jQuery('.getToLoadOrder:nth-child(1)').trigger('click');
// jQuery('.getToLoadOrder:').click()
jQuery(document).on('keyup click','.dispachedQty',function () {
	jQuery('.dispachedQty').each(function() {
		let baseUOM = jQuery(this).closest('tr').find('.baseMatched').next('span').text();
		let saleUOM = jQuery(this).next('span').text();
		if (jQuery(this).val() == '' || jQuery(this).val()  == 0) {

				if($.trim(baseUOM) != $.trim(saleUOM))
					jQuery(this).closest('tr').find('.baseUOMQty').prop('disabled',true);

		} else {
				jQuery(this).closest('tr').find('.baseUOMQty').prop('disabled',false);


		}
	})
})

jQuery(document).on('keyup click', '.fullfillment', function(event) {
	event.preventDefault();
	var dispachedQty = '';	var orderQty = '';	var lastDispatched= '';
	var totalItem 	 = jQuery('#totalItem').val();
	for (var i = 1; i <= totalItem; i++)
	{
			jQuery('.parentTr'+i).each(function()
			{
				 if (jQuery(this).closest('tr').find('td').hasClass('ignore')) {
					 	dispachedQty 			=  jQuery.trim(jQuery(this).closest('tr').find('.dispachedQty').val());
	 					dispachedQty 			=  dispachedQty.replace(/^[0|\D]*/,'');
	 					orderQty 					=  jQuery(this).closest('tr').find('.orderQty').val();
	 					lastDispatched		=  jQuery.trim(jQuery(this).closest('tr').find('.dispatched').val());
	 					lastDispatched 		=  (lastDispatched !='' && lastDispatched != undefined) ? lastDispatched : 0 ;
	 					dispachedQty 			=  parseInt(dispachedQty) + parseInt(lastDispatched);
	 					if (dispachedQty >=  orderQty   )
	 					{
	 						jQuery('.parentTr'+i).find('input[type="checkbox"]').prop('checked',true);
	 						return true
	 					}
	 					else
	 					{
	 						jQuery('.parentTr'+i).find('input[type="checkbox"]').prop('checked',false);
	 						return false
	 					}
				 }

			});
	}
})

jQuery(document).on('keyup','.dispachedQty',function(event) {
	event.preventDefault();
	if (jQuery(this).val() == 0)
	{
			if (jQuery(this).val().trim().length > 1){
					jQuery(this).val(0);
			}
			if (jQuery(this).val().trim().length === 0){
					jQuery(this).val(0);
			}
  }
	var dispachedQty 	=  jQuery.trim(jQuery(this).val());
	let baseUOM = jQuery(this).closest('tr').find('.baseMatched').next('span').text();
    let saleUOM = jQuery(this).next('span').text();
	jQuery(this).val(parseInt(dispachedQty));
	if($.trim(baseUOM) == $.trim(saleUOM))
				    jQuery(this).closest('tr').find('.baseMatched').val(parseInt(jQuery(this).val()));

	var orderQty 			=  jQuery(this).closest('tr').find('.orderQty').val();
	if (orderQty.length != 0) {
		var lastDispatched=  jQuery.trim(jQuery(this).closest('tr').find('.dispatched').val());
		lastDispatched = (lastDispatched !='' && lastDispatched != undefined) ? lastDispatched : 0 ;
		dispachedQty = parseInt(dispachedQty) + parseInt(lastDispatched);
		var pendingQty = parseInt(orderQty) - parseInt(dispachedQty);
		pendingQty = (pendingQty === NaN) ? 0 : pendingQty;
		// console.log('pendingQty :' + pendingQty);
		// console.log('orderQty :' + orderQty);
		if (dispachedQty > orderQty) {
					jQuery(this).closest('tr').addClass('warning');
					jQuery(this).closest('tr').find('.peindingQty').val(0);
					iziToast.destroy();
					iziToast.warning({timeout: 2000,title: 'Warning',message: 'Dispatched Qty Greater then ordered Qty..',position: 'bottomRight',
					});
		}else{
				jQuery(this).closest('tr').removeClass('warning');
				jQuery(this).closest('tr').find('.peindingQty').val(pendingQty);
		}
	}

})
jQuery(document).on('click','.saveDispatchFullfillment',function(event) {

	// event.preventDefault();
	jQuery(this).closest('tr').removeClass('warning');
	var targetClass 			=  jQuery(this).closest('tr').attr('class');
	var thiss = $(this);
	console.log(targetClass);
	jQuery('.'+targetClass).each(function(index) {
		console.log(index);
		var pendingQty 			=  jQuery(this).find('.peindingQty').val();

		pendingQty = (pendingQty == NaN) ? 0 : pendingQty;

		console.log(pendingQty);
		if (pendingQty > 0 ) {
			console.log('if');
				if(thiss.is(':checked')){
					jQuery(this).addClass('warning');
					iziToast.destroy();
					iziToast.warning({timeout: 2000,title: 'Warning',message: 'You are marking order fulfilled before sending all items.',position: 'bottomRight',
					// return false;
				});
				}
				return false;
	}

		if (pendingQty == 0) {
			if(!thiss.is(':checked')){
				// jQuery('.'+targetClass).addClass('warning');
				jQuery(thiss).prop('checked',true)
				iziToast.destroy();
				iziToast.warning({timeout: 2000,title: 'Warning',message: "You Already loaded all items. you can't mark this order incomplete.",position: 'bottomRight',
				// return false;
			});
			}
			return false;
		}

	})
})
jQuery(document).on('submit','.save-dispatch',function(evt){
	 evt.preventDefault();
	jQuery(this).find('input[type="text"]').each(function(index){
	})
	return false;
})
jQuery(document).on('click','.hideRecords',function() {
	$("#orderSearchRecords").hide();
	$('#tableData').show();
})

jQuery(document).on('click','.filterStatistics',function(){
	iziToast.destroy();
	var startDate  = jQuery('#startDate').val();
	var endDate  	 = jQuery('#endDate').val();
	var compareStartDate = startDate.split('-');
	var compareEndDate = endDate.split('-');
	compareStartDate = compareStartDate[2]+'-'+compareStartDate[1]+'-'+compareStartDate[0];
	compareEndDate   = compareEndDate[2]+'-'+compareEndDate[1]+'-'+compareEndDate[0];

	if (new Date(compareEndDate) < new Date(compareStartDate) ) {
		iziToast.info({timeout: 2000,title: 'Info',message: 'Please Select End Date Greater then Start Date..',position: 'bottomRight'});
		return false;
	}
	jQuery('.success').each(function(){
			if (jQuery(this).val() == ''){
					jQuery(this).parent().addClass('has-error');
			}else {
					jQuery(this).parent().removeClass('has-error')
			}
	})
	if(startDate =='' || endDate ==''){
		iziToast.info({timeout: 2000,title: 'Info',message: 'Please Select Start date and End date and try again..',position: 'bottomRight'});
		return false;
	}

	$.ajax({
			type: "POST",
			url: site_url+'get-statistics/',
			beforeSend: function () {
					$('.loader_div').show();
			},
			complete: function () {
					$('.loader_div').hide();
			},
			data : {startDate:startDate,endDate:endDate},
			cache: false,
			dataType: "json",
			success: function (response)
			{
				jQuery('.statisticsDate').html(response.html);
			},
			error: function ()
			{
					alert('Error while request..');
			}
	});
})
jQuery('.filterStatistics').trigger('click');


/*******************URL ACTIVATION/*******************/
   var urlCurrent = window.location.href;
   jQuery('.sidebar-menu li a').removeClass('active');
   jQuery('.sidebar-menu li a').each(function(){
   if($(this).attr('href') == urlCurrent)
   {
      document.title = jQuery(this).text();
			jQuery(this).parents('.collapse').addClass('in').prev().addClass('active').find('.menu-arrow').removeClass('arrow_carrot-right').addClass('arrow_carrot-down');
      jQuery(this).addClass('active');
   };
   })
/*******************URL ACTIVATION/*******************/
jQuery(document).on('keyup','.globalSearch',function(){
	var searchval = jQuery.trim(jQuery(this).val());
	var target = jQuery(this);
	if (searchval !='' && searchval.length > 3) {
		$.ajax({
				type: "POST",
				url:site_url + "global-search",
				beforeSend: function () {
						$('.loader_div').show();
				},
				complete: function () {
						$('.loader_div').hide();
				},
				data : {searchKey:searchval},
				cache: false,
				dataType: "json",
				success: function (response)
				{
						jQuery('.globalSearchList').html(response.html);
				},
				error: function (error)
				{
						console.log(error);
				}
		});
	}else{
		jQuery('.globalSearchList').html(' ');
	}



});

	jQuery(document).on('blur','.toFixed',function () {
		var num = parseFloat($(this).val());
    var cleanNum = num.toFixed(2);
		if (!isNaN(cleanNum)) {
			$(this).val(cleanNum);
		}else{
			$(this).val(0.00);
		}
	})


							$(document).on('keyup keydown','.table-hover tbody tr input',function(event) {
								 addRows();
							});

	            var counter_new = 2;
	            jQuery(document).on('click', '.addSizes', function (event)
	            {
									event.preventDefault();
	                var lastAmount = jQuery('.serialNumberr:last').text();
	                var block = jQuery("#nextLine").clone();
	                block.removeAttr('id');
	                block.removeClass('hide');
	                block.addClass('dealPack addTableRow hasRemovedElement');
	                block.children('.serialNumber').addClass('serialNumberr');
	                block.children('.serialNumber').removeClass('serialNumber');
	                block.children('.serialNumberr').text(parseInt(lastAmount) + parseInt(1));
	                block.children('.variationId').remove();
	                block.find(".length").attr("name","length["+parseInt(lastAmount)+"]").attr("id","length["+parseInt(lastAmount)+"]");
	                block.find(".width").attr("name","width["+parseInt(lastAmount)+"]").attr("id","width["+parseInt(lastAmount)+"]");
	                block.find(".height").attr("name","height["+parseInt(lastAmount)+"]").attr("id","height["+parseInt(lastAmount)+"]");
	                block.children('td.addMins').append('<span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span>');
	                var last = parseInt(lastAmount) + parseInt(1);
	                block.addClass('trSrNo'+last);
	                block.insertAfter(".dealPack:last");
	                var count     = 0;
	                jQuery('.dealPack').each(function () {
	                    jQuery(this).find('td.addMins').html('<span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span>');
	                });
	                $(".dealPack:last").find('td.addMins').html('<span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span><i class="addSizes faIcon  fa fa-plus"></i>');
	                counter_new++;
	                setTimeout(function () {
	                  callToEnhanceValidate();
	                },100)
	               addRows();
	            });
	            jQuery(document).on('click', '.addColor', function (event)
	            {
								event.preventDefault();

	              var block = jQuery("#nextLineColor").clone();
	              var colorRowCount = parseInt( jQuery('.colorPack').length);
	              block.removeAttr('id');
	              block.removeClass('hide');
	              block.addClass('colorPack addTableRow hasRemovedElement');
	              block.find(".color").attr("name","color["+parseInt(colorRowCount)+"]").attr("id","color["+parseInt(colorRowCount)+"]");

	              block.insertAfter(".colorPack:last");
	              var count     = 0;
	              jQuery('.colorPack').each(function () {
	                  jQuery(this).find('td.addMins').html('<span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span>');
	              });
								$(".colorPack:last").find('td.addMins').html('<span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span><i class="addColor faIcon  fa fa-plus"></i>');

	              counter_new++;
	              setTimeout(function () {
	                callToEnhanceValidate();
	              },100)
	             addRows();
	            });
	            jQuery(document).on('click', '.addDesign', function (event)
	            {
									event.preventDefault();
	                var block = jQuery(document).find("#nextLineDesign").clone();
	                block.removeAttr('id');
	                block.removeClass('hide');
	                var designRowCount = parseInt( jQuery('.designPack').length);
	                block.find(".design").attr("name","design["+parseInt(designRowCount)+"]").attr("id","design["+parseInt(designRowCount)+"]");
	                block.addClass('designPack addTableRow hasRemovedElement');
	                block.insertAfter(".designPack:last");
	                var count     = 0;
	                jQuery('.designPack').each(function () {
	                    jQuery(this).find('td.addMins').html('<span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span>');
	                });
									$(".designPack:last").find('td.addMins').html('<span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span><i class="addDesign faIcon  fa fa-plus"></i>');
	                counter_new++;
	                setTimeout(function () {
	                  callToEnhanceValidate();
	                },100)
	                addRows();
	            });

	            /** Variable for assign refIds **/
	            var itemReff 		= "";
	            var itemColor 	= "";
	            var itemDesign 	= "";
	            /******************/
	            jQuery(document).on('click', '.removeLayer', function (event) {
									event.preventDefault();

									var parentDiv 	= jQuery(this).closest('table').find('tbody').find('tr').attr('class').split(' ')[0];
									var currentAdd 	=	jQuery('.'+parentDiv).find('td.addMins').find('span').next('i').attr('class').split(' ')[0];

									if (parentDiv == 'dealPack') {
										var itemRef   	= jQuery(jQuery(this)).closest('tr').find('.variantsSizeId').val();
										if(itemRef !="")
		                {
		                    itemReff     += itemRef+',';
		                    jQuery('.variationRefIds').val(itemReff);
		                }
									}


									if (parentDiv == 'colorPack') {
										var itemRefColor   	= jQuery(jQuery(this)).closest('tr').find('.variationColorId').val();
										if(itemRefColor !="")
		                {
		                    itemColor     += itemRefColor+',';
		                    jQuery('.variationColorIds').val(itemColor);
		                }
									}
									if (parentDiv == 'designPack') {
										var itemRefDesign   	= jQuery(jQuery(this)).closest('tr').find('.variationDesignId').val();
										if(itemRefDesign !="")
		                {
		                    itemDesign     += itemRefDesign+',';
		                    jQuery('.variationDesignIds').val(itemDesign);
		                }
									}


	                jQuery(this).parent('td').parent('tr').remove();
	                var count     = 0;
									if (parentDiv == 'dealPack') {
										jQuery('.serialNumberr').each(function () {
		                    jQuery(this).text(parseInt(count) + parseInt(1));
		                    count++;
		                });
									} else {
										jQuery('.'+parentDiv).each(function (index) {
											count++;
										});
									}

	                if (count == 1 || count == 0) {
											jQuery('.'+parentDiv).find('td.addMins').html('<i class="'+currentAdd+' faIcon fa fa-plus"></i>');
	                }else {
											jQuery("."+parentDiv+':last').find('td.addMins').html('<span class="removeLayer" style="padding: 1px;vertical-align: middle;font-size: 20px;"><i class="fa fa-trash-o iconTabFa faMin"></i></span><i class="'+currentAdd+' faIcon  fa fa-plus"></i>');
	                }
									reIndex();addRows();
	            });
							function reIndex() {
									jQuery('.width').each(function(index) {
											$(this).attr('name','width['+index+']').attr('id','width['+index+']');
									})
									jQuery('.length').each(function(index) {
											$(this).attr('name','length['+index+']').attr('id','length['+index+']');
									})
									jQuery('.height').each(function(index) {
											$(this).attr('name','height['+index+']').attr('id','height['+index+']');
									})
									jQuery('.color').each(function(index) {
											$(this).attr('name','color['+index+']').attr('id','color['+index+']');
									})
									jQuery('.design').each(function(index) {
											$(this).attr('name','design['+index+']').attr('id','design['+index+']');
									})
							}
	            var callToEnhanceValidate = function() {

	               /* jQuery(".length").each(function() {
	                    jQuery(this).rules('remove');
	                    jQuery(this).rules('add', {
	                        required: true,
	                        messages: {
	                            required: "This field is required.",
	                        },
	                    });
	                });

	                jQuery(".width").each(function() {
	                    jQuery(this).rules('remove');
	                    jQuery(this).rules('add', {
	                        required: true,
	                        messages: {
	                            required: "This field is required.",
	                        },
	                    });
	                });

	                jQuery(".height").each(function() {
	                    jQuery(this).rules('remove');
	                    jQuery(this).rules('add', {
	                        required: true,
	                        noSpace: true,
	                        messages: {
	                            required: "This field is required.",
	                        },
	                    });
	                });*/

	            }

	            // jQuery(document).on('click','.addColorInput',function () {
	            //   addRows();
	            // })
	            // jQuery(document).on('click','.updateColorInput',function () {
	            //   addRows();
	            // })



							function addRows()
							{
								var ex ={};
								var dx ={};
	              var colorArray = [];
	              var designArray = [];
	              jQuery('.add-color .addTableRow').each(function(row, tr){
	                var colorVal = jQuery(this).find('.color').val();
	                if (colorVal != undefined  && colorVal.length != 0 && colorVal != "") {
										if(ex[colorVal]) {
												// $(this).addClass('danger');
												buttonDisable();
												iziToast.destroy();
												iziToast.info({
													title: 'info',
													message: "You can't repeat same color again!",
												});
												return false;
										}
										else {
												buttonEnable();
												ex[colorVal] = 1;
										}
	                  colorArray.push(colorVal)
	                }
	              })
	              jQuery('.add-design .addTableRow').each(function(row, tr){
	                var designVal =  jQuery(this).find('.design').val();


	                if (designVal != undefined && designVal.length != 0 && designVal != "") {

										if(dx[designVal]) {
											buttonDisable();
											iziToast.destroy();
											iziToast.info({
												title: 'info',
												message: "You can't repeat same design again!",
											});
												return false;
										}
										else {
										  buttonEnable();
											dx[designVal] = 1;
										}
	                  designArray.push(designVal)
	                }else{
										return false;
									}
	              })

	              var toAppend = '';
	              var indexV = 0;
								var toShow = false;
	              var srNo = 1;
								var eleBlock = '';
								var el = {};
	              jQuery('.add-sizes .addTableRow').each(function(row, tr){
								        // get row
								        var row    = $(this);
								        // get first and second td
								        var first  =  row.find('.length').val();
								        var second =  row.find('.width').val();
								        var thied  =  row.find('.height').val();
												var text = 'tr'+first+''+second+''+thied;
												if ( (first != undefined && first != "") && (second != undefined && second != "") && (thied != undefined && thied != "") ) {
									        if(el[text]) {
														buttonDisable();
														iziToast.destroy();
														iziToast.info({
															title: 'info',
															message: "You can't repeat same Height, Width, Length!",
														});
															return false;
									        }
									        else {
															buttonEnable();
									            el[text] = 1;
									        }
												}

									if (jQuery(tr).find('td:eq(1)').find('input').val() != undefined && colorArray.length > 0 && designArray.length > 0)
									{
	                for (var i = 0; i < colorArray.length; i++)
									{
	                    if(colorArray[i]!=undefined){ var color = colorArray[i];}
	                  	for (var j = 0; j < designArray.length; j++)
											{
		                      if(designArray[j]!=undefined){ var design = designArray[j];}

	                        if (jQuery(tr).find('td:eq(1)').find('input').val() != '' && jQuery(tr).find('td:eq(2)').find('input').val() != '' && jQuery(tr).find('td:eq(3)').find('input').val() !='')
													{
													var attr = jQuery(tr).find('td:eq(1)').find('.length').val()+'X'+jQuery(tr).find('td:eq(2)').find('.width').val()+'X'+jQuery(tr).find('td:eq(3)').find('.height').val()+'X'+color+'X'+design;
													if(checkStatus[attr]!=undefined){
														checkStatus[attr] = checkStatus[attr];
													}else{
														checkStatus[attr] = 'checked';
													}

													var attrDefaultPrice = ($('#itemCost').val() !='' ) 	 		? $('#itemCost').val() : 0;
													var attrMinPrice     = ($('#minimumCost').val() !='' ) 		 ? $('#minimumCost').val() : 0;
													var attrBlockPer     = ($('#blockPercentage').val() !='' ) ? $('#blockPercentage').val() : 0;
													if ($('#'+attr).find('input.attrDefaultPrice').length) {
														attrDefaultPrice =  $('#'+attr).find('input.attrDefaultPrice').val();
														attrBlockPer     =  $('#'+attr).find('input.attrBlockPer').val();
														attrMinPrice     =  $('#'+attr).find('input.attrMinPrice').val();
													}

	                        toAppend +="<tr id='"+attr+"'>"
	                        toAppend +='<td> <i class="fa fa-circle"></i></td>\
	                        <input type="hidden" name="attrLength[]" value="'+jQuery(tr).find('td:eq(1)').find('.length').val()+'">\
	                        <input type="hidden" name="attrWidth[]"  value="'+jQuery(tr).find('td:eq(2)').find('.width').val()+'">\
	                        <input type="hidden" name="attrHeight[]" value="'+jQuery(tr).find('td:eq(3)').find('.height').val()+'">\
	                        <input type="hidden" name="attrColor[]"  value="'+color+'">\
	                        <input type="hidden" name="attrDesign[]" value="'+design+'">';
	                        toAppend +='<td>'+jQuery(tr).find('td:eq(1)').find('.length').val()+'X'+jQuery(tr).find('td:eq(2)').find('.width').val()+'X'+jQuery(tr).find('td:eq(3)').find('.height').val()+'</td>';
	                        toAppend +='<td>'+color+'</td>';
	                        toAppend +='<td>'+design+'</td>';
	                        var checked = jQuery(tr).find('td:eq(5)').find('#status').val();
	                        if (checked == 1) {
	                          var statusAttr = 'checked=""';
	                        }else{
	                          var statusAttr = '';
	                        }

													if (jQuery('.BlockPercentage').hasClass('hide')) {
														eleBlock = "style='display:none'";
														toAppend +='<td><input type="text"  class="form-control toFixed attrMinPrice" name="attrMinPrice[]" value="'+attrMinPrice+'"></td>\
													 <td><input type="text"  class="form-control toFixed attrDefaultPrice" name="attrDefaultPrice[]" value="'+attrDefaultPrice+'"><input type="hidden"  class="form-control toFixed attrBlockPer" name="attrBlockPer[]" value="'+attrBlockPer+'"></td>\
													 <td><label class="switch">\
																							 <input attrId="'+attr+'" '+checkStatus[attr]+' type="checkbox" class="check-attr" name="attributeStatus['+ parseInt(srNo-1) +']" data-toggle="switch">\
																							 <span class="slider round"></span>\
																					 </label>\
																			 </td>';
													}else{
														eleBlock = "style='display:block'";
														toAppend +='<td><input type="text"  class="form-control toFixed attrMinPrice" name="attrMinPrice[]" value="'+attrMinPrice+'"></td>\
													 <td><input type="text"  class="form-control toFixed attrDefaultPrice" name="attrDefaultPrice[]" value="'+attrDefaultPrice+'"></td>\
													 <td><input type="text"  class="form-control toFixed attrBlockPer" name="attrBlockPer[]" value="'+attrBlockPer+'"></td>\
													 <td><label class="switch">\
																							 <input attrId="'+attr+'" '+checkStatus[attr]+' type="checkbox" class="check-attr" name="attributeStatus['+ parseInt(srNo-1) +']" data-toggle="switch">\
																							 <span class="slider round"></span>\
																					 </label>\
																			 </td>';

													}
	                        toAppend +="</tr>";
													toShow = true;
	                        }
	                        srNo++;
	                    }

										}

									}
	                 indexV++;
	                });
	              	toAppend +='</table>'

									var toAppendd = '<table class="table"><thead class="attrTable"><tr><th>#</th><th>Diamentions</th><th>Color</th><th>Design</th><th>Minimum Price</th><th>Default Price</th><th '+eleBlock+'>Block Percentage</th><th>Status</th></tr></thead>';
											toAppendd += toAppend;
									if (toShow) {
										jQuery('.atttibutes').html(toAppendd);
									}else{
										jQuery('.atttibutes').html('');
									}

							} //function end

							function buttonDisable(){
								// alert("ok");
								return jQuery(".btn").prop('disabled', true);
							}

							function buttonEnable(){
								return jQuery(".btn").prop('disabled', false);
							}

							jQuery(document).on('change','.check-attr', function() {
								if(jQuery(this).is(':checked')){
									checkStatus[jQuery(this).attr('attrId')]= 'checked';
								}else{
									checkStatus[jQuery(this).attr('attrId')]= '';
								}
							});

jQuery(document).on('click','.addNewModal',function (eve) {
	eve.preventDefault();
	$.ajax({
		type: "GET",
		url: site_url + "getAddItem",
		dataType: 'json',
		success: function (msg) {
				jQuery('#AddItem').find('.modal-body').html(msg.html);
				// jQuery('#AddItem').modal('show');
			$('#AddItem').modal({backdrop: 'static', keyboard: false})
				$.getScript(site_url+"assets/js/form-validate.js");

		}
	});
});

// jQuery(document).on('click','.sub-menu',function (ev) {
//
// 	// $('.sub').hide();
// 	console.log($(this).find('.sub').is(':visible'));
//   if($(this).find('.sub').is(':visible')){
// 		 $('.sidebar-menu').find('.active').removeClass('active');
//      $(this).find('.sub').hide();
// 	}else{
// 		$('.sidebar-menu').find('.active').removeClass('active');
// 		$('.sub').hide();
// 		$(this).find('.sub').fadeIn(100);
// 		$(this).next('a').addClass('active');
// 	}
//
//  });

jQuery(document).on('click','.fetchErrors',function (ev) {
	var urls = $(this).attr('data-url');

	$.ajax({
			type: "GET",
			url:urls,
			beforeSend: function () {
					$('.loader_div').show();
			},
			complete: function () {
					$('.loader_div').hide();
			},
			cache: false,
			dataType: "html",
			success: function (response)
			{
					jQuery('#get-followup-modal').modal('show').find('.modal-body').find('tbody').html(response);
			},
			error: function (error)
			{
					console.log(error);
			}
	});

 });

jQuery(document).on('click','#blockType',function (ev) {
	var thisvalue = $(this).val();
	if ($.trim(thisvalue) == 'addNew') {
		$(this).val(' ');
    jQuery('.remove-label').remove();
    jQuery('.form-group').removeClass('has-error');
    $('#blockType').not($(this)).each(function(){
      $(this).popover('hide');
    });
    $(this).popover({
      trigger  : 'manual',
      placement: 'auto right',
      container: 'body',
			html 		 : true,
      content  : $('#add-new-block-type').html()
    }).popover('show');
    return false;
	}
 });
var undoValue = '';
jQuery(document).on('focus','.cloneInput',function(){
     console.log("Saving value " + $(this).val());
		 undoValue = $(this).val();
 });

 jQuery(document).on('keyup','.cloneInput',function (ev) {
	 iziToast.destroy();
	 var tile = $(this).closest('.form-group').find('label.col-md-4').text();
	 var currentObj = $(this);
	 var currentObjRef = $(this).attr('data-refer');
	 var tarClas = $(document).find('.attrMinPrice').length;
	if($(this).val() != '' && tarClas != 0 ) {

		$('#add-item-form').find('button[type="submit"]').hide();
		iziToast.question({
        rtl: false,
        layout: 1,
        drag: false,
        timeout: false,
        close: false,
        overlay: false,
        displayMode: 1,
        id: 'question',
        progressBar: true,
        message: 'Are you sure to change '+tile+'?',
        position: 'center',
        buttons: [
            ['<button><b>Confirm</b></button>', function (instance, toast, button, e, inputs) {
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
								$(document).find('.'+currentObjRef).val(currentObj.val());
								$('#add-item-form').find('button[type="submit"]').show();
								addRows();
                // iziToast.success({timeout: 2000,title: 'Success',message: tile+' Changes updated successfully',position: 'center'});
            }, false], // true to focus
            ['<button>NO</button>', function (instance, toast, button, e) {
								currentObj.val(undoValue);
								$('#add-item-form').find('button[type="submit"]').show();
                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
            }]
        ],
        onClosing: function(instance, toast, closedBy){
            // console.info('Closing | closedBy: ' + closedBy);
        },
        onClosed: function(instance, toast, closedBy){
            // console.info('Closed | closedBy: ' + closedBy);
        }
    });
	}

  });

	$(document).ready(function() {
	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });
	});

	jQuery(document).on('change', '#productionOnDemand',function () {
		var v = $(this).val();
		if(v == 1){ $(document).find('.BlockPercentage').removeClass('hide');addRows(); }
		else { $(document).find('.BlockPercentage').addClass('hide');addRows();}
	})
	jQuery('.modal').on('shown', function(){
	    $('.loader_div').show();
	});

	jQuery('.modal').on('shown.bs.modal', function (e) {
  	$('.loader_div').hide();
	})

});
