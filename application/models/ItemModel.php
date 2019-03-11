<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ItemModel extends CI_Model {
    /*     * ********************** Constructor ***************************** */

    public function __construct() {
        parent::__construct();
    }

    public function getTableCloumns($tableName)
    {
            $this->db->select("*");
            $this->db->from($tableName);
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

    public function getItems($limit, $start, $searchKey = null ,$statusBox = null)
    {
        /* * **************** fetching records ******************** */
        $this->db->select("*");
        $this->db->from('products');
        if($searchKey != NULL && $searchKey != '')
		    {
           $where = "( ws_products.itemName LIKE '%$searchKey%' or  ws_products.productRef LIKE '%$searchKey%' or  ws_products.transportCost LIKE '%$searchKey%' or ws_products.design LIKE '%$searchKey%' or  ws_products.color LIKE '%$searchKey%' or  ws_products.catRef LIKE '%$searchKey%')";
           $this->db->where($where);
		    }
        if( $statusBox != NULL && $statusBox != '')
		    {
			    $this->db->where('`ws_products`.`status`',$statusBox);
		    }
        $this->db->order_by('id', 'DESC');
        /** getting count **/
        $tempdb = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start,$limit);
        $query = $this->db->get();
        //  echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0)
        {
            $result = $query->result();
        }
        return array(
            'total_rows'  => $total_rows,
            'result'      => $result
        );
    }
    public function itemsByCatRef($limit, $start, $searchKey = null , $catRef = null , $postData = null)
    {

        /* * **************** fetching records ******************** */
		 $this->db->select("products.*,
		     (select price  from ws_transportCharges where ws_transportCharges.itemRefId = productRef and region_id ='".$postData['customerStateId']."' and ws_transportCharges.deliveryMethodRef ='".$postData['deliveryMethodRef']."'  LIMIT 0,1) as transportCost
        ,(select pricingMode  from ws_transportCharges where ws_transportCharges.itemRefId = productRef and region_id ='".$postData['customerStateId']."' and ws_transportCharges.deliveryMethodRef ='".$postData['deliveryMethodRef']."'  LIMIT 0,1) as pricingMode
        ,(select unitName  from ws_measurement where ws_measurement.unitRef = saleUOM  LIMIT 0,1) as saleUnitName,
        (select unitName  from ws_measurement where ws_measurement.unitRef = baseUOM  LIMIT 0,1) as baseUnitName,
        (select blockType  from ws_blockTypes where ws_blockTypes.id = ws_products.blockType  LIMIT 0,1) as blockType,
        (select categoryName  from ws_categories where ws_categories.catRef = ws_products.catRef  LIMIT 0,1) as categoryName,
        (select categoryName  from ws_categories where ws_categories.catRef = ws_products.subCat  LIMIT 0,1) as subcategoryName,
        ");
        $this->db->from('products');
        // $this->db->join('transportCharges','itemRefId=productRef','left');
        if($searchKey != NULL && $searchKey != '')
		    {
			           $where = "( ws_products.design LIKE '%$searchKey%' or  ws_products.productRef LIKE '%$searchKey%' or  ws_products.transportCost LIKE '%$searchKey%' or ws_products.design LIKE '%$searchKey%' or  ws_products.color LIKE '%$searchKey%' or  ws_products.catRef LIKE '%$searchKey%')";
			           $this->db->where($where);
		    }
        if ($catRef != NULL && $catRef != '') {
          $this->db->where('catRef',$catRef);
        }
        $this->db->where('products.status',1);

        $this->db->order_by('products.id', 'DESC');
        /** getting count **/
        $tempdb = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        // $this->db->limit($start,$limit);
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0)
        {
			$result = $query->result();
            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $result[$key]->variants = getVariantsById($value->productRef);
                }
            }
        }
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }

    public function getProductByRef($userRef, $postData = null)
    {
      /***************** fetching data by ref ******************** */
      $this->db->select("categoryName,products.*,
      (select price  from ws_transportCharges where ws_transportCharges.itemRefId = productRef and region_id ='".$postData['customerStateId']."' and ws_transportCharges.deliveryMethodRef ='".$postData['deliveryMethodRef']."'  LIMIT 0,1) as transportCharge,
      (select pricingMode  from ws_transportCharges where ws_transportCharges.itemRefId = productRef and region_id ='".$postData['customerStateId']."' and ws_transportCharges.deliveryMethodRef ='".$postData['deliveryMethodRef']."'  LIMIT 0,1) as pricingMode,
      (select unitName  from ws_measurement where ws_measurement.unitRef = saleUOM  LIMIT 0,1) as saleUnitName,
      (select unitName  from ws_measurement where ws_measurement.unitRef = baseUOM  LIMIT 0,1) as baseUnitName
      ");
      $this->db->from('products');
      $this->db->where('products.productRef',$userRef);
      $this->db->join('ws_categories','ws_categories.catRef = products.catRef','inner');
      // $this->db->where('products.isDeleted !=1');
      $this->db->order_by('id', 'ASC ');
      $query = $this->db->get();
      $result = array();
      if ($query->num_rows() > 0)
      {
		  $result = $query->row();
		  unset($result->transportCost);
          $result->variants = getVariantsById($result->productRef);
      }
      return  $result;
    }




    public function getCategories($limit, $start, $searchKey = null ,$statusBox = null)
    {
        /* * **************** fetching records ******************** */
        $this->db->select("*");
        $this->db->from('categories');
        if($searchKey != NULL && $searchKey != '')
		    {
			           $where = "( ws_categories.categoryName LIKE '%$searchKey%' or  ws_categories.categoryName LIKE '%$searchKey%' )";
			           $this->db->where($where);
		    }
        if( $statusBox != NULL && $statusBox != '')
        {
          $this->db->where('`ws_categories`.`status`',$statusBox);
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
            'result'     => $result
        );
    }

    /***************** check minimum item price *****************/
    public function getItemMinimumPrice($parms)
    {
      /***************** fetching data by ref ******************** */
      $response =  array();
      $this->db->select("minimumCost");
      $this->db->from('products');
      $this->db->where('products.productRef',$parms['itemRef']);
      $query = $this->db->get();
      $result = array();
      if ($query->num_rows() > 0)
      {
          $result = $query->row();
      }
      if (!empty($result)){
          if (trim($result->minimumCost)  !='' && $result->minimumCost <= $parms['priceChanged']) {
            $response['success'] = true;
            $response['success_message'] = 'Item is available to sold on this price';
          }else {
            $response['success'] = false;
            $response['error_message'] = 'Item price is less then minimum price manager approval needed.';
          }
      }
      else {
        $response['success'] = false;
        $response['error_message'] = 'no data found invalid product id';
      }
      // echo "<pre>";print_r($response);die;
      return $response;
    }
    /***************** check minimum item price *****************/
    public function matchItemPrice($parms)
    {
      $itemRefIds = array();
      foreach ($parms['orderItems'] as $key => $value) {
        $itemRefIds[] = $value['itemRef'];
      }

      /***************** fetching data by ref ******************** */
      $this->db->select("`categoryName,productRef`, `itemName`, `blockType`, `height`, `length`, `width`, products.`catRef`, `design`, `color`, `productionOnDemand`, `transportCost`, `itemCost`, `UOM`, `minimumCost`, products.`status`");
      $this->db->from('products');
      $this->db->where_in('products.productRef',$itemRefIds);
      $this->db->join('ws_categories','ws_categories.catRef = products.catRef','inner');
      $query = $this->db->get();
      // echo $this->db->last_query();die;
      $result = array();
      if ($query->num_rows() > 0)
      {
          $result = $query->result();
      }
      return $result;
    }


    public function exportItems()
    {
      $this->db->select(" itemName,productRef as productSKU,
      (select blockType from ws_blockTypes where ws_blockTypes.id = ws_products.blockType LIMIT 0,1 ) as blockType
      ,`blockPercentage`,
      (SELECT categoryName from ws_categories where ws_categories.catRef = ws_products.catRef limit 0,1 ) as catRef,
      (SELECT categoryName from ws_categories where ws_categories.catRef = ws_products.subCat limit 0,1 ) as subCat, `productionOnDemand`, `itemCost`,
      (SELECT unitName from ws_measurement where ws_measurement.unitRef = ws_products.saleUOM limit 0,1 ) as saleUOM
      , `saleConvQty`, `saleConvLength`,
      (SELECT unitName from ws_measurement where ws_measurement.unitRef = ws_products.baseUOM limit 0,1 ) as baseUOM
      , `baseConvQty`, `baseConvLength`, `minimumCost`, `status`, `addedOn`");
      $this->db->from('products');
      $this->db->order_by('id', 'DESC');
      $query = $this->db->get();
        // echo $this->db->last_query();die;
      $result = array();
      if ($query->num_rows() > 0)
      {
          $result = array();

          foreach ($query->result() as $key => $value) {
            $result[]  = array(
              'Item Name'  => $value->itemName,
              'Product SKU' => $value->productSKU,
              'Block Type' => $value->blockType,
              'Block Percentage' => $value->blockPercentage,
              'Category' => $value->catRef,
              'Sub Category' => $value->subCat,
              'Production on Demand' => $value->productionOnDemand == 0 ? 'No' : 'Yes',
              'Price' => $value->itemCost,
              'Minimum Price' => $value->minimumCost,
              'Sale UOM' => $value->saleUOM,
              'Sale Conv. Qty' => $value->saleConvQty,
              'Sale Conv. Length' => $value->saleConvLength,
              'Base UOM' => $value->baseUOM,
              'Base Conv. Qty' => $value->baseConvQty,
              'Sale Conv. Length' => $value->baseConvLength,
            );
          }
      }
      return  $result;

    }

    public function searchItemsbyName($postData)
    {
      $result = array();
      $this->db->select("productRef AS id,itemName as value ,transportCost as charge");
      $this->db->from('products');
      $this->db->where('products.status',1);
      $searchKey = $postData['searchKey'];
      if($searchKey != NULL && $searchKey != '')
      {
         $where = "(ws_products.design LIKE '%$searchKey%' or ws_products.color LIKE '%$searchKey%' or ws_products.itemName LIKE '%$searchKey%' )";
         $this->db->where($where);
         $this->db->order_by('ws_products.itemName', 'DESC');
         $query = $this->db->get();
         if ($query->num_rows() > 0)
         {
           $result = $query->result();
         }
      }
      return $result;
    }
}
