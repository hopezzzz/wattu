<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class OrderController extends CI_Controller {
  function __construct()
  {
    // Construct the parent class
    parent::__construct();
    $this->load->model('OrderModel');
    $this->perPage = 10;
    $loginSessionData = $this->session->userdata('clientData');
    // echo "<pre>";print_r($loginSessionData);die;
    $this->userRef  = $loginSessionData['userRef'];
    $this->userName = $loginSessionData['userName'];
    $this->userType = $loginSessionData['userType'];
    if(empty($loginSessionData) )
    {
      redirect();
    }
  }

  /*
  * Function Name sales
  *
  * loading all sales orders.
  *
  */

  public function sales()
  {
    $output['title']              = 'Order Management | Sales';
    $output['breadcrumbs']        = 'Sales';

    $this->make_bread->add('Home', 'home', 0);
    $this->make_bread->add('Orders', '', 0);
    $this->make_bread->add('Sales', '', 0);
    $breadcrumb = $this->make_bread->output();
    $output['breadcrumb'] = $breadcrumb;

    $start      = 0;
    $searchKey  = '';
    if ($this->input->is_ajax_request())
    {
      $searchKey  = $this->input->post('searchKey');
      $page       = $this->input->post('page');
      $start      = ( $page - 1 ) * $this->perPage;
    }
    $data = $this->OrderModel->getAllOrders($start, $this->perPage,$searchKey);
    $output['records']          = $data['result'];
    $output['paginationLinks']  = getPagination(site_url('sales'), $this->perPage, $data['total_rows'], '', 1);
    $output['start']			      = $start;
    // echo "<pre>";print_r($output);die;
    if ($this->input->is_ajax_request())
    {
      $response['html'] = $this->load->view('orders/sales/saleslistajax', $output, TRUE);
      echo json_encode($response);
      exit;
    }
    else
    {
      $this->load->view('commonFiles/header',$output);
      $this->load->view('orders/sales/index');
      $this->load->view('commonFiles/footer');
    }
  }

  /*
  * Function Name dispatch
  *
  * loading all dispatch orders.
  *
  */

  public function dispatch()
  {
    $output['title']              = 'Order Management | Sales';
    $output['breadcrumbs']        = 'Dispatch Processing';
    $this->make_bread->add('Home', 'home', 0);
    $this->make_bread->add('Orders', '', 0);
    $this->make_bread->add('Dispatch Processing', '', 0);
    $breadcrumb = $this->make_bread->output();
    $output['breadcrumb'] = $breadcrumb;

    $start      = 0;
    $searchKey  = '';

    $last24HourLoadingGroupdelete = $this->OrderModel->deleteLoadingGroup();
    if ($this->input->is_ajax_request())
    {
      $searchKey  = $this->input->post('searchKey');
      $page       = $this->input->post('page');
      $start      = ( $page - 1 ) * $this->perPage;
    }
    $data = $this->OrderModel->getDispatchPendingOrders($start, $this->perPage,$searchKey,4);
    $output['records']          = $data['result'];
    $output['paginationLinks']  = getPagination(site_url('dispatch'), $this->perPage, $data['total_rows'], '', 1);
    $output['start']			      = $start;
    // echo "<pre>";print_r($output);die;
    if ($this->input->is_ajax_request())
    {
      $response['html'] = $this->load->view('orders/dispatch/dispatchlistajax', $output, TRUE);
      echo json_encode($response);
      exit;
    }
    else
    {
      $this->load->view('commonFiles/header',$output);
      $this->load->view('orders/dispatch/index');
      $this->load->view('commonFiles/footer');
    }
  }

  /*
  *
  * Function Name production
  *
  * loading all production orders.
  *
  */

  public function production()
  {
    $output['title']              = 'Order Management | Sales';
    $output['breadcrumbs']        = 'Production Processing';

    $this->make_bread->add('Home', 'home', 0);
    $this->make_bread->add('Orders', '', 0);
    $this->make_bread->add('Production Processing', '', 0);
    $breadcrumb = $this->make_bread->output();
    $output['breadcrumb'] = $breadcrumb;


    $start      = 0;
    $searchKey  = '';
    if ($this->input->is_ajax_request())
    {
      $searchKey  = $this->input->post('searchKey');
      $limit       = $this->input->post('limit');
      $start      = 0;
    }else{
      $limit = $this->perPage;
    }
    $data            = $this->OrderModel->productionOrders($start, $limit,$searchKey,3);
    $complatedOrders = $this->OrderModel->completedOrders($start, $this->perPage,$searchKey);
    $output['total_rows']          = $data['total_rows'];
    $output['records']          = $data['result'];
    $output['complatedOrders'] = $complatedOrders;
    $output['paginationLinks']  = getPagination(site_url('production-processing'), $this->perPage, $data['total_rows'], '', 1);
    $output['start']			      = $start;
    // echo "<pre>";print_r($output['paginationLinks']);die;
    if ($this->input->is_ajax_request())
    {
      $response['html'] = $this->load->view('orders/production/productionlistajax', $output, TRUE);
      echo json_encode($response);
      exit;
    }
    else
    {
      $this->load->view('commonFiles/header',$output);
      $this->load->view('orders/production/index');
      $this->load->view('commonFiles/footer');
    }
  }

  /*
  *
  * Function Name approval
  *
  * loading all approval orders.
  *
  */

  public function approval()
  {
    $output['title']              = 'Order Management | Approval';
    $output['breadcrumbs']        = 'Approval';

    $this->make_bread->add('Home', 'home', 0);
    $this->make_bread->add('Orders', '', 0);
    $this->make_bread->add('Approval', '', 0);
    $breadcrumb = $this->make_bread->output();
    $output['breadcrumb'] = $breadcrumb;


    $start      = 0;
    $searchKey  = '';
    if ($this->input->is_ajax_request())
    {
      $searchKey  = $this->input->post('searchKey');
      $page       = $this->input->post('page');
      $start      = ( $page - 1 ) * $this->perPage;
    }
    $data = $this->OrderModel->getFinanceOrders($start, $this->perPage,$searchKey,2);
    $output['records']          = $data['result'];
    $output['paginationLinks']  = getPagination(site_url('finance'), $this->perPage, $data['total_rows'], '', 1);
    $output['start']			      = $start;
    // echo "<pre>";print_r($output);die;
    if ($this->input->is_ajax_request())
    {
      $response['html'] = $this->load->view('orders/finance/financelistajax', $output, TRUE);
      echo json_encode($response);
      exit;
    }
    else
    {
      $this->load->view('commonFiles/header',$output);
      $this->load->view('orders/finance/index');
      $this->load->view('commonFiles/footer');
    }
  }

  /*
  *
  * Function Name orderDetails
  *
  * get order details by using order Ref id.
  *
  */
  public function orderDetails($orderRef,$sheetRef = null,$dispatchNum = null)
  {
    $output['title']            = 'Order Management | Order Deatils';
    $output['breadcrumbs']      = 'Order Details';

    $this->make_bread->add('Home', 'home', 0);
    $this->make_bread->add('Orders', '', 0);
    $this->make_bread->add('Order Detail', '', 0);
    $breadcrumb = $this->make_bread->output();
    $output['breadcrumb'] = $breadcrumb;

    $output['orderDetails']     = $this->OrderModel->orderDetails($orderRef);
    $output['orderItems']       = $this->OrderModel->orderItems($orderRef);
    $output['orderAddress']     = $this->OrderModel->orderAddress($orderRef);
    $output['orderComments']    = $this->OrderModel->orderComments($orderRef);
    $output['orderDispatches']  = $this->OrderModel->lastDispatchedOrders($orderRef);
    // pr($output['orderDispatches']['result']);die;
    if(empty($output['orderDetails']))
    {
        $this->session->set_flashdata('error_message','Something went wrong. Please try again.');
        redirect($_SERVER['HTTP_REFERER']);
    }
    // echo "<prE>"; print_r($output);die;
    if ($this->input->is_ajax_request())
    {
      $data         = $this->OrderModel->dispatchOrderItems($orderRef,$sheetRef,2,$dispatchNum);
      // echo "<pre>";print_r($data);die;
      // echo "<pre>";print_r($output['orderItems']);die;
      if(!empty($data)){
        $dataa = array_replace($output['orderItems'],$data);
        $output['orderItems']  = $dataa;
      }
      // echo "<pre>";print_r($output['orderItems']);die;
      echo json_encode($output);exit;
    }
    $this->load->view('commonFiles/header',$output);
    $this->load->view('orders/orderDetails');
    $this->load->view('commonFiles/footer');
  }
  public function dispatchOrderDetails($orderRef,$sheetRef = null,$dispatchNum = null)
  {
    $output['title']              = 'Order Management | Order Deatils';
    $output['breadcrumbs']        = 'Order Details';
    $output['orderDetails']       = $this->OrderModel->orderDetails($orderRef);
    $result                       = new stdClass();
    $result->orderDetails         = new stdClass();
    if (is_array($output['orderDetails'])) {
      foreach ($output['orderDetails'] as $key => $value) {
        $result->orderDetails->$key                   = $value;
        $result->orderDetails->$key->orderItems       = $this->OrderModel->dispatchOrderItems($value->orderRef,null,1,null);
        $result->orderDetails->$key->orderAddress     = $this->OrderModel->orderAddress($value->orderRef);
      }
    } else {
      $key = 0;
      $result->orderDetails->$key                     =   $output['orderDetails'];
      $result->orderDetails->$key->orderItems         = $this->OrderModel->dispatchOrderItems($output['orderDetails']->orderRef,null,1,null);
      $result->orderDetails->$key->orderAddress       = $this->OrderModel->orderAddress($output['orderDetails']->orderRef);
    }

    // echo "<pre>";print_r($result);die;
    $output['html'] = $this->load->view('orders/dispatchOrders',$result,TRUE);
    echo json_encode($output);exit;
  }
  public function addComment($orderRef)
  {
    // echo "<pre>";print_r($_POST);die;
    if ($_POST)
    {
      // $this->form_validation->set_rules('lastName', 'Email', 'required|valid_email');
      $this->form_validation->set_rules('comment', 'Order Comment', 'required');
      if (!$this->form_validation->run())
      {
        $errors                 = $this->form_validation->error_array();
        $response['success']    = false;
        $response['formErrors'] = true;
        $response['errors']     = $errors;
      }
      else
      {
        if (isset($_POST['commentRef']) && $_POST['commentRef'] !='')
        {
          $_POST['modifiedDate']  = date('Y-m-d');
          $responseData = $this->CommonModel->update(array('commentRef' => $_POST['commentRef']),  $_POST, 'ws_orderComments');
          if($responseData)
          {
            $response['commentBox']       = true;
            $response['updatedContent']   = $_POST['comment'];
            $response['commentRef']       = $_POST['commentRef'];

            $response['success']          = true;
            $response['success_message']  = 'Comment updated successfully';
          }
        }
        else
        {
          $_POST['status']        = 1;
          $_POST['addedOn']       = date('Y-m-d H:i:s');
          $_POST['modifiedDate']  = date('Y-m-d H:i:s');
          $_POST['addedBy']       = $this->userRef;
          $_POST['commentRef']    = generateRef();
          $responseData = $this->CommonModel->insert('ws_orderComments', $_POST);
          if($responseData)
          {
            $response['success'] = true;
            $response['success_message'] = 'Comment Added successfully';
            $oldDate = date('Y-m-d H:i:s',strtotime('- 2 sec'));
            if ($this->userType == 1){
              $adminRol =  "Super Admin";
            }elseif ($this->userType == 2) {
              $adminRol =  "Manager";
            }elseif ($this->userType == 3) {
              $adminRol =  "Salesman";
            }
            $timeDif =  dateDiff(date('Y-m-d H:i:s'),$oldDate);

            $data = '<li class="comment user-comment" id="orderComment_'.$_POST['commentRef'].'">
            <div class="info">
            <a href="javascript:void(0)">'.$this->userName.'</a>
            <h4 class="label label-primary">
            '.$adminRol.'
            </h4>
            <br>
            <span> '.$timeDif.'</span>
            </div>
            <a class="avatar" href="#">
            <img src="'.site_url('assets/images/user.png').'" width="35" alt="Profile Avatar" title="'.$this->userName.'" />
            </a>
            <p class="realtive"> '.ucfirst($_POST['comment']).'

            <a style="color: black;" href="javascript:void(0);" class="deleteRecord pull-right" data-name="this comment" data-type="orderComment" data-ref="'.$_POST['commentRef'].'"><i class="fa fa-times"></i></a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a style="padding-right: 16px;color: black" href="javascript:void" data-ref="'.$_POST['commentRef'].'" class="editComment pull-right"><i class="fa fa-edit"></i></a>
            </p>
            </li>';
            $response['commentBox'] = true;
            $response['resetform'] = true;
            $response['commentData'] = $data;
            $orderRef = $_POST['orderRef'];
            $getOrderByRef    = getOrderByRef($orderRef);
            $notificationData = getRegisterIds($orderRef,$getOrderByRef->orderPipline);
            if (!empty($notificationData['users']) && !empty($notificationData['orderData'])) {
                $notificationRef       = generateRef();
                $notificationTitle     = 'Order # ' . $notificationData['orderData']->orderNo . '. status';
                $message = array(
                  'message_id'        => $notificationRef,
                  'message'           => ucfirst($_POST['comment']),
                  'messagetitle'      => $notificationTitle,
                  'type'              => 1,
                  'notificationType'  => 1,
                  'msg_type'          => 'Order comment.',
                  'orderRef'          => $orderRef,
                  'dataRefTo'         => $orderRef,
                  'title'             => $notificationTitle,
                  'notificationRef'   => $notificationRef,
                  'notification'      => $this->userName.' comment on Order # '.$notificationData['orderData']->orderNo,
                );
                foreach ($notificationData['users'] as $key => $registeredUsers) {
                  if ($this->userRef != $registeredUsers->userRef) {
                    $registerID[]          = $registeredUsers->registeredId;
                    $deviceID[]            = $registeredUsers->device_id;

                    $notification = array();
                    $notification['notificationRef'] 							= $notificationRef;
                    $notification['orderRef'] 										= $orderRef;
                    $notification['notificationFrom'] 					  = $this->userRef;
                    $notification['notificationTo']               = $notificationTo = (isset($registeredUsers->userRef)) ? $registeredUsers->userRef : '';
                    $notification['notificationBussinessName']    = $notificationData['orderData']->businessName;
                    $notification['notificationContactName']      = $notificationData['orderData']->customerName;
                    $notification['notificationTitle'] 						= $notificationTitle;
                    $notification['notificationMessage']  				= ucfirst($_POST['comment']);
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
                // echo "<pre>"; print_r($array);die;
                $sendNotification                          = sendNotification($array);
                // echo "<pre>";print_r($sendNotification);die;
            }
          }
          else
          {
            $response['success'] = false;
            $response['error_message'] = 'Something went wrong please try again.';
          }
        }
      }
    }
    if ($response['success'] == true) {
      $response['delayTime']       = '4000';
      $response['modelhide']       = 'add-new-comment-modal';
    }
    echo json_encode($response); die;
  }


  /*
  *
  *Function name orderSearch
  *
  *to Search order
  */

  public function orderSearch()
  {
    $searchKey                    = '';
    if ($this->input->is_ajax_request())
    {
      // echo "<pre>";print_r($_POST);die;
      $searchKey                  = $this->input->post('searchKey');
      $page                       = $this->input->post('page');
      $start                      = ( $page - 1 ) * $this->perPage;
      $data                       = $this->OrderModel->orderSearch($start, $this->perPage,$_POST);
      $output['records']          = $data['result'];
      $output['paginationLinks']  = getPagination(site_url('orderSearch'), $this->perPage, $data['total_rows'], '', 1);
      $output['start']			      = $start;
      $response['html']           = $this->load->view('orders/orderSearch', $output, TRUE);
      echo json_encode($response);exit;
    }
  }
  /*
  *
  *Function name getToLoadOrders
  *
  *to Search order
  */

  public function getToLoadOrders()
  {
    $searchKey                    = '';
    if ($this->input->is_ajax_request())
    {
      // echo "<pre>";print_r($_POST);die;
      $searchKey                  = $this->input->post('searchKey');
      $page                       = $this->input->post('page');
      $start                      = ( $page) * $this->perPage;
      if ($_GET['dataTo'] == 'toLoad') {
        $data                       = $this->OrderModel->getToLoadOrders($start, $this->perPage,$_POST);
        $output['records']          = $data['result'];
        $output['paginationLinks']  = getPagination(site_url('get-to-load-orders'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']			      = $start;
        $response['html']           = $this->load->view('orders/dispatch/toLoad', $output, TRUE);
      }
      elseif ($_GET['dataTo'] == 'pending') {
        $data = $this->OrderModel->getDispatchPendingOrders($start, $this->perPage,$searchKey,4);
        $output['records']          = $data['result'];
        $output['paginationLinks']  = getPagination(site_url('dispatch'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']			      = $start;
        $response['html'] = $this->load->view('orders/dispatch/dispatchlistajax', $output, TRUE);
      }
      elseif ($_GET['dataTo'] == 'dispached') {
        $data = $this->OrderModel->lastDispatchedOrders(null,$this->perPage,$start);
        $output['records']          = $data['result'];
        $output['paginationLinks']  = getPagination(site_url('dispatch'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']			      = $start;
        // echo "<pre>";print_r($output);die;
        $response['html'] = $this->load->view('orders/dispatch/dispatchedOrders', $output, TRUE);
      }
      echo json_encode($response);exit;
    }
  }

  /*
  ** Function markAsLoad
  **to mark order as ready to load
  **
  */

  public function markAsLoad()
  {
    $updateArray = array();
    if (isset($_POST))
    {
      if (isset($_POST['checkedArray'])){
        foreach ($_POST['checkedArray'] as $key => $value) {
          $updateArray[] = array(
            'orderRef' => $value,
            'toLoad'   => 1,
          );
        }
      }
      if (isset($_POST['uncheckedArray'])){
        foreach ($_POST['uncheckedArray'] as $key => $value) {
          $updateArray[] = array(
            'orderRef' => $value,
            'toLoad'   => 0,
          );
          $this->OrderModel->deleteLoadingGroup(null , $value);
        }
      }
      // pr($updateArray);die('asdfasdfasdf');
      $updateOrder  = $this->db->update_batch('ws_orders',$updateArray, 'orderRef');
      if ($updateOrder) {
        $response['success']          = true;
        $response['success_message']  = 'Order is marked as load successfully..';
        $response['toLoadCount']      = getToLoadCount();
        $response['unLoadCount']      = getUnLoadCount();
      }else{
        $response['success']          = true;
        $response['toLoadCount']      = getToLoadCount();
        $response['unLoadCount']      = getUnLoadCount();
        $response['success_message']  = 'Order is marked as load successfully..';
      }
    }
    else
    {
      $response['success'] = false;
      $response['error_message'] = 'Something went worng please try again..';
    }
    echo json_encode($response);die;;
  }

  public function loadOrdersToLoadingsheet()
  {
    // echo "<pre>";print_r($_POST['dispatchNo']);die;
    $loadingSheetArray = array();
    if (isset($_POST))
    {
          $addedOn      = date('Y-m-d H:i:s');
          $modifiedDate = date('Y-m-d H:i:s');
          if ($_POST['prependLoading'] == 1) {
            $addedOn      =  date('Y-m-d H:i:s' , strtotime('+1 day'));
            $modifiedDate = date('Y-m-d H:i:s' , strtotime('+1 day'));
          }

      if ( ( isset($_POST['orderRef'])  && trim($_POST['orderRef']) !='' ) && ( isset($_POST['sheetRef'])  && trim($_POST['sheetRef']) !='' ) )
      {
        $isExits = $this->CommonModel->checkexist('ws_loadedOrders', array('orderRef' => $_POST['orderRef'],'loadedStatus' => 1 ));
         // echo "<pre>";print_r($isExits);die;
        $orderData                        = $this->OrderModel->orderDetails($_POST['orderRef']);
        $orderDataitems                   = $this->OrderModel->orderItems($_POST['orderRef']);

        // pr($orderDataitems);die;
        $dispatchedData['orderRef']       = $orderData->orderRef;
        $dispatchedData['sheetRef']       = $_POST['sheetRef'];
        $dispatchedData['fullfilment']    = 2;
        $dispatchedData['isModifyState']  = 1;
        $dispatchedData['addedOn']        = $addedOn;
        $dispatchedData['modifiedDate']   = $modifiedDate;
        $dispatchedData['addedBy']        = $this->userRef;
        $dispatchedData['dispatchNo']     = $_POST['dispatchNo'];
        // echo "<pre>";print_r($dispatchedData);die;

        if ($isExits) {
          $dispatchedData['dispatchNo']     = (trim($_POST['dispatchNo']) !='' ) ? trim($_POST['dispatchNo']) : dispatchNum($orderData->orderNo,$orderData->orderRef);

          $loadingSheetArray[] = array(
            'orderRef'         => $_POST['orderRef'],
            'loadedStatus'     => 1,
            'sheetRef'        => $_POST['sheetRef'],
            'addedBy'         => $this->userRef,
            'modifiedDate '    => $modifiedDate,
          );
          // echo "<pre>";print_r($dispatchedData);die;

          $this->db->update('ws_dispatched_orders',$dispatchedData, array('dispatchNo' => $dispatchedData['dispatchNo']));
          // echo $this->db->last_query();die;
          $loadedSheet  = $this->db->update_batch('ws_loadedOrders',$loadingSheetArray, 'orderRef');
        } else {
          $dispatchedData['dispatchNo'] = dispatchNum($orderData->orderNo,$orderData->orderRef);
          $addedOn      =  date('Y-m-d H:i:s');
          $modifiedDate = date('Y-m-d H:i:s');
          // prepare loading sheet for tommarow
          if ($_POST['prependLoading'] == 1) {
            $addedOn      =  date('Y-m-d H:i:s' , strtotime('+1 day'));
            $modifiedDate = date('Y-m-d H:i:s' , strtotime('+1 day'));
          }
          $loadingSheetArray   = array(
            'orderRef'        => $_POST['orderRef'],
            'loadedStatus'    => 1,
            'sheetRef'        => $_POST['sheetRef'],
            'addedBy'         => $this->userRef,
            'addedOn'         =>$addedOn,
            'modifiedDate '   => $modifiedDate ,
          );
          $this->db->insert('ws_dispatched_orders',$dispatchedData);
          $loadedSheet  = $this->db->insert('ws_loadedOrders',$loadingSheetArray);
        }
          $orderitems = array();
          foreach ($orderDataitems as $key => $value) {

                foreach ($value->variants as $e => $variantVal) {

                  $toDispatchQty = 0;
                  $lastDispatchedQty = getLastDispatchedItem($_POST['orderRef'],$value->itemRefId,$variantVal->variant_id);
                  if(trim($lastDispatchedQty->lastDispatchedQty) != '' && $lastDispatchedQty->lastDispatchedQty > 0){
                    $toDispatchQty = ($lastDispatchedQty->lastDispatchedQty > $variantVal->qty ) ? 0 : $variantVal->qty - $lastDispatchedQty->lastDispatchedQty;
                  }else {
                    $toDispatchQty = $variantVal->qty;
                  }
                  if($lastDispatchedQty->lastDispatchedQty < $value->qty)
                  {
                    if ($toDispatchQty != 0) {
                      $orderitems[] = array(
                        'dispatchRef'   => $dispatchedData['dispatchNo'],
                        'orderRefId'    => $_POST['orderRef'],
                        'itemRefId'     => $value->itemRefId,
                        'variant_id'    => $variantVal->variant_id,
                        'itemName'      => $value->itemName,
                        'qtyLoaded'     => $toDispatchQty,
                        'qtyNotLoaded'  => 0,
                        'status'        => 1,
                        'addedOn'       => $addedOn,
                        'modifiedDate'  => $modifiedDate,
                      );
                    }


                }

            }
          }

          // echo "<pre>"; print_r($orderitems);die;
					$this->CommonModel->delete($orderitems,'removeDispachedItems');
					if(!empty($orderitems)){
						$this->db->insert_batch('ws_dispatched_Items',$orderitems);
					}


        if ($loadedSheet) {
          $response['success']          = true;
          $response['success_message']  = 'Order is marked as load successfully..';
          $response['dispatchRef']      = $dispatchedData['dispatchNo'];
          $data                         = $this->OrderModel->getToLoadOrders();
          // pr($data);die;
          $output['records']            = $data['result'];
          $output['paginationLinks']    = getPagination(site_url('get-to-load-orders'), $this->perPage, $data['total_rows'], '', 1);
          $output['start']			        = 0;
          $response['html']             = $this->load->view('orders/dispatch/toLoad', $output, TRUE);
        }else{
          $response['success']          = true;
          $response['success_message']  = 'Order is marked as load successfully..';
          $response['dispatchRef']      = $dispatchedData['dispatchNo'];
          $data                       = $this->OrderModel->getToLoadOrders();
          $output['records']          = $data['result'];
          $output['paginationLinks']  = getPagination(site_url('get-to-load-orders'), $this->perPage, $data['total_rows'], '', 1);
          $output['start']			      = 0;
          $response['html']           = $this->load->view('orders/dispatch/toLoad', $output, TRUE);
        }
      }
      else
      {
        $response['success'] = false;
        $response['error_message'] = 'Something went worng please try again..';
      }
      echo json_encode($response);die;;
    }


  }

  public function removeLoadingOrders()
  {
    if ($_POST)
    {
      $thisOrder =$this->CommonModel->delete($_POST['orderRef'], $recordType = 'removeLoadedOrders' );
    }
    if ($thisOrder)
    {
      $response['success']          = true;
      $response['success_message']  = 'Loading Sheet updated successfully..';
    }
    else
    {
      $response['success']          = true;
      $response['success_message']  = 'Loading Sheet updated successfully..';
    }
    echo json_encode($response);exit;
  }


  public function modifyLoadedOrders()
  {
     // echo "<pre>";print_r($_POST);die;
    // echo dispatchNum();die;
    if (isset($_POST['dispatchNum']) && trim($_POST['dispatchNum']) !='')
    {
      $dispatchNum = $_POST['dispatchNum'];
    }
    else
    {
      $dispatchNum = dispatchNum();
    }
    if (isset($_POST) && !empty($_POST['load_item']))
    {
      $orderData['orderRef']        = $_POST['orderRefId'];
      $orderData['sheetRef']        = $_POST['sheetRefId'];
      $orderData['dispatchNo']      = $dispatchNum;
      $orderData['fullfilment']     = 1;
      $orderData['isModifyState']   = 1;
      $orderData['addedOn']         = date('Y-m-d H:i:s');
      $orderData['modifiedDate']    = date('Y-m-d H:i:s');
      $orderData['addedBy']         = $this->userRef;
      $itemArray = array();

      for ($i=0; $i < count($_POST['load_item']['itemRefId']); $i++)
      {

        if ($_POST['load_item']['saleQty'][$i] !='') {
          $itemArray[] = array(
            'itemName'       => $_POST['load_item']['itemName'][$i],
            'itemRefId'      => $_POST['load_item']['itemRefId'][$i],
            'orderRefId'     => $_POST['orderRefId'],
            'dispatchRef'    => $dispatchNum,
            'qtyLoaded'      => $_POST['load_item']['saleQty'][$i],
            'qtyNotLoaded'   => $_POST['load_item']['pendingQty'][$i],
            'variant_id'     => $_POST['load_item']['variant_id'][$i],
            'status'         => 1,
            'height'         => $_POST['load_item']['height'][$i],
            'width'          => $_POST['load_item']['width'][$i],
            'length'         => $_POST['load_item']['length'][$i],
            'modifiedDate'   => date('Y-m-d H:i:s'),
            'addedOn'        => date('Y-m-d H:i:s'),
          );
        }

      }
      // echo "<pre>";print_r($itemArray);die;
      $checkExits = $this->CommonModel->checkexist('ws_dispatched_orders',array('isModifyState'=> 1, 'orderRef' => $orderData['orderRef'] , 'sheetRef' => $orderData['sheetRef'],'dispatchNo'=>$dispatchNum));
      // echo $this->db->last_query();die;
      // echo "<pre>";print_r($itemArray);die;
      if ( (isset($_POST['dispatchNum']) && trim($_POST['dispatchNum']) !='')  && $checkExits)
      {
        $response = $this->CommonModel->update('dispatchNo',$orderData,'ws_dispatched_orders');
        $response = $this->CommonModel->update_multiple('ws_dispatched_Items',$itemArray, array('dispatchRef','itemRefId','variant_id'));
        // echo $this->db->last_query();die;
      }else
      {
        $response = $this->CommonModel->insert('ws_dispatched_orders',$orderData);
        $response = $this->CommonModel->insert_batch('ws_dispatched_Items',$itemArray);
      }
      $output['success']         = true;
      $output['dispatchRef']     = $dispatchNum;
      $output['orderRef']        = $orderData['orderRef'];
      $output['success_message'] = 'Loading Sheet Modify successfully..';
      // $output['modelhide']       = 'modifyLoading-modal';
      // echo "<pre>";print_r($itemArray);echo "********************************************<br>";;
      // echo "<pre>";print_r($orderData);die;
    }
    else
    {
      $output['success']         = false;
      $output['error_message'] = 'Something went worng please try again..';
    }
    unset($_POST);
    echo json_encode($output);exit;
  }

  public function downloadSheet($sheetRef = null , $dataTime = null)
  {
    if ( ($sheetRef != '' && $sheetRef !='') && ($dataTime != '' && $dataTime !='') ) {

        $toDate                    = date('Y-m-d',strtotime($dataTime));
        $exportExportType          = 'excel';
        $searchKey                 = '';
        if( $exportExportType == 'excel' )
        {
            $spreadsheet = new Spreadsheet();
            $sheet       = $spreadsheet->getActiveSheet();
            $writer      = new Xlsx($spreadsheet);
            $filename    = 'Laodingsheet.Xlsx';
            // Set document properties
            $spreadsheet->getProperties()->setCreator('1wayit.com')
                ->setLastModifiedBy('1wayit Team')
                ->setTitle('Export')
                ->setSubject('Export')
                ->setDescription('Export');
            // add style to the header
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                    'bottom' => array(
                        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'rotation'   => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );

            $topBorder = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ];


            $sheetIndexx  = 0;
            $spreadsheet->setActiveSheetIndex($sheetIndexx);
            $sheetIndexx = $sheetIndexx + 1;
            $spreadsheet->getActiveSheet()->getStyle('A1:G1')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('A2:G2')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('A3:G3')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('A4:G4')->applyFromArray($styleArray);
            // auto fit column to content
            $hideColumns = 0;
            foreach(range('A','H') as $columnID)
            {
              $spreadsheet->getActiveSheet()->getStyle("A6:H6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setWrapText(true);;
              $spreadsheet->getActiveSheet()->getStyle("A6:H6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setWrapText(true);;
                if ($columnID !='E' || $columnID !='H') {
                  $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells('A1:A2');
            $spreadsheet->getActiveSheet()->mergeCells('B1:B2');
            $spreadsheet->getActiveSheet()->mergeCells('C1:C2');
            $spreadsheet->getActiveSheet()->mergeCells('C1:D2');
            $spreadsheet->getActiveSheet()->mergeCells('E1:F2');
            $spreadsheet->getActiveSheet()->mergeCells('A19:G20');
            $spreadsheet->getActiveSheet()->mergeCells('A21:G22');
            $spreadsheet->getActiveSheet()->mergeCells('A23:G24');
            $spreadsheet->getActiveSheet()->getRowDimension("6")->setRowHeight(35);
            // $spreadsheet->getActiveSheet()->getRowDimension("21")->setRowHeight(40);
            // $spreadsheet->getActiveSheet()->getRowDimension("21")->setRowWidth(100);

            // set the names of header cells
            $spreadsheet->getActiveSheet()
                ->setCellValue("A1",strtoupper('WATERVALE INVESTMENTS Loading Sheet'));
                $spreadsheet->getActiveSheet()->getStyle('A1:H350')
    ->getAlignment()->setWrapText(true);

            $spreadsheet->getActiveSheet()
                ->setCellValue("B1",date('d').'/'.date('M').'/'.date('Y'));;


            // set the names of header cells
            $spreadsheet->getActiveSheet()->getStyle( "A6:H6" )->getFont()->setBold( true )->setSize(12);
            $spreadsheet->getActiveSheet()
                ->setCellValue("A6",'Order')
                ->setCellValue("B6",'Item')
                ->setCellValue("C6",'Qty Ordered')
                ->setCellValue("D6",'Qty Sent')
                ->setCellValue("E6",'Driver')
                ->setCellValue("F6",'Returns')
                ->setCellValue("G6",'Loading Deviations')
                ->setCellValue("H6",'Remarks')
                ->setCellValue("A19",'Name: ____________________          Department: Operations                            Sign: ____________________')
                ->setCellValue("A21",'Name: ____________________          Department: Admin                                 Sign: ____________________')
                ->setCellValue("A23",'Name: ____________________          Department:_____________________                  Sign: ____________________');
            // $objPHPExcel->getActiveSheet()->getStyle('A6')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(26);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(35);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(9);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);

            $totalcount = 1;
            $x  = 7;
            $xx = 7;
            $xxv = 8;
            $row = $x;
            $newCount = 1;
            $getLoadingSheet = $this->OrderModel->getLoadingSheetData($sheetRef,$toDate);
          //  echo $this->db->last_query();die;
            // echo "<prE>";print_r($getLoadingSheet);die;
            if (!empty($getLoadingSheet)) {
              $spreadsheet->getActiveSheet()
                  ->setCellValue("C1",$getLoadingSheet[0]->sheetName)//"KBZ")
                  ->setCellValue("E1","Page X of X");
                  $totalCount = count($getLoadingSheet);
                  foreach ($getLoadingSheet as $key => $value) {
                    if(!empty($value->dispatchtems))
                    {
                     $remark = getRemark($value->orderRef);


                      // print_r($remark);
                      // die;
                    // $xxv = $xx +count($value->dispatchtems) * 3;
                   //echo "xx ==>".$xx .' == xxv =>'.$xxv ;
                    $spreadsheet->getActiveSheet()->mergeCells("A$xx:A$xxv");
                    $spreadsheet->getActiveSheet()->mergeCells("E$xx:E$xxv");
                    $spreadsheet->getActiveSheet()->getStyle("A$xx:H$xx")->applyFromArray($topBorder);

                    // $spreadsheet->getActiveSheet()->mergeCells("A$x:A$xx");
                    $spreadsheet->getActiveSheet()
                        // ->setCellValue("A$x",$value->businessName.'( '. $value->customerName .' )  Phone '.$value->customerPhone.' #'.$value->dispatchNo);
                        ->setCellValue("A$xx",$value->businessName.' #'.$value->dispatchNo);
                   $vv = "";

                   foreach ($value->dispatchtems as $dispatchtemsKey => $dispatchtems)
                    {
                      $variants  = getOrderItemVariants(null,$dispatchtems->variant_id);
                      $vv =  $row +1  ;
                      //echo  'row == >' . $row.' . '.$vv.'<br>';
                      $spreadsheet->getActiveSheet()->mergeCells("B$row:B$vv");
                      $spreadsheet->getActiveSheet()->mergeCells("C$row:C$vv");
                      $spreadsheet->getActiveSheet()->mergeCells("D$row:D$vv");
                      $spreadsheet->getActiveSheet()->mergeCells("F$row:F$vv");
                      $spreadsheet->getActiveSheet()->mergeCells("G$row:G$vv");
                      $spreadsheet->getActiveSheet()->mergeCells("H$row:H$vv");
                      // $spreadsheet->getActiveSheet()
                      // ->setCellValue("A$row",'Remarks: '.$remark['orderRemarks']);
                      $spreadsheet->getActiveSheet()
                      // ->setCellValue("A$row",'Remarks: '.$remark['orderRemarks'])
                      ->setCellValue("B$row",$dispatchtems->itemName.' ( '. $variants->height."X".$variants->width."X".$variants->length .' '. ucfirst($variants->color) .' '. ucfirst($variants->design).' ) ')
                      ->setCellValue("C$row",getOrderQtyItemsVariants($value->orderRef,$dispatchtems->itemRefId,$variants->variant_id)->orderQty . get_ItemUOM($value->orderRef , $dispatchtems->itemRefId)->saleUOM )
                      ->setCellValue("D$row",' ')
                      ->setCellValue("F$row",' ')
                      ->setCellValue("G$row",' ')
                      ->setCellValue("H$row",' ')
                      ;
                      //$writer->save('Remarks: '.$remark['orderRemarks'] );
                      $row += 2;
                    }

                    //echo  'vv== > '.$vv.'<br>';
                    $xx = $vv + 1;
                  //  echo " *** XX = > ".$xx;
                    if( $totalCount > $key+1 )
                      $newCount = count($getLoadingSheet[$key+1]->dispatchtems);
                    else
                      $newCount = $newCount;
                      $xxv = $xx + ($newCount * 2) - 1;
                    //echo " *** XXV = > ".$xxv;
                    //echo "/////////////////";
                    $rowcount = count($value->dispatchtems);
                    $x = $x + $rowcount + 1;
                    // $xx = $xx + $rowcount;
                   // $xx = $xx + count($value->dispatchtems) + 1;
                  $spreadsheet->getActiveSheet()
                  ->setCellValue("A$xx",'Remarks: '.$remark['orderRemarks']);
                  }
                  }

                  $documentRoot = $_SERVER['DOCUMENT_ROOT'].'/watervale/assets/excel/'.$filename;
                  // Rename worksheet
                  $spreadsheet->getActiveSheet()->setTitle('Loading Sheet');
                  // Redirect output to a client's web browser (Excel2007)
                  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                  header('Content-Disposition: attachment;filename="'.$filename.'"');
                  header('Cache-Control: max-age=0');
                  // If you're serving to IE 9, then the following may be needed
                  header('Cache-Control: max-age=1');
                  // If you're serving to IE over SSL, then the following may be needed
                  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                  header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                  header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                  header('Pragma: public'); // HTTP/1.0
                  $writer->save($documentRoot);
                  $response['success']        = true;
                  $response['fileUrl']        = base_url().'/assets/excel/'.$filename;
                  $response['success_message']  = 'File is ready to download please wait.';
            }
            else
            {
              $response['success']        = false;
              $response['error_message']  = 'Sorry! no record found please try with new entries.';
            }
        }
  }
  else{
    $response['success']         = false;
    $response['error_message'] = 'Something went worng please try again..';
  }
      $response['delayTime']         = 2000;
      echo json_encode($response);die;
  }

  public function saveDispatch()
  {
    $o = 0;$update = array();
    if ($_POST) {
      foreach ($variable = array_keys($_POST['orderRef']) as $key => $value) {
        // update order data
        $orderUpdate[$o]['orderRef']      = $_POST['orderRef'][$value][0];
        // $orderUpdate[$o]['toLoad']      = (isset($_POST['orderRef'][$value]['orderfullfillment'])) ? 0 : 1;
        $orderUpdate[$o]['toLoad']        =  0;
        // update dispatch order data
        $orderData[$o]['orderRef']        = $_POST['orderRef'][$value][0];
        $orderData[$o]['dispatchNo']      = $_POST['orderRef'][$value]['dispatchNo'][0];
        $orderData[$o]['fullfilment']     = (isset($_POST['orderRef'][$value]['orderfullfillment'])) ? 2 : 1;
        $orderData[$o]['invoiceNo']       = (isset($_POST['orderRef'][$value]['invoiceNo'])) ? $_POST['orderRef'][$value]['invoiceNo'][0] : '';
        $orderData[$o]['modifiedDate']    = date('Y-m-d H:i:s');
        $orderData[$o]['addedBy']         = $this->userRef;
        $orderData[$o]['isModifyState']   = 2;
        $itemArray = array();
        for ($i=0; $i < count($_POST['orderRef'][$value]['sheetRef']); $i++)
        {
          $orderData[$o]['sheetRef']   = $_POST['orderRef'][$value]['sheetRef'][$i];
          $orderUpdate[$o]['orderStatus']   = (isset($_POST['orderRef'][$value]['orderfullfillment'])) ? 'Closed' : 'OpenPartiallyFilled';
          if ($_POST['orderRef'][$value]['qtyLoaded'][$i] !='' && $_POST['orderRef'][$value]['qtyLoaded'][$i] != 0) {
              $update[] = array(
                'itemRefId'      => $_POST['orderRef'][$value]['orderItem'][$i],
                'orderRefId'     => $_POST['orderRef'][$value][0],
                'variant_id'     => $_POST['orderRef'][$value]['variant_id'][$i],
                'dispatchRef'    => $_POST['orderRef'][$value]['dispatchNo'][$i],
                'qtyLoaded'      => $_POST['orderRef'][$value]['qtyLoaded'][$i],
                'qtyNotLoaded'   => $_POST['orderRef'][$value]['qtyNotLoaded'][$i],
                'baseConvLength' => $_POST['orderRef'][$value]['baseUOM'][$i],
                'status'         => 1,
                'modifiedDate'   => date('Y-m-d H:i:s'),
                'addedOn'        => date('Y-m-d H:i:s'),
              );
          } else {
              $deleteItem['itemRefId']   = $_POST['orderRef'][$value]['orderItem'][$i];
              $deleteItem['dispatchRef'] = $_POST['orderRef'][$value]['dispatchNo'][$i];
              $deleteItem['variant_id']  = $_POST['orderRef'][$value]['variant_id'][$i];
              $delet =    $this->CommonModel->delete($deleteItem,'deleteDispatchItem');
          }
        }
        // pr($update);die;
        // deleted loaded orders
        $last24HourLoadingGroupdelete = $this->OrderModel->deleteLoadingGroup(null,$_POST['orderRef'][$value][0]);
        $o++;
      }
      // pr($update);die;
      if (!empty($orderData)) {
        $response = $this->CommonModel->update_multiple('ws_dispatched_orders',$orderData, array('orderRef','sheetRef','dispatchNo'));
      }
      if (!empty($orderUpdate)) {
        $response = $this->CommonModel->update_multiple('ws_orders',$orderUpdate, array('orderRef'));
      }
      if (!empty($update)) {
        $response = $this->CommonModel->update_multiple('ws_dispatched_Items',$update, array('dispatchRef','variant_id'));
      }
      if (isset($_POST['orderRefIds'])) {
        $orderRefids = explode(',', $_POST['orderRefIds']);
      }
      $res['success']          = true;
      $res['success_message']  = 'Dispatch updated successfully..';
      $res['isDispatched']     = true;
      $res['orderRefIdsArray'] = $orderRefids;
      $res['orderRefIdsCount'] = count($orderRefids);
      $res['toLoadCount'] = getToLoadCount();
      $res['unLoadCount'] = getUnLoadCount();
      $res['orderFullfilled']  = (isset($_POST['orderRef'][$value]['orderfullfillment'])) ? true : false;
      $res['modelhide']        = 'dispatchlistModal';
      echo json_encode($res); exit;
    }

  }


  public function getDispatchedOrders()
  {
      if ($_POST) {
        $orderRef = $_POST['orderRef'];
        $output['orderDetails']     = $this->OrderModel->orderDetails($orderRef);
        if (isset($_POST['type'])) {
          $result                       = new stdClass();
          $result->orderDetails         = new stdClass();
          // if dispatchref in array
          $dispatchNum = (strpos($_POST['dispatchNo'], ',') !== false) ? explode(',', $_POST['dispatchNo']) : $_POST['dispatchNo'];
          if (is_array($output['orderDetails'])) {
            foreach ($output['orderDetails'] as $key => $value) {
              // echo "<pre>";print_r($dispatchNum[$key]);
              $result->type = $_POST['type'];
              $result->orderDetails->$key                   = $value;
              $result->orderDetails->$key->orderItems       = $this->OrderModel->dispatchOrderItems($value->orderRef,null,1,$value->dispatchNo);
              $result->orderDetails->$key->orderAddress     = $this->OrderModel->orderAddress($value->orderRef);
              // pr($result);die;
            }
          } else {
            // echo "string";
            $key = 0;
            $result->type = $_POST['type'];
            $result->orderDetails->$key                     = $output['orderDetails'];
            $result->orderDetails->$key->orderItems         = $this->OrderModel->dispatchOrderItems($output['orderDetails']->orderRef,null,1,$output['orderDetails']->dispatchNo);
            $result->orderDetails->$key->orderAddress       = $this->OrderModel->orderAddress($output['orderDetails']->orderRef);
          }
          // pr($result);die;
          // $data['type']         =  $result->type;
          // $data['orderDetails'] =  $result;
          // echo '<pre>';print_r(($result));die;
          $response['html']         =   $this->load->view('orders/dispatch/dispachedItems',$result, TRUE);
          echo json_encode($response);exit;

        } else {
          // die('asfasdf');
          $output['orderItems']       = $this->OrderModel->orderItems($orderRef);
          $output['orderAddress']     = $this->OrderModel->orderAddress($orderRef);
          $output['type']             = (isset($_POST['type'])) ? $_POST['type'] : '';
          $response['html']           =   $this->load->view('orders/dispatch/dispachedItems',$output, TRUE);
          // pr($response);die;
          echo json_encode($response);exit;

        }
      }
  }

  public function getDispatchedItems($dispatchNum = null)
  {
    $orderData         = $this->OrderModel->dispatchOrderItems(null,null,2,$dispatchNum);
    $orderDetails      = $this->CommonModel->getData('ws_dispatched_orders' ,array('dispatchNo'  => $orderData[0]->dispatchRef) ,'receivedStatus,returnStatus,errorStatus' );
    $orderErrors       = $this->CommonModel->getData('ws_orderComments' ,array('orderRef'  => $orderData[0]->dispatchRef) ,'comment,type' );
    // echo $this->db->last_query();
    if (empty($orderDetails)) {
      $orderDetails = array();
    }else{
      $orderDetails = $orderDetails[0];
    }
    // echo "<pre>";print_r($orderData);die;
    $tr = '';
    if (!empty($orderData)) {

      foreach ($orderData as $key => $value) {
        $qtyReturn = 0;
        if ($value->qtyReturn != '') {
          $qtyReturn = $value->qtyReturn;
        }
        $itemReason = '';
        if ($value->itemReason != '') {
          $itemReason = $value->itemReason;
        }
        $variants  = getOrderItemVariants(null,$value->variant_id);
        $i = $key+1;
      $tr .='<tr>
           <td>'.$i.'</td>
           <td>'.$value->itemName.'</td>
           <td>'.$variants->length.'X'.$variants->width.'X'.$variants->height.' '.ucfirst($variants->color).' '.ucfirst($variants->design).'</td>
           <td class="dispatchQty">'.$value->qtyLoaded.'</td>

           <td>
              <input type="text" class="form-control validNumber itemDisabled qtyReturn validationInput" name="qtyReturn[]" value="'.$qtyReturn.'">
              <input type="hidden" class="form-control " name="itemRefId[]" value="'.$value->itemRefId.'">
              <input type="hidden" class="form-control" name="dispatchRef[]" value="'.$value->dispatchRef.'">
              <input type="hidden" class="form-control" name="orderRefId[]" value="'.$value->orderRefId.'">

          </td>
           <td><input type="text" name="itemReason[]" class="itemReason form-control validationInput" value="'.$itemReason.'"></td>
         </tr>';
         $success = true;
      }
    } else {
      $success =  false;
    }
    echo json_encode(array('success'=> $success , 'html' => $tr, 'dispatchStatus' => $orderDetails , 'orderNotes' => $orderErrors));die;
  }
}
