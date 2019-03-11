<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class OrderModel extends CI_Model
{
    /*     * ********************** Constructor ***************************** */
    public function __construct()
    {
        parent::__construct();
    }
    public function getAllOrders($limit, $start, $searchKey = null)
    {
        /* * **************** fetching records ******************** */
        $this->db->select("ws_orders.*,(select count(ws_orderItems.id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef ) as orderQty, customerName as fullname,city as cityName,businessName");
        // $this->db->select("ws_orders.*,(select count(ws_orderItems.id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef ) as orderQty, contactName as fullname,name as cityName");
        $this->db->from('ws_orders');
        if ($searchKey != NULL && $searchKey != '') {
            $where = "( ws_orders.orderNo LIKE '%$searchKey%' or  ws_orders.area LIKE '%$searchKey%' )";
            $this->db->where($where);
        }
        $this->db->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'left');
        // $this->db->join('ws_orderItems','ws_orderItems.orderRef = ws_orders.orderRef','left');
        // $this->db->join('ws_customers', 'ws_customers.customerRef = ws_orders.customerRef', 'left');
        // $this->db->join('ws_cities', 'ws_cities.id = ws_customers.cityId', 'left');
        $this->db->order_by('id', 'DESC');
        /** getting count **/
        $tempdb     = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start, $limit);
        $query  = $this->db->get();
        //  echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0) {
            $result = $query->result();
        }
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }
    public function getFinanceOrders($limit, $start, $searchKey = null, $orderPipline)
    {
        /* * **************** fetching records ******************** */
        $this->db->select("ws_orders.*,(select count(ws_orderItems.id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef ) as orderQty, customerName as fullname,city as cityName");
        $this->db->from('ws_orders');
        $this->db->where('orderPipline', $orderPipline);
        $this->db->where('orderStatus !=', 'cancelled');
        $this->db->group_start();
        $this->db->where('managerApprove', 0);
        $this->db->or_where('managerApprove', 1);
        $this->db->group_end();
        // $this->db->join('ws_orderItems','ws_orderItems.orderRef = ws_orders.orderRef','left');
        $this->db->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'left');
        // $this->db->join('ws_customers', 'ws_customers.customerRef = ws_orders.customerRef', 'left');
        // $this->db->join('ws_cities', 'ws_cities.id = ws_customers.cityId', 'left');
        $this->db->order_by('id', 'DESC');
        $tempdb     = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        // $this->db->limit($start, $limit);
        $query  = $this->db->get();
        //  echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0) {
            $result = $query->result();
        }
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }
    public function getDispatchPendingOrders($limit, $start, $searchKey = null, $orderPipline)
    {
        /* * **************** fetching records ******************** */
        $this->db->select("ws_orders.*,(select count(ws_orderItems.id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef ) as orderQty, customerName as fullname,city as cityName , businessName");
        $this->db->from('ws_orders');
        $this->db->group_start();
        $this->db->where('orderPipline', $orderPipline);
        $this->db->where('toLoad', 0);
        $this->db->where('orderStatus != "ClosedFilled"');
        $this->db->where('orderStatus != "Closed"');
        $this->db->group_end();
        $this->db->where('orderStatus != "cancelled"');
        $this->db->where('orderStatus != "reAssign"');
        // $this->db->join('ws_orderItems','ws_orderItems.orderRef = ws_orders.orderRef','left');
        $this->db->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'left');
        $this->db->order_by('id', 'DESC');
        /** getting count **/
        $tempdb     = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        // $this->db->limit($start, $limit);
        $query  = $this->db->get();
         // echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $key => $value)
            {
                $data[$key] = $value;
                $params['orderRef'] = $value->orderRef;
                $data[$key]->orderItems   = $this->getOrdersItems($params);
                $data[$key]->orderComment = $this->orderComments($value->orderRef);
                unset($data[$key]->orderItems['success']);
            }
        }
        // pr($data);die;
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }
    public function productionOrders( $start, $limit, $searchKey = null, $orderPipline)
    {
        /* * **************** fetching records ******************** */
        $this->db->select("ws_orders.id,ws_orders.orderNo,ws_orders.orderRef,orderInProduction,orderStatus,
    (select count(ws_orderItems.id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef ) as orderQty,
    customerName as fullname, city as cityName,
    businessName
    ");
        $this->db->from('ws_orders');
        $this->db->where('orderPipline', $orderPipline);
        $this->db->where('orderInProduction', 1);
        $this->db->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'left');
        /** getting count **/
        $tempdb     = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->order_by('priorityNo', 'ASC');
        $this->db->limit($limit);
        $query  = $this->db->get();
        // echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0) {
            $result = new stdClass();
            $result = $query->result();
            foreach ($result as $key => $value) {
                $result[$key]             = $value;
                $result[$key]->orderItems = $this->orderItems($value->orderRef,1);
            }
        }
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }
    public function completedOrders($limit, $start, $searchKey = null)
    {
        /* * **************** fetching records ******************** */
        $this->db->select("ws_orders.modifiedDate,ws_orders.id,ws_orders.orderNo,ws_orders.orderRef,orderInProduction,orderStatus,
    (select count(ws_orderItems.id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef ) as orderQty,
    contactName as fullname, name as cityName,
    businessName
    ");
        $this->db->from('ws_orders');
        $this->db->where('orderInProduction', 2);
        $this->db->where('ws_orders.modifiedDate >= DATE(NOW()) - INTERVAL 7 DAY');
        // $this->db->join('ws_orderItems','ws_orderItems.orderRef = ws_orders.orderRef','left');
        $this->db->join('ws_customers', 'ws_customers.customerRef = ws_orders.customerRef', 'left');
        $this->db->join('ws_cities', 'ws_cities.id = ws_customers.cityId', 'left');
        $this->db->order_by('priorityNo', 'ASC');
        /** getting count **/
        $tempdb     = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($limit);
        $query  = $this->db->get();
        // echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0) {
            $result = new stdClass();
            $result = $query->result();
            foreach ($result as $key => $value) {
                $result[$key]             = $value;
                $result[$key]->orderItems = $this->productionOrderItems($value->orderRef);
            }
        }
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }
    public function getPipllineOrders($limit, $start, $searchKey = null, $orderPipline)
    {
        /* * **************** fetching records ******************** */
        $this->db->select("ws_orders.*,(select count(ws_orderItems.id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef ) as orderQty, contactName as fullname,name as cityName");
        $this->db->from('ws_orders');
        $this->db->where('managerApprove', 1);
        // $this->db->join('ws_orderItems','ws_orderItems.orderRef = ws_orders.orderRef','left');
        $this->db->join('ws_customers', 'ws_customers.customerRef = ws_orders.customerRef', 'left');
        $this->db->join('ws_cities', 'ws_cities.id = ws_customers.cityId', 'left');
        $this->db->order_by('id', 'DESC');
        /** getting count **/
        $tempdb     = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start, $limit);
        $query  = $this->db->get();
        //  echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0) {
            $result = $query->result();
        }
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }
    /*public function submitOrder($params)
    {

        // echo "<pre>";
        // pr($params);
        // die();
        $lastInserted = '';

        if (isset($params['orderRef']) && trim($params['orderRef']) != '') {
            $orderRef  = trim($params['orderRef']);
            $orderData = $this->CommonModel->getSomeFields('orderNo', array('orderRef =' => $orderRef), 'ws_orders');
        if (!empty($orderData)){$orderNO = $orderData->orderNo;$this->db->set('orderNo', $orderNO);} else { $orderNO = generateOrderNumber();$this->db->set('orderNo', $orderNO);}
        } else { $orderRef = generateRef();$orderNO = generateOrderNumber();$this->db->set('orderNo', $orderNO); }
        $this->db->set('salesRef', $params['userRef']);
        if (trim($params['customerRef']) != '') {
            $this->db->set('customerRef', $params['customerRef']);
        }
        if (trim($params['approvalType']) != '') {
            $this->db->set('approvalType', $params['approvalType']);
        }
        $this->db->set('orderRemarks', $params['orderRemarks']);
        $this->db->set('deliveryMethodRef', $params['deliveryMethodRef']);
        $this->db->set('paymentMethodRef', $params['paymentMethodRef']);
        $this->db->set('dueDays', $params['dueDays']);
        $this->db->set('dueAmount', $params['dueAmount']);
        $this->db->set('managerApprove', $params['managerApprove']);
        $this->db->set('preAuthorization', $params['preAuthorization']);
        $this->db->set('orderPipline', $params['orderPipline']);
        $this->db->set('orderStatus', $params['orderStatus']);
        $this->db->set('orderInProduction', $params['orderInProduction']);
        $this->db->set('status', 1);
        $this->db->set('addedOn', date("Y-m-d H:i:s"));
        $this->db->set('modifiedDate', date("Y-m-d H:i:s"));
        if (isset($params['orderRef']) && trim($params['orderRef']) != '') {
            $this->db->where('orderRef', $orderRef);
            $this->db->update('orders');
        } else {
            $this->db->set('orderRef', $orderRef);
            $this->db->insert('orders');
            $lastInserted = $this->db->insert_id();
        }
        $response['success'] = true;
        $orderPrice          = 0;
        $transportCharge     = 0;
        $orderDiscount       = 0;
        $getCustomerAddress  = getCustomerDetails($params['customerRef']);
        // echo "<pre>";print_r($getCustomerAddress);die;
        if (!empty($getCustomerAddress)) {
            $this->db->set('orderRef', $orderRef);
            $this->db->set('addressLine', $getCustomerAddress->addressLine);
            $this->db->set('customerName', $getCustomerAddress->fullName);
            $this->db->set('businessName', $getCustomerAddress->businessName);
            $this->db->set('customerPhone', $getCustomerAddress->phoneNo1);
            $this->db->set('city', $getCustomerAddress->cityName);
            $this->db->set('state', $getCustomerAddress->stateName);
            $this->db->set('country', $getCustomerAddress->countryName);
            $this->db->set('addedOn', date("Y-m-d"));
            $this->db->set('modifiedOn', date("Y-m-d"));
            $this->db->insert('ws_orderDeliveryAddress');
        }
        foreach ($params['orderItems'] as $key => $orderItems)
        {
            // echo "<prE>";print_r($orderItems);die;
            $itemVariant  = array();
            $variants = $orderItems['variants'];
            $variantsPrice = 0;
            $variantsTras  = 0;
            $price = 0;
            if (!empty($variants))
            {

              if (trim($orderItems['transportCostChanged']) != '') {
                  $variantsTras     = $orderItems['transportCostChanged'];
                  $transportCharge += $orderItems['transportCostChanged'] * $orderItems['qty'];
              } else {
                  $variantsTras     = $orderItems['transportCost'];
                  $transportCharge += $orderItems['transportCost'] * $orderItems['qty'];
              }

                foreach ($variants as $variantsKey => $variantsValue)
                {
                        // if (trim($variantsValue['priceChanged']) != '') {
                            // $variantsPrice = $variantsValue['priceChanged'] * $variantsValue['qty'];
                        // } else {
                            $variantsPrice = $variantsValue['price'] * $variantsValue['qty'];
                        // }
                        $orderItems['discount'] = (trim($orderItems['discount']) == '' ) ? 0 : $orderItems['discount'];
                        if ($orderItems['discountType'] == 1 &&  $orderItems['discount'] != 0 )
                        {
                              $disValue           =   $variantsPrice * $orderItems['discount'] / 100;
                              $orderDiscount     +=   $disValue;
                              // $variantsPrice      =   $variantsPrice - $disValue;
                        }else
                        {
                          if($orderItems['discount'] > $variantsPrice){
                          $output['success']       = false;
                      		$output['Amount']        = $orderItems['discount'];
                      		$output['error_message'] = "Discount price cannot be greater then price";
                      		$this->response($output,REST_Controller::HTTP_CONFLICT);
                          }else{
                              $orderDiscount     += $orderItems['discount'];
                            	// $variantsPrice     =  $variantsPrice - $orderItems['discount'];
                          }
                        }

                        // $variantsPrice   = $variantsPrice;
                        $price          += $variantsValue['price'];
                        $orderPrice     += $variantsValue['price'] * $variantsValue['qty'];

                        $itemVariant[] = array(
                                  'productId'       => $orderItems['itemRef'],
                                  'orderRef'        => $orderRef,
                                  'item_variant_id' => (isset($variantsValue["item_variant_id"])) ? $variantsValue["item_variant_id"] : '',
                                  'height'          => (isset($variantsValue["height"])) ? $variantsValue["height"] : '',
                                  'width'           => (isset($variantsValue["width"])) ? $variantsValue["width"] : '',
                                  'length'          => (isset($variantsValue["length"])) ? $variantsValue["length"] : '',
                                  'color'           => (isset($variantsValue["color"])) ? $variantsValue["color"] : '',
                                  'design'          => (isset($variantsValue["design"])) ? $variantsValue["design"] : '',
                                  'qty'             => (isset($variantsValue["qty"])) ? $variantsValue["qty"] : '',
                                  'price'           => (isset($variantsValue['price'])) ? $variantsValue['price'] : 0,
                                  'minPrice'        => (isset($variantsValue["minPrice"])) ? $variantsValue["minPrice"] : '',
                                  'isCustomize'     => (isset($variantsValue["isCustomize"])) ? $variantsValue["isCustomize"] : 0,
                                  'blockPercentage' => (isset($variantsValue["blockPercentage"])) ? $variantsValue["blockPercentage"] : 0,
                                  'transportCharge' => (isset($variantsTras)) ? $variantsTras : 0,
                                  // 'discountType'    => (isset($variantsValue["discountType"])) ? $variantsValue["discountType"] : 0,
                                  // 'discount'        => (isset($variantsValue["discount"])) ? $variantsValue["discount"] : 0,
                                  'blockType'       => (isset($variantsValue["blockType"])) ? $variantsValue["blockType"] : 'NA',
                              );
                }
            }
            $this->db->set('orderRef', $orderRef);
            $this->db->set('orderItemRef',    generateRef());
            $this->db->set('price',$price );
            $this->db->set('transportCharge', $variantsTras);
            $this->db->set('itemRefId',      $orderItems['itemRef']);
            $this->db->set('itemName',       $orderItems['itemName']);
            $this->db->set('saleUOM',        $orderItems['saleUOM']);
            $this->db->set('saleConvQty',    $orderItems['saleConvQty']);
            $this->db->set('saleConvLength', $orderItems['saleConvLength']);
            $this->db->set('baseUOM',        $orderItems['baseUOM']);
            $this->db->set('baseConvQty',    $orderItems['baseConvQty']);
            $this->db->set('baseConvLength', $orderItems['baseConvLength']);
            $this->db->set('productionOnDemand', $orderItems['productionOnDemand']);
            $this->db->set('discountType', $orderItems['discountType']);
            $this->db->set('discount', $orderItems['discount']);
            // if (trim($orderItems['length']) != '' || trim($orderItems['height']) != '' || trim($orderItems['width']) != '') {
            //     $this->db->set('customizeItem', 1);
            // }
            $this->db->set('qty', $orderItems['qty'] );
            $this->db->set('addedOn', date("Y-m-d H:i:s") );
            $this->db->set('modifiedDate', date("Y-m-d H:i:s") );
            $this->db->set('status', 1);
            $this->db->insert('orderItems');
            if (!empty($itemVariant)) {
              $this->db->insert_batch('ws_orderItemVariants',$itemVariant);
            }
            // echo $this->db->last_query();die;
            if ($this->db->affected_rows() > 0) {
                $response['success']         = true;
                $response['success_message'] = 'Order Submitted successfully.';
                if ($lastInserted != '') {
                    $this->db->set('priorityNo', $lastInserted);
                }
                $this->db->set('orderPrice', $orderPrice);
                $this->db->set('orderDiscount', $orderDiscount);
                $this->db->set('transportCharge',$transportCharge);
                $this->db->where('orderRef', $orderRef);
                $this->db->update('orders');
                if ($this->db->affected_rows() > 0) {
                    $response['success']          = true;
                    $response['success_message']  = 'Order '.$orderNO.' Submitted successfully.';
                    $response['order_no']         = $orderNO;
                } else {
                    $response['success']          = true;
                    $response['success_message']  = 'Order '.$orderNO.' Updated successfully.';
                    $response['order_no']         = $orderNO;
                }
            } else {
                $response['success']         = false;
                $response['success_message'] = 'Something went wrong please try again.';
            }
        }
        return $response;
    }
    */

    public function submitOrder($params)
    {

        // echo "<pre>";
        // pr($params);
        // die();
        $lastInserted = '';

        if (isset($params['orderRef']) && trim($params['orderRef']) != '') {
            $orderRef  = trim($params['orderRef']);
            $orderData = $this->CommonModel->getSomeFields('orderNo', array('orderRef =' => $orderRef), 'ws_orders');
        if (!empty($orderData)){$orderNO = $orderData->orderNo;$this->db->set('orderNo', $orderNO);} else { $orderNO = generateOrderNumber();$this->db->set('orderNo', $orderNO);}
        } else { $orderRef = generateRef();$orderNO = generateOrderNumber();$this->db->set('orderNo', $orderNO); }
        $this->db->set('salesRef', $params['userRef']);
        if (trim($params['customerRef']) != '') {
            $this->db->set('customerRef', $params['customerRef']);
        }
        if (trim($params['approvalType']) != '') {
            $this->db->set('approvalType', $params['approvalType']);
        }
        $this->db->set('orderRemarks', $params['orderRemarks']);
        $this->db->set('deliveryMethodRef', $params['deliveryMethodRef']);
        $this->db->set('paymentMethodRef', $params['paymentMethodRef']);
        $this->db->set('dueDays', $params['dueDays']);
        $this->db->set('dueAmount', $params['dueAmount']);
        $this->db->set('managerApprove', $params['managerApprove']);
        $this->db->set('preAuthorization', $params['preAuthorization']);
        $this->db->set('orderPipline', $params['orderPipline']);
        $this->db->set('orderStatus', $params['orderStatus']);
        $this->db->set('orderInProduction', $params['orderInProduction']);
        $this->db->set('status', 1);
        $this->db->set('addedOn', date("Y-m-d H:i:s"));
        $this->db->set('modifiedDate', date("Y-m-d H:i:s"));
        if (isset($params['orderRef']) && trim($params['orderRef']) != '') {
            $this->db->where('orderRef', $orderRef);
            $this->db->update('orders');
        } else {
            $this->db->set('orderRef', $orderRef);
            $this->db->insert('orders');
            $lastInserted = $this->db->insert_id();
        }
        $response['success'] = true;
        $orderPrice          = 0;
        $transportCharge     = 0;
        $orderDiscount       = 0;
        $getCustomerAddress  = getCustomerDetails($params['customerRef']);
        if (!empty($getCustomerAddress)) {
            $this->db->set('orderRef', $orderRef);
            $this->db->set('addressLine', $getCustomerAddress->addressLine);
            $this->db->set('customerName', $getCustomerAddress->fullName);
            $this->db->set('businessName', $getCustomerAddress->businessName);
            $this->db->set('customerPhone', $getCustomerAddress->phoneNo1);
            $this->db->set('city', $getCustomerAddress->cityName);
            $this->db->set('state', $getCustomerAddress->stateName);
            $this->db->set('country', $getCustomerAddress->countryName);
            $this->db->set('addedOn', date("Y-m-d"));
            $this->db->set('modifiedOn', date("Y-m-d"));
            $this->db->insert('ws_orderDeliveryAddress');
        }
        foreach ($params['orderItems'] as $key => $orderItems)
        {
            $itemVariant    = array();
            $variants       = $orderItems['variants'];
            $variantsPrice  = 0;
            $variantsTras   = 0;
            $price = 0;
            $itemData = $this->CommonModel->getData('ws_products' ,array('productRef' => $orderItems['itemRef'] ) , 'uomType');
            $itemData = $this->CommonModel->getData('ws_transportCharges' ,array('itemRefId' => $orderItems['itemRef'] ) , 'pricingMode');
            if (!empty($variants))
            {
                // if (trim($orderItems['transportCostChanged']) != '') {
                //     $variantsTras     = $orderItems['transportCostChanged'];
                //     $transportCharge += $orderItems['transportCostChanged'] * $orderItems['qty'];
                // } else {
                //     $variantsTras     = $orderItems['transportCost'];
                //     $transportCharge += $orderItems['transportCost'] * $orderItems['qty'];
                // }
                foreach ($variants as $variantsKey => $variantsValue)
                {
                        $orderItems['saleConvLength'] = ($orderItems['saleConvLength'] != 0 ) ? $orderItems['saleConvLength'] : 1;
                        $variantsPrice = ($variantsValue['price'] * $variantsValue['qty'] ) *  $orderItems['saleConvLength'];
                        $orderItems['discount'] = (trim($orderItems['discount']) == '' ) ? 0 : $orderItems['discount'];
                        // calculating transport price
                        if (isset($orderItems['pricingMode']))
                        {
                            if (trim($orderItems['transportCostChanged']) == '')
                            {
                                if (trim(strtolower($orderItems['pricingMode'])) == strtolower('Fixed'))
                                {
                                  $variantsTras     = $orderItems['transportCost'];
                                  $transportCharge += $orderItems['transportCost'] * $variantsValue['qty'];
                                }
                                if (trim(strtolower($orderItems['pricingMode'])) == strtolower('Percentage'))
                                {
                                  $variantsTras     = $variantsPrice / $orderItems['transportCost'];
                                  $transportCharge += $variantsTras * $variantsValue['qty'];
                                }
                            }else
                            {
                              $variantsTras   = 0; $transportCharge  = 0;
                            }
                        }
                        else
                        {
                            $variantsTras     = 0; $transportCharge  = 0;
                        }
                        if ($orderItems['discountType'] == 1 &&  $orderItems['discount'] != 0 )
                        {
                              $disValue           =   $variantsPrice * $orderItems['discount'] / 100;
                              $orderDiscount     +=   $disValue;
                              // $variantsPrice      =   $variantsPrice - $disValue;
                        }else
                        {
                          if($orderItems['discount'] > $variantsPrice){
                          $output['success']       = false;
                      		$output['Amount']        = $orderItems['discount'];
                      		$output['error_message'] = "Discount price cannot be greater then price";
                      		$this->response($output,REST_Controller::HTTP_CONFLICT);
                          }else{
                              $orderDiscount     += $orderItems['discount'];
                            	// $variantsPrice     =  $variantsPrice - $orderItems['discount'];
                          }
                        }

                        // $variantsPrice   = $variantsPrice;
                        $price          += $variantsValue['price'];
                        $orderPrice     += $variantsPrice;

                        $itemVariant[] = array(
                                  'productId'       => $orderItems['itemRef'],
                                  'orderRef'        => $orderRef,
                                  'item_variant_id' => (isset($variantsValue["item_variant_id"])) ? $variantsValue["item_variant_id"] : '',
                                  'height'          => (isset($variantsValue["height"])) ? $variantsValue["height"] : '',
                                  'width'           => (isset($variantsValue["width"])) ? $variantsValue["width"] : '',
                                  'length'          => (isset($variantsValue["length"])) ? $variantsValue["length"] : '',
                                  'color'           => (isset($variantsValue["color"])) ? $variantsValue["color"] : '',
                                  'design'          => (isset($variantsValue["design"])) ? $variantsValue["design"] : '',
                                  'qty'             => (isset($variantsValue["qty"])) ? $variantsValue["qty"] : '',
                                  'price'           => (isset($variantsValue['price'])) ? $variantsValue['price'] : 0,
                                  'minPrice'        => (isset($variantsValue["minPrice"])) ? $variantsValue["minPrice"] : '',
                                  'isCustomize'     => (isset($variantsValue["isCustomize"])) ? $variantsValue["isCustomize"] : 0,
                                  'blockPercentage' => (isset($variantsValue["blockPercentage"])) ? $variantsValue["blockPercentage"] : 0,
                                  'transportCharge' => (isset($variantsTras)) ? $variantsTras : 0,
                                  'blockType'       => (isset($variantsValue["blockType"])) ? $variantsValue["blockType"] : 'NA',
                              );
                }
            }
            $this->db->set('orderRef', $orderRef);
            $this->db->set('orderItemRef',    generateRef());
            $this->db->set('price',$price );
            $this->db->set('transportCharge', $variantsTras);
            $this->db->set('itemRefId',      $orderItems['itemRef']);
            $this->db->set('itemName',       $orderItems['itemName']);
            $this->db->set('saleUOM',        $orderItems['saleUOM']);
            $this->db->set('saleConvQty',    $orderItems['saleConvQty']);
            $this->db->set('saleConvLength', $orderItems['saleConvLength']);
            $this->db->set('baseUOM',        $orderItems['baseUOM']);
            $this->db->set('baseConvQty',    $orderItems['baseConvQty']);
            $this->db->set('baseConvLength', $orderItems['baseConvLength']);
            $this->db->set('productionOnDemand', $orderItems['productionOnDemand']);
            $this->db->set('uomType', $itemData[0]->uomType);
            $this->db->set('discountType', $orderItems['discountType']);
            $this->db->set('discount', $orderItems['discount']);
            $this->db->set('qty', $orderItems['qty'] );
            $this->db->set('addedOn', date("Y-m-d H:i:s") );
            $this->db->set('modifiedDate', date("Y-m-d H:i:s") );
            $this->db->set('status', 1);
            $this->db->insert('orderItems');
            if (!empty($itemVariant)) {
              $this->db->insert_batch('ws_orderItemVariants',$itemVariant);
            }
            // echo $this->db->last_query();die;
            if ($this->db->affected_rows() > 0) {
                $response['success']         = true;
                $response['success_message'] = 'Order Submitted successfully.';
                if ($lastInserted != '') {
                    $this->db->set('priorityNo', $lastInserted);
                }
                $this->db->set('orderPrice', $orderPrice);
                $this->db->set('orderDiscount', $orderDiscount);
                $this->db->set('transportCharge',$transportCharge);
                $this->db->where('orderRef', $orderRef);
                $this->db->update('orders');
                if ($this->db->affected_rows() > 0) {
                    $response['success']          = true;
                    $response['success_message']  = 'Order '.$orderNO.' Submitted successfully.';
                    $response['order_no']         = $orderNO;
                } else {
                    $response['success']          = true;
                    $response['success_message']  = 'Order '.$orderNO.' Updated successfully.';
                    $response['order_no']         = $orderNO;
                }
            } else {
                $response['success']         = false;
                $response['success_message'] = 'Something went wrong please try again.';
            }



        }
        return $response;
    }

    public function orderDetails($orderRef)
    {
        $result = array();
        $this->db->select('orders.*,
        (select userName from ws_users where ws_users.userRef = ws_orders.salesRef limit 0,1) as salesman,
        (select methodName from ws_deliveryMethod where ws_deliveryMethod.deliveryMethodRef = ws_orders.deliveryMethodRef limit 0,1) as deliveryMethod,
        (select payementMethod from ws_pricingMethod where ws_pricingMethod.pricingRef = ws_orders.paymentMethodRef limit 0,1) as paymentMethod,
        (select stateId from ws_customers where ws_customers.customerRef = ws_orders.customerRef limit 0,1) as stateId,
        (select dispatchNo from ws_dispatched_orders where ws_dispatched_orders.orderRef = ws_orders.orderRef and isModifyState = 1 limit 0,1) as dispatchNo
        ');
        $this->db->from('orders');
        if (strpos($orderRef, ',') !== false) {
          $orderRef = explode(',', $orderRef);
          $this->db->where_in('orderRef', $orderRef);
          $row = false;
        }else
        {
          $this->db->where('orderRef', $orderRef);
          $row = true;
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            if ($row) {

              $result = $query->row();
            } else {
              $result = $query->result();
            }
        }
        return $result;
    }
    public function orderItems($orderRef,$onDemand = null)
    {
        $result = array();
        $this->db->select('orderItems.*,
        blockType,
        orderItems.productionOnDemand,
        blockPercentage,
        color,
        (select unitName from ws_measurement where ws_orderItems.baseUOM = unitRef limit 0,1) as baseUOM');
        $this->db->from('orderItems');
        if($onDemand != null && $onDemand !=''){
          $this->db->where('orderItems.productionOnDemand',$onDemand);
        }
        $this->db->join('products', 'productRef = itemRefId', 'inner');
        if (strpos($orderRef, ',') !== false) {
          $orderRef = explode(',', $orderRef);
          $this->db->where_in('orderRef', $orderRef);
          $row = false;
        }else
        {
          $this->db->where('orderRef', $orderRef);
          $row = true;
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $key => $value) {
              $result[$key] = $value;
              $result[$key]->variants = getVariantsByOrderRef($value->orderRef,$value->itemRefId);
              // pr($result);die;
            }

        }
        return $result;
    }
    public function productionOrderItems($orderRef)
    {
        $result = array();
        $this->db->select('orderItems.*,
        blockType,
        blockPercentage,
        (select unitName from ws_measurement where ws_orderItems.baseUOM = unitRef limit 0,1) as baseUOM');
        $this->db->from('orderItems');
        $this->db->join('products', 'productRef = itemRefId', 'inner');
        $this->db->where('products.productionOnDemand', 1);
        if (strpos($orderRef, ',') !== false) {
          $orderRef = explode(',', $orderRef);
          $this->db->where_in('orderRef', $orderRef);
          $row = false;
        }else
        {
          $this->db->where('orderRef', $orderRef);
          $row = true;
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            foreach ($result as $key => $value) {
              $result[$key] = $value;
              $result[$key]->variants = getVariantsByOrderRef($value->orderRef,$value->itemRefId);
              // pr($result);die;
            }
        }
        return $result;
    }
    public function orderAddress($orderRef)
    {
        $result = array();
        $this->db->select('*');
        $this->db->from('orderDeliveryAddress');
        if (strpos($orderRef, ',') !== false) {
          $orderRef = explode(',', $orderRef);
          $this->db->where_in('orderRef', $orderRef);
          $row = false;
        }else
        {
          $this->db->where('orderRef', $orderRef);
          $row = true;
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
        }
        return $result;
    }
    public function customerLastPrice($params)
    {
        $result = array();
        $this->db->select('price');
        $this->db->from('orders');
        $this->db->where('salesRef', $params['userRef']);
        $this->db->where('customerRef', $params['customerRef']);
        $this->db->where('itemRefId', $params['itemRef']);
        $this->db->join('orderItems', 'orderItems.orderRef = orders.orderRef', 'inner');
        $this->db->order_by('orderItems.id', 'desc');
        $this->db->limit(1, 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result['success'] = true;
            $result['data']    = $query->row();
        } else {
            $result['success']       = false;
            $result['error_message'] = 'Opps. no record found please try with new entries.';
        }
        return $result;
    }
    public function orderComments($orderRef)
    {
        $result = array();
        $this->db->select('orderComments.*,userName,userType');
        $this->db->from('orderComments');
        $this->db->where('orderRef', $orderRef);
        $this->db->join('users', 'users.userRef = orderComments.addedBy', 'inner');
        $this->db->order_by('YEAR(addedOn) DESC, MONTH(addedOn) DESC, DAY(addedOn) DESC, TIME(addedOn) DESC');
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $result = $query->result();
        }
        return $result;
    }
    public function getOrders($params)
    {
        $result = array();
        $this->db->select('orders.*,contactName as customerName,');
        $this->db->from('orders');
        // $this->db->where('salesRef',$params['userRef']);
        if (isset($params['orderStatus']) && trim($params['orderStatus']) != '') {
            rtrim($params['orderStatus'], ",");
            $pos = strpos($params['orderStatus'], ",");
            if ($pos !== false) {
                $orderStatus = explode(',', $params['orderStatus']);
            } else {
                $orderStatus = $params['orderStatus'];
            }
            $this->db->where_in('orderStatus', $orderStatus);
        } else {
            $this->db->where('orderStatus !=', 'reAssign');
        }
        if (isset($params['managerApprove']) && trim($params['managerApprove']) != '') {
            $this->db->where('managerApprove', $params['managerApprove']);
            $this->db->where('orderPipline !=', 3);
        } else {
            $this->db->where('salesRef', $params['userRef']);
        }
        $this->db->where('orderStatus !=', 'cancelled');
        $this->db->join('customers', 'customers.customerRef = orders.customerRef', 'inner');
        $this->db->order_by('YEAR(ws_orders.addedOn) DESC, MONTH(ws_orders.addedOn) DESC, DAY(ws_orders.addedOn) DESC, TIME(ws_orders.addedOn) DESC');
        $query = $this->db->get();
         //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $result['success'] = true;
            $result['data']    = $query->result();
        } else {
            $result['success'] = true;
            $result['data']    = array();
        }
        return $result;
    }
    public function getOrdersItems($params)
    {
        $result = array();
        $this->db->select('orders.orderRef,(select blockType from ws_blockTypes where ws_blockTypes.id = ws_products.blockType LIMIT 0,1 ) as blockType,orderItems.customizeItem,
        orderItems.itemRefId,orderItems.itemName,orderItems.qty,orderItems.transportCharge,
        products.transportCost as defaultTransportCharge,orderItems.price,products.itemCost as defaultPrice,
        orderItems.productionOnDemand,products.minimumCost,orderItems.addedOn,
        `ws_orderItems`.`saleUOM` as saleUOM,`ws_orderItems`.`baseUOM`,`ws_products`.`saleUOM` as salesUOMRef,
        `ws_products`.`baseUOM` as baseUOMRef,  `ws_orderItems`.`saleConvQty`, `ws_orderItems`.`saleConvLength` ,
         `ws_orderItems`.`baseConvQty`, `ws_orderItems`.`baseConvLength`
         ');
        $this->db->from('orderItems');
        $this->db->where('orderItems.orderRef', $params['orderRef']);
        // $this->db->where('salesRef', $params['userRef']);
        $this->db->join('orders', 'orders.orderRef = orderItems.orderRef', 'inner');
        $this->db->join('ws_products', 'productRef = orderItems.itemRefId', 'inner');
        $this->db->order_by('YEAR(ws_orderItems.addedOn) DESC, MONTH(ws_orderItems.addedOn) DESC, DAY(ws_orderItems.addedOn) DESC, TIME(ws_orderItems.addedOn) DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result['success'] = true;
            foreach ($query->result() as $key => $value)
            {
              $result['data'][$key]              = $value;
              $result['data'][$key]->variants    =  getVariantsByOrderRef($value->orderRef , $value->itemRefId);
            }
        } else {
            $result['success']       = false;
            $result['error_message'] = 'Opps. no record found please try with new entries.';
        }
        return $result;
    }
    public function apigetOrdersItems($params)
    {
        $result = array();
        $this->db->select('orders.orderRef,orderItems.customizeItem,
        orderItems.itemRefId,orderItems.itemName,orderItems.qty,orderItems.transportCharge,
        orderItems.price,orderItems.discountType,orderItems.discount,
        orderItems.productionOnDemand,products.minimumCost,orderItems.addedOn,
        `ws_orderItems`.`saleUOM` as saleUOM,`ws_orderItems`.`baseUOM`,`ws_products`.`saleUOM` as salesUOMRef,
        `ws_products`.`baseUOM` as baseUOMRef,  `ws_orderItems`.`saleConvQty`, `ws_orderItems`.`saleConvLength` ,
         `ws_orderItems`.`baseConvQty`, `ws_orderItems`.`baseConvLength`
         ');
        $this->db->from('orderItems');
        $this->db->where('orderItems.orderRef', $params['orderRef']);
        // $this->db->where('salesRef', $params['userRef']);
        $this->db->join('orders', 'orders.orderRef = orderItems.orderRef', 'inner');
        $this->db->join('ws_products', 'productRef = orderItems.itemRefId', 'inner');
        $this->db->order_by('YEAR(ws_orderItems.addedOn) DESC, MONTH(ws_orderItems.addedOn) DESC, DAY(ws_orderItems.addedOn) DESC, TIME(ws_orderItems.addedOn) DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result['success'] = true;
            foreach ($query->result() as $key => $value)
            {
              $result['data'][$key]              = $value;
              $result['data'][$key]->variants    =  apiGetVariantsByOrderRef($value->orderRef , $value->itemRefId);
            }
        } else {
            $result['success']       = false;
            $result['error_message'] = 'Opps. no record found please try with new entries.';
        }
        return $result;
    }
    public function dispatchOrderItems($orderRef = null ,$sheetRef = null,$isModifyState = null,$dispatchNum = null)
    {

        $result = array();
        $this->db->select('ws_dispatched_Items.*,
        ws_dispatched_orders.sheetRef,itemReason,
        (select saleUOM from ws_orderItems where ws_orderItems.orderRef = orderRefId and ws_orderItems.itemRefId = ws_dispatched_Items.itemRefId limit 0,1) as saleUOM,
        (select baseUOM from ws_orderItems where ws_orderItems.orderRef = orderRefId and ws_orderItems.itemRefId = ws_dispatched_Items.itemRefId limit 0,1) as baseUOM,
        (select saleConvLength from ws_orderItems where ws_orderItems.orderRef = orderRefId and ws_orderItems.itemRefId = ws_dispatched_Items.itemRefId limit 0,1) as saleConvLength,
        (select uomType from ws_orderItems where ws_orderItems.orderRef = orderRefId and ws_orderItems.itemRefId = ws_dispatched_Items.itemRefId limit 0,1) as uomType,
        (select qty from ws_orderItems where ws_orderItems.orderRef = orderRefId and ws_orderItems.itemRefId = ws_dispatched_Items.itemRefId limit 0,1) as orderQty'
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
        $this->db->group_by('ws_dispatched_Items.variant_id');
        $query = $this->db->get();
        // echo $this->db->last_query();
        // echo "<br>";
        if ($query->num_rows() > 0) {
            $result    = $query->result();
        }

        return $result;
    }
    public function orderSearch($limit, $start, $data)
    {
        /* * **************** fetching records ******************** */
        $this->db->select("ws_orders.*,(select count(ws_orderItems.id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef ) as orderQty, contactName as fullname,name as cityName");
        $this->db->from('ws_orders');
        if (!empty($data)) {
            if (isset($data['orderId']) && trim($data['orderId']) != '') {
                $this->db->where('orderNo', $data['orderId']);
            }

            if (isset($data['customerRef']) && trim($data['customerRef']) != '') {
                $this->db->where('ws_orders.customerRef', $data['customerRef']);
            }
            if (isset($data['salesRef']) && trim($data['salesRef']) != '') {
                $this->db->where('salesRef', $data['salesRef']);
            }
            if (isset($data['orderStatus']) && trim($data['orderStatus']) != '') {
                rtrim($data['orderStatus'], ",");
                $pos = strpos($data['orderStatus'], ",");
                if ($pos !== false) {
                    $orderStatus = explode(',', $data['orderStatus']);
                } else {
                    $orderStatus = array($data['orderStatus']);
                }
                $orderStatus = array_filter($orderStatus);
                if (in_array('complete', $orderStatus)) {
                  if (($key = array_search('complete', $orderStatus)) !== false) {
                      unset($orderStatus[$key]);
                  }
                  if(!empty($orderStatus)){
                    $this->db->where_in('orderStatus', $orderStatus);
                  }
                  $this->db->or_group_start();
                  $this->db->where('orderInProduction', 2);
                  $this->db->group_end();
                  // echo "<pre>";print_r($orderStatus);die;
                }else{
                  if (isset($data['orderPipline']) && trim($data['orderPipline']) != '') {
                      $this->db->where('orderPipline', $data['orderPipline']);
                  }
                  $this->db->where_in('orderStatus', $orderStatus);
                }

            }
            if (isset($data['date']) && trim($data['date']) != '') {
                $where = "( ws_orders.addedOn LIKE '%" . date("Y-m-d", strtotime($data['date'])) . "%' )";
                $this->db->where($where);
            }
        }
        // $this->db->join('ws_orderItems','ws_orderItems.orderRef = ws_orders.orderRef','left');
        $this->db->join('ws_customers', 'ws_customers.customerRef = ws_orders.customerRef', 'left');
        $this->db->join('ws_cities', 'ws_cities.id = ws_customers.cityId', 'left');
        $this->db->order_by('orderStatus', 'DESC');
        $tempdb     = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start, $limit);
        $query  = $this->db->get();
         // echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0) {
            $result = $query->result();
        }
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }
    public function getToLoadOrders($limit = null, $start =null, $data=null)
    {
      $resultArray =  array();
      /* * **************** fetching records ******************** */
      $this->db->select("ws_loadedOrders.sheetRef,ws_orders.orderPrice,ws_orders.id,ws_orders.orderNo,ws_orders.orderRef,orderInProduction,orderStatus,
      (select count(ws_orderItems.id) from ws_orderItems where ws_orderItems.orderRef = ws_orders.orderRef ) as orderQty,
      customerName as fullname, city as cityName,businessName,salesRef,
      (select ws_dispatched_orders.dispatchNo from ws_dispatched_orders where isModifyState = 1 AND (ws_loadedOrders.orderRef = ws_dispatched_orders.orderRef and ws_loadedOrders.sheetRef = ws_dispatched_orders.sheetRef) limit 0,1 ) as dispatchNo
      ");
      $this->db->from('ws_orders');
      $this->db->group_start();
      $this->db->where('orderPipline', 4);
      $this->db->group_end();
      $this->db->where('orderStatus != "cancelled"');
      $this->db->where('toLoad', 1);
      $this->db->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'left');
      $this->db->join('ws_loadedOrders', 'ws_loadedOrders.orderRef = ws_orders.orderRef', 'left');
      $this->db->order_by('ws_orders.addedOn', 'ASC');
      /** getting count **/
      $tempdb     = clone $this->db;
      $total_rows = $tempdb->count_all_results();
      // $this->db->limit($start, $limit);
      $query  = $this->db->get();
      // echo $this->db->last_query();die;
      $result = array();
      if ($query->num_rows() > 0) {
          $result = new stdClass();
          $results = $query->result();
          $result->loadingSheets = new stdClass();
          $result->loadedOrders = new stdClass();

          $i = 0;
          foreach ($results as $key => $value) {
              $sheetData = $this->getLoadedOrders($value->orderRef);
              if (!empty($sheetData)) {
                $refId = $sheetData->sheetRef;
              }
              if (!empty($sheetData)) {

                $resultArray['loadingSheets'][$refId][$key] = $value;
                $resultArray['loadingSheets'][$refId][$key]->orderItems =  $this->orderItems($value->orderRef);
                $resultArray['loadingSheets'][$refId][$key]->orderComment =  $this->orderComments($value->orderRef);
              }else{
                $resultArray['loadedOrders'][$key] = $value;
                $resultArray['loadedOrders'][$key]->orderItems =  $this->orderItems($value->orderRef);
                $resultArray['loadedOrders'][$key]->orderComment =  $this->orderComments($value->orderRef);
              }

          }
      }
       // echo "<pre>"; print_r($resultArray);die;
      return array(
          'total_rows' => $total_rows,
          'result' => $resultArray
      );

    }
    public function getLoadedOrders($orderRef)
    {
      $this->db->select('ws_loadedOrders.sheetRef,refName');
      $this->db->where('orderRef',$orderRef);
      $this->db->group_start();
      $this->db->like('ws_loadedOrders.modifiedDate',date('Y-m-d'));
      $this->db->or_like('ws_loadedOrders.modifiedDate',date('Y-m-d', strtotime('+1 day')));
      $this->db->group_end();
      $this->db->join('ws_loadingSheets','`ws_loadingSheets`.`sheetRef` = ws_loadedOrders.sheetRef','inner');
      $this->db->order_by('refName','ASC');
      $query   = $this->db->get('ws_loadedOrders');
       // echo $this->db->last_query();die;
      if ($query->num_rows() > 0)
      $rows = $query->row();
      else $rows = array();
      return $rows;
    }
    public function getDeliveryAddress($ref)
    {
        $this->db->select("`orderRef`, `addressLine`, `businessName` , `customerName`, `customerPhone`, `city`, `state`, `country`");
        $this->db->from('ws_orderDeliveryAddress');
        $this->db->where('`ws_orderDeliveryAddress`.`orderRef`', $ref);
        $query  = $this->db->get();
        $result = array();
        if ($query->num_rows() > 0) {
            $result = $query->row();
        }
        return $result;
    }
    public function deleteOrder($ref)
    {

        $this->db->where('orderRef', $ref);
        $this->db->delete('ws_orders');
        $order = $this->db->affected_rows();

        $this->db->where('orderRef', $ref);
        $this->db->delete('ws_orderDeliveryAddress');
        $orderAddress = $this->db->affected_rows();

        $this->db->where('orderRef', $ref);
        $this->db->delete('ws_orderComments');
        $orderComments = $this->db->affected_rows();

        $this->db->where('orderRef', $ref);
        $this->db->delete('ws_orderItems');
        $orderItems = $this->db->affected_rows();



        $result['success'] = false;
        if ($orderItems > 0 || $orderAddress > 0 || $order > 0) {
            $result['success'] = true;
        }
        return $result;
    }
    public function fetchProductionOutput($today, $lastdate)
    {
        $this->db->select('*');
        $this->db->from('ws_productionOutput');
        $this->db->where('weekStartDate <=', $today);
        $this->db->where('weekEndDate   >=', $lastdate);
        $this->db->order_by('id', 'desc');
        $query  = $this->db->get();
        // echo $this->db->last_query();die;
        $result = $query->result();
        return $result;
    }
    public function fetchProductionOutputPagination($today, $lastdate, $start, $limit, $searchKey)
    {
        $this->db->select('*');
        $this->db->from('ws_productionOutput');
        $this->db->where('weekStartDate <=', $today);
        $this->db->where('weekEndDate >=', $lastdate);
        $this->db->order_by('id', 'DESC');
        $tempdb     = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($limit, $start);
        $query  = $this->db->get();
        // echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0) {
            $result = $query->result();
        }
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }
    public function userNotifications($params)
    {
        // echo "<pre>";print_r($params);die;
        $this->db->select("notificationRef,orderRef,notificationContactName,notificationBussinessName,notificationTitle,notificationMessage,readStatus, starredStaus,addedOn,(select orderStatus from ws_orders where ws_orders.orderRef = ws_notification.orderRef limit 0,1) as orderStatus,(select orderNo from ws_orders where ws_orders.orderRef = ws_notification.orderRef limit 0,1) as orderNo");
        $this->db->from('ws_notification');
        if (isset($params['filter']) && trim($params['filter'] != '')) {
            if ($params['filter'] == 'unread') {
                $this->db->where('`readStatus`', 1);
            }
            if ($params['filter'] == 'read') {
                $this->db->where('`readStatus`', 2);
            }
            if ($params['filter'] == 'starred') {
                $this->db->where('`starredStaus`', 1);
            }
            if ($params['filter'] == 'normal') {
                $this->db->where('`starredStaus`', 0);
            }
            // if (isset($params['customerName']) && $params['customerName'] !='') {
            //   $this->db->where("`notificationContactName` LIKE '%'".$params['customerName']."'%'");
            // }
        }
        if (isset($params['duration']) && trim($params['duration'] != '')) {
            $where = ('addedOn BETWEEN date_sub(now(),INTERVAL ' . $params['duration'] . ' DAY) and now()');
            $this->db->where($where);
        }
        if (isset($params['customerName']) && trim($params['customerName'] != '')) {
            $where = ('notificationContactName LIKE "%' . $params['customerName'] . '%"');
            $this->db->where($where);
        }
        $this->db->where('`notificationTo`', $params['userRef']);
        $this->db->order_by('YEAR(ws_notification.addedOn) DESC, MONTH(ws_notification.addedOn) DESC, DAY(ws_notification.addedOn) DESC, TIME(ws_notification.addedOn) DESC');
        $query  = $this->db->get();
        // echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0) {
            $result['success']         = true;
            $result['success_message'] = 'Notification data retirve successfully.';
            $result['data']            = $query->result();
        } else {
            $result['success']         = true;
            $result['success_message'] = 'no records found please try with new entries.';
            $result['data']            = array();
        }
        return $result;
    }
    public function getStats($startMidNightDate = null , $endDate = null)
    {


      $startDate          = (is_null($startMidNightDate)) ? date('Y-m-d',strtotime('now')) : date('Y-m-d',strtotime($startMidNightDate));
      $endDate            = (is_null($endDate))           ? date('Y-m-d',strtotime('+7 Days')) : date('Y-m-d',strtotime($endDate));
      $startMidNightDate  = (is_null($startMidNightDate)) ? date('Y-m-d H:i:s',strtotime('-1 day midnight')) : date('Y-m-d H:i:s',strtotime($startMidNightDate,strtotime('-1 day midnight')));
      $nextWorkingDay     = (is_null($startMidNightDate)) ? date('Y-m-d H:i:s',strtotime('+1 day noon')) : date('Y-m-d H:i:s',strtotime('+1 day noon',strtotime($startMidNightDate)));

      $this->db->select('
      SUM(blockPercentage) as totalBlocks,
      (SELECT SUM(blockPercentage) from ws_products where ws_products.productionOnDemand = 1 AND ws_orderItems.`modifiedDate` >= "'.$startDate.'" AND ws_orderItems.`modifiedDate` <= "'.$endDate.'" LIMIT 0,1) as productPercentage'
      );
      $this->db->from('ws_orderItems');
      $this->db->join('ws_products','ws_orderItems.itemRefId = productRef','left');
      $this->db->where('`ws_orderItems`.`productionOnDemand`',1);
      $this->db->where("ws_orderItems.addedOn BETWEEN '$startDate' AND '$endDate'");
      $query = $this->db->get();
      $blocks = $query->row();


      $this->db->select
      ('
      (SELECT COUNT(DISTINCT ws_dispatched_orders.dispatchNo) FROM `ws_dispatched_orders` LIMIT 0,1) as dispatchOrders,
      (SELECT COUNT(DISTINCT ws_dispatched_Items.dispatchRef) FROM `ws_dispatched_Items` WHERE isReturn = 1  LIMIT 0,1) as refuesedOrder,
      (SELECT COUNT(ws_dispatched_orders.id) FROM `ws_dispatched_orders` WHERE FIND_IN_SET(8,errorStatus)  LIMIT 0,1) as defectiveGoods,
      (SELECT COUNT(ws_dispatched_orders.id) FROM `ws_dispatched_orders` WHERE FIND_IN_SET(6,errorStatus) AND FIND_IN_SET(7,errorStatus) LIMIT 0,1) as loadingErrors,
      ');
      $this->db->from('ws_dispatched_orders');
      $this->db->join('ws_dispatched_Items','ws_dispatched_Items.dispatchRef = ws_dispatched_orders.dispatchNo','left');
      $this->db->where("ws_dispatched_orders.addedOn BETWEEN '$startDate' AND '$endDate'");
      $this->db->group_by('dispatchNo');
      $query = $this->db->get();
      // echo $this->db->last_query();die;
      $result = $query->row();

      $this->db->select('id');
      $this->db->from('ws_orders');
      $this->db->group_start();
      $this->db->where('orderStatus','reAssign');
      $this->db->group_end();
      $this->db->where('ws_orders.modifiedDate  <= "'.$startDate.'" - interval 2 day');
      $query = $this->db->get();
      $reAssing = $query->num_rows();

      $this->db->select('
      COUNT(ws_orders.orderRef) as totalOrder,
      (SELECT SUM(orderPrice) from ws_orders where orderStatus!= "cancelled" AND ws_orders.`modifiedDate` >= "'.$startDate.'" AND ws_orders.`modifiedDate` <= "'.$endDate.'" limit 0,1) as grossSales,
      (SELECT COUNT(ws_orders.id) from ws_orders where (orderStatus != "cancelled") AND (managerApprove = 1) AND ws_orders.`modifiedDate` >= "'.$startDate.'" AND ws_orders.`modifiedDate` <= "'.$endDate.'" LIMIT 0,1) as managerApprovedOrders ,
      (SELECT COUNT(ws_orders.id) from ws_orders where (orderStatus = "reAssign") AND modifiedDate  <= curdate() - interval 2 day  LIMIT 0,1) as reAssignedOrders ,
      (SELECT COUNT(ws_orders.id) from ws_orders where (orderStatus = "cancelled") AND ws_orders.`modifiedDate` >= "'.$startDate.'" AND ws_orders.`modifiedDate` <= "'.$endDate.'" LIMIT 0,1) as cancelledOrders,
      (SELECT COUNT(ws_orders.id) from ws_orders where (orderStatus != "cancelled") AND (orderPipline = 2)  AND ws_orders.orderRef NOT IN
      (SELECT ws_orders.orderRef from ws_orders WHERE orderPipline = 4 AND ws_orders.`modifiedDate` >= "'.$startMidNightDate.'" AND ws_orders.`modifiedDate` <= "'.$nextWorkingDay.'" )
      LIMIT 0,1) as approvalQueue,
      (SELECT COUNT(ws_orders.id) from ws_orders  WHERE orderPipline = 4 AND ws_orders.`modifiedDate` >= "'.$startMidNightDate.'" AND ws_orders.`modifiedDate` <= "'.$nextWorkingDay.'" LIMIT 0,1) as aprovedOrders
      ');
      $this->db->from('ws_orders');
      $this->db->where("ws_orders.addedOn BETWEEN '$startDate' AND '$endDate'");
      $query = $this->db->get();
      // echo $this->db->last_query();die;

      $grossSales = $query->row();


      $this->db->select('COUNT(DISTINCT ws_dispatched_orders.orderRef) as dispached,
      (SELECT COUNT(*) FROM `ws_orders` WHERE orderPipline = 4 AND  orderRef NOT IN
      (SELECT ws_dispatched_orders.orderRef from ws_dispatched_orders WHERE ws_dispatched_orders.`addedOn` >= "'.$startMidNightDate.'" AND ws_dispatched_orders.`addedOn` <= "'.$nextWorkingDay.'" )
      LIMIT 0,1 ) as orderCount');
      $this->db->from('ws_dispatched_orders');
      $this->db->where("addedOn BETWEEN '$startMidNightDate' AND '$nextWorkingDay'");
      $query = $this->db->get();
      // echo $this->db->last_query();die;

      $dispatch = $query->row();


      $rr =  array(
        'success'          => true,
        'dispatchStats'    => $result,
        'orderStats'       => $grossSales,
        'blockPercentage'  => $blocks,
        'dispatch'         => $dispatch,
      );

      return $rr;exit;

    }

    public function deleteLoadingGroup($dateTime =null, $ordeRefId = null)
    {
      if ($dateTime == null && $ordeRefId == null) {
          $this->db->where("modifiedDate < (NOW() - INTERVAL 24 HOUR)");
      }

      if ($ordeRefId != null ) {
        $this->db->where("orderRef",$ordeRefId);
      }
      $this->db->delete('ws_loadedOrders');
    }

    public function getLoadingSheetData($sheetRef,$toDate)
    {
      $result = array();
      $this->db->select('*,(SELECT refName FROM ws_loadingSheets WHERE ws_loadingSheets.sheetRef = ws_dispatched_orders.sheetRef LIMIT 0,1) as sheetName');
      $this->db->from('ws_dispatched_orders');
      $this->db->join('ws_orderDeliveryAddress','ws_orderDeliveryAddress.orderRef = ws_dispatched_orders.orderRef','inner');

      $this->db->group_start();
      $this->db->like('ws_dispatched_orders.modifiedDate', $toDate);
      $this->db->or_like('ws_dispatched_orders.modifiedDate', $toDate);
      $this->db->group_end();

      $this->db->where('sheetRef',$sheetRef);
      $this->db->where('isModifyState',1);

      $query = $this->db->get();
      // echo $this->db->last_query();die;
      $sheetData = $query->result();
      foreach ($sheetData as $key => $value) {
          $result[$key] = $value;
          $result[$key]->dispatchtems = $this->dispatchOrderItems($value->orderRef ,$value->sheetRef,1,$value->dispatchNo);
      }
      // echo "<pre>";print_r($result);die;
      return $result;
    }

    public function lastDispatchedOrders($orderRef = null, $limit =null, $start = null)
    {
      $result = array();
      $this->db->select('*,customerName as fullname,city as cityName,ws_dispatched_orders.addedOn as dispatchedDate')
               ->from('ws_dispatched_orders')
               ->join('ws_orders','ws_orders.orderRef = ws_dispatched_orders.orderRef')
               ->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'left')
               ->where('isModifyState',2);
               // ->where('orderStatus != "cancelled"');
     if($orderRef !=''){
       $this->db->where('ws_dispatched_orders.orderRef',$orderRef);
     }else{
       $this->db->where('ws_dispatched_orders.addedOn   >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
     }
      $this->db->order_by('ws_dispatched_orders.addedOn','DESC');
     $tempdb     = clone $this->db;
     $total_rows = $tempdb->count_all_results();
     $this->db->group_by('dispatchNo');
     $this->db->order_by('dispatchNo','DESC');
     // $this->db->limit($start, $limit);
     $query = $this->db->get();
     // echo $this->db->last_query();die;

     $sheetData = $query->result();
     // echo '<pre>'; print_r($sheetData);die;s
      foreach ($sheetData as $key => $value) {
          $result[$key] = $value;
          $result[$key]->dispatchtems = $this->dispatchOrderItems($value->orderRef ,$value->sheetRef,2,$value->dispatchNo);
          $result[$key]->orderComment = $this->orderComments($value->orderRef);
      }
      return array(
          'total_rows' => $total_rows,
          'result' => $result
      );

    }

    public function lastDispatched($orderRef = null , $ids =null)
    {
      $this->db->select('orderRef , itemRefId ,qtyLoaded as lastDispatched');
      $this->db->from('ws_dispatched_Items');
      $this->db->join('ws_dispatched_orders','ws_dispatched_orders.dispatchNo = dispatchRef','left');
      $this->db->where('orderRef', $orderRef);
      $this->db->where_in('itemRefId', $ids);
      $this->db->where('isModifyState !=',1);
      $this->db->order_by('ws_dispatched_Items.id', 'DESC');
      $query  = $this->db->get();
      // echo $this->db->last_query();die;
      $result = array();
      if ($query->num_rows() > 0) {
          $result = $query->result();
      }
      return $result;

    }


    public function closedOrders($start,$end)
    {
    /*
        Order No.
        Order Date
        Dispatch No.
        Dispatch Date
        Invoice No.
        Customer Name
        Delivery Method
        Town
        Region
        Received Status
        Return Status
        Error Status
        Credit Note No.
        One column for each error option marked yes/no
        Comments on Errors
        Comments on dispatch
    */
        $result = array();
        $this->db->select('dispatchNo,ws_orders.orderNo,ws_orders.orderRef,sheetRef,city,state,
            invoiceNo,customerName,receivedStatus,errorStatus,returnStatus,ws_orders.addedOn as orderDate,ws_dispatched_orders.addedOn as dispatchDate,
            (select methodName from ws_deliveryMethod where ws_deliveryMethod.deliveryMethodRef = ws_orders.deliveryMethodRef limit 0,1) as deliveryMethod
            ')
               ->from('ws_dispatched_orders')
               ->join('ws_orders','ws_orders.orderRef = ws_dispatched_orders.orderRef')
               ->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef = ws_orders.orderRef', 'left')
               ->where('isModifyState',2)
               ->where('dispatchStatus',"Closed");
               $this->db->where('ws_orders.modifiedDate >=', date('Y-m-d',strtotime($start) ));
               $this->db->where('ws_orders.modifiedDate <=', date('Y-m-d',strtotime($end) ) );
        $this->db->group_by('dispatchNo');
        $this->db->order_by('dispatchNo','DESC');
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        $exportData = $query->result();
        foreach ($exportData as $key => $value) {
            $result[$key]                   = $value;
            $result[$key]->errors           = orderErrors($value->errorStatus);
            $result[$key]->errorStatus      = (trim($value->errorStatus) != '' ) ? 'Yes' : 'No' ;
            $result[$key]->followupComments = followupComments($value->dispatchNo,array('dispatch','error','creditNote'));
            $result[$key]->dispatchtems     = $this->dispatchOrderItems($value->orderRef ,$value->sheetRef,2,$dispatchNum = null);

        }
        return $result;
    }
}
