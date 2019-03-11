<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CommonModel extends CI_Model {
    public function __construct()
	{
        parent::__construct();
        $loginSessionData = $this->session->userdata('clientData');
        $this->userRef = $loginSessionData['userRef'];
        // // echo "<pre>";print_r($loginSessionData);die;
        // $accountantRoutes = array('customers-list','sales','items','management','finance','production-processing','dispatch-processing','customer-follow-up','categories','users','delivery-method','pricing-method','dashboard','home','ajaxAddUpdateItem','ajaxAddUpdateCategory','add-customer','add-user','update-user','updateUserAjax','register-user','update-status');
        // $route            = $this->uri->segment(1);
        // if( empty($loginSessionData) && $route != 'login')
        //     redirect('login');
        // else if( !empty($loginSessionData) )
        // {
        //     if( !in_array($route,$accountantRoutes))
        //         redirect('dashboard');
        // }


    }

	public function getData($table = null ,$where = null ,$select = null )
	{
		if( $table == null )
			return false;
        if( $select != null )
			$this->db->select($select);
		else
			$this->db->select('*');
		if( $where != null )
			$this->db->where($where);
        $query 	= $this->db->get($table);

		$result = array();
		if($query->num_rows() > 0)
			$result = $query->result();
		return $result;
	}


    /******************
        Check column value already exist
    ******************/
	public function checkexist($table,$where, $betweenCondtion = null)
	{
        $this->db->from($table);
        if($betweenCondtion != null)
        {
          $this->db->where('weekStartDate >=', $betweenCondtion['weekStartDate']);
          $this->db->where('weekEndDate <=', $betweenCondtion['weekEndDate']);
        }
        else{
          $this->db->where($where);
        }
        $query  = $this->db->get();
        // echo $this->db->last_query();die;
    		$output = false;
    		if($query->num_rows() > 0)
    			$output = true;
    		return $output;
	}

	public function insert($table,$data)
	{

		$this->db->insert($table,$data);
		$insert_id = $this->db->insert_id();
		if( $insert_id > 0)
			$output = $insert_id;
		else
			$output = '';
		return $output;
	}

	public function update($where,$data,$table)
	{
		$this->db->where($where);
		$this->db->update($table,$data);
		$db_error = $this->db->error();
		if ($db_error['code'] == 0)
			return '1';
		else
			return '0';
	}
	public function update_multiple($table,$data,$where)
	{
    foreach ($data as $key => $value)
    {
      $this->db->group_start();
      foreach ($where as $key => $whereValue) {
          $loArray[] = $whereValue;
          $this->db->where($whereValue,$value[$whereValue]);
      }
      $this->db->group_end();
      $this->db->update($table,$value);
  }
	$db_error = $this->db->error();
	if ($db_error['code'] == 0)
		return '1';
	else
		return '0';
	}

	public function insert_batch($table,$data)
	{
		$this->db->insert_batch($table,$data);
		$insert_id = $this->db->insert_id();
		if( $insert_id != 0)
			$output = true;
		else
			$output = false;
		return $output;
	}

    function getSomeFields( $fields = null, $condition = null,$table = null )
    {
        $this->db->select($fields);
        $this->db->where($condition);
        $query  = $this->db->get($table);
        $result = array();
        if( $query->num_rows() > 0 )
        {
            $result = $query->row();
        }
        //echo $this->db->last_query();die;
        return $result;
    }


    public function delete($recordRef = null, $recordType = null )
    {
        switch ($recordType)
        {
            case 'users':
            {
                $this->db->set('isDeleted',1);
                $this->db->where('userRef',$recordRef);
                $this->db->update('users');

                $this->db->where('userRef',$recordRef);
                $this->db->set('security_key',generateRef());
                $this->db->update('ws_keys');
                break;
            }
            case 'orders':
            {
                $this->db->where('orderRef',$recordRef);
                $this->db->delete('orders');

                $this->db->where('orderRef',$recordRef);
                $this->db->delete('orderComments');

                $this->db->where('orderRef',$recordRef);
                $this->db->delete('orderItems');

                $this->db->where('orderRef',$recordRef);
                $this->db->delete('orderDeliveryAddress');

                break;
            }
            case 'orderComment':
            {
                $this->db->where('commentRef',$recordRef);
                $this->db->delete('orderComments');
                break;
            }
            case 'deleteNotification':
            {
                $this->db->where('notificationRef',$recordRef);
                $this->db->delete('ws_notification');
                break;
            }
            case 'removeLoadedOrders':
            {
                $this->db->where('orderRef',$recordRef);
                $this->db->where('loadedStatus',1);
                $this->db->delete('ws_loadedOrders');
                $remove = $this->getData('ws_dispatched_orders' ,array('orderRef'=>$recordRef , 'isModifyState'=>1) ,'dispatchNo' );
                $this->db->where('dispatchNo',$remove[0]->dispatchNo);
                $this->db->delete('ws_dispatched_orders');
                $this->db->where('dispatchRef',$remove[0]->dispatchNo);
                $this->db->delete('ws_dispatched_Items');
                break;
            }
            case 'removeDispachedItems':
            {

                foreach ($recordRef as $key => $value) {
                  $this->db->where('dispatchRef',$value['dispatchRef']);
                  $this->db->delete('ws_dispatched_Items');
                }
                break;
            }
            case 'deleteDispatchItem':
            {
                  $this->db->where('itemRefId',$recordRef['itemRefId']);
                  $this->db->where('dispatchRef',$recordRef['dispatchRef']);
                  $this->db->where('variant_id',$recordRef['variant_id']);
                  $this->db->delete('ws_dispatched_Items');

                break;
            }
            case 'transportCharge':
            {
                  $this->db->where('transportRef',$recordRef);
                  $this->db->delete('ws_transportCharges');
                break;
            }
            case 'attributes':
            {
                  $this->db->where('productId',$recordRef);
                  $this->db->delete('ws_variants');

                  $this->db->where('productId',$recordRef);
                  $this->db->delete('ws_variantsDesign');

                  $this->db->where('productId',$recordRef);
                  $this->db->delete('ws_variantsColor');

                  $this->db->where('productId',$recordRef);
                  $this->db->delete('ws_variantsSizes');


                break;
            }
            default:
            {
                //return false;
                break;
            }
        }
        $db_error = $this->db->error();
        if ($db_error['code'] == 0)
            return '1';
        else
            return '0';
    }

    public function updateStatus( $ref = null,$type = null,$status = null )
    {

        switch ($type)
        {
            case 'users':
            {

                $this->db->set('status',$status);
                // if($status == 1){
                //   $this->db->set('isDeleted',0);
                // }
                // else{
                //   $this->db->set('isDeleted',1);
                // }
                $this->db->where('userRef',$ref);
                $this->db->update('users');

                $this->db->where('userRef',$ref);
                $this->db->set('security_key',generateRef());
                $this->db->update('ws_keys');

                break;
            }
            case 'category':
            {
                $this->db->set('status',$status);
                $this->db->where('catRef',$ref);
                $this->db->update('categories');
                break;
            }
            case 'items':
            {
                $this->db->set('status',$status);
                $this->db->where('productRef',$ref);
                $this->db->update('ws_products');
                break;
            }
            case 'customers':
            {
                $this->db->set('status',$status);
                $this->db->where('customerRef',$ref);
                $this->db->update('ws_customers');
                break;
            }
            case 'category':
            {
                $this->db->set('status',$status);
                $this->db->where('catRef',$ref);
                $this->db->update('ws_categories');
                break;
            }
            case 'measurements':
            {
                $this->db->set('status',$status);
                $this->db->where('unitRef',$ref);
                $this->db->update('measurement');
                break;
            }
            case 'deliveryMethod':
            {

                $this->db->set('status',$status);
                $this->db->where('deliveryMethodRef',$ref);
                $this->db->update('deliveryMethod');
                break;
            }
            case 'blockTypes':
            {
                $this->db->set('status',$status);
                $this->db->where('id',$ref);
                $this->db->update('blockTypes');
                break;
            }
            case 'region':
            {
                $ref = ($ref);
                $this->db->set('status',$status);
                $this->db->where('id',$ref);
                $this->db->update('ws_regions');
                break;
            }
            case 'cities':
            {
                $this->db->set('status',$status);
                $this->db->where('id',$ref);
                $this->db->update('ws_cities');
                break;
            }
            case 'transportCharges':
            {
                $this->db->set('status',$status);
                $this->db->where('transportRef',$ref);
                $this->db->update('transportCharges');
                break;
            }
            case 'notification':
            {
                $this->db->set('starredStaus',$status);
                $this->db->where('notificationRef',$ref);
                $this->db->update('ws_notification');
                break;
            }
            case 'loadingSheet':
            {

                $this->db->set('status',$status);
                $this->db->set('modifiedDate',date('Y-m-d H:i:s'));
                $this->db->where('sheetRef',$ref);
                // $this->db->update('ws_loadingSheets');
                if($this->db->update('ws_loadingSheets')){
                  if($status == 0){
                    $this->db->select("orderRef");
                    $this->db->from('ws_loadedOrders');
                    $this->db->where('sheetRef',$ref);
                    $query = $this->db->get();
                    $result = $query->result();

                    foreach ($result as $results) {

                      $this->db->where('sheetRef',$ref);
                      $this->db->delete('ws_loadedOrders');

                      $this->db->select("dispatchNo");
                      $this->db->from('ws_dispatched_orders');
                      $this->db->where('sheetRef',$ref);
                      $this->db->where('orderRef',$result[0]->orderRef);
                      $this->db->group_start();
                      $this->db->where('isModifyState',1);
                      $this->db->group_end();
                      $query = $this->db->get();
                      $dispatchNo = $query->row();

                      $this->db->where('dispatchNo',$dispatchNo->dispatchNo);
                      $this->db->delete('ws_dispatched_orders');

                      $this->db->where('dispatchRef',$dispatchNo->dispatchNo);
                      $this->db->delete('ws_dispatched_Items');

                    }
                  }
                }

                break;
            }
            case 'pricingMethod':
            {
                $this->db->set('status',$status);
                $this->db->set('modifiedDate',date('Y-m-d H:i:s'));
                $this->db->where('pricingRef',$ref);
                $this->db->update('ws_pricingMethod');
                break;
            }
            default:
            {
                //return false;
                break;
            }
        }
        $db_error = $this->db->error();
        if ($db_error['code'] == 0)
            return '1';
        else
            return '0';
    }
    public function changeOrderStatus( $orderRef = null,$dataTo = null,$orderPipline = null , $dataProduction = null,$salesRef = null, $postData = null)
    {
      // echo "<pre>";print_r($postData);die;
        switch ($orderPipline)
        {
            case '2':
            {
                if ($dataTo == 'approved')
                {
                     // if order needs to approved set status = 2
                     // setting user ref which user approved
                     // preAuthorization just a name
                     // sets order pipline values 1 for sales 2 for Approval or fianace 3 for production and 4 for dispatch
                      if ($_POST['dataApprove'] == 1)
                      {
                          $this->db->set('managerApprove',2);
                          $this->db->set('approvedBy',$this->userRef);
                          $this->db->set('preAuthorization',$postData['approvedBy']);
                      }
                      $this->db->set('orderStatus','open');
                      $this->db->set('orderPipline',4);
                }
                elseif ($dataTo =='re-assigned')
                {

                   $this->db->set('orderStatus','reAssign');
                   $this->db->set('salesRef',$salesRef);
                   $this->db->set('orderPipline',2);
                }
                elseif ($dataTo =='managerApprove')
                {
                   $this->db->set('managerApprove',1);
                   $this->db->set('orderStatus','MAN');
                   $this->db->set('preAuthorization',$postData['approvedBy']);
                }
                elseif ($dataTo =='cancelled')
                {
                   $this->db->set('orderStatus','cancelled');
                   $this->db->set('preAuthorization',$postData['approvedBy']);
                }
                $this->db->set('modifiedDate',date('Y-m-d H:i:s'));
                $this->db->where('orderRef',$orderRef);
                $this->db->update('ws_orders');
                //  echo $this->db->last_query();die;
                break;
            }
            case '3':
            {
              $this->db->set('modifiedDate',date('Y-m-d H:i:s'));
              $this->db->set('orderStatus',$postData['dataTo']);
              $this->db->set('orderPipline',$postData['dataPipline']);
              $this->db->where('orderRef',$orderRef);
              $this->db->update('ws_orders');
              break;
            }
            case '4':
            {

              $orderPiplineArray = array('pending' => 2 , 'onHold' => 3 , 'inProduction' => 3 , 'queued' => 3,'Cancelled' => 4,'cancelled' => 4);
              // echo "<pre>";print_r($orderPiplineArray[$postData['dataTo']]);die;

              if ( ($postData['dataTo'] == 'pending') && (isset($postData['itemRefIds']) && isset($postData['estDates'])) )
              {
                $postData['itemRefIds'] =  rtrim($postData['itemRefIds']);
                $postData['estDates']   =  rtrim($postData['estDates']);

                $itemRefids = explode(',',$postData['itemRefIds']);
                $estDates   = explode(',',$postData['estDates']);
                $i = 0;
                foreach ($itemRefids as $key => $value) {
                  if($value !=''){
                    $estDates[$i] = ($estDates[$i] !='') ? $estDates[$i] : date('m-d-Y');
                    $this->db->set('readyEstDate',date('Y-m-d',strtotime($estDates[$i])));
                    $this->db->where('id',$value);
                    $this->db->where('orderRef',$orderRef);
                    $this->db->update('orderItemVariants');
                    $i++;
                  }
                }
                // pr($estDates);pr($itemRefids);pr($postData);die;
              }
              if (isset($postData['dataProduction']) && $postData['dataProduction'] == 1) {
                $this->db->set('orderInProduction',2);
              }
              $this->db->set('modifiedDate',date('Y-m-d H:i:s'));
              $this->db->set('orderStatus', $postData['dataTo']);
              $this->db->set('orderPipline',$orderPiplineArray[$postData['dataTo']]);

              $this->db->where('orderRef',$orderRef);
              $this->db->update('ws_orders');
              // echo $this->db->last_query();die;
              break;
            }
            case 'cancelled':
            {
              $this->db->set('modifiedDate',date('Y-m-d H:i:s'));
              $this->db->set('orderStatus',$postData['dataTo']);
              $this->db->set('orderPipline',$postData['dataPipline']);
              $this->db->where('orderRef',$orderRef);
              $this->db->update('ws_orders');
                break;
            }
            case 'category':
            {
                $this->db->set('status',$status);
                $this->db->where('catRef',$ref);
                $this->db->update('ws_categories');
                break;
            }
            default:
            {
                //return false;
                break;
            }
        }
        $db_error = $this->db->error();
        if ($db_error['code'] == 0)
            return '1';
        else
            return '0';
    }

    public function deleteOrder($orderRef)
    {
        $this->db->set('modifiedDate',date('Y-m-d H:i:s'));
        $this->db->where('orderRef',$orderRef);
        $this->db->update('orders');

        $this->db->where('orderRef',$orderRef);
        $this->db->delete('orderItems');

        $this->db->where('orderRef',$orderRef);
        $this->db->delete('orderDeliveryAddress');

        $this->db->where('orderRef', $orderRef);
        $this->db->delete('ws_orderItemVariants');

        return true;
    }
    public function getNotificationCount($userRef)
    {
      $this->db->select("notificationRef,orderRef,notificationContactName,notificationBussinessName,notificationTitle,notificationMessage,readStatus, starredStaus,addedOn");
      $this->db->from('ws_notification');
      $this->db->where('`readStatus`',1);
      $this->db->where('`notificationTo`',$userRef);
      $this->db->order_by('YEAR(ws_notification.addedOn) DESC, MONTH(ws_notification.addedOn) DESC, DAY(ws_notification.addedOn) DESC, TIME(ws_notification.addedOn) DESC');
      $query = $this->db->get();
      // echo $query->num_rows();die;
      $result = array();
      if ($query->num_rows() > 0)
      {
        $result['success'] = true;
        $result['notificationCount'] = $query->num_rows();
      }else{
        $result['success'] = true;
        $result['notificationCount'] = '';
      }
      return  $result;
    }

    public function deleteDirectory($dir){
    $result = false;
      if ($handle = opendir("$dir")){
          $result = true;
          while ((($file=readdir($handle))!==false) && ($result)){
              if ($file!='.' && $file!='..'){
                  if (is_dir("$dir/$file")){
                      $result = deleteDirectory("$dir/$file");
                  } else {
                       $result = unlink("$dir/$file");
                  }
              }
          }
          closedir($handle);
          // if ($result){
          //     $result = rmdir($dir);
          // }
      }
      return $result;
  }

  public function getOrdersStats($userRef =null)
  {
    $this->load->model('UsersModel');
    $result = array();
    $reAssing = array();
    $this->db->select
    ('
      COUNT(id) as totalOrder,
      (SELECT COUNT(id) from ws_orders where (orderStatus != "cancelled") AND (managerApprove = 1 AND salesRef = "'.$userRef.'") LIMIT 0,1) as managerApprovedOrders ,
      (SELECT COUNT(id) from ws_orders where (orderStatus != "cancelled" AND managerApprove = 0) AND ( orderPipline = 2 AND salesRef = "'.$userRef.'") AND addedOn  <= curdate() - interval 2 day  LIMIT 0,1) as approvedOrders ,
      (SELECT COUNT(id) from ws_orders where (orderStatus = "cancelled") AND ( salesRef = "'.$userRef.'") LIMIT 0,1) as cancelledOrders,
      (SELECT SUM(orderPrice) from ws_orders where (orderStatus != "cancelled") AND ( salesRef = "'.$userRef.'" ) LIMIT 0,1) as orderPrice'
    );
    $this->db->from('ws_orders');
    $this->db->where('salesRef',$userRef);
    $query = $this->db->get();
    // echo $this->db->last_query();die;
    $result = $query->result();

    $this->db->select('id,addedOn,modifiedDate');
    $this->db->from('ws_orders');
    $this->db->group_start();
    $this->db->where('salesRef',$userRef);
    $this->db->where('orderStatus','reAssign');
    $this->db->group_end();
    $this->db->where('ws_orders.modifiedDate >= DATE(NOW()) - INTERVAL 2 DAY');
    $query = $this->db->get();
    // echo $this->db->last_query();die;
    $reAssing = $query->result();
    $response =  array(
      'success'         => true,
      'orderStats'      => $result,
      'reAssingOrders'  => $reAssing,
    );

    $output['success']                                     =  true;
    $output['success_message']                             =  'Orders Stats Retrive successfully..';
    $salesTarget = 0;
    $res = $response['orderStats'][0];
    $output['totalOrder'] = $res->totalOrder ;
    $getUserTargets   = $this->UsersModel->getUserTargets($userRef);

    if (!empty($getUserTargets['data'])) {
      foreach ($getUserTargets['data'] as $key => $value)
      {
        if ($value->targetType == 'monthlyTarget') {
          $output['Sales']['targetAmount']                       =  number_format($value->targetValue,2);
          $output['Sales']['targetAmountType']                   =  $value->valueType;
          $salesTarget = $value->targetValue;
        }else {
          $output[$value->targetType]['myTarget']         =  $value->targetValue;
          $output[$value->targetType]['targetType']       =  $value->valueType;
        }

      }
    }
    // echo $salesTarget;die;
    $temp                                                  =  $salesTarget;

    $output['Sales']['targetAmount']                       =  number_format($temp,2);
    $output['Sales']['totalSalesAmount']                   =  number_format($res->orderPrice,2);
    $output['Sales']['totalSalesPercentage']               =  number_format( ($res->orderPrice > 0 && $temp > 0) ? ( $res->orderPrice / $temp ) * 100 : 0 ,1) ;

    $output['financeOrders']['totalOrder']                 =  $res->approvedOrders;
    $output['financeOrders']['orderPercentage']            =  number_format( ($res->totalOrder > 0) ? ( $res->approvedOrders / $res->totalOrder ) * 100 : 0 , 2);

    $output['approvalOrders']['totalOrder']                =  $res->managerApprovedOrders;
    $output['approvalOrders']['orderPercentage']           =  number_format( ($res->totalOrder > 0) ? number_format( ( $res->managerApprovedOrders / $res->totalOrder ) * 100,1) : 0 , 2);

    $output['cancelledOrders']['totalOrder']               =  $res->cancelledOrders;
    $output['cancelledOrders']['orderPercentage']          =  number_format( ($res->totalOrder > 0) ? number_format( ( $res->cancelledOrders / $res->totalOrder ) * 100,1) : 0 , 2 );

    $output['refuesedOrders']['totalOrder']                =  0;
    $output['refuesedOrders']['orderPercentage']           =  0;

    $output['reAssingOrders']                              = $response['reAssingOrders'];

    return $output;
  }



  public function globalSearch($post)
  {
    $searchKey = $post['searchKey'];
    /*
     * Start Orders Code
     */
    $this->db->select('orderNo,orderStatus,orderPipline,orderRef');
    $this->db->from('ws_orders');
    $this->db->group_start();
    $where = "( orderNo LIKE '%$searchKey%' or  orderStatus LIKE '%$searchKey%' or  orderPipline LIKE '%$searchKey%')";
    $this->db->where($where);
    $this->db->group_end();
    $this->db->order_by('id DESC');
    $this->db->limit(20);
    $query = $this->db->get();
    // echo $this->db->last_query();
    $result1['orders'] = $query->result_array();

    /*
     * Start Customer Search
     */


    $this->db->select('contactName,customerRef,businessName');
    $this->db->from('ws_customers');
    $this->db->group_start();
    $where = "( contactName LIKE '%$searchKey%' or  businessName LIKE '%$searchKey%' or  phoneNo1 LIKE '%$searchKey%' or  customerEmail LIKE '%$searchKey%')";
    $this->db->where($where);
    $this->db->group_end();
    $this->db->limit(20);
    $query2 = $this->db->get();
    $result2['customers'] = $query2->result_array();

    /*
     * Start Search users
     */

    $this->db->select('userName,userRef');
    $this->db->from('ws_users');
    $this->db->group_start();
    $where = "( userName LIKE '%$searchKey%' or  userEmail LIKE '%$searchKey%' or  mobileNo LIKE '%$searchKey%')";
    $this->db->where($where);
    $this->db->group_end();
    $this->db->order_by('id DESC');
    $this->db->limit(20);
    $query3 = $this->db->get();
    $result3['users'] = $query3->result_array();

    $result = array_merge($result1, $result2, $result3);

    return $result;
  }

  public function singleDelete($itemRefrense,$table,$id)
  {
    $this->db->where_in($id,$itemRefrense);
    $this->db->delete($table);
    // echo $this->db->last_query();die;
    return true;
  }
}
?>
