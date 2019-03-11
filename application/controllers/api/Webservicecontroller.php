<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
  header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

  exit(0);

}


// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Webservicecontroller extends REST_Controller {
  function __construct() {

    // Construct the parent class
    parent::__construct();
    $this->load->model('AppLogin');
    $this->load->model('UsersModel');
    $this->load->model('OrderModel');
    $this->load->model('ItemModel');
    $this->load->model('CustomerModel');
    $this->load->model('CommonModel');
    $this->perPageNum = 10;
  }

  public function userSignIn_post() {

    $rest_json=file_get_contents("php://input");
    $postData=json_decode($rest_json, true);
    if ($postData)
    {
      $response = $this->AppLogin->login($postData);
      if ($response['success'] == true)
      {
          unset($response['data']->password);
          if ($response['data']->isDeleted == 1)
          {
            $output['success']       = false;
            $output['error_message'] = 'Your account is deleted. Please contact to Administrator.';
            $this->response($output,REST_Controller::HTTP_CONFLICT);
          }
          elseif ($response['data']->userActive == 0)
          {
            $output['success']       = false;
            $output['error_message'] = 'You have no permission to login in this App. Please contact to Administrator.';
            $this->response($output,REST_Controller::HTTP_CONFLICT);
          }
          else
          {
            $output['success']      = true;
            $output['login_status'] = 1;
            $output['message']      = $response['success_message'];
            $output['data']         = $response['data'];
            $this->response($output);
          }
        }
        else
        {
           $output['success'] = false;
           $output['error_message'] = $response['error_message'];
           $this->response($output,REST_Controller::HTTP_CONFLICT);
        }
    }
    else
    {
        $output['success'] = false;
        $output['error_message'] = 'Check your parameter..';
        $this->response($output,REST_Controller::HTTP_BAD_REQUEST);
    }

  }
  /*
  *
  *function for forget password
  */
  public function forgotPassword_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);

    if ($postData) {
      $response = $this->AppLogin->forgotPassword($postData);
      if ($response['success'] == true) {
        $output['success'] = true;
        $output['success_message']    = $response['message'];
        $output['email']              = $response['email'];
        $output['password']           = $response['password'];
        $this->response($output);
      } else {
        $output['success']          = false;
        $output['error_message']    = $response['message'];
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for updateUserDetails
  */
  public function updateUserDetails_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);

    if ($postData) {
      $response = $this->AppLogin->updateUserDetails($postData);
      if ($response['success'] == true) {
        $output['success'] = true;
        $output['success_message']    = $response['success_message'];
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = $response['error_message'];
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for getUserDetails
  */
  public function getUserDetails_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);

    if ($postData) {
      $response = $this->UsersModel->getDataByRef($postData['userRef']);
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'Your information is not match please try again.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  /*
  *
  *function for getUserDetails
  */
  public function changePassword_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData)
    {
      $oldPasswordVerify = $this->UsersModel->checkOldPassword($postData['userRef'], MD5($postData['oldPassword']));
      if ($oldPasswordVerify) {
        $data['password'] =  md5($postData['newPassword']);
        $data['userRef']  = $postData['userRef'];
        $response = $this->UsersModel->updateUserDetails($data);
        if ($response) {
          $output["success"] = true;
          $output["success_message"] = 'Your new password has been changed!';
          $this->response($output);
        } else {
          $output["success"] = false;
          $output["error_message"] = 'Something went wrong please try again.';
          $this->response($output, REST_Controller::HTTP_CONFLICT);
        }
      }
      else
      {
        $output['success'] = false;
        $output['error_message'] = 'Old Password is incorrect. Please fill correct one';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for getcategories
  */
  public function getcategories_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);

    if ($postData) {
      $response = getCategories();
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'No Category added yet.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for allItems
  */
  public function allItems_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);

    if ($postData) {

      $page = ($postData["page"]) ? $postData["page"] : 0;
      $page = $this->perPageNum * $page;
      $response = $this->ItemModel->getItems($page,$this->perPageNum);
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'No Category added yet.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for allItems
  */
  public function itemsByCatRef_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);

    if ($postData) {

      $page = ($postData["page"]) ? $postData["page"] : 0;
      $page = $this->perPageNum * $page;
      $response = $this->ItemModel->itemsByCatRef($page,$this->perPageNum, null , $postData['catRef'],$postData);
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'No Category added yet.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for searchCustomer
  */
  public function searchCustomer_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);

    if ($postData) {

      $response = $this->CustomerModel->searchCustomer($postData);
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'No Customer added yet.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  /*
  *
  *function for countries
  */
  public function getcountries_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {

      $response = getCountries($postData['searchKey']);
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'No Category added yet.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for getstates
  */
  public function getstates_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {

      $response = getRegionsByCountryId($postData['countryId']);
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'No Country added yet.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for getcity
  */
  public function getcity_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {

      $response = getCitiesByCityName($postData['searchkey'],$postData['stateId']);
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'No City found added yet.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  /*
  *
  *function for placeOrderOptions
  *
  */
  public function placeOrderOptions_post() {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {

      $response['deliveryMethod'] = getDeliveryMethods();
      $response['payementMethod'] = getPaymentMethods();
      $response['approvedBy']     = getManagers();
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'No records added yet.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for submitOrder
  *
  */
  public function submitOrder_post() {
    $rest_json = file_get_contents("php://input");
    log_message('error', $rest_json);
    $postData = json_decode($rest_json, true);
    // echo "<pre>";
    // print_r($postData);
    // die;
    if ($postData) {

      if (isset($postData['orderRef']) && trim($postData['orderRef']) !='')  {
          $record = $this->CommonModel->deleteOrder($postData['orderRef']);

      }

      if (trim($postData['deliveryMethodRef']) =='' && trim($postData['newDeliveryMethod']) !='') {
        $data = array(
          'deliveryMethodRef' => generateRef(),
          'methodName'    => $postData['newDeliveryMethod'],
          'area'          => $postData['newAreaCode'],
          'status'        => 1,
          'addedBy'       => $postData['userRef'],
          'addedOn'       => date('Y-m-d'),
          'modifiedDate'  => date('Y-m-d'),
        );
        $this->CommonModel->insert('ws_deliveryMethod',$data);
      }
      if (empty($postData['orderItems'])) {
        $output['success']            = false;
        $output['error_message']      = 'Oops, no items selected for order please select atleast one item to place order.';
        $this->response($output,REST_Controller::HTTP_CONFLICT);
      }
      if (trim($postData['customerRef']) == '')
      {
        foreach ($postData['newCustomer'] as $key => $value)
        {
          if (!empty($value)) {
            $isCatExits = $this->CommonModel->checkexist('ws_customers', array('phoneNo1 =' => $value['custPhone']));
            if ($isCatExits)
            {
              $output['success']          = false;
              $output['error_message']    = 'Oops, Customer Contact Phone  already taken, please try new one.';
              $this->response($output,REST_Controller::HTTP_CONFLICT);
            }else
            {
              $name = explode(' ', $value['custName']);
              if (count($name) == 1) {
                $name[1] = '';
              }
              $customerRef = generateRef();
              $data = array(
                "customerRef"   => $customerRef,
                "firstName"     => $name[0],
                "lastName"      => $name[1],
                "addressLine"   => $value['custAddress'],
                "cityId"        => $value['custCity'],
                "phoneNo1"      => $value['custPhone'],
                "stateId"       => $value['custState'],
                "countryId"     => 49,
                "status"        => 1,
              );
              $customerAdd = $this->CommonModel->insert('ws_customers',$data);
              if($customerAdd)
              {
                $postData['customerRef'] = $customerRef;
                $response = $this->OrderModel->submitOrder($postData);
              }else
              {
                $output['success']            = false;
                $output['error_message']      = 'something went wrong please try again.';
                $this->response($output,REST_Controller::HTTP_CONFLICT);
              }
            }
          }
        }
      }
      else{
        $response = $this->OrderModel->submitOrder($postData);
      }
      if ($response['success'] == true) {
        $output['success'] = true;
        $output['success_message'] = $response['success_message'];
        log_message('error', json_encode($output));
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'No records added yet.';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  /*
  *
  *function for ItemDetailsByRef_post
  * get items deatils
  */
  public function ItemDetailsByRef_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response = $this->ItemModel->getProductByRef($postData['itemRef'],$postData);
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        unset( $response->id);
        unset( $response->itemImage);
        unset( $response->addedOn);
        unset( $response->addedBy);
        unset( $response->modifiedDate);
        unset( $response->length);
        unset( $response->height);
        unset( $response->width);
        $output['data'] = $response;
        $ItemlastPrice = $this->OrderModel->customerLastPrice($postData);

        if ($ItemlastPrice['success'] == true) {
          $output['ItemlastPrice']       = $ItemlastPrice['data']->price;
          $this->response($output);
        } else {
          $output['ItemlastPrice']       = '';
        }
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'Opps. no record found please try with new entries';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for checkItemMinimumPrice
  *
  */
  public function checkItemMinimumPrice_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response = $this->ItemModel->getItemMinimumPrice($postData);
      if ($response['success'] == true) {
        $output['success'] = true;
        $output['success_message'] = $response['success_message'];
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = $response['error_message'];
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for matchItemPrice
  *
  */
  public function matchItemPrice_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response = $this->ItemModel->matchItemPrice($postData);
      if (!empty($response)) {
        $output['success'] = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['data'] = $response;
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'Opps. no record found please try with new entries';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  /*
  *
  *function for customerLastPrice
  *
  */
  public function customerLastPrice_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response = $this->OrderModel->customerLastPrice($postData);

      if ($response['success'] == true) {
        $output['success']         = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['lastPrice']       = $response['data'];
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = $response['error_message'];
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  /*
  *
  *function for customerLastPrice
  *
  */
  public function getOrders_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response = $this->OrderModel->getOrders($postData);

      if (!empty($response)) {
        unset($response['data']->id);
        $output['success']         = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $output['orderList']    = $response['data'];
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = $response['error_message'];
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for customerLastPrice
  *
  */
  public function getOrdersItems_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response = $this->OrderModel->apigetOrdersItems($postData);
      if (!empty($response)) {
        unset($response['data']->id);
        $output['success']         = true;
        $output['success_message'] = 'Data Retrive successfully.';
        $orderComments['orderComments'] =   $this->OrderModel->orderComments($postData['orderRef']);
        $OrderDetails                   =   $this->OrderModel->orderDetails($postData['orderRef']);
        $orderDeliveryAddress           =   $this->OrderModel->getDeliveryAddress($postData['orderRef']);
        unset($OrderDetails->id);
        $output['OrderDetails']         =   $OrderDetails;
        $output['orderDeliveryAddress'] =   $orderDeliveryAddress;
        if (!empty($orderComments))
        {
          $output['orderComments']      =    $orderComments['orderComments'];
        }
        else
        {
          $output['orderComments']      =   array();
        }
        $output['items']                =   $response['data'];
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = $response['error_message'];
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

/*
  *
  *function for addNewComment
  *
  */
  public function addNewComment_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $data =  array(
        'commentRef'   => generateRef(),
        'comment'      => $postData['comment'],
        'orderRef'     => $postData['orderRef'],
        'addedBy'      => $postData['userRef'],
        'addedOn'      => date('Y-m-d H:i:s'),
        'modifiedDate' => date('Y-m-d H:i:s'),
        'status'       => '',
      );
      $response = $this->CommonModel->insert('ws_orderComments',$data);
       if($response){
         $notificationTo = '';
          $orderRef = $postData['orderRef'];
          $notificationData = getRegisterUsersIds($orderRef);
          $salesmanName     = getUserDetailsByRef($postData['userRef']);
          $getOrderByRef    = getOrderByRef($postData['orderRef']);
          if (!empty($notificationData)) {
            $notificationRef = generateRef();
            $notificationTitle = $salesmanName->userName.' comment on Order # '.$getOrderByRef->orderNo;
            foreach ($notificationData as $key => $value) {
              $notificationTo  = $value->userRef;
              $registerID[]    = $value->registeredId;
              $deviceID[]      = $value->device_id;
              $notification = array();
              $notification['notificationRef'] 							= $notificationRef;
              $notification['orderRef'] 										= $orderRef;
              $notification['notificationFrom'] 					  = $notificationFrom = (isset($salesmanName->userRef)) ? $salesmanName->userRef : '';
              $notification['notificationTo'] 							= $notificationTo;
              $notification['notificationBussinessName'] 		= $getOrderByRef->businessName;
              $notification['notificationContactName'] 			= $getOrderByRef->customerName;
              $notification['notificationTitle'] 						= $notificationTitle;
              $notification['notificationMessage']  				= ucfirst($data['comment']);
              $notification['starredStaus'] 								= 0;
              $notification['status'] 											= 1;
              $notification['readStatus'] 									= 1;
              $notification['addedOn'] 											= date('Y-m-d H:i:s');
               // echo "<pre>";print_r($notification);die;
              $insertData = $this->CommonModel->insert('ws_notification',$notification);

            }
            $message = array(
                'message_id'        => $notificationRef,
                'message'           => ucfirst($data['comment']),
                'messagetitle'      => $notificationTitle,
                'type'              => 1,
                'notificationType'  => 1,
                'msg_type'          => 'Order comment.',
                'orderRef'          => $orderRef,
                'dataRefTo'         => $orderRef,
                'title'             => $notificationTitle,
                'notificationRef'   => $notificationRef,
                'notification'      => $salesmanName->userName.' comment on Order # '.$getOrderByRef->orderNo,
            );
            $array = array(
              'message'     =>  $message,
              'registerID'  =>  $registerID,
              'deviceID'    =>  $deviceID,
            );
            if($message['dataRefTo'] != $postData['userRef']){
              $sendNotification = sendNotification($array);
            }
            // echo "<pre>";print_r($sendNotification);die;
          }
        }
      if ($response) {
        $output['success']               = true;
        $output['success_message']       = 'Comment Added successfully successfully.';
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'Something went wrong please try again';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for userNotifications
  *
  */
  public function userNotifications_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response = $this->OrderModel->userNotifications($postData);
      if ($response['success'] == true) {
        $output['success_message']       = $response['success_message'];
        $output['data']       = $response['data'];
        $this->response($output);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'Something went wrong please try again';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for markAsReadNotification
  * to mark notification as read
  */
  public function updateNotificationStatus_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      unset($postData['userRef']);
      // $postData['readStatus'] = 2;
      $response = $this->CommonModel->update(array('notificationRef' => $postData['notificationRef']),  $postData, 'ws_notification');
      if($response) {
          $output['success']               = true;
          $output['success_message']       = 'notification updated successfully.';
          $this->response($output);
      } else {
          $output['success']            = false;
          $output['error_message']      = 'Something went wrong please try again';
          $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  /*
  *
  *function for deleteNotification
  * to delete notification
  */
  public function deleteNotification_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response = $this->CommonModel->delete($postData['notificationRef'], 'deleteNotification');
      if($response) {
          $output['success']               = true;
          $output['success_message']       = 'notification deleted successfully.';
          $this->response($output);
      } else {
          $output['success']            = false;
          $output['error_message']      = 'Something went wrong please try again';
          $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  public function getNotificationCount_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response = $this->CommonModel->getNotificationCount($postData['userRef']);
      if($response) {
          $output['success']               = true;
          $output['notificationCount']       = $response['notificationCount'];
          $this->response($output);
      } else {
          $output['success']            = false;
          $output['error_message']      = 'Something went wrong please try again';
          $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  public function deleteOrder_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {
      $response   = $this->CommonModel->delete($postData['orderRef'],'orders');
      if($response) {
          $output['success']                 = true;
          $output['notificationCount']       = 'Order Deleted successfully.';
          $this->response($output);
      } else {
          $output['success']            = false;
          $output['error_message']      = 'Something went wrong please try again';
          $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }
  public function orderAction_post()
  {
        $rest_json = file_get_contents("php://input");
        $postData = json_decode($rest_json, true);
        if ($postData)
        {
          $getOrderByRef    = getOrderByRef($postData['orderRef']);
          $data             = getRegisterIds($postData['orderRef'],$getOrderByRef->orderPipline);
          $updateStatus     = array('reAssign' => 'Re-assigned', 'approved' => 'Approved','cancelled' => 'Cancelled' );
          $updateArray      = array();
          if ($postData['type'] == 'reAssign') {
            $updateArray = array(
              'orderStatus'     => 'reAssign',
              'salesRef'        => $data['orderData']->salesRef,
              'modifiedDate'    => date('Y-m-d H:i:s'),
              'orderPipline'    => 2,
            );
          } else if ($postData['type'] == 'approved') {
            $updateArray = array(
              'managerApprove' => 2,
              'approvedBy'     => $postData['userRef'],
              'orderRef'       => $postData['orderRef'],
              'modifiedDate'   => date('Y-m-d H:i:s'),
              'orderStatus'    => 'open',
              'orderPipline'   => 4,
            );
          }
           else if ($postData['type'] == 'cancelled') {
            $updateArray = array(
              'orderRef'       => $postData['orderRef'],
              'modifiedDate'   => date('Y-m-d H:i:s'),
              'orderStatus'    => 'cancelled',
              'orderPipline'   => 4,
            );
          }
          $response = $this->CommonModel->update(array('orderRef' => $postData['orderRef']) , $updateArray , 'ws_orders');
          if($response)
          {
            if (!empty($data['users']) && !empty($data['orderData'])) {
                $notificationRef       = generateRef();
                $notificationTitle  = 'Order # '.$data['orderData']->orderNo.' '.$updateStatus[$postData['type']];
                $message = array(
                    'message_id'        => $notificationRef,
                    'message'           => 'Order # '.$data['orderData']->orderNo.' marked as '.$updateStatus[$postData['type']],
                    'messagetitle'      => $notificationTitle,
                    'type'              => 1,
                    'notificationType'  => 1,
                    'msg_type'          => 'Order Status Update.',
                    'orderRef'          => $postData['orderRef'],
                    'dataRefTo'         => $postData['orderRef'],
                    'title'             => $notificationTitle,
                    'notificationRef'   => $notificationRef,
                    'notification'      =>  'Order # '.$data['orderData']->orderNo.' '.$updateStatus[$postData['type']],
                );
                foreach ($data['users'] as $key => $registeredUsers) {
                  if ($postData['userRef'] != $registeredUsers->userRef) {
                    $registerID[]                                 = $registeredUsers->registeredId;
                    $deviceID[]                                   = $registeredUsers->device_id;
                    $managerInfo                                  = $this->UsersModel->getDataByRef($registeredUsers->userRef);
                    $userName                                     = (!empty($managerInfo)) ? $managerInfo->userName : 'Admin';
                    $notification = array();
                    $notification['notificationRef'] 							= $notificationRef;
                    $notification['orderRef'] 										= $postData['orderRef'];
                    $notification['notificationFrom'] 					  = $postData['userRef'];
                    $notification['notificationBussinessName'] 		= $data['orderData']->businessName;
                    $notification['notificationContactName'] 			= $data['orderData']->customerName;
                    $notification['notificationTo'] 							= $notificationTo = (isset($registeredUsers->userRef)) ? $registeredUsers->userRef : '';
                    $notification['notificationTitle'] 						= $notificationTitle;
                    $notification['notificationMessage']  				= 'Order mark as '.$updateStatus[$postData['type']].' by '.$userName;
                    $notification['starredStaus'] 								= 0;
                    $notification['status'] 											= 1;
                    $notification['readStatus'] 									= 1;
                    $notification['addedOn'] 											= date('Y-m-d H:i:s');
                    $insertData = $this->CommonModel->insert('ws_notification',$notification);
                  }
                }
                $array  = array(
                    'message' => $message,
                    'registerID' => $registerID,
                    'deviceID' => $deviceID
                );
                $sendNotification                                 = sendNotification($array);
            }

              $output['success']                 = true;
              $output['success_message']         = 'Order updated successfully.';
              $this->response($output);
          }
          else
          {
              $output['success']            = false;
              $output['error_message']      = 'Something went wrong please try again';
              $this->response($output, REST_Controller::HTTP_CONFLICT);
          }
      }
      else {
          $output['success'] = false;
          $output['error_message'] = 'Check your parameter.';
          $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
      }
  }
  public function orderStats_post()
  {
    $rest_json = file_get_contents("php://input");
    $postData = json_decode($rest_json, true);
    if ($postData) {

      $response   = $this->CommonModel->getOrdersStats($postData['userRef']);
      if($response) {
        /*$output['success']                                     =  true;
        $output['success_message']                             =  'Orders Stats Retrive successfully..';

        $salesTarget = 0;
        $res = $response['orderStats'][0];
        $output['totalOrder'] = $res->totalOrder ;
        $getUserTargets   = $this->UsersModel->getUserTargets($postData['userRef']);
        if (!empty($getUserTargets['data'])) {
          foreach ($getUserTargets['data'] as $key => $value)
          {
            if ($value->targetType == 'monthlyTarget') {
              $output['Sales']['targetAmount']                       =  number_format($value->targetValue,2);
              $output['Sales']['targetAmountType']                   =  $value->valueType;
              $salesTarget = $value->targetValue;
            }else {
              $output[$value->targetType]['myTarget']         =  $value->targetValue;
              $output[$value->targetType]['targetType']       =  $value->valueType;
            }

          }
        }
        // echo $salesTarget;die;
        $temp                                                  =  $salesTarget;

        $output['Sales']['targetAmount']                       =  number_format($temp,2);
        $output['Sales']['totalSalesAmount']                   =  number_format($res->orderPrice,2);
        $output['Sales']['totalSalesPercentage']               =  number_format( ($res->orderPrice > 0 && $temp > 0) ? ( $res->orderPrice / $temp ) * 100 : 0 ,1) ;

        $output['financeOrders']['totalOrder']                 =  $res->approvedOrders;
        $output['financeOrders']['orderPercentage']            =  number_format( ($res->totalOrder > 0) ? ( $res->approvedOrders / $res->totalOrder ) * 100 : 0 , 2);

        $output['approvalOrders']['totalOrder']                =  $res->managerApprovedOrders;
        $output['approvalOrders']['orderPercentage']           =  number_format( ($res->totalOrder > 0) ? number_format( ( $res->managerApprovedOrders / $res->totalOrder ) * 100,1) : 0 , 2);

        $output['cancelledOrders']['totalOrder']               =  $res->cancelledOrders;
        $output['cancelledOrders']['orderPercentage']          =  number_format( ($res->totalOrder > 0) ? number_format( ( $res->cancelledOrders / $res->totalOrder ) * 100,1) : 0 , 2 );

        $output['refuesedOrders']['totalOrder']                =  0;
        $output['refuesedOrders']['orderPercentage']           =  0;

        $output['reAssingOrders']                              = $response['reAssingOrders'];
        // $output['notificationCount']                           = $response;*/

        $this->response($response);
      } else {
        $output['success']            = false;
        $output['error_message']      = 'Something went wrong please try again';
        $this->response($output, REST_Controller::HTTP_CONFLICT);
      }
    } else {
      $output['success'] = false;
      $output['error_message'] = 'Check your parameter.';
      $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function userTargets_post()
  {
      $rest_json = file_get_contents("php://input");
      $postData = json_decode($rest_json, true);

      if ($postData) {
        // echo "<pre>";print_r($postData);die;
        $response   = $this->UsersModel->getUserTargets($postData['userRef']);
        if($response['success']) {
          $deleteOldTargets = $this->UsersModel->deleteUserTargets($response['data'][0]->targetRef);
          $output['success']            = true;
          $addedOn                      = $response['data'][0]->addedOn;
          $output['success_message']    = 'Targets information updated successfully';
        } else {
          $output['success']            = true;
          $addedOn                      = date('Y-m-d');
          $output['success_message']    = 'Targets information inserted successfully';
        }
        $i = 0;
        $generateRef = generateRef();
        foreach ($postData['userTargets'] as $key => $value) {
          $addTargets[$i] = array(
            'userRef'       => $postData['userRef'],
            'targetRef'     => $generateRef,
            'targetType'    => $key,
            'targetValue'   => $value['value'],
            'valueType'     => $value['valueType'],
            'addedOn'       => $addedOn,
            'modifiedDate'  => date('Y-m-d'),
          );
          $i++;
        }
        // echo "<pre>";print_r($addTargets);die;
        $data = $this->db->insert_batch('ws_userTargets', $addTargets);
        $this->response($output);
      } else {
        $output['success'] = false;
        $output['error_message'] = 'Check your parameter.';
        $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
      }
  }
  public function getUserTargets_post()
  {
      $rest_json = file_get_contents("php://input");
      $postData = json_decode($rest_json, true);
      if ($postData) {
        $response   = $this->UsersModel->getUserTargets($postData['userRef']);
        if($response['success']) {
          $output['success']            = true;
          $output['success_message']    = 'Targets information updated successfully';
          foreach ($response['data'] as $key => $value)
          {
            $output['userTargets'][$value->targetType][]  = array('value' => $value->targetValue , 'valueType' => $value->valueType)  ;
          }
          $this->response($output);
        } else {
          $output['success']            = false;
          $output['error_message']    = 'No entries found please add new entries';
          $this->response($output);
        }
      } else {
        $output['success'] = false;
        $output['error_message'] = 'Check your parameter.';
        $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
      }
  }

  // function to get all active transport transportCharges
  // funtions name getTransportCharges()

  public function getTransportCharges_post()
  {
      $rest_json = file_get_contents("php://input");
      $postData = json_decode($rest_json, true);
      // pr($postData);die;
      if ($postData) {
        $response   = $this->UsersModel->getTransportCharges($postData);
        if($response['success']) {
          $output['success']        = true;
          $output['chargesList']    =  $response['chargesList'];
          $this->response($output);
        } else {
          $output['success']          = false;
          $output['error_message']    = 'No entries found please add new entries';
          $this->response($output);
        }
      } else {
        $output['success'] = false;
        $output['error_message'] = 'Check your parameter.';
        $this->response($output, REST_Controller::HTTP_BAD_REQUEST);
      }
  }

}
