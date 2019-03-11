<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {
  function __construct()
  {
    // Construct the parent class
    parent::__construct();
    $this->load->model('UsersModel');

    $this->load->model('CommonModel');
    $this->perPage = 5;

    $loginSessionData = $this->session->userdata('clientData');
    $this->userRef = $loginSessionData['userRef'];
    if(empty($loginSessionData) )
    {
      redirect();
    }
  }

/*
 *Function name users
 *
 * to users listing
 */

  public function users()
  {

    $output['title']      = 'User Management';
    $output['breadcrumbs']  = 'Users';
    $this->make_bread->add('Home', 'home', 0);
    $this->make_bread->add('Users', '', 0);
    $breadcrumb = $this->make_bread->output();
    $output['breadcrumb'] = $breadcrumb;

    $start      = 0;
    $searchKey  = '';
    if ($this->input->is_ajax_request())
    {
      $searchKey  = $this->input->post('searchKey');
      $page       = $this->input->post('page');
      $start      = ( $page - 1 ) * $this->perPage;
      $start      = $start > 0 ? $start : 1;
      
    }
    $data = $this->UsersModel->userDetails($start, $this->perPage,$searchKey);
    // echo "<pre>";print_r($data);die;
    $output['records']          = $data['result'];
    $output['paginationLinks']  = getPagination(site_url('users'), $this->perPage, $data['total_rows'], '', 1);
    $output['start']			      = $start;
    if ($this->input->is_ajax_request())
    {
      $response['html'] = $this->load->view('users/userslistajax', $output, TRUE);
      echo json_encode($response);
      exit;
    }
    else
    {
      // echo "<pre>";print_r($output);die;
      $this->load->view('commonFiles/header',$output);
      $this->load->view('users/index');
      $this->load->view('commonFiles/footer');
    }

  }

  /*
 *Function name addNewUser
 *
 * to load add new user view
 */

  public function addNewUser()
  {
    $output['permissions']  = getPermissions();
    $output['title']      = 'User Management | Add New User';
    $output['breadcrumbs']  = 'Add User';
    $output['viewuser'] = '';
    $this->make_bread->add('Home', 'home', 0);
    $this->make_bread->add('Users', 'users', 0);
    $this->make_bread->add('Add User', '', 0);
    $breadcrumb = $this->make_bread->output();
    $output['breadcrumb'] = $breadcrumb;

    $output['userDetail'] = $this->UsersModel->getTableCloumns();
    // echo "<pre>";print_r($output);die;
    $this->load->view('commonFiles/header',$output);
    $this->load->view('users/addNewUser');
    $this->load->view('commonFiles/footer');
  }


 /*
  * function name userRegister
  *
  * add new user
  */
  public function userRegister()
  {
    // echo "<pre>";print_r($_POST);die;
    if ($_POST) {
      $this->form_validation->set_rules('userEmail', 'Email', 'required|valid_email');
      if ($this->form_validation->run() == TRUE) {
        $email = $this->input->post('userEmail');
        $userType = $this->input->post('userType');
        $response = $this->UsersModel->checkemail($email);
        if ($response) {
          $res['success'] = false;
          $res['error_message'] = 'Email already exist. Please try again.';
          echo json_encode($res);
          die();
        }
        $password       = randomPassword();
        $randomString   = generateRef();
        $emailTemplate  = getEmailTemplate(1);
        //$receiver_name 		= ucfirst($this->input->post('firstName')).' '.ucfirst($this->input->post('lastName'));
        $variables = array(
          'to'            => $this->input->post('userEmail'),
          'receiver_name' => 'User',
          'password'      => $password,
        );
        $mailSend = sendEmail($variables, $emailTemplate);
        $_POST['userPermissions'] = '';
        if (!empty($_POST['userPermission']))
        {
          foreach ($_POST['userPermission'] as $key => $value) {
            $permissionId = getPermissionsId($key);
            if(!empty($permissionId)){
              $_POST['userPermissions'] .= $permissionId->permissionId.',';
            }
          }
        }
        unset($_POST['userPermission']);
        $_POST['userPermissions'] = rtrim($_POST['userPermissions'],',');
        if ($mailSend == 1) {
          if (isset($_POST['userActive']))
          {
            $_POST['userActive'] = 1;
          }
          else
          {
            $_POST['userActive'] = 0;
          }
          $_POST['userRef']     = generateRef();
          $_POST['password']    = md5($password);
          $_POST['createdDate'] = date('Y-m-d');
          $_POST['status']      = 1;
          $_POST['userActive']  = 1;
          // $_POST['userName']    = $password;
          // echo "<pre>";print_r($_POST);die;
          $result = $this->CommonModel->insert('users', $_POST);
          $emailSession = array(
            'email'   => $email,
            'userRef' => $randomString
          );
          $this->session->set_userdata('emailsession', $emailSession);
        }
        if (trim($result) != '') {
          $res['success'] = 'true';
          $res['url'] =  base_url().'users';
          $res['delayTime'] = 3000;
          $res['success_message'] = 'Verify email sent to ' . $_POST['userEmail'];
        }
        echo json_encode($res);
        die();
      }
    }
  }


  /*
  * function name updateUser
  *
  * get user details by ref id
  */

  public function updateUser($userRef = NULL)
  {
    $output['permissions']  = getPermissions();
    $output['title']        = 'User Management | Update  User';
    $output['breadcrumbs']  = 'Update User';
    $output['viewuser']     = '';

    $this->make_bread->add('Home', 'home', 0);
    $this->make_bread->add('Users', 'users', 0);
    $this->make_bread->add('Update User', '', 0);
    $breadcrumb = $this->make_bread->output();
    $output['breadcrumb'] = $breadcrumb;


    if (strpos(current_url(),'view-user') !==false) {
      $output['viewuser'] = 'view-user';
      $output['breadcrumbs']  = 'View User';
    }
    $userDetails            = $this->UsersModel->getDataByRef($userRef);
    if(empty($userDetails))
    {
      $this->session->set_flashdata('error_message','Something went wrong. Please try again.');
      redirect('users');
    }
    $output['userDetail']     = $userDetails;
    // echo "<pre>";print_r($output); die;
    $this->load->view('commonFiles/header',$output);
    $this->load->view('users/addNewUser');
    $this->load->view('commonFiles/footer');
  }


    /*
  * function name updateUserAjax
  *
  *  to update user
  */


  public function updateUserAjax()
  {

    if ($_POST) {
      $this->form_validation->set_rules('userEmail', 'Email', 'required|valid_email');
      if ($this->form_validation->run() == TRUE) {
        $email = $this->input->post('userEmail');
        $userType = $this->input->post('userType');
        $_POST['userPermissions'] = '';
        if (!empty($_POST['userPermission'])) {
          foreach ($_POST['userPermission'] as $key => $value) {
                $permissionId = getPermissionsId($key);
                if(!empty($permissionId)){
                  $_POST['userPermissions'] .= $permissionId->permissionId.',';
                }
          }
        }

        $_POST['userPermissions'] = rtrim($_POST['userPermissions'],',');
        unset($_POST['userPermission']);
        if (isset($_POST['userActive']))
        {
          $_POST['userActive'] = 1;
        }
        else
        {
          $_POST['userActive'] = 0;
        }
        unset($_POST['userPermission']);

        $userTypeData = $this->CommonModel->getSomeFields("userType",array('userRef' => $_POST['userRef']), 'users');
        if($userTypeData->userType != $userType){
          $this->CommonModel->update(array('userRef' => $_POST['userRef']), array('security_key' => ""), 'ws_keys');
        }
        $result = $this->CommonModel->update(array('userRef' => $_POST['userRef']), $_POST, 'users');

        if (trim($result) != '') {
          $res['success']   = 'true';
          $res['success_message'] = 'User Updated successfully';
          $res['delayTime'] = "3000";
          $res['url'] = base_url().'users';
        }
        echo json_encode($res); die();
      }
    }
  }

  /*
  * Function name myProfile
  *
  * to load my profile view and user details.
  */
  public function myProfile()
  {
    $output['title'] = 'My Profile';
    $output['breadcrumbs']  = 'My Profile';

    $this->make_bread->add('Home', 'home', 0);
    $this->make_bread->add('My Profile', '', 0);
    $breadcrumb = $this->make_bread->output();
    $output['breadcrumb'] = $breadcrumb;


    $output['userDetail'] = $this->UsersModel->getDataByRef($this->userRef);
    // echo "<pre>";print_r($output);die;
    $this->load->view('commonFiles/header',$output);
    $this->load->view('users/profile');
    $this->load->view('commonFiles/footer');
  }

  /*
  * Function name updateUserDetails
  *
  * to load update  user details.
  */

  public function updateUserDetails() {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $oldPassword  = md5($this->input->post('oldPassword'));
      $userRef      = $this->input->post('userRef');

      if (trim($this->input->post('confirmPassword')) !='') {
        $data['password'] =  md5($this->input->post('confirmPassword'));
      }
      if (trim($userRef) !='') {
        $data['userRef'] = $userRef;
      }
      if (trim($this->input->post('userName')) !='') {
        $data['userName'] = ucwords($this->input->post('userName'));
      }
      if (trim($this->input->post('mobileNo')) !='') {
        $data['mobileNo'] = $this->input->post('mobileNo');
      }
      // updating password and info
      if(trim($this->input->post('confirmPassword')) != '' )
      {
        $oldPasswordVerify = $this->UsersModel->checkOldPassword($userRef, $oldPassword);
        if ($oldPasswordVerify) {
          $response = $this->UsersModel->updateUserDetails($data);
          if ($response) {
            $result["success"] = true;
            $result['delayTime'] = '4000';
            $result["success_message"] = 'Your new password has been changed!';
          } else {
            $result["success"] = true;
            $result["success_message"] = 'Your new password has been changed!';
          }
        } else {
          $result['success'] = false;
          $result['error_message'] = 'Old Password is incorrect. Please fill correct one';
        }
      }
      else
      {
          //updating other information
          $response = $this->UsersModel->updateUserDetails($data);
          if($response)
          {
            $result["success"] = true;
            $result['delayTime'] = '4000';
            $result["success_message"] = 'Your profile updated successfully.!';
          }
          else {
            $result["success"] = true;
            $result['delayTime'] = '4000';
            $result["success_message"] = 'Your profile already updated.!';
          }
      }
        if ($result["success"]) {
            $result['hideEleid'] = 'EditProfile';
            $result['reload'] = true;
        }
        echo json_encode($result);die();
    }

  }

  /*
  * Function name checkOldPassword
  *
  * to check Old Password.
  */


  public function checkOldPassword()
  {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $oldPassword  = md5(trim($this->input->post('oldpassword')));
      $oldPasswordVerify = $this->UsersModel->checkOldPassword($this->userRef, $oldPassword);
      // echo $oldPasswordVerify; die;
      if ($oldPasswordVerify) {
        $result["success"] = true;
        // $result['url'] = site_url('dashboard');
        $result['delayTime'] = '4000';
        $result["success_message"] = 'Your new password has been changed!';
      } else {
        $result['success'] = false;
        $result['error_message'] = 'Old Password is incorrect. Please fill correct one';
      }
      echo json_encode($result);die();
    }
  }

   /*
  * Function name searchSalesman
  *
  * to search searchSalesman.
  */

  public function searchSalesman()
  {
    if ($_GET) {
      $response = $this->UsersModel->searchSalesman($_GET);
      echo json_encode($response);die;
    }
  }
//  udpate user password

  public function updateUserPassword()
  {
    if ($this->input->is_ajax_request()) {
        if ($this->input->post('userRef') !='') {
          $checkUserExits = $this->CommonModel->checkexist('users', array('userRef' => $this->input->post('userRef')));
          if (!$checkUserExits) {
            $response['success'] = false;
            $response['error_message'] = 'Invalid Request..user not exits';
          } else {
            $checkUserExits = $this->CommonModel->checkexist('users', array(
                                                                            'userRef' => $this->input->post('userRef'),
                                                                            'password' => md5(trim($this->input->post('old_password')))
                                                                          ));
            if( !$checkUserExits  )
            {
              $response['success']    = false;
              $response['formErrors'] = true;
              $response['errors']     = array('old_password' => 'Password you entered is incorrect please try again with valid password.');
            }else{
                $updateuser = array(
                  'userRef'   =>  $this->input->post('userRef'),
                  'password'  =>   md5(trim($this->input->post('confirm_password')))
                );

                $update = $this->UsersModel->updateUserDetails($updateuser);
                if ($update) {
                  $response['success'] = true;
                  $response['success_message'] = 'Password has been updated successfully..';
                  $response['modelhide'] = 'change-password-modal';
                } else {
                  $response['success'] = false;
                  $response['error_message'] = 'Unknown Error occur please try again..';
                }
            }
          }
        }else {
          $response['success'] = false;
          $response['error_message'] = 'Invalid Request..';
          $response['modelhide'] = 'change-password-modal';
        }
    }else {
      $response['success'] = false;
      $response['error_message'] = 'Invalid Request..';
    }
    echo json_encode($response);exit;
  }
}
