<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CustomerModel extends CI_Model {
    /*     * ********************** Constructor ***************************** */

    public function __construct() {
        parent::__construct();
    }

    public function customersDetails($limit, $start, $searchKey = null ,$statusBox = null)
    {

        /* * **************** fetching records ******************** */
        $this->db->select("customers.*,contactName as fullName,ws_countries.name as countryName , ws_regions.name as stateName , ws_cities.name as cityName");
        $this->db->from('ws_customers');
        if($searchKey != NULL && $searchKey != '')
		    {
			           $where = "(ws_customers.customerEmail LIKE '%$searchKey%' or ws_customers.contactName LIKE '%$searchKey%' or ws_customers.businessName LIKE '%$searchKey%' or  phoneNo1 LIKE '%$searchKey%' or ws_customers.phoneNo2 LIKE '%$searchKey%' )";
			           $this->db->where($where);
		    }
        if( $statusBox != NULL && $statusBox != '')
		    {
			    $this->db->where('`ws_customers`.`status`',$statusBox);
		    }
        $this->db->join('ws_countries','ws_countries.id = countryId','left');
        $this->db->join('ws_cities','ws_cities.id = cityId','left');
        $this->db->join('ws_regions','ws_regions.id = stateId','left');

        $tempdb = clone $this->db;
        $num_results = $tempdb->count_all_results();

        $this->db->order_by('id', 'DESC');
        $this->db->limit($start,$limit);
        $query = $this->db->get();
          // echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0)
        {
            $result = $query->result();
        }
        return array(
            'total_rows' => $num_results,
            'result' => $result
        );
    }
    public function getTableCloumns()
    {
            $this->db->select("*");
            $this->db->from('customers');
            $this->db->order_by('id','ASC');
            $query        = $this->db->get();
            $fields       = $query->list_fields();
            $customerDetails = new stdClass();
            foreach ($fields as $field)
            {
               $customerDetails->$field = '';
            }
            return $customerDetails;
    }
    public function searchCustomer($postData)
    {

      $result = array();
      $this->db->select("stateId,cityId,businessName,customerRef,contactName as fullName,ws_countries.name as countryName , ws_regions.name as stateName , ws_cities.name as cityName, phoneNo1,phoneNo2,addressLine,deliveryMethodRef");
      $this->db->from('ws_customers');
      $this->db->join('ws_countries','ws_countries.id = countryId','left');
      $this->db->join('ws_cities','ws_cities.id = cityId','left');
      $this->db->join('ws_regions','ws_regions.id = stateId','left');
      $this->db->where('ws_customers.status',1);
      $searchKey = $postData['searchKey'];
      if($searchKey != NULL && $searchKey != '')
      {
         $where = "(ws_customers.customerEmail LIKE '%$searchKey%' or ws_customers.contactName LIKE '%$searchKey%' or ws_customers.businessName LIKE '%$searchKey%' or  phoneNo1 LIKE '%$searchKey%' or ws_customers.phoneNo2 LIKE '%$searchKey%' )";
         $this->db->where($where);

      }
      $this->db->order_by('ws_customers.contactName', 'DESC');
      $query = $this->db->get();
      if ($query->num_rows() > 0)
      {
        $result = $query->result();
      }
         // echo $this->db->last_query();die;
      return $result;
    }
    public function searchCustomerByName($postData)
    {
      $result = array();
      $this->db->select("customerRef AS id,contactName as value");
      $this->db->from('ws_customers');
      $this->db->join('ws_countries','ws_countries.id = countryId','left');
      $this->db->join('ws_cities','ws_cities.id = cityId','left');
      $this->db->join('ws_regions','ws_regions.id = stateId','left');
      $this->db->where('ws_customers.status',1);
      $searchKey = $postData['searchKey'];
      if($searchKey != NULL && $searchKey != '')
      {
         $where = "(ws_customers.customerEmail LIKE '%$searchKey%' or ws_customers.contactName LIKE '%$searchKey%' or ws_customers.businessName LIKE '%$searchKey%' or  phoneNo1 LIKE '%$searchKey%' or ws_customers.phoneNo2 LIKE '%$searchKey%' )";
         $this->db->where($where);
         $this->db->order_by('ws_customers.contactName', 'DESC');
         $query = $this->db->get();
         if ($query->num_rows() > 0)
         {
           $result = $query->result();
         }
      }
      return $result;
    }
    public function getDataByRef($ref)
    {
      $this->db->select("*");
      $this->db->from('ws_customers');
      $this->db->where('ws_customers.customerRef',$ref);
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
    public function exportCustomers($value='')
    {
      $result = array();
      $this->db->select(" `businessName`, `phoneNo2`, `phoneNo1`, `customerEmail`, contactName as fullName, ws_countries.name as countryName , ws_regions.name as stateName , ws_cities.name as cityName, addressLine, methodName");
      $this->db->from('ws_customers');
      $this->db->join('ws_countries'        ,'ws_countries.id = countryId','left');
      $this->db->join('ws_cities'           ,'ws_cities.id    = cityId'   ,'left');
      $this->db->join('ws_regions'          ,'ws_regions.id   = stateId'  ,'left');
      $this->db->join('ws_deliveryMethod'   ,'ws_deliveryMethod.deliveryMethodRef = ws_customers.deliveryMethodRef','left');
      $this->db->where('ws_customers.status',1);
      $this->db->order_by('ws_customers.contactName', 'DESC');
      $query = $this->db->get();
      if ($query->num_rows() > 0)
      {
           $result = $query->result_array();
      }
      // echo $this->db->last_query();die;
      return $result;
    }

    public function followups($limit, $start, $searchKey = null )
    {

      /* * **************** fetching records ******************** */
      $this->db->select("customers.*,contactName as fullName,ws_countries.name as countryName , ws_regions.name as stateName , ws_cities.name as cityName");
      $this->db->from('ws_customers');
      if($searchKey != NULL && $searchKey != '')
      {
        $where = "(ws_customers.customerEmail LIKE '%$searchKey%' or ws_customers.contactName LIKE '%$searchKey%' or ws_customers.businessName LIKE '%$searchKey%' or  phoneNo1 LIKE '%$searchKey%' or ws_customers.phoneNo2 LIKE '%$searchKey%' )";
        $this->db->where($where);
      }
      $this->db->join('ws_countries','ws_countries.id = countryId','left');
      $this->db->join('ws_cities','ws_cities.id = cityId','left');
      $this->db->join('ws_regions','ws_regions.id = stateId','left');
      $this->db->join('ws_orders','ws_orders.customerRef = ws_customers.customerRef','inner');
      $this->db->join('ws_dispatched_orders','ws_dispatched_orders.orderRef = ws_orders.orderRef','inner');
      $this->db->where_in('orderStatus',array('OpenPartiallyFilled','ClosedFilled'));
      $this->db->group_by('ws_customers.customerRef');
      $tempdb = clone $this->db;
      $num_results = $tempdb->count_all_results();
      $this->db->order_by('id', 'DESC');
      $this->db->limit($start,$limit);
      $query = $this->db->get();
      // echo $this->db->last_query();die;
      $result = array();
      if ($query->num_rows() > 0)
      {
        $result = $query->result();
      }
      return array(
        'total_rows' => $num_results,
        'result' => $result
      );
    }
    public function followupOrders($limit, $start, $searchKey, $customerRef = null)
    {
      /* * **************** fetching records ******************** */
      $this->db->select("ws_orders.orderRef,ws_orders.addedOn,ws_orders.orderStatus,ws_orders.orderNo,ws_orders.orderPrice,`customerName`, `city`, `state`, `country`
      ,(select count(id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef limit 0,1) as orderQty,
      (select methodName from ws_deliveryMethod where ws_deliveryMethod.deliveryMethodRef = ws_orders.deliveryMethodRef limit 0,1) as deliveryMethod
      ");
      $this->db->from('ws_orders');
      $this->db->join('ws_orderDeliveryAddress','`ws_orderDeliveryAddress`.`orderRef` = `ws_orders`.`orderRef`','inner');
      // $this->db->where_in('orderStatus',array('OpenPartiallyFilled','ClosedFilled'));
      $this->db->where_in('orderStatus',array('ClosedFilled'));
      if(!is_null($customerRef))
          $this->db->where('customerRef',$customerRef);
      $tempdb = clone $this->db;
      $num_results = $tempdb->count_all_results();
      $this->db->order_by('addedOn', 'ASC');
      $this->db->limit($start,$limit);
      $query = $this->db->get();
      // echo $this->db->last_query();die;
      $result = array();
      if ($query->num_rows() > 0)
      {
        $result = $query->result();
        foreach ($result as $key => $value) {
          $ordersData[$key] = $value;
          $ordersData[$key]->dispatchOrderDetails = $this->lastDispatchedOrders(null, null, $searchKey = null, $value->orderRef);
        }
      }
      // pr($ordersData);die;
      return array(
        'total_rows' => $num_results,
        'result' => $result
      );
    }
    public function completedFollowups($limit, $start, $searchKey, $customerRef = null)
    {
      $result = array();
      $this->db->select('orderStatus,ws_dispatched_orders.*,businessName , customerName ,customerPhone , city as cityName, state,ws_dispatched_orders.addedOn as dispatchedDate')
               ->from('ws_dispatched_orders')
               ->join('ws_orders','ws_orders.orderRef = ws_dispatched_orders.orderRef')
               ->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'inner');
               $this->db->where_in('ws_dispatched_orders.dispatchStatus',array('Closed'));
               $this->db->where_in('ws_dispatched_orders.isModifyState',2);
               $this->db->where('ws_orders.modifiedDate >= DATE(NOW()) - INTERVAL 7 DAY');
               //$this->db->where('orderStatus != "Closed"');
               // ->where('ws_dispatched_orders.orderRef',$orderPipline);
     $tempdb      = clone $this->db;
     $num_results = $tempdb->count_all_results();
     $this->db->group_by('dispatchNo');
     $this->db->order_by('dispatchNo','DESC');
     $this->db->limit($start, $limit);
     $query = $this->db->get();
     $sheetData = $query->result();
     // echo '<pre>'; print_r($sheetData);die;
      foreach ($sheetData as $key => $value) {
          $result[$key]               = $value;
          $result[$key]->dispatchtems = $this->dispatchOrderItems($value->orderRef ,$value->sheetRef,null,$value->dispatchNo);
      }
      // echo "<pre>";
      // print_r($result);die;
      return array(
        'total_rows' => $num_results,
        'result' => $result
      );

      // /* * **************** fetching records ******************** */
      // $this->db->select("ws_orders.orderRef,ws_orders.addedOn,ws_orders.orderStatus,ws_orders.orderNo,ws_orders.orderPrice,`customerName`, `city`, `state`, `country`
      // ,(select count(id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef limit 0,1) as orderQty,
      // (select methodName from ws_deliveryMethod where ws_deliveryMethod.deliveryMethodRef = ws_orders.deliveryMethodRef limit 0,1) as deliveryMethod
      // ");
      // $this->db->from('ws_orders');
      // $this->db->join('ws_orderDeliveryAddress','`ws_orderDeliveryAddress`.`orderRef` = `ws_orders`.`orderRef`','inner');
      // // $this->db->where_in('orderStatus',array('OpenPartiallyFilled','ClosedFilled'));
      // $this->db->group_start();
      // $this->db->where_in('orderStatus',array('Closed'));
      // $this->db->where('ws_orders.modifiedDate >= DATE(NOW()) - INTERVAL 7 DAY');
      // $this->db->group_end();
      // if(!is_null($customerRef))
      //     $this->db->where('customerRef',$customerRef);
      //
      // $tempdb = clone $this->db;
      // $num_results = $tempdb->count_all_results();
      // $this->db->limit($start,$limit);
      // $query = $this->db->get();
      // // echo $this->db->last_query();die;
      // $result = array();
      // if ($query->num_rows() > 0)
      // {
      //   $result = $query->result();
      //   foreach ($result as $key => $value) {
      //     $ordersData[$key] = $value;
      //     $ordersData[$key]->dispatchOrderDetails = $this->lastDispatchedOrders(null, null, $searchKey = null, $value->orderRef);
      //   }
      // }
      // // pr($ordersData);die;
      // return array(
      //   'total_rows' => $num_results,
      //   'result' => $result
      // );
    }

    public function lastDispatchedOrders($limit, $start, $searchKey = null, $orderPipline)
    {
      $result = array();
      $this->db->select('ws_dispatched_orders.*,businessName , customerName ,customerPhone , city as cityName, state,ws_dispatched_orders.addedOn as dispatchedDate')
               ->from('ws_dispatched_orders')
               ->join('ws_orders','ws_orders.orderRef = ws_dispatched_orders.orderRef')
               ->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'inner')
               ->where('isModifyState',2)
               ->where('orderStatus != "cancelled"')
               ->where('ws_dispatched_orders.orderRef',$orderPipline);
     $tempdb     = clone $this->db;
     $total_rows = $tempdb->count_all_results();
     $this->db->group_by('dispatchNo');
     $this->db->order_by('dispatchNo','DESC');
     // $this->db->limit($start, $limit);
     $query = $this->db->get();
     $sheetData = $query->result();
     // echo '<pre>'; print_r($sheetData);die;
      foreach ($sheetData as $key => $value) {
          $result[$key] = $value;
          $result[$key]->dispatchtems = $this->dispatchOrderItems($value->orderRef ,$value->sheetRef,2,$value->dispatchNo);
      }
      return  $result;
    }
    public function customerFollowupOrders($limit, $start, $searchKey = null, $orderPipline)
    {
      $result = array();
      $this->db->select('orderStatus,ws_dispatched_orders.*,businessName , customerName ,customerPhone , city as cityName, state,ws_dispatched_orders.addedOn as dispatchedDate')
               ->from('ws_dispatched_orders')
               ->join('ws_orders','ws_orders.orderRef = ws_dispatched_orders.orderRef')
               ->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'inner')
               ->where('isModifyState',2)
               ->where('ws_dispatched_orders.dispatchStatus = "pending"');
      $this->db->group_by('dispatchNo');
     $this->db->order_by('ws_dispatched_orders`.`id` ','DESC');
     $tempdb      = clone $this->db;
     $num_results = $tempdb->count_all_results();
     $this->db->limit($start, $limit);
     $query = $this->db->get();
     // echo $num_results. $this->db->last_query();die;
     $sheetData = $query->result();
     // echo '<pre>'; print_r($sheetData);die;
      foreach ($sheetData as $key => $value) {
          $result[$key]               = $value;
          $result[$key]->dispatchtems = $this->dispatchOrderItems($value->orderRef ,$value->sheetRef,2,$value->dispatchNo);

      }
      // echo "<pre>";
      // print_r($result);
      // die;
      return array(
        'total_rows' => $num_results,
        'result' => $result
      );
    }

    public function dispatchOrderItems($orderRef = null ,$sheetRef = null,$isModifyState = null,$dispatchNum = null)
    {

        $result = array();
        $this->db->select('ws_dispatched_Items.*,
        (select color from ws_products where ws_dispatched_Items.itemRefId = productRef limit 0,1 ) as color,
        (select design from ws_products where ws_dispatched_Items.itemRefId = productRef limit 0,1 ) as design,
        (select blockType from ws_products where ws_dispatched_Items.itemRefId = productRef limit 0,1 ) as blockType'
        );
        $this->db->from('ws_dispatched_orders');
        if ($orderRef != null && $orderRef !='')
        {
          if (strpos($orderRef, ',') !== false) {
            $orderRef = explode(',', $orderRef);
            $this->db->where_in('ws_dispatched_Items.orderRefId', $orderRef);
            $row = false;
          }else
          {
            $this->db->where('ws_dispatched_Items.orderRefId', $orderRef);
            $row = true;
          }
        }
        if ($sheetRef != null && $sheetRef !='') {
          $this->db->where('ws_dispatched_orders.sheetRef', $sheetRef);
        }
        if ($isModifyState != null && $isModifyState !='') {
          $this->db->where('ws_dispatched_orders.isModifyState', $isModifyState);
        }
        if ($dispatchNum != null && $dispatchNum !='') {
          $this->db->where('ws_dispatched_orders.dispatchNo', $dispatchNum);
        }
        $this->db->join('ws_dispatched_Items', 'ws_dispatched_Items.dispatchRef = ws_dispatched_orders.dispatchNo', 'inner');
        // $this->db->group_by('ws_dispatched_Items.itemRefId');
        $query = $this->db->get();
        // echo $this->db->last_query();
        // echo "<br>";
        if ($query->num_rows() > 0) {
            $result    = $query->result();
        }
        return $result;
    }
}
