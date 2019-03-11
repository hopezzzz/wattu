<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class UsersModel extends CI_Model {
  /*     * ********************** Constructor ***************************** */

  public function __construct() {
    parent::__construct();
  }

  public function login($email, $password)
  {
    $this->db->where('userEmail', $email);
    $this->db->where('password', $password);
    $this->db->where('status', 1);
    $this->db->where('isDeleted', 0);
    $this->db->limit(1);
    $q = $this->db->get('users');
    if ($q->num_rows() == 1) {
      $result = $q->row();
      return $result;
    }
  }

  public function getTableCloumns()
  {
    $this->db->select("*");
    $this->db->from('users');
    $this->db->order_by('id','ASC');
    $query        = $this->db->get();
    $fields       = $query->list_fields();
    $userDetail = new stdClass();
    foreach ($fields as $field)
    {
      $userDetail->$field = '';
    }
    return $userDetail;
  }

  public function checkemail($email) {
    $this->db->where('userEmail', $email);
    $this->db->where('isDeleted != ', 1);

    $q = $this->db->get('users');
    if ($q->num_rows() > 0) {
      $result = true;
    } else {
      $result = false;
    }
    return $result;
  }

  public function userDetails($limit, $start, $searchKey = null )
  {
    /* * **************** fetching records ******************** */
    $this->db->select("*");
    $this->db->from('users');
    $this->db->where('users.isDeleted !=1');
    $this->db->where_in('users.userType',array(2,3));
    if($searchKey != NULL && $searchKey != '')
    {
      $where = "( ws_users.userName LIKE '%$searchKey%' or  ws_users.userEmail LIKE '%$searchKey%' or  ws_users.mobileNo LIKE '%$searchKey%' )";
      $this->db->where($where);
    }
    $this->db->order_by('id', 'DESC');
    /** getting count **/
    $tempdb = clone $this->db;
    $total_rows = $tempdb->count_all_results();
    $this->db->limit($start,$limit);
    $query = $this->db->get();
    //echo $this->db->last_query();die;
    $result = array();
    if ($query->num_rows() > 0)
    {
      $result = $query->result();
    }
    return array(
      'total_rows' => $total_rows,
      'result' => $result
    );
  }

  public function getDataByRef($userRef)
  {
    /***************** fetching data by ref ******************** */
    $this->db->select("*");
    $this->db->from('users');
    $this->db->where('users.userRef',$userRef);
    // $this->db->where('users.isDeleted !=1');
    $this->db->order_by('id', 'ASC ');
    $query = $this->db->get();
    $result = array();
    if ($query->num_rows() > 0)
    {
      $result = $query->row();
    }
    return  $result;
  }

  public function checkOldPassword($id, $oldpassword) {
    $this->db->where('userRef', $id);
    $this->db->where('password', $oldpassword);
    $q = $this->db->get('users');
    if ($q->num_rows() >= 1) {
      $result = true;
    } else {
      $result = false;
    }
    return $result;
  }

  public function updateUserDetails($data) {
    $this->db->where('userRef', $data['userRef']);
    $q = $this->db->update('users', $data);
    $total = $this->db->affected_rows();
    if ($total > 0) {
      $result = true;
    } else {
      $result = false;
    }

    return $result;
  }

  public function searchSalesman($postData)
  {
    $result = array();
    $this->db->select("userRef AS id,userName as value");
    $this->db->from('users');
    $this->db->where('users.userType',3);
    $searchKey = $postData['searchKey'];
    if($searchKey != NULL && $searchKey != '')
    {
      $where = "( userName LIKE '%$searchKey%' or  mobileNo LIKE '%$searchKey%' or userEmail LIKE '%$searchKey%' )";
      $this->db->where($where);
      $this->db->order_by('users.userName', 'DESC');
      $query = $this->db->get();
      if ($query->num_rows() > 0)
      {
        $result = $query->result();
      }
    }
    return $result;
  }

  /*
  *
  * To get user targets
  */
  public function getUserTargets($userRef)
  {
    $isFound= false;
    $result = array();
    $this->db->select('*');
    $this->db->from('ws_userTargets');
    $this->db->where('userRef',$userRef);
    $this->db->group_start();
    $this->db->where('addedOn >=' , date('Y-m-01'));
    $this->db->where('modifiedDate <=' ,date('Y-m-t'));
    // $this->db->where('addedOn <=' , date('Y-m-d'));
    // $this->db->where('modifiedDate >=' , date('Y-m-d'));
    $this->db->group_end();
    $query = $this->db->get();
    // echo $this->db->last_query();die;
    if ($query->num_rows() > 0) {
      $isFound = true;
      $result = $query->result();
    }
    return array('success' => $isFound, 'data' => $result);
  }


  public function deleteUserTargets($ref)
  {
    $this->db->where('targetRef',$ref);
    $this->db->delete('ws_userTargets');
    return true;
  }


  public function getTransportCharges($postData)
  {
    $result = array();
    $isSuccess = false;
    $this->db->select('transportRef,itemRefId,region_id,deliveryMethodRef,pricingMode,price,status');
    $this->db->from('ws_transportCharges');
    // $this->db->where('userRef',$userRef);
    $this->db->group_start();
    $this->db->where('status',1);
    $this->db->group_end();
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
      $isSuccess = true;
      $result = $query->result();
    }
    return array('success' => $isSuccess, 'chargesList' => $result);
  }

}
