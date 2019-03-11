<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SettingController extends CI_Controller
{
    function __construct()
    {

        // Construct the parent class
        parent::__construct();
        $this->load->model('SettingModel');
        $this->load->model('CommonModel');
        $this->load->model('OrderModel');
        $this->perPage    = 10;
        $loginSessionData = $this->session->userdata('clientData');
        $this->userRef    = $loginSessionData['userRef'];
        if (empty($loginSessionData)) {
            redirect();
        }
    }
    /*
     *
     *Function name deliveryMethod
     *
     * all delivery method listing
     */
    public function deliveryMethod()
    {
        $output['title']           = 'Settings | Delivery Method';
        $output['breadcrumbs']      = "Delivery Method";
        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Settings', '', 0);
        $this->make_bread->add('Delivery Method', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;


        $start                     = 0;
        $searchKey                 = '';
        $statusBox = '';
        if ($this->input->is_ajax_request()) {
            $searchKey = $this->input->post('searchKey');
            $page      = $this->input->post('page');
            $statusBox = $this->input->post('statusBox');
            $start     = ($page - 1) * $this->perPage;
        }
        $data                      = $this->SettingModel->getDeliveryMethod($start, $this->perPage, $searchKey,$statusBox);
        $output['records']         = $data['result'];
        $output['paginationLinks'] = getPagination(site_url('delivery-method'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']           = $start;
        if ($this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('settings/deliveryMethod/dilivertMethodlistajax', $output, TRUE);
            echo json_encode($response);
            exit;
        } else {
            $this->load->view('commonFiles/header', $output);
            $this->load->view('settings/deliveryMethod/index');
            $this->load->view('commonFiles/footer');
        }
    }
    /*
     *
     *Function name addUpdateDeliveryMethod
     *
     * add and update a  delivery method
     */
    public function addUpdateDeliveryMethod()
    {
        if ($_POST) {
            $this->form_validation->set_rules('methodName', 'Delivery Method Name', 'required');
            if (!$this->form_validation->run()) {
                $errors                 = $this->form_validation->error_array();
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = $errors;
            } else {
                if ($_POST['deliveryMethodRef'] != '') {
                    $isExits = $this->CommonModel->checkexist('ws_deliveryMethod', array(
                        'deliveryMethodRef !=' => $_POST['deliveryMethodRef'],
                        'methodName' => trim(ucwords($_POST['methodName']))
                    ));
                } else {
                    $isExits = $this->CommonModel->checkexist('ws_deliveryMethod', array(
                        'methodName' => trim(ucwords($_POST['methodName']))
                    ));
                }

                if ($isExits) {
                    $response['success']    = false;
                    $response['formErrors'] = true;
                    $response['errors']     = array(
                        'methodName' => 'Oops, Delivery Method Name already taken.'
                    );
                    echo json_encode($response);
                    die;
                } else {
                    $_POST['methodName'] = ucwords($_POST['methodName']);
                    if (trim($this->input->post('deliveryMethodRef')) != '') {
                        $_POST['modifiedDate'] = date('Y-m-d');
                        $responseData          = $this->CommonModel->update(array(
                            'deliveryMethodRef' => $_POST['deliveryMethodRef']
                        ), $_POST, 'ws_deliveryMethod');
                        if ($responseData) {
                            $response['success']         = true;
                            $response['success_message'] = ' Delivery Method Updated successfully';
                        } else {
                            $response['success']         = true;
                            $response['success_message'] = ' Delivery Method already updated.';
                        }
                    } else {
                        $_POST['status']            = 1;
                        $_POST['addedOn']           = date('Y-m-d');
                        $_POST['modifiedDate']      = date('Y-m-d');
                        $_POST['addedBy']           = $this->userRef;
                        $_POST['deliveryMethodRef'] = generateRef();
                        $responseData               = $this->CommonModel->insert('ws_deliveryMethod', $_POST);
                        if ($responseData) {
                            $response['success']         = true;
                            // $response['reload'] = true;
                            $response['newMethod']       = true;
                            $response['success_message'] = ' Delivery Method Added successfully';
                        } else {
                            $response['success']       = false;
                            $response['error_message'] = 'Something went wrong please try again.';
                        }
                    }
                }
            }
            if ($response['success'] == true) {
                $response['deliveryMethod'] = true;
                $response['modelhide']      = 'AddDeliveryMethod';
                $response['data']           = $_POST;
            }
            echo json_encode($response);
            die;
        }
    }
    /*
     *
     *Function name pricingMethod
     *
     * pricing method listing
     */
    public function pricingMethod()
    {
        $output['title']           = 'Settings | Pricing policy';
        $output['parentUrl']       = "";
        $output['parentUrlActive'] = "Setting Management";
        $output['breadcrumbs']     = 'Pricing policy';
        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Settings', '', 0);
        $this->make_bread->add('Pricing policy', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;

        $start                     = 0;
        $searchKey                 = '';
        $statusBox                 = '';
        if ($this->input->is_ajax_request()) {
            $searchKey = $this->input->post('searchKey');
            $page      = $this->input->post('page');
            $statusBox = $this->input->post('statusBox');
            $start     = ($page - 1) * $this->perPage;
        }
        $data                      = $this->SettingModel->getPricingMethod($start, $this->perPage, $searchKey,$statusBox);
        $output['records']         = $data['result'];
        $output['paginationLinks'] = getPagination(site_url('pricing-method'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']           = $start;
        if ($this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('settings/pricingMethod/pricingMethodlistajax', $output, TRUE);
            echo json_encode($response);
            exit;
        } else {
            $this->load->view('commonFiles/header', $output);
            $this->load->view('settings/pricingMethod/index');
            $this->load->view('commonFiles/footer');
        }
    }
    /*
     *
     *Function name addUpdatePricingMethod
     *
     * to add update a pricing method.
     */
    public function addUpdatePricingMethod()
    {
        if ($_POST) {
            $this->form_validation->set_rules('payementMethod', 'Delivery Method Name', 'required');
            if (!$this->form_validation->run()) {
                $errors                 = $this->form_validation->error_array();
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = $errors;
            } else {
                if ($_POST['pricingRef'] != '') {
                    $isExits = $this->CommonModel->checkexist('ws_pricingMethod', array(
                        'pricingRef !=' => $_POST['pricingRef'],
                        'payementMethod' => trim(ucwords($_POST['payementMethod']))
                    ));
                } else {
                    $isExits = $this->CommonModel->checkexist('ws_pricingMethod', array(
                        'payementMethod' => trim(ucwords($_POST['payementMethod']))
                    ));
                }

                if ($isExits) {
                    $response['success']    = false;
                    $response['formErrors'] = true;
                    $response['errors']     = array(
                        'payementMethod' => 'Oops, Delivery Method Name already taken.'
                    );
                    echo json_encode($response);
                    die;
                } else {
                    $_POST['payementMethod'] = ucwords($_POST['payementMethod']);
                    if (trim($this->input->post('pricingRef')) != '') {
                        $_POST['modifiedDate'] = date('Y-m-d');
                        $responseData          = $this->CommonModel->update(array(
                            'pricingRef' => $_POST['pricingRef']
                        ), $_POST, 'ws_pricingMethod');
                        if ($responseData) {
                            $response['success']         = true;
                            $response['success_message'] = ' Delivery Method Updated successfully';
                        } else {
                            $response['success']         = true;
                            $response['success_message'] = ' Delivery Method already updated.';
                        }
                    } else {
                        $_POST['status']       = 1;
                        $_POST['addedOn']      = date('Y-m-d');
                        $_POST['modifiedDate'] = date('Y-m-d');
                        $_POST['addedBy']      = $this->userRef;
                        $_POST['pricingRef']   = generateRef();
                        $responseData          = $this->CommonModel->insert('ws_pricingMethod', $_POST);
                        if ($responseData) {
                            $response['success']         = true;
                            // $response['reload'] = true;
                            $response['newMethod']       = true;
                            $response['success_message'] = ' Delivery Method Added successfully';
                        } else {
                            $response['success']       = false;
                            $response['error_message'] = 'Something went wrong please try again.';
                        }
                    }
                }
            }
            if ($response['success'] == true) {
                $response['pricingMethod'] = true;
                $response['modelhide']     = 'AddPricingMethod';
                $_POST['addedOn']      = date('d-M-Y');
                $response['data']          = $_POST;
            }
            echo json_encode($response);
            die;
        }
    }
    /************************************************************************************/
    /*
     *
     *Function name unitOfMeasurement
     *
     * listing of unit of measurement.
     */
    public function unitOfMeasurement()
    {

        $output['title']           = 'Unit of Measurement';
        $output['breadcrumbs']     = 'Unit of Measurement';

        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Settings', '', 0);
        $this->make_bread->add('Unit of Measurement', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;

        $start     = 0;
        $searchKey = '';
        $statusBox = '';
        if ($this->input->is_ajax_request()) {
            $searchKey = $this->input->post('searchKey');
            $page      = $this->input->post('page');
            $statusBox = $this->input->post('statusBox');

            $start     = ($page - 1) * $this->perPage;
        }
        $data                      = $this->SettingModel->fetchMesurementList($start, $this->perPage, $searchKey,$statusBox);
        //echo '<pre>';print_r($data); die;
        $output['records']         = $data['result'];
        $output['paginationLinks'] = getPagination(site_url('unit-of-measurement'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']           = $start;
        if ($this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('settings/measurements/unitListAjax', $output, TRUE);
            echo json_encode($response);
            exit;
        } else {
            $this->load->view('commonFiles/header', $output);
            $this->load->view('settings/measurements/index');
            $this->load->view('commonFiles/footer');
        }
    }
    /*
     *Function name addMeasurement
     *
     * to load add measurement view.
     */
    public function addMeasurement()
    {
        $output['title']     = 'Add Unit of Measurement';
        $output['parentUrl'] = 'Setting';
        $measurementdetails  = $this->Setting->getMeasurementTableCloumns();
        $output['result']    = $measurementdetails;
        $this->load->view('layout/header', $output);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/settinglayout');
        $this->load->view('settings/measurements/add-measurement');
        $this->load->view('layout/footer');
    }
    /*
     *Function name addUnitOfMeasurement
     *
     * to add a measurement. and  update a measurement by ref id
     */
    public function addUnitOfMeasurement()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('unitName', 'Unit of measurement', 'required|trim');
            $unitName = $this->input->post('unitName');
            if ($_POST['unitRef'] != '')
                $isValueExits = $this->CommonModel->checkexist('measurement', array(
                    'unitRef !=' => $this->input->post('unitRef'),
                    'unitName =' => $unitName
                ));
            else
                $isValueExits = $this->CommonModel->checkexist('measurement', array(
                    'unitName =' => $unitName
                ));
            if ($isValueExits) {
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = array(
                    'unitName' => 'Oops, this name already taken.'
                );
                echo json_encode($response);die;
            } else {
                if (!$this->form_validation->run()) {
                    $errors                 = $this->form_validation->error_array();
                    $response['success']    = false;
                    $response['formErrors'] = true;
                    $response['errors']     = $errors;
                } else {
                    if (isset($_POST['unitRef']) && trim($_POST['unitRef']) != "") {
                        $_POST['modifiedDate'] = date('Y-m-d');
                        $this->CommonModel->update(array(
                            'unitRef' => $_POST['unitRef']
                        ), $_POST, 'measurement');
                        $response['success_message'] = 'Record updated successfully!';
                    } else {
                        $_POST['createdDate']  = date('Y-m-d');
                        $_POST['modifiedDate'] = date('Y-m-d');
                        $_POST['status']       = 1;
                        $_POST['unitRef']      = generateRef();
                        $this->CommonModel->insert('measurement', $_POST);
                        $response['success_message'] = 'Record added successfully!';
                        $response['newOUM']          = true;
                        $response['createdDate']     = date('d M Y', strtotime($_POST['createdDate']));
                    }
                    $response['success'] = true;
                    if ($_POST['unitRef'] == '') {
                        $response['resetform'] = true;
                    }

                    $response['modelhide'] = 'addUnitOfMeasurement';
                    $response['unitRef']   = $_POST['unitRef'];
                    $response['unitName']  = $_POST['unitName'];
                    $response['delayTime'] = '2000';
                }
            }
            echo json_encode($response);
            die;
        }
    }
    /************************************************************************************/
    public function regions()
    {
        // $data = getRegions();
        // echo "<pre>";print_r($data);die;
        $output['title']           = 'Regions';
        $output['breadcrumbs']     = 'Regions';

        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Settings', '', 0);
        $this->make_bread->add('Regions', '', 0);
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
        }
        $data                      = $this->SettingModel->fetchAllRegions($start, $this->perPage, $searchKey,$statusBox);
        //echo '<pre>';print_r($data); die;
        $output['records']         = $data['result'];
        $output['paginationLinks'] = getPagination(site_url('regions'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']           = $start;
        if ($this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('settings/regions/regionListAjax', $output, TRUE);
            echo json_encode($response);exit;
        }
        else
        {
            $this->load->view('commonFiles/header', $output);
            $this->load->view('settings/regions/index');
            $this->load->view('commonFiles/footer');
        }
    }
    public function getCitesByStaeId($cityIdd = null, $any = null)
    {
        $cityId                    = base64_decode($cityIdd);
        // $data = getRegions();
        // echo "<pre>";print_r($data);die;
        $output['title']           = 'Cities';
        $output['breadcrumbs']     = 'Regions ';

        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Settings', '', 0);
        $this->make_bread->add('Cities', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;

        $start     = 0;
        $searchKey = '';
        $statusBox = '';
        if (!isset($_GET['ajax'])) {
            if ($this->input->is_ajax_request()) {
                $searchKey = $this->input->post('searchKey');
                $page      = $this->input->post('page');
                $statusBox = $this->input->post('statusBox');
                $start     = ($page - 1) * $this->perPage;
            }
        }
        // echo $this->perPage;
        $data                      = $this->SettingModel->fetchAllCitiesById($start, $this->perPage, $searchKey, $cityId,$statusBox);
        // echo '<pre>';print_r($data); die;
        $output['records']         = $data['result'];
        $output['paginationLinks'] = getPagination(site_url('cities/' . $cityIdd), $this->perPage, $data['total_rows'], '', 1, 4);
        $output['start']           = $start;
        if (!isset($_GET['ajax']) && $this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('settings/regions/citiesListAjax', $output, TRUE);
            echo json_encode($response);exit;
        }
        else
        {
            $this->load->view('settings/regions/citiesindex', $output);
        }
    }
    public function addUpdateCity()
    {
        if ($_POST) {
            $this->form_validation->set_rules('sta_id', 'State Name', 'required');
            $this->form_validation->set_rules('name', 'City Name', 'required');
            if (!$this->form_validation->run()) {
                $errors                 = $this->form_validation->error_array();
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = $errors;
            } else {
                if ($_POST['cityId'] != '') {
                    $isExits = $this->CommonModel->checkexist('ws_cities', array(
                        'id !=' => $_POST['cityId'],
                        'sta_id =' => $_POST['sta_id'],
                        'name' => trim(ucwords($_POST['name']))
                    ));
                    // echo $this->db->last_query();die;
                } else {
                    $isExits = $this->CommonModel->checkexist('ws_cities', array(
                        'sta_id =' => $_POST['sta_id'],
                        'name' => trim(ucwords($_POST['name']))
                    ));
                }

                if ($isExits) {
                    $response['success']    = false;
                    $response['formErrors'] = true;
                    $response['errors']     = array(
                        'name' => 'Oops, Town is already exits.'
                    );
                    echo json_encode($response);
                    die;
                } else {
                    $_POST['name'] = ucwords($_POST['name']);
                    if (trim($this->input->post('cityId')) != '') {
                        $id = $_POST['cityId'];unset($_POST['cityId']);
                        $responseData = $this->CommonModel->update(array(
                            'id' => $id
                        ), $_POST, 'ws_cities');
                        $response['update']         = true;
                        if ($responseData) {
                            $response['success']         = true;
                            $response['success_message'] = ' Record Updated successfully';
                        } else {
                            $response['success']         = true;
                            $response['success_message'] = ' Record already updated.';
                        }
                        $_POST['city_id'] = $id;
                    } else {
                        $_POST['status']       = 1;
                        $_POST['con_id']       = 49;
                        $_POST['add_date']     = date('Y-m-d');
                        $_POST['locationType'] = 'City';
                        unset($_POST['cityId']);
                        $responseData = $this->CommonModel->insert('ws_cities', $_POST);
                        $insert_id = $this->db->insert_id();
                        if ($responseData) {
                            $response['success']         = true;
                            // $response['reload'] = true;
                            $response['newMethod']       = true;
                            $response['success_message'] = 'Record Added successfully';
                            $_POST['city_id'] = $insert_id;
                        } else {
                            $response['success']       = false;
                            $response['error_message'] = 'Something went wrong please try again.';
                        }
                    }
                }
            }
            if ($response['success'] == true) {
                // $response['reload']     = true;
                $response['cityRecord'] = true;
                $response['modelhide']  = 'addUpdateCity';
                $response['data']       = $_POST;
            }
            echo json_encode($response);die;
        }
    }
    public function addUpdateNewRegion()
    {
        if ($_POST) {
            $this->form_validation->set_rules('name', 'City Name', 'required');
            if (!$this->form_validation->run()) {
                $errors                 = $this->form_validation->error_array();
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = $errors;
            } else {
                if ($_POST['stateId'] != '') {
                    $isExits = $this->CommonModel->checkexist('ws_regions', array(
                        'id !=' => $_POST['stateId'],
                        'country_id !=' => 49,
                        'name' => trim(ucwords($_POST['name']))
                    ));
                } else {
                    $isExits = $this->CommonModel->checkexist('ws_regions', array(
                        'country_id =' => 49,
                        'name' => trim(ucwords($_POST['name']))
                    ));
                }

                if ($isExits) {
                    $response['success']    = false;
                    $response['formErrors'] = true;
                    $response['errors']     = array(
                        'name' => 'Oops, Region is already exits.'
                    );
                    echo json_encode($response);die;
                } else {
                    $_POST['name'] = ucwords($_POST['name']);
                    if (trim($this->input->post('stateId')) != '') {
                        $id = $_POST['stateId'];
                        unset($_POST['stateId']);
                        $responseData = $this->CommonModel->update(array(
                            'id' => $id
                        ), $_POST, 'ws_regions');
                        if ($responseData) {
                            $response['success']         = true;
                            $response['success_message'] = ' Record Updated successfully';
                        } else {
                            $response['success']         = true;
                            $response['success_message'] = ' Record already updated.';
                        }
                    } else {
                        $_POST['status']     = 1;
                        $_POST['country_id'] = 49;
                        unset($_POST['stateId']);
                        $responseData = $this->CommonModel->insert('ws_regions', $_POST);
                        if ($responseData) {
                            $response['success']         = true;
                            // $response['reload'] = true;
                            $response['newState']        = true;
                            $response['success_message'] = 'Record Added successfully';
                        } else {
                            $response['success']       = false;
                            $response['error_message'] = 'Something went wrong please try again.';
                        }
                    }
                }
            }
            if ($response['success'] == true) {
                $response['reload']     = true;
                $response['cityRecord'] = true;
                $response['modelhide']  = 'addNewRegion';
                $response['data']       = $_POST;
            }
            echo json_encode($response);
            die;
        }
    }
    /*
    Function :- transportCharges
    Work to load transport chrages listing
    */
    public function transportCharges()
    {
        $output['title']           = 'Transport Charges';
        $output['breadcrumbs']     = 'Transport Charges';
        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Settings', '', 0);
        $this->make_bread->add('Transport Charges', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb']           = $breadcrumb;

        $start                     = 0;
        $searchKey                 = '';
        if ($this->input->is_ajax_request()) {
            $searchKey = $this->input->post('searchKey');
            $page      = $this->input->post('page');
            $start     = ($page - 1) * $this->perPage;
        }
        $data                      = $this->SettingModel->fetchAllTranportCharges($start, $this->perPage, $searchKey);
        // die('asdf');
        // echo '<pre>';print_r($data); die;
        $output['records']         = $data['result'];
        $output['paginationLinks'] = getPagination(site_url('transport-charges'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']           = $start;

        if ($this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('settings/transportCharges/transportListAjax', $output, TRUE);
            echo json_encode($response);
            exit;
        } else {
            $this->load->view('commonFiles/header', $output);
            $this->load->view('settings/transportCharges/index');
            $this->load->view('commonFiles/footer');
        }
    }

    public function addNewTransportCharge()
    {
      $this->make_bread->add('Home', 'home', 0);
      $this->make_bread->add('Settings', '', 0);
      $this->make_bread->add('Add Transport Charges', '', 0);
      $breadcrumb = $this->make_bread->output();
      $output['breadcrumb']           = $breadcrumb;
        // echo "<pre>";print_r($_POST);die;
        if (!empty($_POST)) {
          $this->form_validation->set_rules('itemRefId', 'Item Name', 'required', array('required' => 'Please Select valid item and try again.'));
          if (!$this->form_validation->run())
          {
            $errors                 = $this->form_validation->error_array();
            $response['success']    = false;
            $response['formErrors'] = true;
            $response['errors']     = $errors;
          }
          else
          {

            $getStates = getRegionsByCountryId(49);
            $regId     = 0;
            $transportData = $this->CommonModel->getData('ws_transportCharges',array('itemRefId' => $_POST['itemRefId']),'transportRef,addedOn');
            $checkExits = $this->CommonModel->checkexist('ws_transportCharges',array('itemRefId' => $_POST['itemRefId']));
            if (!empty($transportData)) {
              $transportRef = $transportData[0];
            } else {
              $transportRef = generateRef();
            }
            foreach ($getStates['result'] as $key => $value) {
                $data['transportCharge'][] = $_POST['region_id'][$value->stateId];
                for ($i = 0; $i < count($data['transportCharge'][$regId]['deliveryMethodRef']); $i++) {
                    // if ($checkExits && (isset($data['transportCharge'][$regId]['transportRef'][$i]) && trim($data['transportCharge'][$regId]['transportRef'][$i]) != '') ) {
                    if ($checkExits ){
                          $updateDataArray[] = array(
                            'itemRefId'         => $_POST['itemRefId'],
                            'transportRef'      => $transportRef->transportRef,
                            'region_id'         => $data['transportCharge'][$regId][0],
                            'deliveryMethodRef' => $data['transportCharge'][$regId]['deliveryMethodRef'][$i],
                            'pricingMode'       => $_POST['pricingMode'],
                            'price'             => $data['transportCharge'][$regId]['price'][$i],
                            'status'            => 1,
                            'addedBy'           => $this->userRef,
                            'addedOn'           => $transportRef->addedOn,
                            'modifiedDate'      => date('Y-m-d H:i:s')
                          );
                    } else {
                          $AddataArray[] = array(
                              'itemRefId'         => $_POST['itemRefId'],
                              'transportRef'      => $transportRef,
                              'region_id'         => $data['transportCharge'][$regId][0],
                              'deliveryMethodRef' => $data['transportCharge'][$regId]['deliveryMethodRef'][$i],
                              'pricingMode'       => $_POST['pricingMode'],
                              'price'             => $data['transportCharge'][$regId]['price'][$i],
                              'status'            => 1,
                              'addedBy'           => $this->userRef,
                              'addedOn'           => date('Y-m-d H:i:s'),
                              'modifiedDate'      => date('Y-m-d H:i:s')
                          );
                    }
                }
                $regId++;
            }
            if (!empty($AddataArray)) {
                $insertData = $this->CommonModel->insert_batch('ws_transportCharges', $AddataArray);
                if ($insertData) {
                    $response['success']          = true;
                    $response['url']              = base_url() . 'transport-charges';
                    $response['delayTime']        = 1000;
                    $response['success_message']  = 'Transport Charges Added successfully.';
                }
            }
            // echo "<pre>";print_r($updateDataArray);die();
            if (!empty($updateDataArray)) {
                $this->CommonModel->delete($transportRef->transportRef, 'transportCharge');
                $updatedata = $this->CommonModel->insert_batch('ws_transportCharges', $updateDataArray);
                if ($updatedata) {
                    $response['success']          = true;
                    $response['url']              = base_url() . 'transport-charges';
                    $response['delayTime']        = 1000;
                    $response['success_message']  = 'Transport Charges Updated successfully.';
                }
            }
          }
          echo json_encode($response); die;
        } else {
            $output['title']           = 'Transport Charges';
            $output['breadcrumbs']     = 'Add New Transport Charges';
            $output['details']         = $this->SettingModel->getTableCloumns();
            $this->load->view('commonFiles/header', $output);
            $this->load->view('settings/transportCharges/addNewTransport');
            $this->load->view('commonFiles/footer');
        }
    }

    public function updateTransportCharges($refId = null)
    {
        $output['details'] = $this->CommonModel->getData('ws_transportCharges', array(
            'itemRefId' => $refId
        ), 'ws_transportCharges.*,(select itemName from ws_products where ws_transportCharges.itemRefId = productRef limit 0,1) as itemName');
        if (!empty($output['details'])) {
            $data['details']    = $output['details'];
            $regionId           = '';
            $details            = new stdClass();
            $details->transport = new stdClass();
            foreach ($data['details'] as $key => $transportValue) {
                $details->transport->transportDetails                        = (object) array(
                    'itemName'    => $transportValue->itemName,
                    'itemRefId'   => $transportValue->itemRefId,
                    'pricingMode' => $transportValue->pricingMode
                );
                $region_id                                                   = ($regionId == '') ? $transportValue->region_id : $regionId;
                $region_id                                                   = ($regionId != '' && $regionId != $transportValue->region_id) ? $regionId : $transportValue->region_id;
                $details->transport->details->{$transportValue->region_id}[] = array(
                                                                                  'transportRef' => $transportValue->transportRef,
                                                                                  'region_id' => $transportValue->region_id,
                                                                                  'deliveryMethodRef' => $transportValue->deliveryMethodRef,
                                                                                  'price' => $transportValue->price
                                                                              );
                $regionId                                                    = $transportValue->region_id;
            }

              $this->load->view('settings/transportCharges/updateTransport', $details);
        }
        else {
          $this->load->view('settings/transportCharges/addNewTransport');
        }
    }
    /*
    Function :- productionOutput
    Work to load production output listing
    */
    public function productionOutput()
    {
        $output['title']           = 'Production Output';
        $output['breadcrumbs']     = 'Production Output';

        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Settings', '', 0);
        $this->make_bread->add('Production Output', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;


        $today                     = date('Y-m-d', strtotime('-0 monday'));
        $day                       = date('l');
        $ddate                     = date('Y-m-d');
        $this_end_date             = date('Y-m-d', strtotime('- 1 year'));
        $start                     = 0;
        $searchKey                 = '';
        if ($this->input->is_ajax_request()) {
            $searchKey = $this->input->post('searchKey');
            $page      = $this->input->post('page');
            $start     = ($page - 1) * $this->perPage;
        }
        $data                      = $this->OrderModel->fetchProductionOutputPagination($today, $this_end_date, $start, $this->perPage, $searchKey);
        // echo '<pre>';print_r($data); die;
        $output['records']         = $data['result'];
        $output['paginationLinks'] = getPagination(site_url('production-output'), $this->perPage, $data['total_rows'], '', 1);
        $output['start']           = $start;
        if ($this->input->is_ajax_request()) {
            $response['html'] = $this->load->view('settings/productionOutput/listingAjax', $output, TRUE);
            echo json_encode($response);
            exit;
        } else {
            $this->load->view('commonFiles/header', $output);
            $this->load->view('settings/productionOutput/listing');
            $this->load->view('commonFiles/footer');
        }

    }
    /*
    Function :- addUpdateProductionOutput
    Work to Add and update production output
    */
    public function addUpdateProductionOutput()
    {
        $output['title']           = 'Production Output';
        $output['breadcrumbs']     = 'Production Output';

        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Settings', '', 0);
        $this->make_bread->add('Add Production Output', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;

        if (!empty($_POST)) {
          // echo "<pre>";print_r($_POST);die;
            $blocks = '';
            foreach ($_POST['days'] as $key => $value) {
                $blocks .= $value . ',';
            }
            $blocks = rtrim($blocks, ',');

            $today           = date('Y-m-d');
            $day             = date('l');
            $ddate           = date('Y-m-d');
            $this_start_date = date('Y-m-d', strtotime('now'));
            $date            = new DateTime($ddate);
            $week            = $date->format("W");
            $year            = date('Y');
            $dateTime        = new DateTime();
            $dateTime->setISODate($year, $week);
            $dateTime->modify('+6 days');
            $this_end_date = $dateTime->format('Y-m-d');
            $data          = array(
                'productionRef' => generateRef(),
                'weekStartDate' => $this_start_date,
                'weekEndDate'   => $this_end_date,
                'blocks'        => $blocks,
                'addedBy'       => $this->userRef
            );
            // echo "<pre>";print_r($data);die;
            $isExits       = $this->CommonModel->checkexist('ws_productionOutput', null, $data);
            // echo "isexuts = >$isExits";die;
            if ($isExits) {
                unset($data['productionRef']);
                $responseData = $this->CommonModel->update(array(
                    'weekStartDate' => $this_start_date,
                    'weekEndDate' => $this_end_date
                ), $data, 'ws_productionOutput');
                if ($responseData) {
                    $response['success']         = true;
                    $response['success_message'] = ' Record Updated successfully';
                } else {
                    $response['success']         = true;
                    $response['success_message'] = ' Record already updated.';
                }
            } else {
                $responseData = $this->CommonModel->insert('ws_productionOutput', $data);
                if ($responseData) {
                    $response['success']         = true;
                    // $response['reload'] = true;
                    $response['newState']        = true;
                    $response['success_message'] = 'Record Added successfully';
                } else {
                    $response['success']       = false;
                    $response['error_message'] = 'Something went wrong please try again.';
                }
            }
            // $response['reload'] = true;
            echo json_encode($response);
            exit;
        } else {
            $today                      = date('Y-m-d', strtotime('now'));
            $day                        = date('l');
            $ddate                      = date('Y-m-d');
            $this_end_date              = date('Y-m-d', strtotime('-3 monday'));
            $output['productionOutput'] = $this->OrderModel->fetchProductionOutput($today, $this_end_date);
            // echo "<pre>";print_r($output);die;
            $this->load->view('commonFiles/header', $output);
            $this->load->view('settings/productionOutput/index');
            $this->load->view('commonFiles/footer');
        }
    }

      /*
     * Function name exportExcel
     *
     * To export items into CSV file
     *
     */
      // Export data in CSV format
     public function exportExcel(){
       // echo getStateByName('Rift Valley');
      $filename = 'transport_'.date('Ymd').'.csv';
      $usersData =  $this->SettingModel->getTransportData();
       // echo "<pre>"; print_r($usersData);die;
      exportData($usersData,$filename);
     }

    /*
     * Function name importTransport
     *
     * To export items into xls file
     *
     */
     public function importTransport(){
       // echo generateSKU();die;
       $file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
       // echo "<pre>";print_r($file_data);die;
       $addData     = array();
       $updatedata  = array();
       $transportRefs = generateRef();
       $itemName = '';
       $isCatExits = false;
       if (!empty($file_data)) {
         foreach($file_data as $row)
         {
            $transportData = $this->CommonModel->getData('ws_transportCharges',array('itemRefId' => $row['productSKU']),'transportRef,addedOn');
            if ($itemName != $row['itemName'])
            {
               if (!empty($transportData)) {
                 $isCatExits = $this->CommonModel->checkexist('ws_transportCharges', array('itemRefId' => $row['productSKU']  ));
                 $transportRefup = $transportData[0];
                 $this->CommonModel->delete($transportRefup->transportRef, 'transportCharge');
               }else{
                 $transportRefs  = generateRef();
               }
            }
             if ($isCatExits)
             {
               $updatedata[] = array(
                 'itemRefId'           =>    ($row['productSKU']),
                 'transportRef'           => $transportRefup->transportRef,
                 'region_id'           =>    getStateByName($row['region']),
                 'deliveryMethodRef'   =>    getDeliveryMethodByName($row['transportMethod']),
                 'pricingMode'         =>    ($row['pricingMode']),
                 'price'               =>    $row['Value'],
                 'addedOn'             =>    date('Y-m-d'),
                 'status'              =>    1,
                 'modifiedDate'        =>    date('Y-m-d'),
                 'addedOn'             =>    $transportRefup->addedOn,
                 'addedBy'             =>    $this->userRef,
               );
             }else
             {
               $addData[] = array(
                 'transportRef'        =>    $transportRefs,
                 'itemRefId'           =>    ($row['productSKU']),
                 'region_id'           =>    getStateByName($row['region']),
                 'deliveryMethodRef'   =>    getDeliveryMethodByName($row['transportMethod']),
                 'pricingMode'         =>    ($row['pricingMode']),
                 'price'               =>    $row['Value'],
                 'addedOn'             =>    date('Y-m-d'),
                 'status'              =>    1,
                 'modifiedDate'        =>    date('Y-m-d'),
                 'addedBy'             =>    $this->userRef,
               );
             }
             $itemName = $row['itemName'];
         }
           // echo "<pre>";print_r($addData);
           // echo "*********************************************************************";
           // echo "<pre>";print_r($updatedata);die;

           if (!empty($updatedata)) {
             $responseData = $this->CommonModel->insert_batch('ws_transportCharges', $updatedata);
           }
           if (!empty($addData)) {
             $responseData = $this->CommonModel->insert_batch('ws_transportCharges', $addData);
           }
           if($responseData)
           {
             $response['success'] = true;
             $response['success_message'] = 'Record Updated successfully..';
           }else{
             $response['success'] = false;
             $response['error_message'] = 'Something went wrong please try again.';
           }

       }else {
         $response['success'] = false;
         $response['error_message'] = 'Something went wrong please try again.';
       }
         // echo "<pre>";print_r($updatedata);
         // echo "<pre>";print_r($addData);
         echo json_encode($response);
         die;
     }

     /*
     * Funtion Loading Sheets
     *
     */

     public function loadingSheets()
     {
       $output['title']           = 'Loading Sheets';
       $output['breadcrumbs']     = 'Loading Sheets';

       $this->make_bread->add('Home', 'home', 0);
       $this->make_bread->add('Settings', '', 0);
       $this->make_bread->add('Loading Sheets', '', 0);
       $breadcrumb = $this->make_bread->output();
       $output['breadcrumb'] = $breadcrumb;

       $start                     = 0;
       $searchKey                 = '';
       $statusBox                 = '';
       if ($this->input->is_ajax_request()) {
           $searchKey = $this->input->post('searchKey');
           $page      = $this->input->post('page');
           $statusBox = $this->input->post('statusBox');

           $start     = ($page - 1) * $this->perPage;
       }
       $data                      = $this->SettingModel->fetchAllLoadingSheets($start, $this->perPage, $searchKey,$statusBox);
       $output['records']         = $data['result'];
       $output['paginationLinks'] = getPagination(site_url('loading-sheets'), $this->perPage, $data['total_rows'], '', 1);
       $output['start']           = $start;
       if ($this->input->is_ajax_request()) {
           $response['html'] = $this->load->view('settings/loadingSheets/ajaxLoadingSheets', $output, TRUE);
           echo json_encode($response);exit;
       }
       else {
           $this->load->view('commonFiles/header', $output);
           $this->load->view('settings/loadingSheets/index');
           $this->load->view('commonFiles/footer');
       }
     }

     public function addLoadingSheet()
     {
         if ($_POST) {
             $this->form_validation->set_rules('refName', 'Sheet Name', 'required');
             if (!$this->form_validation->run()) {
                 $errors                 = $this->form_validation->error_array();
                 $response['success']    = false;
                 $response['formErrors'] = true;
                 $response['errors']     = $errors;
             } else {
                 if ($_POST['sheetRef'] != '') {
                     $isExits = $this->CommonModel->checkexist('ws_loadingSheets', array(
                         'sheetRef !=' => $_POST['sheetRef'],
                         'refName' => trim(ucwords($_POST['refName']))
                     ));
                 } else {
                     $isExits = $this->CommonModel->checkexist('ws_loadingSheets', array(
                         'refName' => trim(ucwords($_POST['refName']))
                     ));
                 }

                 if ($isExits) {
                     $response['success']    = false;
                     $response['formErrors'] = true;
                     $response['errors']     = array(
                         'refName' => 'Oops, Sheet Name is already exits.'
                     );
                     echo json_encode($response);
                     die;
                 } else {
                     $_POST['refName'] = ucwords($_POST['refName']);
                     $_POST['modifiedDate'] = date('Y-m-d H:i:s');
                     if (trim($this->input->post('sheetRef')) != ''){

                       $responseData = $this->CommonModel->update(array(
                           'sheetRef' => trim($this->input->post('sheetRef'))
                       ), $_POST, 'ws_loadingSheets');
                         if ($responseData) {
                             $response['success']         = true;
                             $response['success_message'] = ' Record Updated successfully';
                         } else {
                             $response['success']         = true;
                             $response['success_message'] = ' Record already updated.';
                         }
                     } else {
                         $_POST['status']         = 1;
                         $_POST['sheetRef']       = generateRef();
                         $_POST['addedOn']        = date('Y-m-d H:i:s');
                         $_POST['modifiedDate']   = date('Y-m-d H:i:s');
                         $responseData = $this->CommonModel->insert('ws_loadingSheets', $_POST);
                         if ($responseData) {
                             $response['success']               = true;
                             $response['newLoadingSheet']       = true;
                             $response['newSheet']       = true;
                             $response['success_message'] = 'Record Added successfully';
                         } else {
                             $response['success']       = false;
                             $response['error_message'] = 'Something went wrong please try again.';
                         }
                     }
                 }
             }
             if ($response['success'] == true) {
                 $response['data']       = $_POST;
                 $response['modelhide']       = 'addNewLoadingSheet';
             }
             echo json_encode($response);die;
         }
     }

     /*
      *
      *Function name deliveryMethod
      *
      * all delivery method listing
      */
     public function blockTypes()
     {
         $output['title']             = 'Settings | Block Types';
         $output['breadcrumbs']       = "Block Types";
         $this->make_bread->add('Home', 'home', 0);
         $this->make_bread->add('Settings', '', 0);
         $this->make_bread->add('Block Types', '', 0);
         $breadcrumb = $this->make_bread->output();
         $output['breadcrumb'] = $breadcrumb;
         $start                     = 0;
         $searchKey                 = '';
         $statusBox                 = '';
         if ($this->input->is_ajax_request()) {
             $searchKey = $this->input->post('searchKey');
             $page      = $this->input->post('page');
             $start     = ($page - 1) * $this->perPage;
             $statusBox = $this->input->post('statusBox');
         }
         $data                      = $this->SettingModel->blockTypes($start, $this->perPage, $searchKey,$statusBox);
         $output['records']         = $data['result'];
         $output['paginationLinks'] = getPagination(site_url('block-types'), $this->perPage, $data['total_rows'], '', 1);
         $output['start']           = $start;
         if ($this->input->is_ajax_request()) {
             $response['html'] = $this->load->view('settings/blockTypes/blockTypelistajax', $output, TRUE);
             echo json_encode($response);
             exit;
         } else {
             $this->load->view('commonFiles/header', $output);
             $this->load->view('settings/blockTypes/index');
             $this->load->view('commonFiles/footer');
         }
     }
     /*
      *
      *Function name addUpdateDeliveryMethod
      *
      * add and update a  delivery method
      */
     public function addUpdateblockTypes()
     {
         if ($_POST) {
             $this->form_validation->set_rules('blockType', 'Block Type', 'required|trim');
             if (!$this->form_validation->run()) {
                 $errors                 = $this->form_validation->error_array();
                 $response['success']    = false;
                 $response['formErrors'] = true;
                 $response['errors']     = $errors;
             } else {
                 if ($_POST['id'] != '') {
                     $isExits = $this->CommonModel->checkexist('blockTypes', array(
                         'id !=' => $_POST['id'],
                         'blockType' => trim(ucwords($_POST['blockType']))
                     ));
                 } else {
                     $isExits = $this->CommonModel->checkexist('blockTypes', array(
                         'blockType' => trim(ucwords($_POST['blockType']))
                     ));
                 }

                 if ($isExits) {
                     $response['success']    = false;
                     $response['formErrors'] = true;
                     $response['errors']     = array(
                         'methodName' => 'Oops, Delivery Method Name already taken.'
                     );
                     echo json_encode($response);
                     die;
                 } else {
                     $_POST['blockType'] = ucwords($_POST['blockType']);
                     if (trim($this->input->post('id')) != '') {
                         $responseData          = $this->CommonModel->update(array(
                             'id' => $_POST['id']
                         ), $_POST, 'blockTypes');
                         if ($responseData) {
                             $response['success']         = true;
                             $response['success_message'] = ' Block Type Updated successfully';
                         } else {
                             $response['success']         = true;
                             $response['success_message'] = ' Block Type already updated.';
                         }
                     } else {
                         $_POST['status']            = 1;
                         $_POST['addedOn']           = date('Y-m-d');
                         $_POST['addedBy']           = $this->userRef;
                         $responseData               = $this->CommonModel->insert('blockTypes', $_POST);
                         if ($responseData) {
                             $_POST['id']                 = $this->db->insert_id();
                             $response['success']         = true;
                             $response['newMethod']       = true;
                             $_POST['addedOn']            = date('d-m-Y', strtotime($_POST['addedOn']));
                             $response['success_message'] = ' Block Type Added successfully';
                         } else {
                             $response['success']       = false;
                             $response['error_message'] = 'Something went wrong please try again.';
                         }
                     }
                 }
             }
             if ($response['success'] == true) {
                 $response['blockType'] = true;
                 $response['modelhide']      = 'blockTypeModal';

                 $response['data']           = $_POST;
             }
             echo json_encode($response);die;
         }
     }

}
