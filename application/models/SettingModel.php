<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class SettingModel extends CI_Model {
    /*     * ********************** Constructor ***************************** */

    public function __construct() {
        parent::__construct();
    }


    public function getDeliveryMethod($limit, $start, $searchKey = null ,$statusBox = null)
    {

        /* * **************** fetching records ******************** */
        $this->db->select("*");
        $this->db->from('ws_deliveryMethod');
        if($searchKey != NULL && $searchKey != '')
		    {
			           $where = "( ws_deliveryMethod.methodName LIKE '%$searchKey%' or  ws_deliveryMethod.area LIKE '%$searchKey%' )";
			           $this->db->where($where);
		    }

        if( $statusBox != NULL && $statusBox != '')
		    {
			    $this->db->where('`ws_deliveryMethod`.`status`',$statusBox);
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
            'total_rows' => $total_rows,
            'result' => $result
        );
    }

    public function getPricingMethod($limit, $start, $searchKey = null ,$statusBox = null)
    {

        /* * **************** fetching records ******************** */
        $this->db->select("*");
        $this->db->from('ws_pricingMethod');
        if($searchKey != NULL && $searchKey != '')
		    {
			           $where = "( ws_pricingMethod.payementMethod LIKE '%$searchKey%')";
			           $this->db->where($where);
		    }
        if( $statusBox != NULL && $statusBox != '')
        {
          $this->db->where('`ws_pricingMethod`.`status`',$statusBox);
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
            'total_rows' => $total_rows,
            'result' => $result
        );
    }

    public function fetchMesurementList($limit, $start, $searchKey = null,$statusBox = null )
    {
        /* * **************** fetching records ******************** */
        $this->db->select("*");
        $this->db->from('measurement');
        if($searchKey != NULL && $searchKey != '')
        {

            $where = "( measurement.unitName LIKE '%$searchKey%' )";
            $this->db->where($where);
        }
        if( $statusBox != NULL && $statusBox != '')
        {
          $this->db->where('measurement.status',$statusBox);
        }
        $this->db->order_by('id', 'DESC');
        /** getting count **/
        $tempdb = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start,$limit);
        $query = $this->db->get();
        #echo $this->db->last_query();die;
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
    public function fetchAllRegions($limit, $start, $searchKey = null,$statusBox = null )
    {

        /* * **************** fetching records ******************** */
        $this->db->select("*");
        $this->db->from('ws_regions');
        if($searchKey != NULL && $searchKey != '')
        {

            $where = "( ws_regions.name LIKE '%$searchKey%' )";
            $this->db->where($where);
        }
        if( $statusBox != NULL && $statusBox != '')
		    {
			    $this->db->where('`ws_regions`.`status`',$statusBox);
		    }
        $this->db->where('country_id', 49);
        $this->db->order_by('id', 'DESC');
        /** getting count **/
        $tempdb = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start,$limit);
        $query = $this->db->get();
        // echo $this->db->last_query();die;
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
    public function fetchAllCitiesById($limit, $start, $searchKey = null ,$cityId =null,$statusBox = null )
    {
      // echo "$limit";die;
        /* * **************** fetching records ******************** */
        $this->db->select("*");
        $this->db->from('ws_cities');
        if($searchKey != NULL && $searchKey != '')
        {

            $where = "( ws_cities.name LIKE '%$searchKey%' )";
            $this->db->where($where);
        }
        if( $statusBox != NULL && $statusBox != '')
		    {
			    $this->db->where('`ws_cities`.`status`',$statusBox);
		    }
        $this->db->where('con_id', 49);
        $this->db->where('sta_id', $cityId);
        $this->db->order_by('id', 'DESC');
        /** getting count **/
        $tempdb = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start,$limit);
        $query = $this->db->get();
        // echo $this->db->last_query();die;
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
    public function fetchAllTranportCharges($limit, $start, $searchKey = null ,$cityId =null)
    {
      // echo "$limit";die;
        /* * **************** fetching records ******************** */
        $this->db->select("transportRef,itemName, name as region,itemRefId, methodName as deliveryMethod, pricingMode,transportCharges.status,price");
        $this->db->from('ws_transportCharges');
        if($searchKey != NULL && $searchKey != '')
        {
            $where = "( ws_deliveryMethod.methodName LIKE '%$searchKey%'  OR products.itemName LIKE '%$searchKey%'  )";
            $this->db->where($where);
        }
        $this->db->join('products','productRef = itemRefId' ,'inner');
        $this->db->join('regions','regions.id = region_id' ,'inner');
        $this->db->join('ws_deliveryMethod','ws_deliveryMethod.deliveryMethodRef = transportCharges.deliveryMethodRef' ,'inner');
        $this->db->group_by('itemName');
        $this->db->order_by('transportCharges.id', 'DESC');
        /** getting count **/
        $tempdb = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start,$limit);
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        $result = array();
        if ($query->num_rows() > 0)
        {
            $result = $query->result();
        }

        // print_r($result);exit();
        return array(
            'total_rows' => $total_rows,
            'result' => $result
        );
    }

    public function getTableCloumns()
    {
            $this->db->select("*");
            $this->db->from('ws_transportCharges');
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

    public function getTransportData()
    {
      $this->db->select("itemName ,productRef as productSKU,ws_transportCharges.price as Value,methodName as transportMethod, name as region,pricingMode");
      $this->db->from('ws_transportCharges');
      $this->db->join('ws_deliveryMethod','`ws_deliveryMethod`.`deliveryMethodRef` = ws_transportCharges.deliveryMethodRef','inner');
      $this->db->join('ws_regions','`ws_regions`.`id` = region_id','inner');
      $this->db->join('products','productRef = itemRefId','inner');
      $this->db->order_by('ws_transportCharges.id', 'DESC');
      $query = $this->db->get();
         // echo $this->db->last_query();die;
      $result = array();
      if ($query->num_rows() > 0)
      {
          $result = $query->result_array();
      }
      return  $result;
    }

    public function fetchAllLoadingSheets($limit, $start, $searchKey = null ,$statusBox = null)
    {

        /* * **************** fetching records ******************** */
        $this->db->select("*");
        $this->db->from('ws_loadingSheets');
        if($searchKey != NULL && $searchKey != '')
		    {
           $where = "( ws_loadingSheets.refName LIKE '%$searchKey%' )";
           $this->db->where($where);
		    }
        if( $statusBox != NULL && $statusBox != '')
		    {
			    $this->db->where('`ws_loadingSheets`.`status`',$statusBox);
		    }
        $this->db->order_by('id', 'DESC');
        /** getting count **/
        $tempdb = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start,$limit);
        $query = $this->db->get();
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

    public function blockTypes($limit, $start, $searchKey = null,$statusBox = null )
    {

        /* * **************** fetching records ******************** */
        $this->db->select("*");
        $this->db->from('blockTypes');
        if($searchKey != NULL && $searchKey != '')
		    {
			           $where = "( blockTypes.blockType LIKE '%$searchKey%' or  blockTypes.blockType LIKE '%$searchKey%' )";
			           $this->db->where($where);
		    }
        if( $statusBox != NULL && $statusBox != '')
		    {
			    $this->db->where('blockTypes.`status`',$statusBox);
		    }
        $this->db->order_by('id', 'DESC');
        /** getting count **/
        $tempdb = clone $this->db;
        $total_rows = $tempdb->count_all_results();
        $this->db->limit($start,$limit);
        $query = $this->db->get();
        $result = array();
        if ($query->num_rows() > 0)
        {
            $result = $query->result();
        }
        return array('total_rows' => $total_rows,'result' => $result);
    }
}
