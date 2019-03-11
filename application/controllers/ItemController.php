<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ItemController extends CI_Controller {
  function __construct()
  {
    // Construct the parent class
    parent::__construct();
    $this->load->model('ItemModel');
    $this->load->library('csvimport');
    $this->load->model('CommonModel');
    $this->perPage    = 10;
    $loginSessionData = $this->session->userdata('clientData');
    $this->userRef    = $loginSessionData['userRef'];
    if(empty($loginSessionData) )
    {
      redirect();
    }
  }

  /*
* Function Name items
*
* to items listing.
*
*/

  public function items()
  {

      $output['title']              = 'Item Management';
      $output['breadcrumbs']        = 'Items';
      $this->make_bread->add('Home', 'home', 0);
      $this->make_bread->add('Items', '', 0);
      $breadcrumb = $this->make_bread->output();
      $output['breadcrumb'] = $breadcrumb;

      $output['categories']         = getCategories();
      $start      = 0;
      $searchKey  = '';
      $statusBox = '';
      if ($this->input->is_ajax_request())
      {
        $searchKey  = $this->input->post('searchKey');
        $statusBox = $this->input->post('statusBox');
        $page       = $this->input->post('page');
        $start      = ( $page - 1 ) * $this->perPage;
      }
      $data = $this->ItemModel->getItems($start, $this->perPage,$searchKey,$statusBox);
      $output['records']          = $data['result'];
      $output['paginationLinks']  = getPagination(site_url('items'), $this->perPage, $data['total_rows'], '', 1);
      $output['start']			      = $start;
      if ($this->input->is_ajax_request())
      {
        $response['html'] = $this->load->view('items/itemlistajax', $output, TRUE);
        echo json_encode($response);
        exit;
      }
      else
      {
        $this->load->view('commonFiles/header',$output);
        $this->load->view('items/index');
        $this->load->view('commonFiles/footer');
      }


  }


/*
*
* Function Name ajaxAddUpdateItem
*
* to update item and add item with this common function.
*
*/

public function ajaxAddUpdateItem()
{
  // pr($_POST);die;
  if ($_POST)
  {
      $this->form_validation->set_rules('itemName', 'Category Name', 'required');
      $this->form_validation->set_rules('catRef', 'Category Name', 'required');
      $this->form_validation->set_rules('productionOnDemand', 'Production On Demand ', 'required');
      $this->form_validation->set_rules('itemCost', 'item Cost', 'required');
      $this->form_validation->set_rules('minimumCost', 'Minimum Cost', 'required');
      $AddsizeVariation   = array();
      $AddcolorVariation  = array();
      $AdddesignVariation = array();
      $colorVariation     = array();
      $designVariation    = array();
      $sizeVariation      = array();
      $updateSizeVariation      = array();
      if (!$this->form_validation->run())
      {
        $errors                 = $this->form_validation->error_array();
        $response['success']    = false;
        $response['formErrors'] = true;
        $response['errors']     = $errors;
      }
      else
      {
        if ($_POST['productRef'] !='') {
          $isCatExits = $this->CommonModel->checkexist('ws_products',
              array(
                  'productRef !=' => $_POST['productRef'],
                  'catRef ='  => $_POST['catRef'],
                  'itemName'  => trim(ucwords($_POST['itemName'])),
                ));
        }else{
          $isCatExits = $this->CommonModel->checkexist('ws_products',
              array(
                'catRef ='  => $_POST['catRef'],
                'itemName'  => trim(ucwords($_POST['itemName'])),
              ));
        }
        // echo $this->db->last_query();
        // print_r($isCatExits);die;
        if ($isCatExits)
        {
            $response['success']    = false;
            $response['formErrors'] = true;
            $response['errors']     = array('itemName' => 'Oops, this item already taken, please try new one.');
            echo json_encode($response); die;
        }else
        {


          if(isset($_POST['variationRefIds']) && trim( $_POST['variationRefIds'] )  !='' )
          {
              $_POST['variationRefIds'] = rtrim($_POST['variationRefIds'] ,',');
              $itemRefrense = explode(',',$_POST['variationRefIds']);
              $deleteRecords = $this->CommonModel->singleDelete($itemRefrense,'ws_variantsSizes','id');
          }
          if(isset($_POST['variationColorIds']) && trim( $_POST['variationColorIds'] )  !='' )
          {
              $_POST['variationColorIds'] = rtrim($_POST['variationColorIds'] ,',');
              $itemRefrense = explode(',',$_POST['variationColorIds']);
              $deleteRecords = $this->CommonModel->singleDelete($itemRefrense,'ws_variantsColor','id');
          }
          if(isset($_POST['variationDesignIds']) && trim( $_POST['variationDesignIds'] )  !='' )
          {
              $_POST['variationDesignIds'] = rtrim($_POST['variationDesignIds'] ,',');
              $itemRefrense = explode(',',$_POST['variationDesignIds']);
              $deleteRecords = $this->CommonModel->singleDelete($itemRefrense,'ws_variantsDesign','id');
          }

            $_POST['itemName']  = ucwords($_POST['itemName']);
            if (trim($this->input->post('productRef')) !='')
            {
              $_POST['modifiedDate']  = date('Y-m-d');
                if (!empty($_POST['attrLength'])) {
                for ($i=0; $i < count($_POST['attrLength']); $i++) {

                     if (
                          $_POST['attrHeight'] !='' && $_POST['attrWidth'][$i] !='' &&
                          $_POST['attrLength'] !='' && $_POST['attrColor'][$i] !='' &&
                          $_POST['attrDesign'] !=''
                        ) {
                       $ws_variants[$i] = array(
                             'productId'        => ($_POST['productRef'] !='') ? $_POST['productRef'] : '',
                             'item_variant_id'  => $_POST['attrLength'][$i].'X'.$_POST['attrWidth'][$i].'X'.$_POST['attrHeight'][$i].'X'.$_POST['attrColor'][$i].'X'.$_POST['attrDesign'][$i],
                             'height'           => ($_POST['attrHeight'][$i] !='') ? $_POST['attrHeight'][$i]: '0' ,
                             'width'            => ($_POST['attrWidth'][$i] !='') ? $_POST['attrWidth'][$i]: '0' ,
                             'length'           => ($_POST['attrLength'][$i] !='') ? $_POST['attrLength'][$i]: '0' ,
                             'color'            => ($_POST['attrColor'][$i] !='') ? $_POST['attrColor'][$i]: '' ,
                             'status'           => (isset($_POST['attributeStatus'][$i])) ? 1 : 0,
                             'design'           => ($_POST['attrDesign'][$i] !='') ? $_POST['attrDesign'][$i]: '' ,
                             'price'            =>  ($_POST['attrDefaultPrice'][$i] !='') ? $_POST['attrDefaultPrice'][$i]: '0' ,
                             'minPrice'         => ($_POST['attrMinPrice'][$i] !='') ? $_POST['attrMinPrice'][$i]: '0' ,
                             'blockPercentage'  => ($_POST['attrBlockPer'][$i] !='') ? $_POST['attrBlockPer'][$i]: '0' ,
                       );
                     }

                }// end for loop
              }
                if (!empty($_POST['color'])) {
                    for ($i=0; $i < count($_POST['color']); $i++) {
                      if ($_POST['color'][$i] !='') {
                        $colorsArray[$i] = array(
                          'productId' => ($_POST['productRef'] !='') ? $_POST['productRef'] : '',
                          'productColor'    => ($_POST['color'][$i] !='') ? $_POST['color'][$i]: '' ,
                        );
                      }

                    }
                }
                if (!empty($_POST['design'])) {
                    for ($i=0; $i < count($_POST['design']); $i++) {
                      if ($_POST['design'][$i] !='') {
                        $designsArray[$i] = array(
                          'productId' => ($_POST['productRef'] !='') ? $_POST['productRef'] : '',
                          'productDesign'    => ($_POST['design'][$i] !='') ? $_POST['design'][$i]: '' ,
                        );
                      }

                    }
                }

                if (!empty($_POST['length'])) {
                    for ($i=0; $i < count($_POST['length']); $i++) {
                      if ($_POST['height'][$i] !='' && $_POST['width'][$i] !='' && $_POST['length'][$i] !='') {
                        $sizesArray[$i] = array(
                          'productId' => ($_POST['productRef'] !='') ? $_POST['productRef'] : '',
                          'height'    => ($_POST['height'][$i] !='') ? $_POST['height'][$i]: '0' ,
                          'width'     => ($_POST['width'][$i] !='') ? $_POST['width'][$i]: '0' ,
                          'length'    => ($_POST['length'][$i] !='') ? $_POST['length'][$i]: '0' ,
                        );
                      }

                    }
                }

              // pr($_POST);die;
              $postData =  array(
                'productRef'          => $_POST['productRef'],'itemName'        => $_POST['itemName'],
                'catRef'              => $_POST['catRef'],'subCat'              => $_POST['subCat'],
                'uomType'             => $_POST['uomType'],'blockType'          => $_POST['blockType'],
                'baseUOM'             => $_POST['baseUOM'],'baseConvQty'        => $_POST['baseConvQty'],
                'baseConvLength'      => $_POST['baseConvLength'],'saleUOM'     => $_POST['saleUOM'],
                'saleConvQty'         => $_POST['saleConvQty'],'saleConvLength' => (trim($_POST['saleConvLength']) !='' && $_POST['saleConvLength'] !=0 ) ? $_POST['saleConvLength'] : 1 ,
                'productionOnDemand'  => $_POST['productionOnDemand'],'blockPercentage'  => $_POST['blockPercentage'],
                'itemCost'            => $_POST['itemCost'],'minimumCost'  => $_POST['minimumCost'],
                'addedOn'             => date('Y-m-d'),'modifiedDate'  => date('Y-m-d'),'addedBy'  =>$this->userRef,
              );

              // echo "<pre>";
              // print_r($postData);
              // die;
              $responseData = $this->CommonModel->update(array('productRef' => $_POST['productRef']),  $postData, 'ws_products');
              if($responseData)
              {
                $this->CommonModel->delete($_POST['productRef'] , 'attributes' );
                if (!empty($ws_variants)) {

                  $this->CommonModel->insert_batch('ws_variants', $ws_variants);
                }
                if(!empty($designsArray))
                   $this->CommonModel->insert_batch('ws_variantsDesign', $designsArray);

                if(!empty($colorsArray))
                   $this->CommonModel->insert_batch('ws_variantsColor', $colorsArray);

                if(!empty($sizesArray))
                   $this->CommonModel->insert_batch('ws_variantsSizes', $sizesArray);

                // pr($sizesArray);die;
                $response['success'] = true;
                $response['success_message'] = 'Item Updated successfully';
              }else{
                $response['success'] = true;
                $response['success_message'] = 'Item already updated.';
              }
            }
            else
            {
             $_POST['status']        = 1;
             $_POST['addedOn']       = date('Y-m-d');
             $_POST['modifiedDate']  = date('Y-m-d');
             $_POST['addedBy']       = $this->userRef;
             // $_POST['productRef']        = generateRef();
             $_POST['productRef']        = generateSKU();

             if (!empty($_POST['color'])) {
                 for ($i=0; $i < count($_POST['color']); $i++) {
                   if ($_POST['color'][$i] !='') {
                     $colorsArray[$i] = array(
                       'productId' => ($_POST['productRef'] !='') ? $_POST['productRef'] : '',
                       'productColor'    => ($_POST['color'][$i] !='') ? $_POST['color'][$i]: '' ,
                     );
                   }

                 }
             }
             if (!empty($_POST['design'])) {
                 for ($i=0; $i < count($_POST['design']); $i++) {
                   if ($_POST['design'][$i] !='') {
                     $designsArray[$i] = array(
                       'productId' => ($_POST['productRef'] !='') ? $_POST['productRef'] : '',
                       'productDesign'    => ($_POST['design'][$i] !='') ? $_POST['design'][$i]: '' ,
                     );
                   }

                 }
             }

             if (!empty($_POST['length'])) {
                 for ($i=0; $i < count($_POST['length']); $i++) {
                   if ($_POST['height'][$i] !='' && $_POST['width'][$i] !='' && $_POST['length'][$i] !='' ) {
                     $sizesArray[$i] = array(
                       'productId' => ($_POST['productRef'] !='') ? $_POST['productRef'] : '',
                       'height'    => ($_POST['height'][$i] !='') ? $_POST['height'][$i]: '0' ,
                       'width'     => ($_POST['width'][$i] !='') ? $_POST['width'][$i]: '0' ,
                       'length'    => ($_POST['length'][$i] !='') ? $_POST['length'][$i]: '0' ,
                     );
                   }

                 }
             }

             if (!empty($_POST['attrLength'])) {
                 for ($i=0; $i < count($_POST['attrLength']); $i++) {

                    if ($_POST['attrHeight'][$i] !='' && $_POST['attrWidth'][$i] !='' && $_POST['attrLength'][$i] !='' && $_POST['attrColor'][$i] !='' && $_POST['attrDesign'][$i] !='') {
                      $variationArray[$i] = array(
                        'productId'        => ($_POST['productRef'] !='') ? $_POST['productRef'] : '',
                        'item_variant_id'  => $_POST['attrLength'][$i].'X'.$_POST['attrWidth'][$i].'X'.$_POST['attrHeight'][$i].'X'.$_POST['attrColor'][$i].'X'.$_POST['attrDesign'][$i],
                        'height'           => ($_POST['attrHeight'][$i] !='') ? $_POST['attrHeight'][$i]: '0' ,
                        'width'            => ($_POST['attrWidth'][$i] !='') ? $_POST['attrWidth'][$i]: '0' ,
                        'length'           => ($_POST['attrLength'][$i] !='') ? $_POST['attrLength'][$i]: '0' ,
                        'color'            => ($_POST['attrColor'][$i] !='') ? $_POST['attrColor'][$i]: '' ,
                        'status'           =>(isset($_POST['attributeStatus'][$i])) ? 1 : 0,
                        'design'           => ($_POST['attrDesign'][$i] !='') ? $_POST['attrDesign'][$i]: '' ,
                        'price'            => ($_POST['attrDefaultPrice'][$i] !='') ? $_POST['attrDefaultPrice'][$i]: '0' ,
                        'minPrice'         => ($_POST['attrMinPrice'][$i] !='') ? $_POST['attrMinPrice'][$i]: '0' ,
                        'blockPercentage'  => ($_POST['attrBlockPer'][$i] !='') ? $_POST['attrBlockPer'][$i]: '0' ,
                      );
                   }

                 }
             }


             $postData =  array(
               'productRef'          => $_POST['productRef'],'itemName'        => $_POST['itemName'],
               'catRef'              => $_POST['catRef'],'subCat'              => $_POST['subCat'],
               'uomType'             => $_POST['uomType'],'blockType'          => $_POST['blockType'],
               'baseUOM'             => $_POST['baseUOM'],'baseConvQty'        => $_POST['baseConvQty'],
               'baseConvLength'      => $_POST['baseConvLength'],'saleUOM'     => $_POST['saleUOM'],
               'saleConvQty'         => $_POST['saleConvQty'],'saleConvLength' => (trim($_POST['saleConvLength']) !='' && $_POST['saleConvLength'] !=0 ) ? $_POST['saleConvLength'] : 1 ,
               'productionOnDemand'  => $_POST['productionOnDemand'],'blockPercentage'  => $_POST['blockPercentage'],
               'itemCost'            => $_POST['itemCost'],'minimumCost'  => $_POST['minimumCost'],'status'  =>1,
               'addedOn'             => date('Y-m-d'),'modifiedDate'  => date('Y-m-d'),'addedBy'  =>$this->userRef,

             );
             $responseData = $this->CommonModel->insert('ws_products', $postData);
             if($responseData)
             {
               if(!empty($variationArray))
                  $this->CommonModel->insert_batch('ws_variants', $variationArray);

               if(!empty($designsArray))
                  $this->CommonModel->insert_batch('ws_variantsDesign', $designsArray);

               if(!empty($colorsArray))
                  $this->CommonModel->insert_batch('ws_variantsColor', $colorsArray);

               if(!empty($sizesArray))
                  $this->CommonModel->insert_batch('ws_variantsSizes', $sizesArray);

               $response['success'] = true;
              //  $response['reload'] = true;
               $response['newItem']   = true;
               $response['success_message'] = 'Item Added successfully';
             }else{
               $response['success'] = false;
               $response['error_message'] = 'Something went wrong please try again.';
             }
         }
        }
      }
      if ($response['success'] == true) {
          $response['itemData']   = true;
          $response['modelhide']  = 'AddItem';
          $_POST['catRef']        = getCategoryByRef($_POST['catRef']);
          $_POST['blockType']        = getblockType($_POST['blockType']);
          $response['data']       = $_POST;


      }
      echo json_encode($response); exit;
  }
}


/*

*function name getItemDetails

*get item details by ref if

*/
 public function getItemDetails()
 {
      if($_POST){
          $response = getItemByRef($_POST['productRef']);
          if(!empty($response)){
            $output['success'] = true;
            $data['record']     = $response;
            $output['html'] = $this->load->view('items/updateItem', $data, TRUE);
            $output['data'] = $response;
          }else{
            $output['success'] = false;
          }
      }else{
        $output['success'] = false;
      }
      echo json_encode($output);die;
  }


/*

*function name categories

* to categories listing

*/


 public function categories()
 {
   $output['title']             = 'Categories Management';
   $output['breadcrumbs']       = 'Categories';
   $output['categories']         = getCategories();

   $this->make_bread->add('Home', 'home', 0);
   $this->make_bread->add('Categories', 'categories', 0);
   $breadcrumb = $this->make_bread->output();
   $output['breadcrumb']           = $breadcrumb;


   $start      = 0;
   $searchKey  = '';
   $statusBox  = '';
   if ($this->input->is_ajax_request())
   {
     $searchKey  = $this->input->post('searchKey');
     $page       = $this->input->post('page');
       $statusBox = $this->input->post('statusBox');
     $start      = ( $page - 1 ) * $this->perPage;
   }
   $data = $this->ItemModel->getCategories($start, $this->perPage,$searchKey,$statusBox);
   $output['records']          = $data['result'];
   $output['paginationLinks']  = getPagination(site_url('categories'), $this->perPage, $data['total_rows'], '', 1);
   $output['start']			      = $start;
   if ($this->input->is_ajax_request())
   {
     $response['html'] = $this->load->view('categories/categorieslistajax', $output, TRUE);
     echo json_encode($response);
     exit;
   }
   else
   {
     $this->load->view('commonFiles/header',$output);
     $this->load->view('categories/index');
     $this->load->view('commonFiles/footer');
   }
 }


 /*
  * Function name ajaxAddUpdateCategory
  *
  * To Add Update a category
  *
  */


   public function ajaxAddUpdateCategory()
   {

     if ($_POST)
     {

        if (isset($_POST['parentCatRef']) && trim($_POST['parentCatRef']) !='' ) {
          $this->form_validation->set_rules('categoryName', 'Category Name', 'required');
          $this->form_validation->set_rules('parentCatRef', 'Parent Category Name', 'required');
          if (!$this->form_validation->run())
          {
            $errors                 = $this->form_validation->error_array();
            $response['success']    = false;
            $response['formErrors'] = true;
            $response['errors']     = $errors;
          }
          else
          {
            $frontEnd = false;
            if (isset($_POST['frontEnd'])) {
              unset($_POST['frontEnd']);
              $frontEnd = true;
            }
            if (isset($_POST['catRef']) && trim($_POST['catRef']) !='') {
              $isCatExits = $this->CommonModel->checkexist('categories', array('catRef !=' => $_POST['catRef'],'parentCatRef' => $_POST['parentCatRef'], 'categoryName' => trim(ucwords($_POST['categoryName']))));
           }else {
             $isCatExits = $this->CommonModel->checkexist('categories', array('parentCatRef ' => $_POST['parentCatRef'], 'categoryName' => trim(ucwords($_POST['categoryName']))));
           }
            if ($isCatExits)
            {
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = array('categoryName' => 'Oops, Category name already taken.');
                echo json_encode($response); die;
            }else
            {
                 $_POST['categoryName']  = ucwords($_POST['categoryName']);
                if (trim($this->input->post('catRef')) !='')
                {
                  $_POST['modifiedDate']  = date('Y-m-d');
                  $responseData = $this->CommonModel->update(array('catRef' => $_POST['catRef']),  $_POST, 'categories');
                  if ($frontEnd) {
                    $response['updateSubCatFront']  = true;
                    $response['data']               = $_POST;
                  }else{
                    $response['updateSubCat']   = true;
                    $response['data']           = $_POST;
                  }

                  if($responseData)
                  {
                    $response['success'] = true;
                    $response['success_message'] = 'Category Updated successfully';
                  }else{
                    $response['success'] = true;
                    $response['success_message'] = 'Category already updated.';

                  }
                }
                else
                {
                 $_POST['status']        = 1;
                 $_POST['addedOn']       = date('Y-m-d');
                 $_POST['modifiedDate']  = date('Y-m-d');
                 $_POST['addedBy']       = $this->userRef;
                 $_POST['catRef']        = generateRef();
                 $_POST['parentCatRef']  = $_POST['parentCatRef'];
                 $responseData = $this->CommonModel->insert('categories', $_POST);
                 if($responseData)
                 {
                   $response['success']     = true;

                   if ($frontEnd) {
                     $response['addSubCatFront']     =  true;
                   }else{
                     $response['newSubCat']   = true;
                     $response['reload'] = true;
                   }
                   $response['data']           = $_POST;
                   $response['success_message'] = 'Sub Category Added successfully';
                   $response['delayTime'] = 1000;
                 }else{
                   $response['success'] = false;
                   $response['error_message'] = 'Something went wrong please try again.';
                 }
             }
            }
          }
          if ($response['success'] == true) {
              $response['categoryData']   = true;
              $response['modelhide']      = 'add-Category-modal';
              $response['data']           = $_POST;
          }
        }
        else
        {
          $this->form_validation->set_rules('categoryName', 'Category Name', 'required');
          if (!$this->form_validation->run())
          {
            $errors                 = $this->form_validation->error_array();
            $response['success']    = false;
            $response['formErrors'] = true;
            $response['errors']     = $errors;
          }
          else
          {
            if ($_POST['catRef'] !='') {
              $isCatExits = $this->CommonModel->checkexist('categories', array('catRef !=' => $_POST['catRef'], 'categoryName' => trim(ucwords($_POST['categoryName']))));
            }else{
              $isCatExits = $this->CommonModel->checkexist('categories', array('categoryName' => trim(ucwords($_POST['categoryName']))));
            }

            $frontEnd = false;
            if (isset($_POST['frontEnd'])) {
              unset($_POST['frontEnd']);
              $frontEnd = true;
            }

            if ($isCatExits)
            {
                $response['success']    = false;
                $response['formErrors'] = true;
                $response['errors']     = array('categoryName' => 'Oops, Category name already taken.');
                echo json_encode($response); die;
            }else
            {
                 $_POST['categoryName']  = ucwords($_POST['categoryName']);
                if (trim($this->input->post('catRef')) !='')
                {
                  $_POST['modifiedDate']  = date('Y-m-d');
                  $responseData = $this->CommonModel->update(array('catRef' => $_POST['catRef']),  $_POST, 'categories');
                  if($responseData)
                  {
                    $response['success'] = true;
                    $response['success_message'] = 'Category Updated successfully';
                  }else{
                    $response['success'] = true;
                    $response['success_message'] = 'Category already updated.';
                  }
                }
                else
                {
                 $_POST['status']        = 1;
                 $_POST['addedOn']       = date('Y-m-d');
                 $_POST['modifiedDate']  = date('Y-m-d');
                 $_POST['addedBy']       = $this->userRef;
                 $_POST['catRef']        = generateRef();
                 $responseData = $this->CommonModel->insert('categories', $_POST);
                 if($responseData)
                 {
                   $response['success'] = true;
                   // $response['reload'] = true;
                   if (!$frontEnd) {
                     $response['newCat']   = true;
                   }

                   $response['success_message'] = 'Category Added successfully';
                 }else{
                   $response['success'] = false;
                   $response['error_message'] = 'Something went wrong please try again.';
                 }
             }
            }
          }
          if ($response['success'] == true) {
              if ($frontEnd) {
                  $response['categoryDataFront']  = true;
              }else{
                  $response['categoryData']  = true;
              }

              $response['modelhide']  = 'AddCategory';
              $response['data']     = $_POST;
          }
        }
         echo json_encode($response); die;
     }
   }

  /*
  * Function name getSubCategories
  *
  * To Search sub category using parent cat ref id
  *
  */

   public function getSubCategories()
   {
     if ($_POST) {
          $categories = getSubCategories($_POST['parentCatRef']);
          $option = "<option> Select Sub Category</option><option>Add New Category</option>";
          if (!empty($categories)) {
              foreach ($categories as $key => $value) {
                $option .='<option value="'.$value->catRef.'">'.$value->categoryName.'</option>';
              }
          } else {
            $option .='<option value="">No Category found</option>';
          }
          echo $option;
     }
   }


 /*
  * Function name exportCSV
  *
  * To export items into CSV file
  *
  */


   // Export data in CSV format
  public function exportCSV(){
   $filename = 'items_'.date('Ymd').'.csv';
   $usersData =  $this->ItemModel->exportItems();
   exportData($usersData,$filename);
  }


 /*
  * Function name importItems
  *
  * To import items from CSV file
  *
  */

  public function importItems()
  {
    $blockType = array('Recon' => 1,'​​Comfort' => 2);
    $file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
    // echo "<pre>";print_r($file_data);die;
    $i = 0;
    foreach($file_data as $row)
    {
       $_POST[] = $row;
       $_POST[$i]['addedOn']             = date('Y-m-d');
       $_POST[$i]['status']              = 1;
       $_POST[$i]['modifiedDate']        = date('Y-m-d');
       $_POST[$i]['addedBy']             = $this->userRef;
       $_POST[$i]['productRef']          = generateRef();
       $_POST[$i]['catRef']              = getParentCategoryByName($row['catRef']);
       $_POST[$i]['subCat']              = getSubCategoryByName($row['subCat']);
       $_POST[$i]['UOM']                 = getUOMbyName($row['UOM']);
       $_POST[$i]['blockType']           = $row['blockType'];
       $_POST[$i]['blockPercentage']     = $row['blockPercentage'];
       $_POST[$i]['productRef']          = $row['productSKU'];
       $i++;
    }
    foreach ($_POST as $key => $value) {
      $isCatExits = $this->CommonModel->checkexist('ws_products', array('catRef =' => $value['catRef'], 'itemName' => trim(ucwords($value['itemName']))));
      // echo $this->db->last_query();
      // print_r($isCatExits);die;
      if ($isCatExits)
      {
          $response['success']    = false;
          $response['formErrors'] = true;
          $response['errors']     = array('itemName' => 'Oops, Item name already taken, please try new one.');
      }
      else
      {
           $value['itemName']  = ucwords($value['itemName']);
           $responseData      = $this->CommonModel->insert('ws_products', $value);
           if($responseData)
           {
             $response['success'] = true;
             $response['newItem']   = true;
             $response['success_message'] = 'Item Added successfully';
           }
           else
           {
             $response['success'] = false;
             $response['error_message'] = 'Something went wrong please try again.';
           }
       }
      }
      echo json_encode($response);die;
    }
    /*
    Function name searchItemsbyName
    work :- search items by names
    */
    public function searchItemsbyName()
    {
      if ($_GET) {
        $response = $this->ItemModel->searchItemsbyName($_GET);
        echo json_encode($response);die;
      }
    }

    /*
    Function name getAddItem
    work :- search items by names
    */

    public function getAddItem()
    {
        $dara['categories']         = getCategories();
        $output['html']             =  $this->load->view('items/getAddItem',$dara,TRUE);
        echo json_encode($output);die;
    }


}
