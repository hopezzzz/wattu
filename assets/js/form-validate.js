$(document).ready(function ()
{
  var minPhoneLen = 10;
  var maxPhoneLen = 15;
  $.validator.addMethod("noSpace", function(value, element,param)
  {
    //      	return value.indexOf(" ") >= 0 && value != "";
    return $.trim(value).length >= param;

  }, "No space please and don't leave it empty");
  $.validator.addMethod("greaterThan",
  function (value, element, param) {
    var $min = $(param);
    if (this.settings.onfocusout) {
      $min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
        $(element).valid();
      });
    } return parseInt(value) > parseInt($min.val()); }, "Max must be greater than min");
    jQuery.validator.addMethod("nameRegex", function(value, element) {
      return this.optional(element) || /^[a-z\ \s]+$/i.test(value);
    }, "felid must contain only letters & space"),
    jQuery.validator.addMethod("notEqual", function(value, element, param) {
      return this.optional(element) || value != param;
    }, "Value must be greater than 0"),
    $.validator.addMethod('minStrict', function (value, el, param) {
    return value > param;
  },"value should be greater then 0.00");
  /*====================Start login form validation================= */
  var site_url = $('#site_url').val();
  $("#login-form").validate({
    errorClass   : "has-error",
    highlight    : function(element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight  : function(element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      email:
      {
        required: true,
        noSpace: true,
        email: true
      },
      password:
      {
        required: true,
        noSpace: true,
        minlength: 5,
      }
    },
    messages:
    {
      email: {
        required: "Email is required.",
        email: "Please enter valid email",
      },
      password: {
        required: "Password is required.",
        minlength: "Password must contain at least 5 characters.",
      },
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });
  $("#forget-form").validate({
    errorClass   : "has-error",
    highlight    : function(element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight  : function(element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      email:
      {
        required: true,
        noSpace: true,
        email: true
      },
    },
    messages:
    {
      email: {
        required: "Email is required.",
        email: "Please enter valid email",
      },
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });

  //forgot Password
  $("#forgotPassword-form").validate({
    errorClass   : "has-error",
    highlight    : function(element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight  : function(element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      email:
      {
        required: true,
        noSpace: true,
        email: true
      },
    },
    messages:
    {
      email: {
        required: "Email is required.",
        email: "Please enter valid email",
      },
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });



  //change Password-form
  $("#register-user-form").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      userName : {
        required: true,
      },
      mobileNo:
      {
        required: true,
        noSpace: true,
        minlength: 10,
        number: true,
      },
      userAddress:
      {
        required: false,
      },
      userEmail:
      {
        required: true,
        noSpace: false,
      },
      userType:
      {
        required: true,
      },
    },
    messages:
    {
      userName: "Name is required.",
      mobileNo: {
        'required' : "Mobile Number required.",
        'minlength': "Mobile Number must contain at least 10 Number.",
      },
      userName: "Name  is required.",
    },
    submitHandler: function (form)
    {

      var checkValue = $("input[type='checkbox']:checked").length
        if(checkValue == 0){
          iziToast.destroy();
					iziToast.info({timeout: 2000,title: 'Info',message: 'Please Allow Atleast One Permissions .',position: 'bottomRight',
        })
        return false;
      }else{
        formSubmit(form);
      }
    }
  });


  $("#update-user-form").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      userName : {
        required: true,
      },
      oldPassword:
      {
        required: false,
        noSpace: false
      },
      mobileNo:
      {
        required: false,
        noSpace: false,
        minlength: 10,
        number: true,
      },
      newPassword:
      {
        required: function(element) {
          var company = $('#oldPassword').val();
          if( company != '' )
          return true;
          else
          return false;
        },
        // noSpace: true,
        minlength: 5,
        // equalTo: '#confirmPassword'
      },
      confirmPassword:
      {
        required: function(element) {
          var company = $('#newPassword').val();
          if( company != '' )
          return true;
          else
          return false;
        },
        // noSpace: true,
        minlength: 5,
        equalTo: '#newPassword'
      },
    },
    messages:
    {
      oldPassword: "Old Password is required.",
      mobileNo: {
        'required' : "Mobile Number required.",
        'minlength': "Mobile Number must contain at least 10 Number.",
      },
      userName: "Old Password is required.",
      newPassword:
      {
        'required': "New Password is required.",
        'minlength': "New Password must contain at least 5 characters.",
        //'equalTo': "New Password and Confirm Password not mactched."
      },
      confirmPassword:
      {
        'required': "Confirm Password is required.",
        'minlength': "Confirm Password must contain at least 5 characters.",
        'equalTo': "New Password and Confirm Password not mactched."
      },
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });

  jQuery(document).on('click','button[type="submit"]',function () {
    //User Register form Form Validations
    $("#add-item-form").validate({
      errorClass: "has-error",
      highlight: function (element, errorClass) {
        $(element).parents('.form-group').addClass(errorClass);
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass(errorClass);
      },
      rules:
      {
        // "design[]":
        // {
        //   required: true,
        // },
        // "width[]":
        // {
          required: function(element) {
                  var prodOnDemand = $('#productionOnDemand').val();
                  if( prodOnDemand == 1 )
                      return true;
                  else
                      return false;
          },
        // },
        // "height[]":
        // {
        //   required: function(element) {
        //           var prodOnDemand = $('#productionOnDemand').val();
        //           if( prodOnDemand == 1 )
        //               return true;
        //           else
        //               return false;
        //   },
        // },
        // "length[]":
        // {
        //   required: function(element) {
        //           var prodOnDemand = $('#productionOnDemand').val();
        //           if( prodOnDemand == 1 )
        //               return true;
        //           else
        //               return false;
        //   },
        // },
        catRef:
        {
          required: true,
        },
        // "color[]":{
        //   required : false,
        //   nameRegex :true,
        // },
        blockType:{
          required: function(element) {
                  var prodOnDemand = $('#productionOnDemand').val();
                  if( prodOnDemand == 1 )
                      return true;
                  else
                      return false;
          },
        },
        blockPercentage:{
          required: function(element) {
                  var prodOnDemand = $('#productionOnDemand').val();
                  if( prodOnDemand == 1 )
                      return true;
                  else
                      return false;
          },
          max: 100,
        },
        productionOnDemand:{
          required : true,
        },
        transportCost:{
          required : true,
        },
        saleConvLength:{
          required : true,
          number : true,
          min: 1

        },
        minimumCost : {
          required : true,
        },
        itemCost:{
          required : true,
          greaterThan : '#minimumCost',
        },
        itemName:{
          required : true,
        },
        saleUOM :  {
          required : true,
        },
        baseUOM :  {
          required : true,
        },
        uomType :  {
          required : true,
        },
      },
      messages:
      {
        itemName            : "Item Name is required.",
        "design"            : "This fields is required.",
        "height[]"          : "This fields is required.",
        "length[]"          : "This fields is required.",
        "width[]"           : "This fields is required..",
        catRef              : "Category is required.",
        "color"             : {
                                'required' : "This fields is required..",
                                'nameRegex': "Item Color must contain only letters.",
        },
        blockType           : "Block Type is required.",
        productionOnDemand  : "Production On Demand is required.",
        transportCost       : "Transport Cost is required.",
        itemCost: {
                 'required' : "Item Cost is required.",
                 'greaterThan': "Item Cost must be greater then minimum cost.",
        },
        blockPercentage: {
                 'required' : "Block Percentage is required.",
                 'max'      : "Percentage can not be more then 100.",
        },
        minimumCost         : "Minimum Cost is required.",
        saleUOM             : "UOM  is required.",
        baseUOM             : "UOM  is required.",
        uomType             : "UOM Type  is required.",

      },
      submitHandler: function (form)
      {
        formSubmit(form);
      }

    });


        // $(".length").each(function()
        // {
        //     $(this).rules('remove');
        //     $(this).rules('add', {
        //             required: true,
        //             messages: {
        //               required: "This field is required"
        //             },
        //      });
        // });
        //
        // $(".width").each(function()
        // {
        //     $(this).rules('remove');
        //     $(this).rules('add', {
        //             required: true,
        //             messages: {
        //               required: "This field is required"
        //             },
        //      });
        // });
        // $(".height").each(function() {
        //     $(this).rules('remove');
        //     $(this).rules('add', {
        //         required: true,
        //         noSpace: true,
        //         messages: {
        //             required: "This field is required"
        //         },
        //     });
        // });
        // $(".color").each(function() {
        //     $(this).rules('remove');
        //     $(this).rules('add', {
        //         required: true,
        //         noSpace: true,
        //         messages: {
        //             required: "This field is required"
        //         },
        //     });
        // });
        // $(".design").each(function() {
        //     $(this).rules('remove');
        //     $(this).rules('add', {
        //         required: true,
        //         noSpace: true,
        //         messages: {
        //             required: "This field is required"
        //         },
        //     });
        // });
  })

  //add Loans Form Validations
  $("#add-customer-form").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      contactName:
      {
        required: true,
        noSpace:true,
      },

      lastName:
      {
        required: true,

      },
      // customerEmail:
      //         {
      //             required: true,
      //         },
      businessName:
      {
        required: true,
      },
      phoneNo1:
      {
        required: true,
        minlength: 10,
        maxlength: 10,
      },
      countryId:
      {
        required: true,
      },
      stateId:
      {
        required: true,
      },
      cityId:
      {
        required: true,
      },
      deliveryMethodRef:
      {
        required: true,
      },
    },
    messages:
    {
      contactName: "Contact Name is required.",
      businessName: "Business Name is required.",
      phoneNo1  : {
        'required' : "Phone No. is required.",
        'minlength': "mobile number should be min length 10 digit",
        'maxlength': "mobile number should be max length 10 digit",
      },
      deliveryMethodRef: "This field is required.",
      loanType: "Loan Type is required.",

    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });

  //add-category-form
  $("#add-sub-category-form").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      categoryName:
      {
        required: true,
      },
      parentCatRef:
      {
        required: true,
      },
    },
    messages:
    {
      categoryName: "Category Name is required.",
      parentCatRef : "Parent Category is required.",
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });
  $("#add-category-form").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      categoryName:
      {
        required: true,
      },

    },
    messages:
    {
      categoryName: "Category Name is required.",

    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });

  //add-category-form
  $("#deliveryMethod-form").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      methodName:
      {
        required: true,
      },
      // area:
      // {
      //   required: true,
      // },
    },
    messages:
    {
      methodName: "Delivery method name is required.",
      area:       "Area name is required.",
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });

  // add update block type
  $("#add-block-type-form").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      blockType:
      {
        required: true,
        noSpace: true,
      },
    },
    messages:
    {
      methodName: "Block Type is required.",
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });

  $("#pricingMethod-form").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      payementMethod:
      {
        required: true,
      },
    },
    messages:
    {
      payementMethod: "Payement Method is required.",
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });

  $("#comment-order").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      comment:
      {
        required: true,
      },
    },
    messages:
    {
      comment: "order comment is required.",
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });
  $("#addUnitOfMeasurement").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      unitName:
      {
        required: true,
      },
    },
    messages:
    {
      unitName: "Unit of measurement is required.",
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });

$("#add-update-city-form").validate({
  errorClass: "has-error",
  highlight: function (element, errorClass) {
    $(element).parents('.form-group').addClass(errorClass);
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).parents('.form-group').removeClass(errorClass);
  },
  rules:
  {
    name:
    {
      required: true,
      nameRegex :true,
    },
    sta_id:
    {
      required: true,
    },
  },
  messages:
  {
    name  : {
      'required' : "Name is required.",
      'nameRegex': "City Name must contain only letters.",
    },
    sta_id : 'State is required.'
  },
  submitHandler: function (form)
  {
    formSubmit(form);
  }
});
$("#add-update-region").validate({
  errorClass: "has-error",
  highlight: function (element, errorClass) {
    $(element).parents('.form-group').addClass(errorClass);
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).parents('.form-group').removeClass(errorClass);
  },
  rules:
  {
    name:
    {
      required: true,
      nameRegex :true,
    },
  },
  messages:
  {
    name  : {
      'required' : "Region Name is required.",
      'nameRegex': "Region Name must contain only letters.",
    },
  },
  submitHandler: function (form)
  {
    formSubmit(form);
  }
});


  $("#add-transport-form").validate({
    errorClass: "has-error",
    highlight: function (element, errorClass) {
      $(element).parents('.form-group').addClass(errorClass);
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents('.form-group').removeClass(errorClass);
    },
    rules:
    {
      price:
      {
        required: true,
        number :true,
      },
      deliveryMethodRef:
      {
        required: true,
      },
      region_id:
      {
        required: true,
      },
      itemRefId:
      {
        required: true,
      },
      pricingMode:
      {
        required: true,
      },
    },
    messages:
    {
      name  : {
        'required' : "Price is required.",
        'number': "Price must contain only numbers.",
      },
      deliveryMethodRef: "Delivery method is required.",
      region_id: "Region is required.",
      itemRefId: "item is is required.",
      pricingMode: "pricing mode is required.",
    },
    submitHandler: function (form)
    {
      formSubmit(form);
    }
  });



$("#productionOutput").validate({
  errorClass: "has-error",
  highlight: function (element, errorClass) {
    $(element).parents('.form-group').addClass(errorClass);
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).parents('.form-group').removeClass(errorClass);
  },
  rules:
  {
    "days[]":
            {
                required: true,
            },

  },
  messages:
  {
    "days[]"  : "This field is required.",
  },
  submitHandler: function (form)
  {
    formSubmit(form);
  }
});

$("#addNewLoadingSheet-form").validate({
  errorClass: "has-error",
  highlight: function (element, errorClass) {
    $(element).parents('.form-group').addClass(errorClass);
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).parents('.form-group').removeClass(errorClass);
  },
  rules:
  {
    refName:
            {
                required: true,
            },

  },
  messages:
  {
    refName  : "This field is required.",
  },
  submitHandler: function (form)
  {
    formSubmit(form);
  }
});


// Update password
$("#change-password").validate({
  errorClass   : "has-error",
  highlight    : function(element, errorClass) {
    $(element).parents('.form-group').addClass(errorClass);
  },
  unhighlight  : function(element, errorClass, validClass) {
    $(element).parents('.form-group').removeClass(errorClass);
  },
  rules:
  {
    old_password:
            {
                required: true,
                noSpace: true,
                minlength: 5,
                // equalTo: '#confirmPassword'
            },
    new_password:
            {
                required: true,
                noSpace: true,
                minlength: 5,
            },
    confirm_password:
            {
                required: true,
                noSpace: true,
                minlength: 5,
                equalTo: '#new_password'
            },
  },
  messages:
  {
    password:
            {
                'required': "Password is required.",
                'minlength': "New Password must contain at least 5 characters.",
            },
    new_password:
            {
                'required': "Confirm Password is required.",
                'minlength': "Confirm Password must contain at least 5 characters.",
            },
    confirm_password:
            {
                'required': "Confirm Password is required.",
                'minlength': "Confirm Password must contain at least 5 characters.",
                'equalTo': "Password and Confirm Password not mactched."
            },
  },
  submitHandler: function (form)
  {
    formSubmit(form);
  }
});

$(".save-dispatch").validate({
  errorClass: "has-error",
  highlight: function (element, errorClass) {
    $(element).parents('.form-group').addClass(errorClass);
  },
  unhighlight: function (element, errorClass, validClass) {
    $(element).parents('.form-group').removeClass(errorClass);
  },
  submitHandler: function (form)
  {
    if ($('.dispachedQty').filter(function() {
        return parseInt(this.value, 10) !== 0;
          }).length === 0) {
            iziToast.info({
              timeout: 2000,
              title: 'info',
              message: 'at least one Loaded Now (Sales UOM) is more than 0',
              position: 'bottomRight',
            });
          return false;
      }
      else {

      }
    var i = 0;
    $('.save-dispatch input[type="text"]').each(function(index){
        if (jQuery(this).val() == '')
        {
          jQuery(this).attr('placeholder','Required..').parents('td').addClass('has-error');
          i++;
        }else
        {
          jQuery(this).parents('td').removeClass('has-error');
        }
    });
    if (i == 0) {
      formSubmit(form);
    }
  }
});

});

function formSubmit(form)
{
  $.ajax({
    url         : form.action,
    type        : form.method,
    data        : new FormData(form),
    contentType : false,
    cache       : false,
    processData : false,
    dataType    : "json",
    beforeSend  : function () {
      $("input[type=submit]").attr("disabled", "disabled");
      $("button[type=submit]").attr("disabled", "disabled");
      $(".loader_div").show();
    },
    complete: function () {
      $(".loader_div").hide();
      $("button[type=submit]").removeAttr("disabled");
      $("input[type=submit]").removeAttr("disabled");
    },
    success: function (response) {
      var appendTo = '';
      $(".loader_div").hide();
      if (response.formErrors) {
        $("input[type=submit]").removeAttr("disabled");
        $("button[type=submit]").removeAttr("disabled")
      }
      $("button[type=submit]").removeAttr("disabled");
      $("input[type=submit]").removeAttr("disabled");
      iziToast.destroy();
      var delayTime = 2000;
      if(response.delayTime)
      delayTime = response.delayTime;
      if (response.success)
      {
        iziToast.success({
          timeout: delayTime,
          title: 'Success',
          message: response.success_message,
          position: 'bottomRight',
        });
      }
      else
      {
        if( response.formErrors)
        {
          $.each(response.errors, function( index, value )
          {
            $("input[name='"+index+"']").parents('.form-group').addClass('has-error');
            $("input[name='"+index+"']").after('<label id="'+index+'-error" class="has-error" for="'+index+'">'+value+'</label>');
            $("select[name='"+index+"']").parents('.form-group').addClass('has-error');
            $("select[name='"+index+"']").after('<label id="'+index+'-error" class="has-error" for="'+index+'">'+value+'</label>');
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
      }
      if(response.modelhide)
      {
        jQuery('#'+response.modelhide).modal('hide');
      }
      if(response.ajaxPageCallBack)
      {
        response.formid = form.id;
        ajaxPageCallBack(response);
      }

      if(response.resetform)
      {
        $('#'+form.id).resetForm();
      }
      if(response.submitDisabled)
      {
        $("input[type=submit]").attr("disabled", "disabled");
        $("button[type=submit]").attr("disabled", "disabled");
      }
      if(response.hideEleid){
        jQuery('#'+response.hideEleid).hide();
        jQuery("html, body").animate({scrollTop: 0}, "slow");
      };
      if (response.reload) {
        setTimeout(function(){  location.reload(); }, delayTime)
      }
      if(response.url)
      {
        if(response.delayTime)
        setTimeout(function() { window.location.href=response.url;}, response.delayTime);
        else
        window.location.href=response.url;
      }

      if (response.commentBox) {

        if (response.commentData)
        {
          jQuery('.comment-section li:first').before(response.commentData);
          $('html, body').animate({
            scrollTop: $(".comment-section").offset().top - 50
          }, 0);
          disableScroll();
          setTimeout(function(){enableScroll()},0);
        }else {
          setTimeout(function(){enableScroll()},0);
          jQuery('#orderComment_'+response.commentRef).find('.realtive').html(response.updatedContent)
          $('html, body').animate({
            scrollTop: $('#orderComment_'+response.commentRef).offset().top - 50
          }, 0);
        }
      }
      if (response.isDispatched) {
        var dispatchCount = jQuery(document).find('.toDispatchCount').text();
        var toLoadCount = jQuery('.toLoadCount').text();
        var newCount = response.orderRefIdsCount;
        newCount =  parseInt(dispatchCount) + parseInt(newCount);
        toLoadCount = parseInt(toLoadCount) - parseInt(response.orderRefIdsCount);

        jQuery('.toLoadCount').html(response.toLoadCount);
        jQuery('.toDispatchCount').html(newCount);
        if (response.orderFullfilled) {
					$.each(response.orderRefIdsArray,function(i){
						jQuery('.loadingSheetsRecord .'+response.orderRefIdsArray[i]).remove();
					});
        }
        $.each(response.orderRefIdsArray,function(i){
             jQuery('.loadingSheetsRecord .'+response.orderRefIdsArray[i]).prependTo('.noLoadingRecords > table > tbody');
             jQuery('.'+response.orderRefIdsArray[i]).find('.modifyLoading').attr('data-dispatch','');
             jQuery('.'+response.orderRefIdsArray[i]).find('.loadingSheet').val(' ');
        	   // jQuery('.loadingSheetsRecord .'+response.orderRefIdsArray[i]).remove();
        });

        jQuery('.loadingSheetGroups').each(function(index,data) {
      		var dataLength = jQuery(this).find('table tbody tr').length;
      		if (dataLength == 0) jQuery(this).remove();
      	});
        setTimeout(function() {
          location.reload();
        },delayTime);
      }
      if (response.data) {
        if (response.categoryData) {

          if (response.newSubCat) {
            $('#subCat').append( '<option value="'+response.data.catRef+'">'+response.data.categoryName+'</option>' );
            $('#subCat').val(response.data.catRef);
          }else{
            if (response.updateSubCat) {
              var selectVal = jQuery('#parentCatRef').find('option[value="'+response.data.catRef+'"]').text();
              jQuery('#category_'+response.data.catRef).find('.updateSubCategory').html(response.data.categoryName);
              jQuery('#category_'+response.data.catRef).find('.updateSubCategory').attr('data-name',response.data.categoryName).attr('data-parentref',response.data.parentCatRef).attr('data-ref',response.data.catRef);
            }
          };

          if (response.newCat) {

            // var count = jQuery('#tableData table tr:last').index() + 1;
            var count = jQuery('#tableData table tbody tr').length + 1;
            var selectVal = jQuery('#catRef').find('option[value="'+response.data.catRef+'"]').text();
            appendTo += '<tr id="category_'+response.data.catRef+'">';
            appendTo += '<td class="srNum">'+count+'</td>';
            appendTo += '<td><a href="javascript:void(0)" class="updateCategory" data-ref="'+response.data.catRef+'" data-name="'+response.data.categoryName+'">'+response.data.categoryName+'</a></td>';
            appendTo += '<td><span class="label label-info">Parent Category</span></td><td></td>';
            appendTo += '<td class="statusTd"><span class="label label-success">Active</span></td>';
            appendTo += '<td><a href="javascript:void(0);" data-status="1" class="updateStatus" data-name="'+response.data.categoryName+'" data-type="category" data-ref="'+response.data.catRef+'">Make Inactive </a></td>';
            appendTo += '</tr>';

            $('#tableData table tr:first').after(appendTo);
            $("#tableData table td.srNum").each(function(i,v) {
              $(v).text(i + 1);
            });
          }else {
            var selectVal = jQuery('#parentCatRef').find('option[value="'+response.data.parentCatRef+'"]').text();
            jQuery('#category_'+response.data.catRef).find('.updateCategory').html(response.data.categoryName);
            jQuery('#category_'+response.data.catRef).find('.updateCategory').attr('data-name',response.data.categoryName);
            $('#category_'+response.data.catRef+'td:nth-child(4)').css("background-color", "yellow");
            jQuery('#category_'+response.data.catRef+' td:nth-child(4)').html(selectVal);
          }
        }
      }

        if (response.categoryDataFront) {
          $('#catRef').append( '<option selected value="'+response.data.catRef+'">'+response.data.categoryName+'</option>' );
          $('#parentCatRef').append( '<option value="'+response.data.catRef+'">'+response.data.categoryName+'</option>' );
        }
        if (response.addSubCatFront) {
          $('#catRef').val( response.data.parentCatRef );
          $('#subCat').append( '<option value="'+response.data.catRef+'" selected>'+response.data.categoryName+'</option>' );
          $("#subCat option[value='1']").remove();

        }
        if (response.deliveryMethod) {
          if (response.newMethod) {
            // var count = jQuery('#tableData table tr:last').index() + 1;
            jQuery('#tableData table tbody').find('.noRecord').remove();
            var count = jQuery('#tableData table tbody tr').length + 1;
            appendTo += '<tr id="deliveryMethod_'+response.data.deliveryMethodRef+'">';
            appendTo += '<td class="srNum">'+count+'</td>';
            appendTo += '<td><a href="javascript:void(0)" class="updateDeliveryMethod" data-ref="'+response.data.deliveryMethodRef+'" data-name="'+response.data.methodName+'">'+response.data.methodName+'</a></td>';
            // appendTo += '<td>'+response.data.area+'</td>';
            appendTo += '<td class="statusTd"><span class="label label-success">Active</span></td>';
            appendTo += '<td><a href="javascript:void(0);" data-status="1" class="updateStatus" data-name="'+response.data.methodName+'" data-type="deliveryMethod" data-ref="'+response.data.deliveryMethodRef+'">Make Inactive </a></td>';
            appendTo += '</tr>';
            $('#tableData table tr:first').after(appendTo);
          }else {
            jQuery('#deliveryMethod_'+response.data.deliveryMethodRef).find('.updateDeliveryMethod').html(response.data.methodName);
            jQuery('#deliveryMethod_'+response.data.deliveryMethodRef).find('.updateDeliveryMethod').attr('data-name',response.data.methodName);
            // jQuery('#deliveryMethod_'+response.data.deliveryMethodRef).find('.updateDeliveryMethod').attr('data-area',response.data.area);
            jQuery('#deliveryMethod_'+response.data.deliveryMethodRef).find('.area').html(response.data.area);
          }
        }
        if (response.blockType) {
          if (response.newMethod) {
            // var count = jQuery('#tableData table tr:last').index() + 1;
            jQuery('#tableData table tbody').find('.noRecord').remove();
            var count = jQuery('#tableData table tbody tr').length + 1;
            appendTo += '<tr id="blockTypes_'+response.data.id+'">';
            appendTo += '<td class="srNum">'+count+'</td>';
            appendTo += '<td><a href="javascript:void(0)" class="updateBlockTypeMethod" data-ref="'+response.data.id+'" data-name="'+response.data.blockType+'">'+response.data.blockType+'</a></td>';
            appendTo += '<td>'+response.data.addedOn+'</td>';
            appendTo += '<td class="statusTd"><span class="label label-success">Active</span></td>';
            appendTo += '<td><a href="javascript:void(0);" data-status="1" class="updateStatus" data-name="'+response.data.blockType+'" data-type="blockTypes" data-ref="'+response.data.id+'">Make Inactive </a></td>';
            appendTo += '</tr>';
            $('#tableData table tr:first').after(appendTo);
          }else {
            jQuery('#blockTypes_'+response.data.id).find('.updateBlockTypeMethod').html(response.data.blockType);
            jQuery('#blockTypes_'+response.data.id).find('.updateBlockTypeMethod').attr('data-name',response.data.blockType);
            // jQuery('#deliveryMethod_'+response.data.deliveryMethodRef).find('.updateBlockTypeMethod').attr('data-area',response.data.area);
          }
        }

        if (response.pricingMethod) {
          if (response.newMethod) {
            // var count = jQuery('#tableData table tr:last').index() + 1;
            jQuery('#tableData table tbody').find('.noRecord').remove();
            var count = jQuery('#tableData table tbody tr').length + 1;
            appendTo += '<tr id="pricingMethod_'+response.data.pricingRef+'">';
            appendTo += '<td class="srNum">'+count+'</td>';
            appendTo += '<td><a href="javascript:void(0)" class="updatePricingMethod" data-ref="'+response.data.pricingRef+'" data-name="'+response.data.payementMethod+'">'+response.data.payementMethod+'</a></td>';
            appendTo += '<td class="">'+response.data.addedOn+'</td>';
            appendTo += '<td class="statusTd"><span class="label label-success">Active</span></td>';
            appendTo += '<td><a href="javascript:void(0);" data-status="1" class="updateStatus" data-name="'+response.data.payementMethod+'" data-type="deliveryMethod" data-ref="'+response.data.pricingRef+'">Make Inactive </a></td>';
            appendTo += '</tr>';
            $('#tableData table tr:first').after(appendTo);
            $("#tableData table td.srNum").each(function(i,v) {
              $(v).text(i + 1);
            });
          }else {
            jQuery('#pricingMethod_'+response.data.pricingRef).find('.updatePricingMethod').html(response.data.payementMethod);
            jQuery('#pricingMethod_'+response.data.pricingRef).find('.updatePricingMethod').attr('data-name',response.data.payementMethod);
          }
        }
        if (response.itemData) {
          if (response.newItem) {
            // var count = jQuery('#tableData table tr:last').index() + 1;
            var count = jQuery('#tableData table tbody tr').length + 1;
            var selectVal = jQuery('#catRef').find('option[value="'+response.data.catRef+'"]').text();
            appendTo += '<tr id="items_'+response.data.productRef+'">';
            appendTo += '<td class="srNum">'+count+'</td>';
            appendTo += '<td><a href="javascript:void(0)" class="updateItem" data-ref="'+response.data.productRef+'" data-name="'+response.data.itemName+'">'+response.data.itemName+'</a></td>';
            appendTo += '<td>'+response.data.productRef+'</td>';
            appendTo += '<td class="TdcatRef">'+response.data.catRef+'</td>';
            appendTo += '<td class="TdinStock">'+response.data.blockType+'</td>';
            // appendTo += '<td class="Tdsize">'+response.data.size+'</td>';
            appendTo += '<td class="TditemCost">'+response.data.itemCost+'</td>';
            // appendTo += '<td class="Tdcolor">'+response.data.color+'</td>';
            appendTo += '<td class="statusTd"><span class="label label-success">Active</span></td>';
            appendTo += '<td><a href="javascript:void(0);" data-status="1" class="updateStatus" data-name="'+response.data.itemName+'" data-type="items" data-ref="'+response.data.productRef+'">Make Inactive </a></td>';
            appendTo += '</tr>';
            $('#tableData table tr:first').after(appendTo);
            $("#tableData table td.srNum").each(function(i,v) {
              $(v).text(i + 1);
            });
          }else{
            jQuery('#items_'+response.data.productRef).find('.updateItem').html(response.data.itemName);
            jQuery('#items_'+response.data.productRef).find('.updateItem').attr('data-name',response.data.itemName);
            jQuery('#items_'+response.data.productRef).find('.TdcatRef').html(response.data.catRef);
            jQuery('#items_'+response.data.productRef).find('.TdinStock').html(response.data.blockType);
            jQuery('#items_'+response.data.productRef).find('.Tdsize').html(response.data.size);
            jQuery('#items_'+response.data.productRef).find('.TditemCost').html(response.data.itemCost);
            // jQuery('#items_'+response.data.productRef).find('.Tdcolor').html(response.data.color);
          }

        }



      if (response.unitRef) {
        if (response.newOUM) {
            var count = jQuery('#tableData table tbody tr').length + 1;
            appendTo += '<tr id="measurements__'+response.unitRef+'">';
            appendTo += '<td class="srNum">'+count+'</td>';
            appendTo += '<td><a href="javascript:void(0)" class="updateUOM" data-ref="'+response.unitRef+'" data-name="'+response.unitName+'">'+response.unitName+'</a></td>';
            appendTo += '<td>'+response.createdDate+'</td>';
            appendTo += '<td class="statusTd"><span class="label label-success">Active</span></td>';
            appendTo += '<td><a href="javascript:void(0);" data-status="1" class="updateStatus" data-name="'+response.unitRef+'" data-type="items" data-ref="'+response.unitName+'">Make Inactive </a></td>';
            appendTo += '</tr>';
            jQuery('#tableData table tr:first').after(appendTo);

            jQuery('#UOM').append( '<option value="'+response.unitRef+'">'+response.unitName+'</option>' );
            jQuery('#UOM').val(response.unitRef);
          }
          else {
            jQuery('#measurements_'+response.unitRef).find('.updateUOM').html(response.unitName);
          }
        }
        if (response.newSheet) {
          if (response.newLoadingSheet) {
              var count = jQuery('#tableData table tbody tr').length + 1;
              appendTo += '<tr id="loadingSheet_'+response.data.sheetRef+'">';
              appendTo += '<td class="srNum">'+count+'</td>';
              appendTo += '<td><a href="javascript:void(0)" class="updateUOM" data-ref="'+response.data.sheetRef+'" data-name="'+response.data.refName+'">'+response.data.refName+'</a></td>';
              appendTo += '<td class="statusTd"><span class="label label-success">Active</span></td>';
              appendTo += '<td><a href="javascript:void(0);" data-status="1" class="updateStatus" data-name="'+response.data.refName+'" data-type="loadingSheet" data-ref="'+response.data.sheetRef+'">Make Inactive </a></td>';
              appendTo += '</tr>';
              $('#tableData table tr:first').after(appendTo);
              $("td.srNum").each(function(i,v) {
              	$(v).text(i + 1);
              });
          } else {
            jQuery('#loadingSheet_'+response.data.sheetRef).find('.updateSheet').html(response.data.refName);
          }
        }

        if (response.cityRecord) {
          if (response.update) {
            jQuery('#cities_'+response.data.city_id).find("td:eq(1)").html(response.data.name);
          }else{
            var count = jQuery('.detailDrow #tableData table tbody tr').length + 1;
            appendTo += '<tr id="cities_'+response.data.city_id+'">';
            appendTo += '<td class="srNum">'+count+'</td>';
            appendTo += '<td><a href="javascript:void(0)" data-parent="'+response.data.sta_id+'" class="updateCity" data-ref="'+response.data.city_id+'" data-name="'+response.data.name+'">'+response.data.name+'</a></td>';
            appendTo += '<td class="statusTd"><span class="label label-success">Active</span></td>';
            appendTo += '<td><a href="javascript:void(0)" data-parent="'+response.data.sta_id+'" data-ref="'+response.data.city_id+'" class="updateCity"> <i class="fa fa-edit"></i></a>  &nbsp;&nbsp;\
            <a href="javascript:void(0);" data-status="1" class="updateStatus" data-name="'+response.data.name+'" data-type="cities" data-ref="'+response.data.city_id+'">Make Inactive </a></td>';
            appendTo += '</tr>';
            $('.detailDrow  #tableData table tr:first').after(appendTo);
            $(".detailDrow td.srNum").each(function(i,v) {
              $(v).text(i + 1);
            });

            $(document).find('tr.noRecord').hide();
          }
        }
        $("td.srNum").each(function(i,v) {
          $(v).text(i + 1);
        });
    },
    error:function(response){
      var delayTime = 3000;
      iziToast.error({
        timeout: delayTime,
        title: 'Error',
        message: 'Connection Error!',
        position: 'bottomRight',
      });
    }
  });

}
