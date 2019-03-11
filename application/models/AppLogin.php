<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AppLogin extends CI_Model {
    /*     * ********************** Constructor ***************************** */

    public function __construct() {
        parent::__construct();
    }

    function login($detail) {
        $this->db->select('`userRef`, `userName`, `mobileNo`, `userEmail`, `password`, `userType`, `isDeleted`, `userActive`, `userAddress`, `dispatchAccess`, `financialAccess`, `productionAccess`, `userManagement`, `itemManagement`, `status`');
        $this->db->where('userEmail', $detail['email']);
        $this->db->where('password', md5($detail['password']));
        $this->db->where('status', 1);
        $this->db->where('isDeleted', 0);
        $this->db->limit(1);
        $query = $this->db->get('users');
        $result = $result = $query->row();
        $security_key = generateRef();
        // echo $this->db->last_query();
        // print_r($result);die;
        $response = array();
        if (empty($result)) {
            $response['success'] = false;
            $response['error_message'] = 'Your email or password does not match .Please enter details again.';
        } else {
             $device_id     = $detail['device_id'];
             $registeredId  = $detail['registeredId'];
             $device_type   = $detail['device_type'];
             $this->db->where('userRef',$result->userRef);
             $exist         = $this->db->get('keys');
             $result1       = $exist->row();
              //  print_r($result1);die;
            if (empty($result1))
            {
              $this->db->set('security_key',$security_key);
              #setting Authentication Key
              $result->ApiKey   = $security_key;

              $this->db->set('userRef',$result->userRef);
              $this->db->set('device_id',$device_id);
              $this->db->set('registeredId',$registeredId);
              $this->db->set('device_type',$device_type);
              $this->db->set('date_created',date('Y-m-d'));
              $this->db->set('ip_addresses',$_SERVER['REMOTE_ADDR']);
        	    $this->db->insert('keys');
            }else
            {
              $this->db->set('security_key',$security_key);
              $this->db->set('device_id',$device_id);
              $this->db->set('registeredId',$registeredId);
              $this->db->set('device_type',$device_type);
              $this->db->set('ip_addresses',$_SERVER['REMOTE_ADDR']);
              $this->db->where('userRef',$result->userRef);
        	    $this->db->update('keys');
            }
            $this->db->set('status',1);
            $this->db->where('userRef',$result->userRef);
      			$this->db->update('users');

            $response['success'] = true;
            $response['success_message'] = 'Login successfully.';
            $response['login_status'] = 1;
            #setting Authentication Key
            $result->ApiKey   = $security_key;
            $response['data'] = $result;
        }
        return $response;
    }
    function forgotPassword($detail) {

        $this->db->select('*');
        $this->db->where('userEmail', $detail['email']);
        $this->db->where('isDeleted != ', 1);
        $this->db->where('status', '1');
        $query  = $this->db->get('users');
        $result = $query->row();
       // echo $this->db->last_query();die;
        //print_r($result);die;
        if (empty($result)) {
            $response['success'] = false;
            $response['message'] = 'Your email does not match .Please enter details again.';
        } else {
            $userRef  = $result->userRef;
            $email    = $detail['email'];
            $password = randomPassword();
            $from_email = 'noreply@docpoke.in';
            $to_email = $email;
            $emailTemplate = getEmailTemplate(2);
              $variables = array(
                  'to' => $to_email,
                  'email' => $to_email,
                  'loginUrl' => site_url(),
                  'site_title' => 'Watervale',
                  'receiver_name' => 'User',
                  'newPassword' => $password,
              );
              $sendMail = sendEmail($variables, $emailTemplate);
            if ($sendMail) {
              $response['success']  = true;
              $response['message']  = 'Password has been sent successfully to your email.';
              $response['email']    = $email;
              $response['password'] = $password;
              $this->db->set('password',md5($password));
              $this->db->where('userRef',$userRef);
              $data = $this->db->update('users');
            }else{
              $response['success'] = false;
              $response['error_message'] = "Something went wrong please try again";
            }

        }
        return $response;
    }

    public function updateUserDetails($data) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('userRef',$data['userRef']);
        $result = $this->db->get();
        $result = $result->result();
        if(!empty($result)){
          $this->db->where('userRef', $data['userRef']);
          $q      = $this->db->update('users', $data);
          $total  = $this->db->affected_rows();
          if ($total > 0) {
              $response['success']      = true;
              $response['success_message'] = "Your Profile updated successfully.";
          } else {
            $response['success']      = true;
            $response['success_message'] = "Your Profile already updated.";
          }
        }else{
          $response['success']      = false;
          $response['success_message'] = "Your information not matched please try again.";
        }
        return $response;
    }
}
