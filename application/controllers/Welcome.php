<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->Model('CommonModel');
        $this->perPage    = 10;
        $loginSessionData = $this->session->userdata('clientData');
        $this->userRef    = $loginSessionData['userRef'];
        $this->userName   = $loginSessionData['userName'];
    }


    /*
     * Function name deleteRecord
     *
     * common function to delete records.
     */

    public function deleteRecord()
    {
        if ($this->input->is_ajax_request()) {
            $recordRef  = $this->input->post('ref');
            $recordType = $this->input->post('type');
            if ($recordRef == '' || $recordType == '') {
                $response['success']       = false;
                $response['error_message'] = 'Something went wrong. Please try agian.';
            } else {
                $result = $this->CommonModel->delete($recordRef, $recordType);
                if ($result) {
                    $response['success']         = true;
                    $response['success_message'] = 'Record deleted successfully.';
                } else {
                    $response['success']       = false;
                    $response['error_message'] = 'Something went wrong. Please try agian.';
                }
            }
            echo json_encode($response);
            die;
        }
    }
    /*
     * Function name updateStatus
     *
     * common function to update Status of records.
     */

    public function updateStatus()
    {
        if ($this->input->is_ajax_request()) {
            $ref    = $this->input->post('ref');
            $type   = $this->input->post('type');
            $status = $this->input->post('status');
            if ($ref == '' || $type == '' || $status == '') {
                $response['success']       = false;
                $response['error_message'] = 'Something went wrong. Please try agian.';
            } else {
                if ($status == 1)
                    $status = 0;
                else
                    $status = 1;
                $result = $this->CommonModel->updateStatus($ref, $type, $status);
                if ($result) {
                    $response['success']         = true;
                    $response['success_message'] = 'Record updated successfully.';
                    $response['status']          = $status;
                } else {
                    $response['success']       = false;
                    $response['error_message'] = 'Something went wrong. Please try agian.';
                }
            }
            echo json_encode($response);
            die;
        }
    }

    /*
     * Function name changeOrderStatus
     *
     * common function to change Order Status of orders.
     */

    public function changeOrderStatus()
    {


        if ($this->input->is_ajax_request()) {

            $orderRef       = $this->input->post('orderRef');
            $dataTo         = $this->input->post('dataTo');
            $dataPipline    = $this->input->post('dataPipline');
            $dataProduction = $this->input->post('dataProduction');
            $comment        = $this->input->post('comment');
            $salesRef       = $this->input->post('salesRef');
            $approvedBy     = $this->input->post('approvedBy');
            $reload         = $this->input->post('reload');

            $data = getRegisterIds($orderRef, $dataPipline);


            unset($_POST['reload']);
            if ($orderRef == '' || $dataTo == '' || $dataPipline == '') {
                $response['success']       = false;
                $response['error_message'] = 'Something went wrong. Please try agian.';
            } else {
                $result                = $this->CommonModel->changeOrderStatus($orderRef, $dataTo, $dataPipline, $dataProduction, $salesRef, $_POST);
                $_POST['orderPipline'] = $_POST['dataPipline'];
                $_POST['status']       = $_POST['dataTo'];
                if ($_POST['status'] == 'managerApprove') {
                    $_POST['status'] = 'MAN';
                }
                if ($_POST['status'] == 're-assigned') {
                    $_POST['status'] = 'reAssign';
                }
                $_POST['addedBy']      = $this->userRef;
                $_POST['addedOn']      = date('Y-m-d H:i:s');
                $_POST['modifiedDate'] = date('Y-m-d H:i:s');
                $_POST['commentRef']   = generateRef();
                unset($_POST['dataTo']);unset($_POST['salesRef']);
                unset($_POST['dataPipline']);unset($_POST['dataProduction']);unset($_POST['approvedBy']);unset($_POST['dataApprove']);unset($_POST['itemRefIds']);
                if ($comment != '') {
                    $commentData         = array(
                        'addedBy' => $_POST['addedBy'],
                        'modifiedDate' => $_POST['modifiedDate'],
                        'addedOn' => $_POST['addedOn'],
                        'commentRef' => $_POST['commentRef'],
                        'orderRef' => $orderRef,
                        'comment' => $comment
                    );
                    $resultData          = $this->CommonModel->insert('orderComments', $commentData);
                    $notificationMessage = $comment;
                } else {
                    $notificationMessage = 'order Status has been updated';
                }
                if ($result) {
      					$data = getRegisterIds($orderRef, $dataPipline);
      					// echo $this->db->last_query();
      					// pr($data);die;
                if (!empty($data['users']) && !empty($data['orderData'])) {

                  // pr($data);die;
                    $notificationRef       = generateRef();
                    $notificationTitle     = 'Order # ' . $data['orderData']->orderNo . '. status';
                    $message = array(
                        'message_id'       => $notificationRef,
                        'message'          => $notificationMessage,
                        'messagetitle'     => $notificationTitle,
                        'type'             => 1,
                        'notificationType' => 1,
                        'msg_type'         => 'Order Status Update.',
                        'orderRef'         => $orderRef,
                        'dataRefTo'        => $orderRef,
                        'title'            => $notificationTitle,
                        'notificationRef'  => $notificationRef,
                        'notification'     => 'Order #'. $data['orderData']->orderNo . '. status updated'
                    );

                    foreach ($data['users'] as $key => $registeredUsers) {
                      if ($this->userRef != $registeredUsers->userRef) {
                        $registerID[]          = $registeredUsers->registeredId;
                        $deviceID[]            = $registeredUsers->device_id;

                        // $notification                              = array();
                        $notification['notificationRef']           = $notificationRef;
                        $notification['orderRef']                  = $orderRef;
                        $notification['notificationFrom']          = $this->userRef;
                        $notification['notificationBussinessName'] = $data['orderData']->businessName;
                        $notification['notificationContactName']   = $data['orderData']->customerName;
                        $notification['notificationTo']            = $notificationTo = (isset($registeredUsers->userRef)) ? $registeredUsers->userRef : '';
                        $notification['notificationTitle']         = $notificationTitle;
                        $notification['notificationMessage']       = 'Order mark as ' . orderStatus($_POST['status']) . ' by ' . $this->userName;
                        $notification['starredStaus']              = 0;
                        $notification['status']                    = 1;
                        $notification['readStatus']                = 1;
                        $notification['addedOn']                   = date('Y-m-d H:i:s');
                        $insertData                                = $this->CommonModel->insert('ws_notification', $notification);
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
                // die;

                    $response['success'] = true;
                    $response['status']  = orderStatus($_POST['status']);
                    if ($reload != 'false') {
                        $response['reload'] = true;
                    }
                    $response['success_message'] = 'Record updated successfully.';
                } else {
                    $response['success']       = false;
                    $response['error_message'] = 'Something went wrong. Please try agian.';
                }
            }
            echo json_encode($response);
            die;
        }
    }

    /*
     * Function name getCities
     *
     *  get all getCities by region id.
     */

    public function getCities()
    {
        if ($this->input->is_ajax_request()) {
            $region_id = $this->input->post('id');
            if ($region_id <= 0) {
                $output['success']       = false;
                $output['error_message'] = 'Something happens wrong. Please try again.';
            } else {
                $result = getCitiesByRegionId($region_id);
                if ($result['success'] == false) {
                    $output['success']       = false;
                    $output['error_message'] = 'Something happens wrong. Please try again.';
                } else {
                    $output['success'] = true;
                    $output['cities']  = $result['result'];
                }
            }
            echo json_encode($output);
            die;
        }
    }

    /*
     * Function name getStates
     *
     *  get all getStates by country id.
     */

    public function getStates()
    {
        if ($this->input->is_ajax_request()) {
            $region_id = $this->input->post('id');
            if ($region_id <= 0) {
                $output['success']       = false;
                $output['error_message'] = 'Something happens wrong. Please try again.';
            } else {
                $result = getRegionsByCountryId($region_id);
                if ($result['success'] == false) {
                    $output['success']       = false;
                    $output['error_message'] = 'Something happens wrong. Please try again.';
                } else {
                    $output['success'] = true;
                    $output['cities']  = $result['result'];
                }
            }
            echo json_encode($output);
            die;
        }
    }
    /*
     * Function name getStates
     *
     *  get all getStates by country id.
     */

    public function reArrageOrders()
    {
        if ($this->input->is_ajax_request()) {
            if (isset($_POST['orderByNo']) && !empty($_POST['orderByNo'])) {
                $response = $this->db->update_batch('ws_orders', $_POST['orderByNo'], 'orderRef');
                if ($response) {
                    $output['success']         = true;
                    $output['success_message'] = 'Order Priority Updated successfully.';
                } else {
                    $output['success_message'] = 'Order Priority already updated';
                    $output['success']         = true;
                }
            } else {
                $output['success']       = true;
                $output['error_message'] = 'Something went wrong please try again.';
            }
            echo json_encode($output);
            die;
        }
    }

    public function addNewLoadingSheet()
    {
        $refName  = trim($_POST['refName']);
        $status   = $_POST['status'];
        $sheetRef = generateRef();
        if ($status != '' && $refName != '')
            $isSheetExits = $this->CommonModel->checkexist('ws_loadingSheets', array(
                'refName' => $refName
            ));
        if ($isSheetExits) {
            $response['success']       = false;
            $response['formErrors']    = true;
            $response['error_message'] = 'Please enter Correcting Entries!';
            $response['errors']        = array(
                'refName' => 'Oops, This Name already taken.'
            );
            echo json_encode($response);
            die;
        } else {
            $newName    = array(
                'sheetRef' => $sheetRef,
                'refName' => ucfirst($refName),
                'status' => $status,
                'addedBy' => $this->userRef,
                'addedOn' => date('Y-m-d H:i:s'),
                'modifiedDate' => date('Y-m-d H:i:s')
            );
            $resultData = $this->CommonModel->insert('ws_loadingSheets', $newName);
            if ($resultData) {
                $data = $this->CommonModel->getData('ws_loadingSheets', array(
                    'sheetRef' => $sheetRef
                ), '*');
                if (!empty($data)) {
                    $response['data'] = $data[0];
                } else {
                    $response['data'] = array();
                }
                $response['success']         = true;
                $response['success_message'] = 'Loading Sheet added successfully..';

            } else {
                $response['success']         = false;
                $response['success_message'] = 'Something went worng please try again..';
            }
        }
        echo json_encode($response);
        die;
    }
}
