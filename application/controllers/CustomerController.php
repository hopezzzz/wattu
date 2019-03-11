<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerController extends CI_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('CustomerModel');
        $this->load->model('CommonModel');
        $this->load->model('OrderModel');
        $this->perPage    = 10;
        $loginSessionData = $this->session->userdata('clientData');
        $this->userRef    = $loginSessionData['userRef'];
        if (empty($loginSessionData)) {
            redirect();
        } //empty($loginSessionData)
    }

    /*
     * Function Name Customers
     *
     * For customer listing.
     *
     */
    public function customers()
    {

        $output['title']           = 'Customer Management';
        $output['breadcrumbs']     = 'Customers';
        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('customers-list', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;
        $start     = 0;
        $searchKey = '';
        $statusBox = '';
        if ($this->input->is_ajax_request()) {
            $searchKey = $this->input->post('searchKey');
            $statusBox = $this->input->post('statusBox');
            $page      = $this->input->post('page');
            $start     = ($page - 1) * $this->perPage;
        } //$this->input->is_ajax_request()
        $data                      = $this->CustomerModel->customersDetails($start, $this->perPage, $searchKey,$statusBox);
        // echo "<pre>";print_r($data);die;
        $output['records']         = $data['result'];
        $output['paginationLinks'] = getPagination(site_url('customers-list'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']           = $start;
        if ($this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('customers/customerslistajax', $output, TRUE);
            echo json_encode($response);
            exit;
        } //$this->input->is_ajax_request()
        else {
            $this->load->view('commonFiles/header', $output);
            $this->load->view('customers/index');
            $this->load->view('commonFiles/footer');
        }

    }

    /*
     * Function Name addNewCustomer
     *
     * to load add new customer view.
     *
     */

    public function addNewCustomer()
    {
        $output['title']           = 'Customer Management';
        $output['breadcrumbs']     = 'Add Customer';
        $output['viewuser']        = '';
        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Customers', 'customers-list', 0);
        $this->make_bread->add('Add Customer', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;
        $output['userDetail']      = $this->CustomerModel->getTableCloumns();
        $output['countries']       = getCountries(null, '49');
        $output['btnValue']        = 'Add Customer';
        // echo "<pre>";print_r($output['countries'] );die;

        $this->load->view('commonFiles/header', $output);
        $this->load->view('customers/addNewCustomer');
        $this->load->view('commonFiles/footer');
    }

    /*
     * Function Name updateCustomer
     *
     * update customer by ref id.
     *
     */

    public function updateCustomer($customerRef = NULL)
    {

        $output['title']           = 'Customer Management | Update  Customer';
        $output['breadcrumbs']     = 'Update Customer';
        $output['viewuser']        = '';

        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Customers', 'customers-list', 0);
        $this->make_bread->add('Update Customer', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb']        = $breadcrumb;


        $output['countries']       = getCountries(null, '49');
        $output['btnValue']        = 'Update Customer';
        // echo "<pre>";print_r($output['countries'] );die;

        if (strpos(current_url(), 'view-customer') !== false) {
            $output['viewuser']    = 'viewcustomer';
            $output['breadcrumbs'] = 'View Customer';
        } //strpos(current_url(), 'view-customer') !== false
        $customerDetails = $this->CustomerModel->getDataByRef($customerRef);
        if (empty($customerDetails)) {
            $this->session->set_flashdata('error_message', 'Something went wrong. Please try again.');
            redirect('users');
        } //empty($customerDetails)
        $output['userDetail'] = $customerDetails;
        // echo "<pre>";print_r($output); die;
        $this->load->view('commonFiles/header', $output);
        $this->load->view('customers/addNewCustomer');
        $this->load->view('commonFiles/footer');
    }

    /*
     * Function Name AjaxAddUpdateCustomer
     *
     * to add new customer...
     *
     */

    public function AjaxAddUpdateCustomer()
    {

        if ($_POST) {
            // $this->form_validation->set_rules('lastName', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('contactName', 'Contact Name', 'required');
            $this->form_validation->set_rules('businessName', 'Business', 'required');
            $this->form_validation->set_rules('phoneNo1', 'Phone No. 1', 'required');
            $this->form_validation->set_rules('countryId', 'Country', 'required');
            $this->form_validation->set_rules('stateId', 'State', 'required');
            $this->form_validation->set_rules('cityId', 'City', 'required');
            $this->form_validation->set_rules('deliveryMethodRef', 'Delivery Method', 'required');
            if (!$this->form_validation->run()) {
                $errors                 = $this->form_validation->error_array();
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = $errors;
            } //!$this->form_validation->run()
            else {
                if ($_POST['customerRef'] != '') {
                    $isCatExits = $this->CommonModel->checkexist('ws_customers', array(
                        'customerRef !=' => $_POST['customerRef'],
                        'phoneNo1 =' => $_POST['phoneNo1']
                    ));
                } //$_POST['customerRef'] != ''
                else {
                    $isCatExits = $this->CommonModel->checkexist('ws_customers', array(
                        'phoneNo1 =' => $_POST['phoneNo1']
                    ));
                }
                //  echo $this->db->last_query();
                // print_r($isCatExits);die;
                if ($isCatExits) {
                    $response['success']    = false;
                    $response['formErrors'] = true;
                    $response['errors']     = array(
                        'phoneNo1' => 'Oops, Customer Contact Phone  already taken, please try new one.'
                    );
                    echo json_encode($response);
                    die;
                } //$isCatExits
                else {
                    $_POST['contactName'] = ucwords($_POST['contactName']);
                    if (trim($this->input->post('customerRef')) != '') {
                        $_POST['modifiedDate'] = date('Y-m-d');
                        $responseData          = $this->CommonModel->update(array(
                            'customerRef' => $_POST['customerRef']
                        ), $_POST, 'ws_customers');
                        if ($responseData) {
                            $response['success']         = true;
                            $response['success_message'] = 'Customer Updated successfully';
                        } //$responseData
                        else {
                            $response['success']         = true;
                            $response['success_message'] = 'Customer already updated.';
                        }
                    } //trim($this->input->post('customerRef')) != ''
                    else {
                        $_POST['status']       = 1;
                        $_POST['addedOn']      = date('Y-m-d');
                        $_POST['modifiedDate'] = date('Y-m-d');
                        $_POST['addedBy']      = $this->userRef;
                        $_POST['customerRef']  = generateRef();
                        $responseData          = $this->CommonModel->insert('ws_customers', $_POST);
                        if ($responseData) {
                            $response['success']         = true;
                            $response['success_message'] = 'Customer Added successfully';
                        } //$responseData
                        else {
                            $response['success']       = false;
                            $response['error_message'] = 'Something went wrong please try again.';
                        }
                    }
                }
            }
            if ($response['success'] == true) {
                $response['url']       = site_url('customers-list');
                $response['delayTime'] = '4000';
            } //$response['success'] == true
            echo json_encode($response);
            die;
        } //$_POST
    }

    /*
     *
     * function name searchCustomerByName
     *
     * search cutomer by name.
     */

    public function searchCustomerByName()
    {
        if ($_GET) {
            $response = $this->CustomerModel->searchCustomerByName($_GET);
            echo json_encode($response);
            die;
        } //$_GET
    }

    /**
    function exportCustomers
    to export all customers in csv file
    **/
    public function importCustomers()
    {
        $file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
        // echo "<pre>";print_r($file_data);die;
        if (!empty($file_data)) {
            foreach ($file_data as $row) {
                if ($row['customerEmail'] != '') {
                    $isCatExits = $this->CommonModel->checkexist('ws_customers', array(
                        'customerEmail' => $row['customerEmail'],
                        'phoneNo1 =' => $row['phoneNo1'],
                        'phoneNo2 =' => $row['phoneNo2']
                    ));
                } //$row['customerEmail'] != ''
                else {
                    $isCatExits = $this->CommonModel->checkexist('ws_customers', array(
                        'phoneNo1 =' => $row['phoneNo1']
                    ));
                }
                if ($isCatExits) {
                    $updatedata[] = array(
                        'businessName' => ucfirst($row['businessName']),
                        'contactName' => ucwords($row['fullName']),
                        'phoneNo2' => $row['phoneNo2'],
                        'phoneNo1' => $row['phoneNo1'],
                        'customerEmail' => $row['customerEmail'],
                        'addressLine' => $row['addressLine'],
                        'countryId' => 49,
                        'cityId' => getCityByName($row['cityName']),
                        'stateId' => getStateByName($row['stateName']),
                        'deliveryMethodRef' => getMethodByName($row['methodName']),
                        'addedOn' => date('Y-m-d'),
                        'status' => 1,
                        'modifiedDate' => date('Y-m-d'),
                        'addedBy' => $this->userRef
                    );

                } //$isCatExits
                else {
                    $addData[] = array(
                        'customerRef' => generateRef(),
                        'businessName' => ucfirst($row['businessName']),
                        'contactName' => ucwords($row['fullName']),
                        'phoneNo2' => $row['phoneNo2'],
                        'phoneNo1' => $row['phoneNo1'],
                        'customerEmail' => $row['customerEmail'],
                        'addressLine' => $row['addressLine'],
                        'countryId' => 49,
                        'cityId' => getCityByName($row['cityName']),
                        'stateId' => getStateByName($row['stateName']),
                        'deliveryMethodRef' => getMethodByName($row['methodName']),
                        'addedOn' => date('Y-m-d'),
                        'status' => 1,
                        'modifiedDate' => date('Y-m-d'),
                        'addedBy' => $this->userRef
                    );
                }
            } //$file_data as $row
            // echo "<pre>";print_r($addData);die;
            // echo "<pre>";print_r($updatedata);die;
            if (!empty($updatedata)) {
                $responseData = $this->db->update_batch('ws_customers', $updatedata, 'customerEmail');
            } //!empty($updatedata)
            if (!empty($addData)) {
                $responseData = $this->CommonModel->insert_batch('ws_customers', $addData);
            } //!empty($addData)
            if ($responseData) {
                $response['success']         = true;
                $response['success_message'] = 'Record Updated successfully..';
            } //$responseData
            else {
                $response['success']       = false;
                $response['error_message'] = 'Something went wrong please try again.';
            }

        } //!empty($file_data)
        else {
            $response['success']       = false;
            $response['error_message'] = 'Something went wrong please try again.';
        }
        // echo "<pre>";print_r($updatedata);
        // echo "<pre>";print_r($addData);
        echo json_encode($response);
        die;

    }
    /**
     **function exportCustomers***********
     **to export all customers in csv file**
     **/
    public function exportCustomers()
    {
        $filename  = 'items_' . date('Ymd') . '.csv';
        $usersData = $this->CustomerModel->exportCustomers();
        // echo "<pre>";print_r($usersData);die;
        if (!empty($usersData)) {
            exportData($usersData, $filename);
        } //!empty($usersData)
        else {
            redirect(base_url() . 'customers-list');
        }
    }


/****************************** Functions for customer followup start from here ***************************/
    /**
     **function followup***********
     **to export all customers in csv file**
    **/
    public function followup()
    {
        $output['title']              = 'Order Management | Customer Follow Up';
        $output['breadcrumbs']        = 'Customer Follow up';

        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Customer Follow up', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb']           = $breadcrumb;

        $start     = 0; $searchKey = '';
        if ($this->input->is_ajax_request()) {
            $searchKey = $this->input->post('searchKey');
            $page      = $this->input->post('page');
            $start     = ($page - 1) * $this->perPage;
        } //$this->input->is_ajax_request()
        $data                      = $this->CustomerModel->followups($start, $this->perPage, $searchKey);
        // echo "<pre>";print_r($data);die;
        $output['records']         = $data['result'];
        $output['paginationLinks'] = getPagination(site_url('customer-follow-up'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']           = $start;
        if ($this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('customerFollowup/followlistAjax', $output, TRUE);
            echo json_encode($response);exit;
        } //$this->input->is_ajax_request()
        else {
            $this->load->view('commonFiles/header', $output);
            $this->load->view('customerFollowup/index');
            $this->load->view('commonFiles/footer');
        }
    }
    public function followupOrders()
    {
      $output['title']              = 'Order Management | Orders';
      $output['breadcrumbs']        = 'Customer Follow up';
      $this->make_bread->add('Home', 'home', 0);
      $this->make_bread->add('Customer Follow up', '', 0);
      $breadcrumb = $this->make_bread->output();
      $output['breadcrumb']           = $breadcrumb;
      $start     = 0;
      $searchKey = '';
            if ($this->input->is_ajax_request()) {
                $searchKey = $this->input->post('searchKey');
                $page      = $this->input->post('page');
                // $start     = ($page - 1) * $this->perPage;
                $start     = 1;
                $this->perPage = $this->input->post('limit');
            }
        $data                      = $this->CustomerModel->customerFollowupOrders($start, $this->perPage, $searchKey,null);

        $completedFollowups        = $this->CustomerModel->completedFollowups($start, $this->perPage, $searchKey);
        $output['records']         = $data['result'];
        $output['total_rows']      = $data['total_rows'];
        $output['complatedOrders'] = $completedFollowups['result'];
        // echo '<pre>';print_r($output); die;
        $output['paginationLinks'] = getPagination(site_url('customer-follow-up-orders'), $this->perPage, $data['total_rows'], '', 1, 4);
        $output['pagination']      = getPagination(site_url('customer-follow-up-orders'), $this->perPage, $completedFollowups['total_rows'], '', 1, 4);
        $output['start']           = $start;
        if (!isset($_GET['ajax']) && $this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('customerFollowup/followupOrders', $output, TRUE);
            echo json_encode($response['html']);exit;
        }
        else
        {
          $this->load->view('commonFiles/header', $output);
          $this->load->view('customerFollowup/followupOrders', $output);
          $this->load->view('commonFiles/footer');
        }

    }


    public function saveCustomerFollowup()
    {
      // pr($_POST);die;
      if ($_POST) {
        $data['errorStatus']        = '';
        $data['returnStatus']       = '';
        $data['receivedStatus']     = '';
        if (isset($_POST['returnStatus'])) {
            $data['returnStatus']   = (isset($_POST['returnStatusVal'])) ? 'Full' : 'Part';
        }
        if (isset($_POST['errorStatus']))
        {
            if (!empty($_POST['errors']))
            {
              foreach ($_POST['errors'] as $key => $value) {
                $data['errorStatus'] .= $value.',';
              }
              $data['errorStatus'] = rtrim($data['errorStatus'],',');
            }
        }
        if (isset($_POST['recevedStatus']))
        {
          $data['receivedStatus'] = (isset($_POST['recevedStatusVal'])) ? 'Full' : 'Part';
        }
        if (isset($_POST['itemRefId']))
        {
          for ($i=0; $i < count($_POST['itemRefId']); $i++)
          {
            if (trim( $_POST['itemReason'][$i]) !='')
            {
                $reasonArray[] = array(
                  'itemRefId'   => $_POST['itemRefId'][$i],
                  'dispatchRef' => $_POST['dispatchRef'][$i],
                  'itemReason'  => $_POST['itemReason'][$i],
                  'qtyReturn'   => $_POST['qtyReturn'][$i],
                  'isReturn'    => (trim($_POST['itemReason'][$i] > 0) !='' && $_POST['itemReason'][$i] > 0) ? 1 : 0,
                );
            }
          }
        }
        if (!empty($reasonArray))
        {
            $this->CommonModel->update_multiple('ws_dispatched_Items',$reasonArray,array('dispatchRef','itemRefId'));
        }
        if (isset($_POST['commentErrors']))
        {
            $comment['type']        =  'error';
            $comment['orderRef']    =  $_POST['dispatchRef'][0];
            $comment['comment']     =  $_POST['commentErrors'];
            $comment['commentRef']  =  generateRef();
            if ( trim($_POST['commentErrors'])  != '' ) {
              $this->CommonModel->insert('ws_orderComments',$comment);
            }
        }
        if (isset($_POST['comment']))
        {
            $comment['type']        =  'dispatch';
            $comment['orderRef']    =  $_POST['dispatchRef'][0];
            $comment['comment']     =  $_POST['comment'];
            if ( trim($_POST['comment'])  != '' ) {
              $this->CommonModel->insert('ws_orderComments',$comment);
            }

        }
        if (isset($_POST['creditNote']))
        {
            $comment['type']        =  'creditNote';
            $comment['orderRef']    =  $_POST['dispatchRef'][0];
            $comment['comment']     =  $_POST['creditNote'];
            if ( trim($_POST['creditNote'])  != '' ) {
              $this->CommonModel->insert('ws_orderComments',$comment);
            }
        }
        if ($_POST['data-ref'] == 'saveNclose' ) {
            $data['dispatchStatus']   = 'Closed';
            // $updateOrder['orderRef']      = $_POST['orderRefId'][0];
            // $updateOrder['modifiedDate']  = date('Y-m-d H:i:s');
            // $this->CommonModel->update(array('orderRef' => $updateOrder['orderRef']),$updateOrder,'ws_orders');
        }
        if (!empty($data)) {
            $this->CommonModel->update(array('dispatchNo' => $_POST['dispatchRef'][0]),$data,'ws_dispatched_orders');
        }
        $response['success'] = true;
        $response['success_message'] = 'Record updated successfully';
        $response['delayTime'] = 3000;
        $response['data'] = $data;
        $response['dispatchRef'] = $_POST['dispatchRef'][0];
      }else{
        $response['success'] = false;
        $response['error_message'] = 'Something went wrong please try again';
        $response['delayTime'] = 3000;
      }
      if ($response['success']) {
        $response['modelhide'] = 'confirm-update-customer-follow-up-modal';
      }
      echo json_encode($response);die;
    }

    public function exportCustomerFollowup($start,$end)
    {
        $closed = $this->OrderModel->closedOrders($start,$end);
        // echo pr($closed);
        foreach ($closed as $key => $value) {
          $errors = array();
          $followupComments = array();
          $followupComments['errorComment']     = '';
          $followupComments['dispatchComment']  = '';
          $followupComments['creditNote']       = '';
          $exportData[$key] = array
                            (
                                'customerName'     =>   $value->customerName,
                                // 'orderNo'          =>   $value->dispatchNo,
                                // 'orderNo'          =>   $value->orderNo,
                                'dispatchNo'       =>   $value->dispatchNo,
                                'orderDate'        =>   $value->orderDate,
                                'dispatchDate'     =>   $value->dispatchDate,
                                'dispatchNo'       =>   $value->dispatchNo,
                                'city'             =>   $value->city,
                                'state'            =>   $value->state,
                                'invoiceNo'        =>   $value->invoiceNo,
                                'receivedStatus'   =>   $value->receivedStatus,
                                'errorStatus'      =>   $value->errorStatus,
                                'returnStatus'     =>   $value->returnStatus,
                                'orderDate'        =>   $value->orderDate,
                                'dispatchDate'     =>   $value->dispatchDate,
                                'deliveryMethod'   =>   $value->deliveryMethod,
                            );
            $arrayCount = count($value->errors);
            $kk = 0;
            foreach ( getFollowupErrors() as $error)
            {
              if(count($value->errors)>0)
              {
                  if ($value->errors[$kk]->id  ===  $error->id)
                  {
                      $errors[str_replace(' ', '_', $error->error)]  = 'Yes';
                  }
                  else{
                        $errors[str_replace(' ', '_', $error->error)]  = 'No';
                  }
                  $kk++;
                  if ($arrayCount == $kk) {
                    $kk = 0;
                  }
               }
               else
               {
                  $errors[str_replace(' ', '_', $error->error)]  = 'No';
               }

            }
            if (count($errors)> 0) {
              $expData[] = array_merge($exportData[$key], $errors);
            }else{
              $expData[] = $exportData[$key];
            }

            if(count($value->followupComments)>0)
            {
              foreach ( $value->followupComments as $comment)
              {
                  if ($comment->type == 'error') {
                      $followupComments['errorComment']     = $comment->comment;
                  }
                  if ($comment->type == 'dispatch') {
                      $followupComments['dispatchComment']  = $comment->comment;
                  }
                  if ($comment->type == 'creditNote') {
                      $followupComments['creditNote']       = $comment->comment;
                  }

              }
            }
            $exportCustomersFollowup[] = array_merge($expData[$key], $followupComments);
        }
        // pr($exportCustomersFollowup);
        // die;
        $filename = 'followup_'.date('Ymd').'.csv';
        exportData($exportCustomersFollowup,$filename);
    }



    /**
     * getFollowupError to get follow up errors
     */


     public function getFollowupError($id)
     {
       $disptch = disptachFollowup($id);
       $error = explode(',',$disptch->errorStatus);
       $getFollowupErrors = getDispacpErrors($error);
       $div = '';
       foreach ($getFollowupErrors as $key => $value) {
         $div .='<tr><td>'.$value->error.'</td><td>YES</td></tr>';
       }
       echo $div;
     }
}
