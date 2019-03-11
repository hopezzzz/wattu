<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('UsersModel');
        $this->load->model('OrderModel');
        $this->load->model('CommonModel');
        $this->perPageNum = 10;

        $loginSessionData = $this->session->userdata('clientData');
        $this->userRef    = $loginSessionData['userRef'];
        $this->userType    = $loginSessionData['userType'];

        if (empty($loginSessionData)) {
            redirect();
        }
    }

    /*
     * Function Name dashboard
     *
     * load dashboard view.
     *
     */

    public function dashboard()
    {
        $output['title']       = 'Dashboard';
        $output['breadcrumbs'] = 'Dashboard';
        // $output['statistics']  = $this->OrderModel->getStats();

        $this->make_bread->add('Home', 'home', 0);
        $this->make_bread->add('Dashboard', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;

        $this->load->view('commonFiles/header', $output);
        $this->load->view('dashboard/index');
        $this->load->view('commonFiles/footer');
    }

    /*
     * Function Name dashboard
     *
     * load home view.
     *
     */

    public function home()
    {
        // echo "<pre>";print_r($_SESSION);die;
        if ($this->userType == 1) {
          redirect($_SERVER['HTTP_REFERER']);
        }
        $output['userRef']           = $this->userRef;
        $output['title']             = 'Watervale | Home';
        $output['breadcrumbs']       = 'Home';
        $this->make_bread->add('Home', '', 0);
        $breadcrumb = $this->make_bread->output();
        $output['breadcrumb'] = $breadcrumb;

        $output['userNotifications'] = $this->OrderModel->userNotifications($output);

        $output['states'] = $this->CommonModel->getOrdersStats($this->userRef);
        // pr($output['states']);die;
        /*
        $this->make_bread->add('first crumb', 'testing', 1);
        $this->make_bread->add('second crumb', 'the_test', 0);
        $this->make_bread->add('test','http://google.com');
        $breadcrumb = $this->make_bread->output();
        echo $breadcrumb;die;
        */


        // echo "<pre>";print_r($ordersStates);die;
        $this->load->view('commonFiles/header', $output);
        $this->load->view('home/index');
        $this->load->view('commonFiles/footer');
    }

    public function getStatistics()
    {
        if ($_POST)
        {

            $startDate = date('Y-m-d H:i:s',strtotime($_POST['startDate']));
            $endDate   = date('Y-m-d H:i:s',strtotime($_POST['endDate']));
            $response =  $this->OrderModel->getStats($startDate,$endDate);

            // echo "<pre>";
            // print_r($response);
            // die;
            if ($response) {
              $data['statistics'] = $response;
              $output['html'] = $this->load->view('dashboard/statistics',$data,TRUE);
            } else {
              $output['success'] = false;
              $output['error_message'] = 'Something went wrong please try again';
            }

        }else{
            $output['success'] = false;
            $output['error_message'] = 'Something went wrong please try again';
        }
        echo json_encode($output);exit;
    }


    public function getNotifications()
    {
        if ($_POST) {
            $params['duration'] = $_POST['filterType'];
            $params['filter']   = '';
            if ($_POST['filterType'] == 'Unread') {
                $params['filter'] = 'unread';
                unset($params['duration']);
            }
            if ($_POST['filterType'] == 'Starred') {
                $params['filter'] = 'starred';
                unset($params['duration']);

            }
            if ($_POST['filterType'] == 'Normal') {
                $params['filter'] = 'normal';
                unset($params['duration']);
            }
            if ($_POST['filterType'] == 'all') {
                $params['filter'] = '';
                unset($params['duration']);
            }
            if ($_POST['filterType'] == 'read') {
                $params['filter'] = 'read';
                unset($params['duration']);
            }
            if ($_POST['customerName'] != '') {
                $params['customerName'] = $_POST['customerName'];
            }
            $params['userRef'] = $this->userRef;
            $data              = $this->OrderModel->userNotifications($params);
            $dataHTML          = '';
            if (!empty($data['data'])) {
                foreach ($data['data'] as $key => $value) {
                    $readStatus = ($value->readStatus == 1) ? 'unread' : '';
                    $data       = '<a href="javascript:void(0);" data-noti="notification" data-status="' . $value->starredStaus . ' " class="updateStatus" data-name="' . ucfirst($value->notificationTitle) . '" data-type="notification" data-ref="' . $value->notificationRef . '">';

                    if ($value->starredStaus == 0) {
                        $data .= '<i class="fa fa-star-o statusTD" style="float:right"></i>';
                    } else {
                        $data .= '<i class="fa fa-star statusTD" style="float:right"></i>';
                    }
                    $data .= '</a>';
                    $dataHTML .= '
          <div class="act-time">
            <div class="activity-body act-in" id="notification_' . $value->notificationRef . '">
              <span class="arrow"></span>
              <div class="text">
              ' . $data . '
                <p class="attribution"><a href="javascript:void(0)" data-ref="' . $value->notificationRef . '" class="markAsReadNotification">' . ucwords($value->notificationContactName) . '</a> Order Number #' . $value->orderNo . '</p>
                <p class="attribution">Time:- ' . date('d-m-Y H:i:s', strtotime($value->addedOn)) . '</p>
                <p>' . $value->notificationMessage . ' <a href="' . base_url() . 'order-details/' . $value->orderRef . '"><em>Order Detail</em></a></p>
                <span class="new">' . $readStatus . '</span>
              </div>
            </div>
          </div>';
                }
            } else {
                $dataHTML .= 'No Record Found Please try with new entries.';
            }
            echo $dataHTML;
            exit;
        }
    }


    public function notificationListing()
    {


            $params['userRef']          = $this->userRef;
            $data                       = $this->OrderModel->userNotifications($params);
            $output['title']            = 'User Notification';
            $output['breadcrumbs']      = 'Notifications';
            $output['notification']     =  $data;
            $this->make_bread->add('Home', '', 0);
            $this->make_bread->add('Notifications', '', 0);
            $breadcrumb = $this->make_bread->output();
            $output['breadcrumb'] = $breadcrumb;
            $this->load->view('commonFiles/header', $output);
            $this->load->view('users/notifications');
            $this->load->view('commonFiles/footer');
    }


    public function updateNotificationStatus()
    {
        if (trim($_POST['notificationRef']) != '') {
            $_POST['readStatus'] = 2;
            $response            = $this->CommonModel->update(array(
                'notificationRef' => $_POST['notificationRef']
            ), $_POST, 'ws_notification');
            if ($response) {
                $output['success']         = true;
                $output['success_message'] = 'notification updated successfully.';
            } else {
                $output['success']       = false;
                $output['error_message'] = 'Something went wrong please try again';
            }
        } else {
            $output['success']       = false;
            $output['error_message'] = 'Something went wrong please try again';
        }
        echo json_encode($output);
        exit;
    }

    public function globalSearch()
    {
        if ($_POST) {
          $data['data'] = $this->CommonModel->globalSearch($_POST);
          $response['html'] = $this->load->view('dashboard/searchList',$data,TRUE);
          echo json_encode($response);
        }else{
          echo "string";
        }
        exit;
    }
}
