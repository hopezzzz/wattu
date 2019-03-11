<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
  //sudo a2enmod rewrite
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/********** Routes for web ************/

$route['login']             	   = "LoginController/login";
$route['forget-password']    	   = "LoginController/forgetPassword";
$route['logout']            	   = "LoginController/logout";
$route['checkOldPassword'] 		   = 'UserController/checkOldPassword';
$route['updateUserDetails'] 	   = 'UserController/updateUserDetails';

$route['dashboard']         	           = "DashboardController/dashboard";
$route['dashboard']         	           = "DashboardController/dashboard";
$route['home']              	           = "DashboardController/home";
$route['getNotifications']               = "DashboardController/getNotifications";
$route['notifications']                  = "DashboardController/notificationListing";
$route['updateNotificationStatus']       = "DashboardController/updateNotificationStatus";
$route['get-statistics']                 = "DashboardController/getStatistics";
$route['global-search']                  = "DashboardController/globalSearch";
/***** User Management Routes *******/
$route['users']                    = "UserController/users";
$route['add-user']                 = "UserController/addNewUser";
$route['update-user/(:any)']       = "UserController/updateUser/$1";
$route['view-user/(:any)']         = "UserController/updateUser/$1";
$route['updateUserAjax']           = "UserController/updateUserAjax";
$route['register-user']            = "UserController/userRegister";
$route['my-profile']               = "UserController/myProfile";
$route['searchSalesman']           = "UserController/searchSalesman";
$route['update-user-password']     = "UserController/updateUserPassword";


/******************Items and categories routes **************/
$route['items']                    = "ItemController/items";
$route['ajaxAddUpdateItem']        = "ItemController/ajaxAddUpdateItem";
$route['getItemDetails']           = "ItemController/getItemDetails";
$route['categories']               = "ItemController/categories";
$route['getSubCategories']		     = 'ItemController/getSubCategories';
$route['ajaxAddUpdateCategory']    = "ItemController/ajaxAddUpdateCategory";
$route['export-items']             = "ItemController/exportCSV";
$route['import-items']             = "ItemController/importItems";
$route['searchItemsbyName']        = "ItemController/searchItemsbyName";
$route['getAddItem']               = "ItemController/getAddItem";
/*************************************************************************/

/******************Customer Management Routes *****************************/
$route['customers-list']           = "CustomerController/customers";
$route['add-customer']             = "CustomerController/addNewCustomer";
$route['update-customer/(:any)']   = "CustomerController/updateCustomer/$1";
$route['view-customer/(:any)']     = "CustomerController/updateCustomer/$1";
$route['AjaxAddUpdateCustomer']    = "CustomerController/AjaxAddUpdateCustomer";
$route['searchCustomer']           = "CustomerController/searchCustomerByName";
$route['import-customers']         = "CustomerController/importCustomers";
$route['export-customers']         = "CustomerController/exportCustomers";
$route['get-follow-up-error/(:any)']         = "CustomerController/getFollowupError/$1";

/**************************************************************************/
$route['delivery-method']          = "SettingController/deliveryMethod";
$route['addUpdateDeliveryMethod']  = "SettingController/addUpdateDeliveryMethod";

$route['block-types']              = "SettingController/blockTypes";
$route['addUpdateblockTypes']      = "SettingController/addUpdateblockTypes";

$route['pricing-method']           = "SettingController/pricingMethod";
$route['addUpdatePricingMethod']   = "SettingController/addUpdatePricingMethod";
$route['unit-of-measurement']      = "SettingController/unitOfMeasurement";
$route['addUnitOfMeasurement']     = "SettingController/addUnitOfMeasurement";


$route['regions']                  = "SettingController/regions";
$route['cities/(:any)']            = "SettingController/getCitesByStaeId/$1";
$route['addUpdateCity']            = "SettingController/addUpdateCity";
$route['addNewRegion']             = "SettingController/addUpdateNewRegion";
$route['transport-charges']        = "SettingController/transportCharges";
$route['add-new-transport-charge'] = "SettingController/addNewTransportCharge";
$route['update-transport-charges/(:any)'] = "SettingController/updateTransportCharges/$1";

$route['production-output']         = "SettingController/productionOutput";
$route['add-new-production-output'] = "SettingController/addUpdateProductionOutput";
$route['addUpdateProductionOutput'] = "SettingController/addUpdateProductionOutput";
$route['exportExcel']               = "SettingController/exportExcel";
$route['import-transport']          = "SettingController/importTransport";

$route['loading-sheets']            = "SettingController/loadingSheets";
$route['add-loading-sheet']         = "SettingController/addLoadingSheet";
$route['import-transport']          = "SettingController/importTransport";


/************************Settings Routes **************/


/***********************order management *******************/
$route['sales']                                = "OrderController/sales";
$route['management']                           = "OrderController/management";
$route['approval']                             = "OrderController/approval";
$route['production-processing']                = "OrderController/production";
$route['dispatch-processing']                  = "OrderController/dispatch";
$route['customer-follow-up']                   = "OrderController/customerFollowUp";
$route['order-details/(:any)']                 = "OrderController/orderDetails/$1";
$route['dispatch-order-details/(:any)']        = "OrderController/dispatchOrderDetails/$1";
$route['get-dispatch-items/(:any)']            = "OrderController/getDispatchedItems/$1";
$route['order-details/(:any)/(:any)/(:any)']   = "OrderController/orderDetails/$1/$2/$3";
$route['addComment']                           = "OrderController/addComment/";
$route['orderSearch']                          = "OrderController/orderSearch/";
$route['mark-load-orders']                     = "OrderController/markAsLoad/";
$route['get-to-load-orders']                   = "OrderController/getToLoadOrders/";
$route['load-orders-to-loadingsheet']		       = 'OrderController/loadOrdersToLoadingsheet';
$route['remove-loading-orders']		             = 'OrderController/removeLoadingOrders';
$route['modify-load-orders-sheet']		         = 'OrderController/modifyLoadedOrders';
$route['get-dispached-order-details']		       = 'OrderController/getDispatchedOrders';
$route['download-sheet/(:any)/(:any)']	       = 'OrderController/downloadSheet/$1/$2';
$route['save-dispatch']		                     = 'OrderController/saveDispatch';


/*********************************************************************************/

$route['customer-follow-up']		               = 'CustomerController/followup';
// $route['customer-follow-up-orders/(:any)']		 = 'CustomerController/followupOrders/$1';
$route['customer-follow-up-orders']		       = 'CustomerController/followupOrders/';
$route['save-customer-follow-up']		           = 'CustomerController/saveCustomerFollowup';
$route['export-customer-follow-up/(:any)/(:any)']		         = 'CustomerController/exportCustomerFollowup/$1/$2';


/********************* Customer Follow-up Routes *********************************/

/********************* Customer Follow-up Routes *********************************/


/*******************************************************/
/** Common funtions **/
$route['chage-order-status']		= 'welcome/changeOrderStatus';
$route['delete-record']			    = 'welcome/deleteRecord';
$route['update-status']			    = 'welcome/updateStatus';
$route['getStates']			        = 'welcome/getStates';
$route['getCities']			        = 'welcome/getCities';
$route['re-arrage-orders']			= 'welcome/reArrageOrders';
$route['addNewLoadingSheet']		= 'welcome/addNewLoadingSheet';
/**********************/



/**************************************/

############  ROUETES FOR  APP ###########
$route['App/user_signin']               = 'api/Webservicecontroller/userSignIn';
$route['App/forgotPassword']            = 'api/Webservicecontroller/forgotPassword';
$route['App/getuserdetails']            = 'api/Webservicecontroller/getUserDetails';
$route['App/updateUserDetails']         = 'api/Webservicecontroller/updateUserDetails';
$route['App/changePassword']            = 'api/Webservicecontroller/changePassword';
$route['App/getcategories']             = 'api/Webservicecontroller/getcategories';
// $route['App/allItems']               = 'api/Webservicecontroller/allItems';
$route['App/itemsByCatRef']             = 'api/Webservicecontroller/itemsByCatRef';
$route['App/searchCustomer']            = 'api/Webservicecontroller/searchCustomer';
$route['App/getcountries']              = 'api/Webservicecontroller/getcountries';
$route['App/getstates']                 = 'api/Webservicecontroller/getstates';
$route['App/getcity']                   = 'api/Webservicecontroller/getcity';
$route['App/placeOrderOptions']         = 'api/Webservicecontroller/placeOrderOptions';
$route['App/submitOrder']               = 'api/Webservicecontroller/submitOrder';
$route['App/updateOrder']               = 'api/Webservicecontroller/updateOrder';
$route['App/ItemDetailsByRef']          = 'api/Webservicecontroller/ItemDetailsByRef';
$route['App/checkItemMinimumPrice']     = 'api/Webservicecontroller/checkItemMinimumPrice';
$route['App/matchItemPrice']            = 'api/Webservicecontroller/matchItemPrice';
$route['App/customerLastPrice']         = 'api/Webservicecontroller/customerLastPrice';
$route['App/getOrders']                 = 'api/Webservicecontroller/getOrders';
$route['App/getOrderDetails']           = 'api/Webservicecontroller/getOrdersItems';
$route['App/deleteOrder']          	    = 'api/Webservicecontroller/deleteOrder';
$route['App/addNewComment']             = 'api/Webservicecontroller/addNewComment';
$route['App/userNotifications']         = 'api/Webservicecontroller/userNotifications';
$route['App/updateNotificationStatus']  = 'api/Webservicecontroller/updateNotificationStatus';
$route['App/deleteNotification']        = 'api/Webservicecontroller/deleteNotification';
$route['App/getNotificationCount']      = 'api/Webservicecontroller/getNotificationCount';
$route['App/orderAction']               = 'api/Webservicecontroller/orderAction';
$route['App/orderStats']                = 'api/Webservicecontroller/orderStats';
$route['App/userTargets']               = 'api/Webservicecontroller/userTargets';
$route['App/getUserTargets']            = 'api/Webservicecontroller/getUserTargets';
$route['App/getTransportCharges']       = 'api/Webservicecontroller/getTransportCharges';

############  ROUETES FOR  APP ###########
$route['default_controller'] = 'LoginController/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
