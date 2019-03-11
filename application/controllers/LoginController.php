<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {
  function __construct()
  {

    // Construct the parent class
    parent::__construct();
    $this->load->model('UsersModel');
    $this->perPageNum = 10;
  }


 /*
  * Function name login
  *
  * login to application
  *
  */

  public function login()
  {
    $loginSessionData = $this->session->userdata('clientData');
    if( !empty($loginSessionData) )
    {
      redirect('dashboard');
    }
    if ($_POST) {
      $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
      $this->form_validation->set_rules('password', 'Password', 'required');

      if ($this->form_validation->run())
      {
        $email      = $this->input->post('email');
        $password   = md5($this->input->post('password'));
        $result     = $this->UsersModel->login($email, $password);
        // echo $this->db->last_query();
        // echo "<pre>";print_r($result);die;
        if (!empty($result) )
        {
          if ($result->isDeleted == 1) {
            $res['success']       = false;
            $res['error_message'] = 'Your account is deleted. Please contact to Administator.';
          }else if ($result->userType == 3) {
            $res['success']       = false;
            $res['error_message'] = 'Your are not authenticate user to login please contact administrator.';
          }else
          {
              $arrayVal = array(
                'clientId'                 => $result->id,
                'userRef'                  => $result->userRef,
                'userName'                 => $result->userName,
                'LoginTime'                => time(),
                'userType'                 => $result->userType,
                'clientEmail'              => $result->userEmail,
                'DispatchPipelineWrite'    => 0,
                'FinancePipelineWrite'     => 0,
                'ProductionPipelineWrite'  => 0,
                'CustomerManagerWrite'     => 0,
                'ItemManagerWrite'         => 0,
                'ReAssignedOrdersWrite'    => 0,
                'CustomerFollowUpWrite'    => 0,
                'UserManagementWrite'      => 0,
              );
              if ($result->userPermissions !='') {
                $getUserPermissions = getUserPermissionsByIds($result->userPermissions);
                if (!empty($getUserPermissions)) {
                  foreach ($getUserPermissions as $key => $value) {
                    $permissionName = str_replace('-', '', encode_url_ci($value->permissionName));
                    $arrayVal[$permissionName] = 1;
                  }
                }
              }
              // echo "<pre>"; print_r($arrayVal);die;
              // delete loading sheet files..
              $documentRoot = $_SERVER['DOCUMENT_ROOT'].'/watervale';
              $de = $this->CommonModel->deleteDirectory($documentRoot.'/assets/excel');

              /////
              $this->session->set_userdata('clientData', $arrayVal);
              $res['success']         = true;
              $res['url']             = site_url('dashboard');
              $res['delayTime']       = 1500;
              $res['success_message'] = 'Login successful ! , Please wait while we redirect you to.';
            }
        }
        else
        {
          $res['success'] = false;
          $res['error_message'] = 'Either Username and password is incorrect.';
        }
      }
      else
      {
        $errors             = $this->form_validation->error_array();
        $res['success']     = false;
        $res['formErrors']  = true;
        $res['errors']      = $errors;
      }
      echo json_encode($res);die();
  }

$this->load->view('login/index');
}


 /*
  * Function name logout
  *
  * logout from application
  *
  */

public function logout() {
    $this->session->sess_destroy();
    redirect(site_url());
}
public function forgetPassword()
{

  if ($_POST) {
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    if ($this->form_validation->run())
    {
      // print_r($_POST);die;
      $email      = $this->input->post('email');
      $emailExistResult = $this->CommonModel->checkexist('users',array('userEmail' => $email));
      if( $emailExistResult != FALSE ){
        $this->load->model('AppLogin');
        $result = $this->AppLogin->forgotPassword($_POST);

        // print_r($result);die;exit();

        $res['success']         = true;
        $res['url']             = site_url('login');
        $res['delayTime']       = 1500;
        $res['success_message'] = 'Password change successfully ! , Please check your email.';

      }else{
        // echo $emailExistResult;die('adsfasdf');
        $errors             =  array('email' => 'Incorrect Email id.', );;
        $res['success']     = false;
        $res['formErrors']  = true;
        $res['errors']      = $errors;
      }
    }else{
      $errors             = $this->form_validation->error_array();
      $res['success']     = false;
      $res['formErrors']  = true;
      $res['errors']      = $errors;
    }
  }
  echo json_encode($res);die;

}

}
