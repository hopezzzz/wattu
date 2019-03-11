<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function phoneFormat($phoneNo = NULL) //return $phoneNo;
{
    $phoneNo = str_replace('-', '', $phoneNo);
    $phoneNo = str_replace(' ', '', $phoneNo);
    $len     = strlen($phoneNo);
    if ($len > 10) {
        $first  = substr($phoneNo, 0, 1);
        $second = substr($phoneNo, 1, 2);
        $third  = substr($phoneNo, 3, 2);
        $fourth = substr($phoneNo, 5, 4);
        $fifth  = substr($phoneNo, 9, 4);
        $final  = $first;
        if ($second)
            $final .= '-' . $second;
        if ($third)
            $final .= '-' . $third;
        if ($fourth)
            $final .= '-' . $fourth;
        if ($fifth)
            $final .= '-' . $fifth;
    } //$len > 10
    else {
        $first  = substr($phoneNo, 0, 3);
        $second = substr($phoneNo, 3, 3);
        $third  = substr($phoneNo, 6, 4);
        $final  = $first;
        if ($second)
            $final .= '-' . $second;
        if ($third)
            $final .= '-' . $third;
    }
    //echo $final;
    return $final;
}
function getEmailTemplate($id = NULL)
{
    $ci =& get_instance();
    $ci->db->select('*');
    $ci->db->where('id', $id);
    $query  = $ci->db->get('emailTemplates');
    $result = array();
    if ($query->num_rows() > 0) {
        $result = $query->row_array();
    } //$query->num_rows() > 0
    return $result;
}

function checkEmailExist($id = NULL)
{
    $ci =& get_instance();
    $ci->db->select('*');
    $ci->db->where('userEmail', $id);
    $query  = $ci->db->get('users');
    $result = array();
    if ($query->num_rows() > 0) {
        $result = $query->row_array();
    } //$query->num_rows() > 0
    return $result;
}

function sendEmail($variables, $templateData)
{
    $ci =& get_instance();
    // 'logo' => '<img src="' . $ci->config->item('logo') . '" alt="Logo" >',

    $ci->email_var    = array(
        'logo' => '<img src="'.base_url().'/assets/img/logo.png" alt="Logo" >',
        'site_title' => 'Watervale',
        'site_url' => site_url(),
        'copyrightText' => $ci->config->item('copyrightText')
    );
    // $ci->config_email = Array(
    //   'protocol' => "ssl",
    //   'smtp_host' => "mail.cashmann.co.uk",
    //   'smtp_port' => '25',
    //   'smtp_user' => 'wateval@cashmann.co.uk',
    //   'smtp_pass' => 'admin786',
    //   'mailtype' => "html",
    //   'wordwrap' => TRUE,
    //   'crlf' => '\r\n',
    //   'charset' => "utf-8"
    // );
    $ci->config_email = Array(
        'protocol' => "smtp",
        'smtp_host' => "ssl://smtp.googlemail.com",
        'smtp_port' => '465',
        'smtp_user' => 'ranjan.sns29@gmail.com',
        'smtp_pass' => '9459849310',
        'mailtype' => "html",
        'wordwrap' => TRUE,
        'crlf' => '\r\n',
        'charset' => "utf-8"
    );
    $variables        = array_merge($variables, $ci->email_var);
    // echo '<pre>'; print_r($variables); die;
    $replacements     = array();
    foreach ($variables as $key => $val) {
        $replacements['({' . $key . '})'] = $val;
    } //$variables as $key => $val


    $template = preg_replace(array_keys($replacements), array_values($replacements), $templateData['description']);
    //echo '<pre>'; print_r($template); die;
    $ci->email->initialize($ci->config_email);
    $ci->email->set_newline("\r\n");
    $ci->email->from('noreply@docpoke.in', 'Watervale');
    $ci->email->to($variables['to']);
    $ci->email->subject($templateData['subject']);
    $ci->email->message($template);
    if ($ci->config->item('replyTo'))
        $ci->email->reply_to($ci->config->item('replyTo'));
    // echo "<pre>";print_r($ci->email);die('lol');
    $ci->email->send();
    $ci->email->print_debugger(); //die;
    return true;
}
function getPagination($url, $perPage, $totalItem = 0, $type, $number, $segment = null)
{
    $ci =& get_instance();
    /* Create Pagination links */
    //pagination settings
    $config['base_url']   = $url;
    $config['total_rows'] = $totalItem;
    $config['per_page']   = $perPage;
    if ($segment != null || $segment != '') {
        $config["uri_segment"]   = $segment;
        $config['full_tag_open'] = '<ul class="pagination ajax_pagingsearc4Html" id="ajax_pagingsearc4Html' . $number . '" rel="' . $type . '">';
    } //$segment != null || $segment != ''
    else {
        $config["uri_segment"]   = 3;
        $config['full_tag_open'] = '<ul class="pagination ajax_pagingUL" id="ajax_pagingsearc' . $number . '" rel="' . $type . '">';
    }
    $config['page_query_string'] = TRUE;
    $choice                      = $config["total_rows"] / $config["per_page"];
    //$config["num_links"] = floor($choice);
    $config["num_links"]         = 2;
    //config for bootstrap pagination class integration
    $config['full_tag_close']    = '</ul>';
    $config['first_link']        = false;
    $config['last_link']         = false;
    $config['first_tag_open']    = '<li>';
    $config['first_tag_close']   = '</li>';
    $config['prev_link']         = '&laquo';
    $config['prev_tag_open']     = '<li class="prev">';
    $config['prev_tag_close']    = '</li>';
    $config['next_link']         = '&raquo';
    $config['next_tag_open']     = '<li>';
    $config['next_tag_close']    = '</li>';
    $config['last_tag_open']     = '<li>';
    $config['last_tag_close']    = '</li>';
    $config['cur_tag_open']      = '<li class="active"><a href="#">';
    $config['cur_tag_close']     = '</a></li>';
    $config['num_tag_open']      = '<li>';
    $config['num_tag_close']     = '</li>';
    $ci->pagination->initialize($config);
    return $ci->pagination->create_links();
}
function demoCredentials($projectName = null, $username = null, $password = null, $projectUrl = null, $user_role = null)
{
    $ci =& get_instance();
    $config['hostname'] = '166.62.28.127';
    $config['username'] = 'democredentials';
    $config['password'] = 'democredentials';
    $config['database'] = 'democredentials';
    $config['dbdriver'] = 'mysqli';
    $config['dbprefix'] = '';
    $config['pconnect'] = FALSE;
    $config['db_debug'] = TRUE;
    $config['cache_on'] = FALSE;
    $config['cachedir'] = '';
    $config['char_set'] = 'utf8';
    $config['dbcollat'] = 'utf8_general_ci';
    $credentialsDB      = $ci->load->database($config, TRUE);
    $credentialsDB->select('*');
    $credentialsDB->where('project_name', $projectName);
    $credentialsDB->where('username', $username);
    $query = $credentialsDB->get('credentials');
    if ($query->num_rows() > 0) {
        $result = $query->row();
        $id     = $result->id;
        $credentialsDB->set('password', $password);
        $credentialsDB->set('modified_date', date('Y-m-d'));
        $credentialsDB->set('project_url', $projectUrl);
        $credentialsDB->set('user_role', $user_role);
        $credentialsDB->where('id', $id);
        $credentialsDB->update('credentials');
        return true;
    } //$query->num_rows() > 0
    else {
        $credentialsDB->set('project_name', $projectName);
        $credentialsDB->set('username', $username);
        $credentialsDB->set('password', $password);
        $credentialsDB->set('project_url', $projectUrl);
        $credentialsDB->set('user_role', $user_role);
        $credentialsDB->set('add_date', date('Y-m-d'));
        $credentialsDB->set('modified_date', date('Y-m-d'));
        $credentialsDB->insert('credentials');
        return true;
    }
    //echo "<pre>";print_r($result);
}
function generateRef()
{
    $length        = 8;
    $randomString  = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    $randomString1 = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    $randomString  = $randomString . $randomString1;
    return $randomString;
}
function randomPassword()
{
    $alphabet    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass        = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n      = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    } //$i = 0; $i < 8; $i++
    return implode($pass); //turn the array into a string
}
function generateOrderNumber()
{
    $ci =& get_instance();
    $ci->db->select('orderNo');
    $ci->db->order_by('orderNo', 'desc');
    $query   = $ci->db->get('ws_orders');
    $orderNo = '';
    if ($query->num_rows() > 0) {
        $result     = $query->result();
        $docnumbers = array();
        foreach ($result as $key => $val) {
            $result = 0;
            // $result += substr($val->orderNo, 4, strlen($val->orderNo));
            if (is_numeric($result))
                $docnumbers[] = $val->orderNo;
        } //$result as $key => $val
        $maxDocNo = max($docnumbers);
        // echo "string => $maxDocNo";
        $orderNo  = $maxDocNo;
        $len      = strlen($orderNo);
        $orderNo  = substr($orderNo, 0, $len);
        // echo "sss$orderNo";
        $orderNo  = str_pad($orderNo + 1, 5, 0, STR_PAD_LEFT);
        $orderNo  = $orderNo;
    } //$query->num_rows() > 0
    else {
        $orderNo = '00001';
    }
    return $orderNo;
}
function getCategories()
{
    $ci =& get_instance();
    $ci->db->select("catRef,categoryName");
    $ci->db->from("categories");
    //$ci->db->where("status", 1);
    $ci->db->group_start();
    $ci->db->where('parentCatRef IS NULL', null, false);
    $ci->db->or_where('parentCatRef = "" ');
    $ci->db->group_end();
    $query  = $ci->db->get();
    $result = $query->result();
    return $result;
}
function getCategoryByRef($ref)
{
    $ci =& get_instance();
    $ci->db->select("categoryName");
    $ci->db->from("categories");
    $ci->db->where("catRef", $ref);
    $query  = $ci->db->get();
    $result = $query->row();
    return $result->categoryName;
}
function getItemByRef($ref)
{
    $ci =& get_instance();
    $ci->db->select("*");
    $ci->db->from("ws_products");
    $ci->db->where("productRef", $ref);
    $query  = $ci->db->get();
    $result = $query->row();

    $ci->db->select("*");
    $ci->db->from("ws_variants");
    $ci->db->where("productId", $result->productRef);
    $ci->db->order_by("id", 'DESC');
    $query  = $ci->db->get();
    $variantsSize = $query->result();



    return  array(
                    'itemDetails' => $result, getProductAttributs($result->productRef),
                  ); ;
}
function numberFormat($number)
{
    if (empty($number)) {
        return '';
    } //empty($number)
    else {
        $number = str_replace('-', '', $number);
        return number_format($number, 2, '.', ',');
    }
}
function getCountries($countryName = null, $coutryId = null)
{
    $ci =& get_instance();
    $ci->db->select('name as countryName,id as countryId');
    if ($countryName != null && trim($countryName) != '') {
        $where = "( name LIKE '%$countryName%')";
        $ci->db->where($where);
    } //$countryName != null && trim($countryName) != ''
    if ($coutryId != null && trim($coutryId) != '') {
        $ci->db->where('id', $coutryId);
    } //$coutryId != null && trim($coutryId) != ''
    $ci->db->order_by('name', 'asc');
    $query    = $ci->db->get('countries');
    $result   = $query->result();
    $db_error = $ci->db->error();
    if ($db_error['code'] != 0) {
        $response['success']       = false;
        $response['error_message'] = $db_error['message'];
    } //$db_error['code'] != 0
    else {
        $response['success']         = true;
        $response['success_message'] = 'success';
        $response['result']          = $result;
    }
    return $response;
}
function getRegionsByCountryId($country_id = NULL)
{
    $ci =& get_instance();
    $ci->db->select('name as stateName,id as stateId');
    $ci->db->where('country_id', $country_id);
    $ci->db->where('status', 1);
    $ci->db->order_by('name', 'asc');
    $query    = $ci->db->get('ws_regions');
    $result   = $query->result();
    $db_error = $ci->db->error();
    if ($db_error['code'] != 0) {
        $response['success']       = false;
        $response['error_message'] = $db_error['message'];
    } //$db_error['code'] != 0
    else {
        $response['success']         = true;
        $response['success_message'] = 'success';
        $response['result']          = $result;
    }
    return $response;
}
function getRegionsById($Id = NULL)
{
    $result = array();
    $ci =& get_instance();
    $ci->db->select('name as stateName,id as stateId');
    $ci->db->where('id', $Id);
    $query    = $ci->db->get('ws_regions');
    if ($query->num_rows() > 0 ) {
      $result   = $query->row();
    }
    return $result;
}

function getCitiesByRegionId($region_id = NULL)
{
    $ci =& get_instance();
    $ci->db->select('name as cityName ,id as CityId');
    $ci->db->where('sta_id', $region_id);
    $ci->db->order_by('name', 'asc');
    $query    = $ci->db->get('cities');
    $result   = $query->result();
    $db_error = $ci->db->error();
    if ($db_error['code'] != 0) {
        $response['success']       = false;
        $response['error_message'] = $db_error['message'];
    } //$db_error['code'] != 0
    else {
        $response['success']         = true;
        $response['success_message'] = 'success';
        $response['result']          = $result;
    }
    return $response;
}
function getDeliveryMethods()
{
    $ci =& get_instance();
    $ci->db->select('deliveryMethodRef , methodName, area');
    $ci->db->where('status', 1);
    $ci->db->order_by('methodName', 'asc');
    $query    = $ci->db->get('deliveryMethod');
    // echo $ci->db->last_query();dei;
    $result   = $query->result();
    $response = array();
    if (!empty($result)) {
        $response = $result;
    } //!empty($result)
    return $response;
}
function getPaymentMethods()
{
    $ci =& get_instance();
    $ci->db->select('pricingRef , payementMethod');
    $ci->db->where('status', 1);
    $ci->db->order_by('payementMethod', 'asc');
    $query    = $ci->db->get('pricingMethod');
    $result   = $query->result();
    $response = array();
    if (!empty($result)) {
        $response = $result;
    } //!empty($result)
    return $response;
}
function getUOM()
{
    $ci =& get_instance();
    $ci->db->select('unitRef , unitName');
    $ci->db->where('status', 1);
    $ci->db->order_by('unitName', 'asc');
    $query    = $ci->db->get('measurement');
    $result   = $query->result();
    $response = array();
    if (!empty($result)) {
        $response = $result;
    } //!empty($result)
    return $response;
}
function getManagers()
{
    $ci =& get_instance();
    $ci->db->select('userRef , userName', 'mobileNo');
    $ci->db->where('status', 1);
    $ci->db->where('userType', 2);
    $ci->db->order_by('userName', 'asc');
    $query    = $ci->db->get('ws_users');
    $result   = $query->result();
    $response = array();
    if (!empty($result)) {
        $response = $result;
    } //!empty($result)
    return $response;
}
function getSalesman()
{
    $ci =& get_instance();
    $ci->db->select('userRef , userName', 'mobileNo');
    $ci->db->where('userType !=', 1);
    $ci->db->where('isDeleted !=', 1);
    $ci->db->order_by('userName', 'asc');
    $query    = $ci->db->get('ws_users');
    $result   = $query->result();
    $response = array();
    if (!empty($result)) {
        $response = $result;
    } //!empty($result)
    return $response;
}
/*
<select class="form-control" id="stateSel" value="" name="stateId" aria-required="true" aria-invalid="false">
<option value="">Select State</option>
<option value="2004">Central</option>
<option value="2005">Coast</option>
<option value="2006">Eastern</option>
<option value="2007">Nairobi</option>
<option value="2008">North Eastern</option>
<option value="2009">Nyanza</option>
<option value="2010">Rift Valley</option>
<option value="2011">Western</option></select>
*/
function getCitiesByCityName($name = NULL, $id = null)
{
    $ci =& get_instance();
    $ci->db->select('cities.name as cityName ,cities.id as CityId,ws_regions.id as state_id , ws_regions.name as stateName');
    $ci->db->from('cities');
    $where = "( ws_cities.name LIKE '%" . trim($name) . "%')";
    if ($name != null || $name != '') {
        $ci->db->where($where);
    } //$name != null || $name != ''
    if ($id != null || $id != '') {
        $ci->db->where('sta_id', $id);
    } //$id != null || $id != ''
    $ci->db->where_in('sta_id', array(
        457,
        504,
        509,
        540,
        543,
        546,
        558,
        567
    ));
    $ci->db->join('ws_regions', 'ws_regions.id = sta_id', 'left');
    $ci->db->order_by('ws_cities.name ', 'asc');
    $query    = $ci->db->get();
    // echo $ci->db->last_query();die;
    $result   = $query->result();
    $result   = $query->result();
    $response = array();
    if (!empty($result)) {
        $response = $result;
    } //!empty($result)
    return $response;
}
function amountFormat($amount = NULL)
{
    $amounts = explode('.', $amount);
    $amount  = $amounts[0];
    $decimal = isset($amounts[1]) ? $amounts[1] : '00';
    $decimal = substr($decimal, 0, 2);
    $amount  = $amount . '.' . $decimal;
    return $amount;
}
function getCustomerDetails($customerRef)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("businessName,customerRef,contactName as fullName,ws_countries.name as countryName , ws_regions.name as stateName , ws_cities.name as cityName, phoneNo1,addressLine");
    $ci->db->from('ws_customers');
    $ci->db->join('ws_countries', 'ws_countries.id = countryId', 'left');
    $ci->db->join('ws_cities', 'ws_cities.id = cityId', 'left');
    $ci->db->join('ws_regions', 'ws_regions.id = stateId', 'left');
    $ci->db->where('ws_customers.customerRef', $customerRef);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->row();
    } //$query->num_rows() > 0
    return $result;
}
function getSubCategories($parentCatRef)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("catRef,categoryName");
    $ci->db->from('categories');
    $ci->db->where('parentCatRef', $parentCatRef);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->result();
    } //$query->num_rows() > 0
    return $result;
}
function getParentCategory($parentCatRef)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("catRef,categoryName");
    $ci->db->from('categories');
    $ci->db->where('catRef', $parentCatRef);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->row();
    } //$query->num_rows() > 0
    // echo "<pre>";print_r($result);echo "</pre>";
    return $result;
}
function dateDiff($start, $end)
{
    $datetime1 = new DateTime($start);
    return $end;
    /*
    $datetime2 = new DateTime($end);
    $interval = $datetime1->diff($datetime2);

    if(  $interval->format('%d') > 0){
    if ($interval->format('%d') > 1) {
    $day = 'Days ago';
    }else{
    $day = ' Day ago';
    }
    return $interval->format('%d')." $day";
    }elseif ($interval->format('%h') == 0) {
    return $interval->format('%i')." Min " . $interval->format('%s')." seconds ago";;
    }else {
    return $interval->format('%h')." Hours ".$interval->format('%i')." Minutes ago";
    }*/
}
function objectToArray($d)
{
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    } //is_object($d)
    if (is_array($d)) {
        /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
        return array_map(__FUNCTION__, $d);
    } //is_array($d)
    else {
        // Return array
        return $d;
    }
}
function exportData($assocDataArray, $fileName)
{
    ob_clean();
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    if (strpos($fileName, 'xls') !== false) {
        header("Content-Type: text/xls");
    } //strpos($fileName, 'xls') !== false
    else {
        header('Content-Type: text/csv');
    }
    header('Content-Disposition: attachment;filename=' . $fileName);
    if (isset($assocDataArray['0'])) {
        $fp = fopen('php://output', 'w');
        fputcsv($fp, array_keys($assocDataArray['0']));
        foreach ($assocDataArray AS $values) {
            fputcsv($fp, $values);
        } //$assocDataArray AS $values
        fclose($fp);
    } //isset($assocDataArray['0'])
    ob_flush();
}
function getSubCategoryByName($categoryName)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("catRef");
    $ci->db->from('categories');
    $ci->db->where('categoryName', $categoryName);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result->catRef;
    } //$query->num_rows() > 0
    else {
        return '';
    }
}
function getParentCategoryByName($categoryName)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("catRef");
    $ci->db->from('categories');
    $ci->db->where('categoryName', $categoryName);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result->catRef;
    } //$query->num_rows() > 0
    else {
        return '';
    }
}
function getUOMbyName($uom)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("unitRef");
    $ci->db->from('measurement');
    $ci->db->where('unitName', $uom);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result->unitRef;
    } //$query->num_rows() > 0
    else {
        return '';
    }
}
function getStateByName($stateName = null)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("id");
    $ci->db->from('ws_regions');
    $where = ('(ws_regions.name like "%' . $stateName . '%")');
    $ci->db->where($where);
    $ci->db->where('country_id', 49);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result->id;
    } //$query->num_rows() > 0
    else {
        return '';
    }
}
function getCityByName($cityName = null)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("id");
    $ci->db->from('ws_cities');
    $where = ('(ws_cities.name like "%' . $cityName . '%")');
    $ci->db->where($where);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result->id;
    } //$query->num_rows() > 0
    else {
        return '';
    }
}
function getMethodByName($methodName = null)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("deliveryMethodRef");
    $ci->db->from('ws_deliveryMethod');
    $where = ('(ws_deliveryMethod.methodName like "%' . $methodName . '%")');
    $ci->db->where($where);
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result->deliveryMethodRef;
    } //$query->num_rows() > 0
    else {
        return '';
    }
}
function status($val)
{
    $status = array(
        'Inactive',
        'Active'
    );
    return $status[$val];
}
function ActiveClass($val)
{
    $class = array(
        'label-warning',
        'label-success'
    );
    return $class[$val];
}
function getDiamentions($length = null  , $width  = null, $height = null)
{
    $height = ($height  != null && $height != '' ) ? $height : '0' ;
    $width  = ($width   != null && $width  != '' )   ? $width  : '0' ;
    $length  = ($length   != null && $length  != '' )   ? $length  : '0' ;
    $dimension = $length.'X'.$width.'X'.$height;
    return $dimension;
}
function daysName($val)
{
    $dayName = array(
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    );
    return $dayName[$val];
}
function orderStatus($val)
{
    $status = array(
        'approved'                =>    'Approved',
        'pending'                 =>    'Pending',
        'MAN'                     =>    'Manager Approval Needed',
        'NA'                      =>    'NA',
        'queued'                  =>    'Queued',
        'inProduction'            =>    'In Production',
        'open'                    =>    'Open',
        'OpenPartiallyFilled'     =>    'Open Partially Filled',
        'ClosedFilled'            =>    'Closed Filled',
        'ClosedPartiallyFilled'   =>    'Closed Partially Filled',
        'Closed'                  =>    'Closed',
        'reAssign'                =>    'Re-assigned',
        'onHold'                  =>    'On Hold',
        'cancelled'               =>    'Cancelled',
        'Cancelled'               =>    'Cancelled'
    );
    return $status[$val];
}
function sendNotification($data)
{
    $ci          = & get_instance();
    $apiKey      = 'AAAAE5hXmn8:APA91bHU1QIS3Nf7xsRHlGA1HUhMSNZbosuhcBF22rt5OHslmxw8wejj8cE53nbdn5E6gMliWC3lrOmSNwSsJCg7zQJ_lz9kksOm1ctdvlRAOZgJpTLRWtakDGyMirzjHl0MMdHBaZ5P';
    $register_Id = $data['registerID'];
    $DeviceId    = $data['deviceID'];
    $message     = $data['message'];
    if (empty($register_Id))
        return false;
    $phones = array();
    if (!empty($DeviceId)) {
        foreach ($DeviceId as $vk => $vl) {
            if ($vl == '2') {
                $phones['ios'][] = $register_Id[$vk];
            } //$vl == '2'
            else {
                $phones['android'][] = $register_Id[$vk];
            }
        } //$DeviceId as $vk => $vl
    } //!empty($DeviceId)
    foreach ($phones as $os => $ar) {
        if ($os == 'ios') {
            $fields = array(
                'registration_ids' => $ar,
                'priority' => 'high',
                'notification' => array(
                    'sound' => 'default',
                    'orderRef' => $message['orderRef'],
                    'title' => $message['titleName'],
                    'body' => $message['title'],
                    'badge' => 1,
                    'content_available' => 'true'
                )
            );
        } //$os == 'ios'
        else {
            $fields = array(
                'registration_ids' => $ar,
                'data' => $message,
                'priority' => 'high',
                'notification' => array(
                    'sound' => 'default',
                    'title' => $message['messagetitle'],
                    'body' => $message['title']
                )
            );
        }
        $headers = array(
            'Content-Type:application/json',
            'Authorization: key=' . $apiKey
        );
        $ch      = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($fields)
        ));
        $response[$os]['response'] = curl_exec($ch);
    } //$phones as $os => $ar
    //echo "<pre>"; print_r($fields); die;
    curl_close($ch);
    return $response;
}
function getRegisterIds($orderRef,$dataPipline = null)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("userName,ws_keys.userRef,ws_keys.device_id,ws_keys.registeredId,ws_keys.device_type");
    $ci->db->from('ws_keys');
    $ci->db->join('users', 'users.userRef=ws_keys.userRef', 'inner');
    $ci->db->like('users.userPermissions', $dataPipline);

    $query = $ci->db->get();
    // echo $ci->db->last_query();die;
    if ($query->num_rows() > 0) {
        $result = $query->result();
        $ci->db->select("ws_orders.salesRef,ws_orderDeliveryAddress.businessName,ws_orderDeliveryAddress.customerName,orderNo");
        $ci->db->from('ws_orders');
        $ci->db->join('ws_orderDeliveryAddress', 'ws_orderDeliveryAddress.orderRef=ws_orders.orderRef', 'inner');
        $ci->db->where('ws_orders.orderRef', $orderRef);
        $query = $ci->db->get();
        $orderData = $query->row();

        return ['users' =>$result , 'orderData' =>$orderData ];
    } //$query->num_rows() > 0
    else {
        return ['users' => array() , 'orderData' => array() ];
    }
}
function getRegisterUsersIds($orderRef)
{
    $ci =& get_instance();
    $result = array();
    $ci->db->select("
   userName,
   ws_keys.userRef,
   ws_keys.device_id,
   ws_keys.registeredId,
   ws_keys.device_type
   ");
    $ci->db->from('ws_keys');
    $ci->db->join('users', 'users.userRef=ws_keys.userRef', 'left');
    // $ci->db->join('ws_orderDeliveryAddress','ws_orderDeliveryAddress.orderRef=ws_orders.orderRef','inner');
    // $ci->db->join('ws_orders','salesRef=users.userRef','inner');
    // $ci->db->where('ws_orders.orderRef',$orderRef);
    $ci->db->where_in('users.userType', array(
        1,
        2
    ));
    $ci->db->group_by('users.userRef');
    $query = $ci->db->get();
    // echo $ci->db->last_query();die;
    if ($query->num_rows() > 0) {
        $result = $query->result();
        return $result;
    } //$query->num_rows() > 0
    else {
        return '';
    }
}
function getRegions($country_id = NULL)
{
    $ci =& get_instance();
    $ci->db->select('name as stateName,id as stateId');
    $ci->db->order_by('name', 'asc');
    $query    = $ci->db->get('ws_regions');
    $result   = $query->result();
    $db_error = $ci->db->error();
    if ($db_error['code'] != 0) {
        $response['success']       = false;
        $response['error_message'] = $db_error['message'];
    } //$db_error['code'] != 0
    else {
        $response['success']         = true;
        $response['success_message'] = 'success';
        $response['result']          = $result;
        foreach ($result as $key => $value) {
            $ci->db->set('stateRef', generateRef());
            $ci->db->where('id', $value->stateId);
            $ci->db->update('ws_regions');
        } //$result as $key => $value
    }
    die;
    return $response;
}
function getPermissions()
{
    $result = array();
    $ci =& get_instance();
    $ci->db->select('permissionName as permissionName, id as permissionId');
    $ci->db->from('ws_userPermission');
 $ci->db->where('status',1);
    $ci->db->order_by('id', 'asc');
    $query = $ci->db->get();
    if ($query->num_rows() > 0) {
        $result = $query->result();
    } //$query->num_rows() > 0
    return $result;
}
function getPermissionsId($prmisssionName = NULL)
{
    if (strpos($prmisssionName, 're-assigned') !== false) {
        $prmisssionName = 'Re-Assigned Orders Write';
    } //strpos($prmisssionName, 're-assigned') !== false
    else {
        $prmisssionName = str_replace("-", " ", $prmisssionName);
    }
    $result = array();
    $ci =& get_instance();
    $ci->db->select('id as permissionId');
    $ci->db->from('ws_userPermission');
    $where = ('(ws_userPermission.permissionName like "%' . $prmisssionName . '%")');
    $ci->db->where($where);
    $query = $ci->db->get();
    // echo $ci->db->last_query();die;
    if ($query->num_rows() > 0) {
        $result = $query->row();
    } //$query->num_rows() > 0
    return $result;
}
function encode_url_ci($string)
{
    return $stringAfter = str_replace(" ", "-", $string);
}
function getUserPermissionsByIds($ids = null)
{
    $ids    = explode(',', $ids);
    $result = array();
    $ci =& get_instance();
    $ci->db->select('permissionName,id');
    $ci->db->from('ws_userPermission');
    $ci->db->where_in('id', $ids);
    $query = $ci->db->get();
    // echo $ci->db->last_query();die;
    if ($query->num_rows() > 0) {
        $result = $query->result();
    } //$query->num_rows() > 0
    return $result;
}
function getUserDetailsByRef($id = null)
{
    $result = array();
    $ci =& get_instance();
    $ci->db->select('*');
    $ci->db->from('users');
    $ci->db->where('userRef', $id);
    $query = $ci->db->get();
    // echo $ci->db->last_query();die;
    if ($query->num_rows() > 0) {
        $result = $query->row();
    } //$query->num_rows() > 0
    return $result;
}
function getOrderByRef($orderRef = null)
{
    $result = array();
    $ci =& get_instance();
    $ci->db->select('orderNo,businessName,customerName,orderPipline');
    $ci->db->from('orders');
    $ci->db->join('orderDeliveryAddress', 'orderDeliveryAddress.orderRef = orders.orderRef', 'inner');
    $ci->db->where('orders.orderRef', $orderRef);
    $query = $ci->db->get();
    // echo $ci->db->last_query();die;
    if ($query->num_rows() > 0) {
        $result = $query->row();
    } //$query->num_rows() > 0
    return $result;
}
function getNotificationCount()
{
    $ci =& get_instance();
    $loginSessionData = $ci->session->userdata('clientData');
    $ci->userRef      = $loginSessionData['userRef'];
    $ci->load->model('CommonModel');
    $count = $ci->CommonModel->getNotificationCount($ci->userRef);
    if (!empty($count) != '') {
        $result = $count['notificationCount'];
    } //!empty($count) != ''
    else {
        $result = '';
    }
    return $result;
}
function productionDays($dayCount)
{
  $productionDays = array(
    'Mon' => 0,
    'Tue' => 1,
    'Wed' => 2,
    'Thu' => 3,
    'Fri' => 4,
    'Sat' => 5,
    'Sun' => 6,
  );
  return $productionDays[$dayCount];
}
function nextDays($dayCount)
{
  $productionDays = array(
     0 =>  'Mon',
     1 =>  'Tue',
     2 =>  'Wed',
     3 =>  'Thu',
     4 =>  'Fri',
     5 =>  'Sat',
     6 =>  'Sun',
  );
  return $productionDays[$dayCount];
}
// function getCurrrentProductionOutput($day)
// {
//     $ci = & get_instance();
//     $ci->db->select('*');
//     $ci->db->from('ws_productionOutput');
//     $ci->db->order_by('id`', 'DESC');
//     $ci->db->limit(1);
//     $query    = $ci->db->get();
//     if($query->num_rows() > 0){
//       $result   = $query->row();
//     }
//     $productionDays = array(
//       'Mon' => 0,
//       'Tue' => 1,
//       'Wed' => 2,
//       'Thu' => 3,
//       'Fri' => 4,
//       'Sat' => 5,
//       'Sun' => 6,
//     );
//     $productionByDays = explode(',',$result->blocks);
//     $i = 0;
//     foreach ($productionDays as $key => $value) {
//       $currentDayProduction[$key]= $productionByDays[$i];
//       $i++;
//     }
//     return $currentDayProduction[$day];
// }
function getCurrrentProductionOutput($day)
{
    $ci = & get_instance();
    $ci->db->select('*');
    $ci->db->from('ws_productionOutput');
    $ci->db->order_by('id`', 'DESC');
    $ci->db->limit(1);
    $query    = $ci->db->get();
    $daysProduction = array();
    if($query->num_rows() > 0){
      $result   = $query->row();
      $daysProduction = explode(',', $result->blocks);
    }
    return $daysProduction[$day];
}

function getDeliveryMethodByName($methodName = null)
{
  $ci = & get_instance();
  $ci->db->select('deliveryMethodRef');
  $ci->db->from('ws_deliveryMethod');
  $where = ('(ws_deliveryMethod.methodName like "%' . $methodName . '%")');
  $ci->db->where($where);
  $query    = $ci->db->get();
  $result = '';
  if($query->num_rows() > 0){
    $resultData   = $query->row();
    $result = $resultData->deliveryMethodRef;
  }
  return $result;
}
function limit_words($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ",array_splice($words,0,$word_limit)).'...';
}
function productId()
{
    $ci =& get_instance();
    $ci->db->select('id');
    $ci->db->order_by('id', 'desc');
    $query   = $ci->db->get('ws_products');
    $productId = '';
    if ($query->num_rows() > 0) {
        $result     = $query->result();
        $docnumbers = array();
        foreach ($result as $key => $val) {
            $result = 0;
            // $result += substr($val->orderNo, 4, strlen($val->orderNo));
            if (is_numeric($result))
                $docnumbers[] = $val->id;
        } //$result as $key => $val
        $maxDocNo = max($docnumbers);
        // echo "string => $maxDocNo";
        $productId  = $maxDocNo;
        $len      = strlen($productId);
        $productId  = substr($productId, 0, $len);
        // echo "sss$productId";
        $productId  = str_pad($productId + 1, 5, 0, STR_PAD_LEFT);
        $productId  = $productId;
    } //$query->num_rows() > 0
    else {
        $productId = '00001';
    }
    return $productId;
}
function dispatchNum($orderNo = null,$orderRef = null)
{
    $ci =& get_instance();
    $ci->db->select('dispatchNo');
    if (!is_null($orderRef)) {
      $ci->db->where('orderRef',$orderRef);
    }
    $ci->db->order_by('dispatchNo', 'desc');
    $query   = $ci->db->get('ws_dispatched_orders');
    $dispatchNum = '';
    if ($query->num_rows() > 0) {
        $result     = $query->result();
        $docnumbers = array();
        foreach ($result as $key => $val) {
            $result = 0;
            // $result += substr($val->orderNo, 4, strlen($val->orderNo));
            if (is_numeric($result))
                $docnumbers[] = $val->dispatchNo;
        } //$result as $key => $val
        $maxDocNo = max($docnumbers);
          // echo "string => $maxDocNo";
        $dispatchNum  = $maxDocNo;
        $dispatchLast = explode('-', $dispatchNum);
        // $len          = strlen($dispatchNum);
        // $dispatchNum  = substr($dispatchNum, 6, $len);
        // echo "sss$dispatchNum";
        $dispatchNum  = str_pad($dispatchLast[1] + 1, 2, 0, STR_PAD_LEFT);
        $dispatchNum  = $orderNo.'-'.$dispatchNum;
        // echo $dispatchNum;
    } //$query->num_rows() > 0
    else {
        $dispatchNum = $orderNo.'-01';
    }
    // echo $dispatchNum;die;
    return $dispatchNum;
}
/***************************************************************************************************************
****************************************************************************************************************
Old Dispacth no BadFunctionCallException
function dispatchNum($orderNo = null)
{
    $ci =& get_instance();
    $ci->db->select('dispatchNo');
    $ci->db->order_by('dispatchNo', 'desc');
    $query   = $ci->db->get('ws_dispatched_orders');
    $dispatchNum = '';
    if ($query->num_rows() > 0) {
        $result     = $query->result();
        $docnumbers = array();
        foreach ($result as $key => $val) {
            $result = 0;
            // $result += substr($val->orderNo, 4, strlen($val->orderNo));
            if (is_numeric($result))
                $docnumbers[] = $val->dispatchNo;
        } //$result as $key => $val
        $maxDocNo = max($docnumbers);
         // echo "string => $maxDocNo";
        $dispatchNum  = $maxDocNo;
        $len          = strlen($dispatchNum);
        $dispatchNum  = substr($dispatchNum, 0, $len);
        // echo "sss$dispatchNum";
        $dispatchNum  = str_pad($dispatchNum + 1, 5, 0, STR_PAD_LEFT);
        $dispatchNum  = $dispatchNum;
    } //$query->num_rows() > 0
    else {
        $dispatchNum = '100001';
    }
    return $dispatchNum;
}
**************************************************************************************************************
**************************************************************************************************************/
function generateSKU()
{
    $length         = 4;
    $randomString   = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    $randomString1  = substr(str_shuffle("0123456789ABCDE4543FG45OPQRSTUVWXYZ"), 0, $length);
    $lengths        = 3;
    $randomStrings  = substr(str_shuffle("0123456789"), 0, $lengths);
    $productNo      = 'PRO-'.$randomString.'-'. productId();
    return $productNo;
}
function getToLoadCount()
{
  $ci =& get_instance();
  $ci->db->select('id');
  $ci->db->where('toLoad',1);
  $ci->db->where('orderPipline',4);
  $ci->db->where('orderStatus != "cancelled"');
  $query   = $ci->db->get('orders');
  if ($query->num_rows() > 0)
  $rows = $query->num_rows();
  else $rows = 0;
  return $rows;
}
function getUnLoadCount()
{
  $ci =& get_instance();
  $ci->db->select('id');
  $ci->db->group_start();
  $ci->db->where('orderPipline', 4);
  $ci->db->where('toLoad', 0);
  $ci->db->where('orderStatus != "ClosedFilled"');
  $ci->db->where('orderStatus != "Closed"');
  $ci->db->group_end();
  $ci->db->where('orderStatus != "cancelled"');
  $ci->db->where('orderStatus != "reAssign"');
  $query   = $ci->db->get('orders');
  // echo $ci->db->last_query();die;
  if ($query->num_rows() > 0)
  $rows = $query->num_rows();
  else $rows = 0;
  return $rows;
}
function getDispatchCount()
{
  $ci =& get_instance();
  $ci->db->select('id');
  $ci->db->where('isModifyState',2);
  $ci->db->where('ws_dispatched_orders.addedOn   >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
  $query   = $ci->db->get('ws_dispatched_orders');
  // echo $ci->db->last_query();die;
  if ($query->num_rows() > 0)
  $rows = $query->num_rows();
  else $rows = 0;
  return $rows;
}
function getLoadingSheets()
{
  $ci =& get_instance();
  $ci->db->select('sheetRef,refName');
  $ci->db->where('status',1);
  $ci->db->order_by('refName','ASC');
  $query   = $ci->db->get('ws_loadingSheets');
  if ($query->num_rows() > 0)
  $rows['loadingSheets'] = $query->result();
  else $rows['loadingSheets'] = array();
  return $rows;
}


function getLoadingSheet($sheetRef)
{
  $ci =& get_instance();
  $ci->db->select('sheetRef,refName');
  $ci->db->where('sheetRef',$sheetRef);
  $query   = $ci->db->get('ws_loadingSheets');
  if ($query->num_rows() > 0)
  $rows = $query->row()->refName;
  else $rows = 'NA';
  return $rows;
}

function getLoadingSheetByRef($ref)
{
  $ci =& get_instance();
  $ci->db->select('sheetRef,refName');
  $ci->db->where('sheetRef',$ref);
  $query   = $ci->db->get('ws_loadingSheets');
  if ($query->num_rows() > 0)
  $rows = $query->row();
  else $rows = array();
  return $rows;
}

function oddEven($value)
{
  $row = '';
  if ($value % 2 == 0) {
    $row =  "evenRow";
  }else{
    $row =  "oddRow";
  }
  return $row;
}


function getOrderQtyItems($orderRef,$itemRefid)
{
  $ci =& get_instance();
  $ci->db->select('qty as orderQty, price');
  $ci->db->from('ws_orderItems');
  $ci->db->where('orderRef',$orderRef);
  $ci->db->where('itemRefId',$itemRefid);
  $query   = $ci->db->get();
  if ($query->num_rows() > 0)
  $rows = $query->row();
  else $rows = array();
  return $rows;
}
function getOrderQtyItemsVariants($orderRef,$itemRefid,$variant_id = null)
{
  $ci =& get_instance();
  $ci->db->select('ws_orderItemVariants.qty as orderQty');
  $ci->db->from('ws_orderItemVariants');
  $ci->db->where('ws_orderItemVariants.orderRef',$orderRef);
  $ci->db->where('productId',$itemRefid);
  if ($variant_id !='') {
    $ci->db->where('id',$variant_id);
  }
  $query   = $ci->db->get();
  if ($query->num_rows() > 0)
  $rows = $query->row();
  else $rows = array();
  return $rows;
}


function getDispatchedItems($orderRef,$itemRefid,$variant_id=null)
{
  $ci =& get_instance();
  $ci->db->select('SUM(qtyLoaded) as dispatchedQty');
  $ci->db->from('ws_dispatched_Items');
  $ci->db->join('ws_dispatched_orders','ws_dispatched_orders.dispatchNo = dispatchRef','inner');
  $ci->db->group_start();
  $ci->db->where('orderRefId',$orderRef);
  $ci->db->where('itemRefId',$itemRefid);
  if ($variant_id !='') {
    $ci->db->where('variant_id',$variant_id);
  }
  $ci->db->group_end();
  $ci->db->where('isModifyState',2);
  $query   = $ci->db->get();
  // echo $ci->db->last_query();
  if ($query->num_rows() > 0)
  $rows = $query->row();
  else $rows = array();
  return $rows;
}

// function getLastDispatchedItem($orderRef,$itemRefid)
// {
//   $ci =& get_instance();
//   $ci->db->select('SUM(qtyLoaded) as lastDispatchedQty');
//   $ci->db->from('ws_dispatched_Items');
//   $ci->db->join('ws_dispatched_orders','ws_dispatched_orders.dispatchNo = dispatchRef','inner');
//   $ci->db->group_start();
//   $ci->db->where('orderRefId',$orderRef);
//   $ci->db->where('itemRefId',$itemRefid);
//   $ci->db->group_end();
//   $ci->db->where('isModifyState',2);
//   $ci->db->order_by('ws_dispatched_Items.id','DESC');
//   $ci->db->limit(1);
//   $query   = $ci->db->get();
//   // echo $ci->db->last_query();die;
//   if ($query->num_rows() > 0)
//   $rows = $query->row();
//   else $rows = array();
//   return $rows;
// }
function getLastDispatchedItem($orderRef,$itemRefid,$variant_id = null)
{
  $ci =& get_instance();
  $ci->db->select('SUM(qtyLoaded) as lastDispatchedQty');
  $ci->db->from('ws_dispatched_Items');
  $ci->db->join('ws_dispatched_orders','ws_dispatched_orders.dispatchNo = dispatchRef','inner');
  $ci->db->group_start();
  $ci->db->where('orderRefId',$orderRef);
  $ci->db->where('itemRefId',$itemRefid);
  if ($variant_id !='') {
    $ci->db->where('variant_id',$variant_id);
  }
  $ci->db->group_end();
  $ci->db->where('isModifyState',2);
  $ci->db->order_by('ws_dispatched_Items.id','DESC');
  $ci->db->limit(1);
  $query   = $ci->db->get();

  if ($query->num_rows() > 0){
    $rows = new stdClass;
    $rows->lastDispatchedQty = $query->row()->lastDispatchedQty;
    $ci->db->select('qtyLoaded as lastDispatched');
    $ci->db->from('ws_dispatched_Items');
    $ci->db->join('ws_dispatched_orders','ws_dispatched_orders.dispatchNo = dispatchRef','inner');
    $ci->db->group_start();
    $ci->db->where('orderRefId',$orderRef);
    $ci->db->where('itemRefId',$itemRefid);
    if ($variant_id !='') {
      $ci->db->where('variant_id',$variant_id);
    }
    $ci->db->group_end();
    $ci->db->where('isModifyState',2);
    $ci->db->order_by('ws_dispatched_Items.id','ASC');
    $ci->db->limit(1);
    $query   = $ci->db->get();
    // echo $ci->db->last_query();
    if ($query->num_rows() > 0)
      $rows->lastDispatched = $query->row()->lastDispatched;

  }
  else $rows = array();
  // pr($rows);die;
  return $rows;
}

function getTotalDispatchQty($orderRef,$itemRefid, $variant_id = null)
{
  $ci =& get_instance();
  $ci->db->select('SUM(qtyLoaded) as totalDipatchQty');
  $ci->db->from('ws_dispatched_Items');
  $ci->db->join('ws_dispatched_orders','ws_dispatched_orders.dispatchNo = dispatchRef','inner');
  $ci->db->group_start();
  $ci->db->where('orderRefId',$orderRef);
  $ci->db->where('itemRefId',$itemRefid);
  if ($variant_id !='' && $variant_id != null) {
    $ci->db->where('variant_id',$variant_id);
  }
  $ci->db->group_end();
  $ci->db->where('isModifyState',2);
  $query   = $ci->db->get();
  // echo $ci->db->last_query();die;
  if ($query->num_rows() > 0)
  $rows = $query->row();
  else $rows = array();
  return $rows;
}
function getPendingDispatchQty($orderRef,$itemRefid,$variant_id = null)
{
  $ci =& get_instance();
  $ci->db->select('(qtyNotLoaded) as pendingDispatchQty');
  $ci->db->from('ws_dispatched_Items');
  $ci->db->join('ws_dispatched_orders','ws_dispatched_orders.dispatchNo = dispatchRef','inner');
  $ci->db->group_start();
  $ci->db->where('orderRefId',$orderRef);
  $ci->db->where('itemRefId',$itemRefid);
  if ($variant_id !='') {
    $ci->db->where('variant_id',$variant_id);
  }
  $ci->db->group_end();
  $ci->db->where('isModifyState',1);
  $ci->db->order_by('ws_dispatched_Items.id','DESC');
  $ci->db->limit(1);
  $query   = $ci->db->get();
  // echo "<pre>"; echo $ci->db->last_query();echo "</pre>";
  if ($query->num_rows() > 0)
  $rows = $query->row();
  else $rows = array();
  return $rows;
}

function getToDispatchQty($orderRef,$itemRefid,$variant_id = null)
{
  $ci =& get_instance();
  $ci->db->select('(qtyLoaded) toDispatchQty');
  $ci->db->from('ws_dispatched_Items');
  $ci->db->join('ws_dispatched_orders','ws_dispatched_orders.dispatchNo = dispatchRef','inner');
  $ci->db->group_start();
  $ci->db->where('orderRefId',$orderRef);
  $ci->db->where('itemRefId',$itemRefid);
  // checking for variant id
  $ci->db->where('variant_id',$variant_id);
  $ci->db->group_end();
  $ci->db->where('isModifyState',1);
  $ci->db->order_by('ws_dispatched_Items.id','DESC');
  $ci->db->limit(1);
  $query   = $ci->db->get();
  // echo "<pre>"; echo $ci->db->last_query();echo "</pre>";
  if ($query->num_rows() > 0)
  $rows = $query->row();
  else $rows = array();
  return $rows;
}
 function pr($value)
{
  echo "<pre>";print_r($value);echo "</pre></br>";
}

function getFollowupErrors()
{
  $ci =& get_instance();
  $ci->db->select('*');
  $ci->db->from('ws_followupErrors');
  $ci->db->order_by('ws_followupErrors.id','ASC');
  $query   = $ci->db->get();
  // echo "<pre>"; echo $ci->db->last_query();echo "</pre>";
  if ($query->num_rows() > 0)
    $rows = $query->result();
  else
    $rows = array();
  return $rows;
}
function getDispacpErrors($error = array())
{
  $ci =& get_instance();
  $ci->db->select('*');
  $ci->db->from('ws_followupErrors');
  $ci->db->where_in('id',$error);
  $query   = $ci->db->get();
  // echo "<pre>"; echo $ci->db->last_query();echo "</pre>";
  if ($query->num_rows() > 0)
    $rows = $query->result();
  else
    $rows = array();
  return $rows;
}
function disptachFollowup($dispatchNum)
{
  $ci =& get_instance();
  $ci->db->select('*');
  $ci->db->from('ws_dispatched_orders');
  $ci->db->where('dispatchNo',$dispatchNum);
  $query   = $ci->db->get();
  if ($query->num_rows() > 0)
    $rows = $query->row();
  else
    $rows = array();
  return $rows;
}
function orderErrors($ids)
{
  $ids = explode(',', $ids);
  $ci =& get_instance();
  $ci->db->select('*');
  $ci->db->from('ws_followupErrors');
  $ci->db->where_in('id',$ids);
  $query   = $ci->db->get();
  if ($query->num_rows() > 0)
      $rows = $query->result();
  else
      $rows = array();
  return $rows;
}
function followupComments($ref,$type)
{

  $ci =& get_instance();
  $ci->db->select('type,comment,orderRef as dispatchNo');
  $ci->db->from('ws_orderComments');
  $ci->db->where('orderRef',$ref);
  $ci->db->where_in('type',$type);
  $query   = $ci->db->get();
  // echo $ci->db->last_query();die;
  if ($query->num_rows() > 0)
      $rows = $query->result();
  else
      $rows = array();
  return $rows;
}

function getVariantsById($id = null)
{
    if($id != null){
        $ci =& get_instance();
        $ci->db->select('id,ws_variants.*');
        $ci->db->from('ws_variants');
        $ci->db->where('productId',$id);
        $ci->db->where('status',1);
        $query   = $ci->db->get();
        // echo $ci->db->last_query();die;
        if ($query->num_rows() > 0)
            $rows = $query->result();
        else
            $rows = array();
        return $rows;
    }


}
function getOrderItemVariants($pro = null,$id = null)
{
    $ci =& get_instance();
    $rows = array();
    if($pro != null){

        $ci->db->select('*');
        $ci->db->from('ws_orderItemVariants');
        $ci->db->where('productId',$pro);
        $ci->db->where('status',1);
        $query   = $ci->db->get();
        // echo $ci->db->last_query();die;
        if ($query->num_rows() > 0)
            $rows = $query->row();
    }else{
      $ci->db->select('ws_orderItemVariants.* , id as variant_id ');
      $ci->db->from('ws_orderItemVariants');
      $ci->db->where('id',$id);
      $ci->db->where('status',1);
      $query   = $ci->db->get();
      // echo $ci->db->last_query();die;
      if ($query->num_rows() > 0)
          $rows = $query->row();
    }
    return $rows;

}

function getProductAttributs($id = null)
{
    if($id != null){
        $ci =& get_instance();
        $ci->db->select('*');
        $ci->db->from('ws_variants');
        $ci->db->where('productId',$id);
        // $ci->db->where('status',1);
        $ci->db->order_by('design','ASC');
        $query   = $ci->db->get();
        // echo $ci->db->last_query();die;
        $getVariants = array();
        if ($query->num_rows() > 0)
            $getVariants = $query->result();

        $ci =& get_instance();
        $ci->db->select('*');
        $ci->db->from('variantsSizes');
        $ci->db->where('productId',$id);
        $ci->db->where('status',1);
        $query   = $ci->db->get();
        // echo $ci->db->last_query();die;
        $variantsSizes = array();
        if ($query->num_rows() > 0)
            $variantsSizes = $query->result();

        $ci =& get_instance();
        $ci->db->select('*');
        $ci->db->from('variantsDesign');
        $ci->db->where('productId',$id);
        $ci->db->where('status',1);
        $query   = $ci->db->get();
        // echo $ci->db->last_query();die;
        $variantsDesign = array();
        if ($query->num_rows() > 0)
            $variantsDesign = $query->result();


        $ci =& get_instance();
        $ci->db->select('*');
        $ci->db->from('variantsColor');
        $ci->db->where('productId',$id);
        $ci->db->where('status',1);
        $query   = $ci->db->get();
        // echo $ci->db->last_query();die;
        $variantsColor = array();
        if ($query->num_rows() > 0)
            $variantsColor = $query->result();

        return array(
          'getVariants'     => $getVariants,
          'variantsSizes'   => $variantsSizes,
          'variantsDesign'  => $variantsDesign,
          'variantsColor'   => $variantsColor,
        );
    }
}
function getVariantsByOrderRef($orderRef = null , $productRef = null)
{
    if($orderRef != null  ){
        $ci =& get_instance();
        $ci->db->select('*, `id` as `variant_id`, `height`, `width`, `length`, `color`, `design`,`price`, `minPrice`, `blockPercentage` , blockType , qty,isCustomize
        ');
        $ci->db->from('ws_orderItemVariants');
        $ci->db->where('orderRef',$orderRef);
        if ($productRef != '' || $productRef !=null) {
          $ci->db->where('productId',$productRef);
        }
        $ci->db->where('status',1);
        $query   = $ci->db->get();
        // echo $ci->db->last_query();die;
        if ($query->num_rows() > 0)
            $rows = $query->result();
        else
            $rows = array();
        return $rows;
    }
}

function apiGetVariantsByOrderRef($orderRef = null , $productRef = null)
{
  if($orderRef != null  ){
      $ci =& get_instance();
      // $ci->db->select('*, `id` as `variant_id`, `height`, `width`, `length`, `color`, `design`,`price`, `minPrice`, `blockPercentage` , blockType , qty,isCustomize');
      $ci->db->select('`id` as `variant_id`, `productId`, `item_variant_id`, `orderRef`, `height`, `width`, `length`, `color`, `design`, `price`, `minPrice`, `transportCharge`, `blockPercentage`, `blockType`, `discountType`, `discount`, `qty`, `isCustomize`
      ,(select price from ws_variants where ws_variants.item_variant_id = ws_orderItemVariants.item_variant_id LIMIT 0,1) as defaultPrice');
      $ci->db->from('ws_orderItemVariants');
      $ci->db->where('orderRef',$orderRef);
      if ($productRef != '' || $productRef !=null) {
        $ci->db->where('productId',$productRef);
      }
      $ci->db->where('status',1);
      $query   = $ci->db->get();
      // echo $ci->db->last_query();die;
      if ($query->num_rows() > 0)
          $rows = $query->result();
      else
          $rows = array();
      return $rows;
  }
}

function getRemark($id = NULL)
{
    $ci =& get_instance();
    $ci->db->select('orderRemarks');
    $ci->db->where('orderRef', $id);
    $query  = $ci->db->get('ws_orders');
    $result = array();
    if ($query->num_rows() > 0) {
        $result = $query->row_array();
    } //$query->num_rows() > 0
    return $result;
}

function get_ItemUOM($orderRef , $itemRefid){
  $ci =& get_instance();
  $ci->db->select('saleUOM,baseUOM');
  $ci->db->where('orderRef', $orderRef);
  $ci->db->where('itemRefId', $itemRefid);
  $ci->db->where('saleUOM !=', "");
  $ci->db->where('baseUOM !=', "");
  $query  = $ci->db->get('ws_orderItems');
  // echo $ci->db->last_query();die;
  $result = array();
  if ($query->num_rows() > 0) {
      $result = $query->row();
  } else{
    $result = new stdClass();
    $result->saleUOM = 'NA';
    $result->baseUOM = 'NA';
  }
  // $result->saleUOM = $orderRef;
  // $result->baseUOM = $itemRefid;
  // pr($result);die;
  return $result;
}

function echoVariants($value)
{

  $height = ($value->height  != null && $value->height != '' ) ? $value->height : '0' ;
  $width  = ($value->width   != null && $value->width  != '' )   ? $value->width  : '0' ;
  $length  = ($value->length   != null && $value->length  != '' )   ? $value->length  : '0' ;
  $qty = ($value->qty  != null && $value->qty != '' ) ? $value->qty : '0' ;
  $color  = ($value->color   != null && $value->color  != '' )   ? $value->color  : '' ;
  $design  = ($value->design   != null && $value->design  != '' )   ? $value->design  : '' ;
  $dimension = $length.'x'.$width.'x'.$height .' '. $color . ' ' .$design;
  return $dimension;
}
function blockTypes()
{

  $ci =& get_instance();
  $ci->db->select('blockType,id');
  $ci->db->where('status ', 1);
  $query  = $ci->db->get('blockTypes');
  $result = array();
  if ($query->num_rows() > 0) {
      $result = $query->result();
  }
  return $result;

}
function getblockType($id)
{
  $result = '';
  $ci =& get_instance();
  $ci->db->select('blockType');
  $ci->db->where('id ', $id);
  $query  = $ci->db->get('blockTypes');
  $result = 'NA';
  if ($query->num_rows() > 0) {
      $result = $query->row()->blockType;
  }
  return $result;

}
function blockPercentage($blockPercentage)
{
  $totals = 0;
  if (!empty($blockPercentage)) {
    foreach ($blockPercentage as $key => $value) {
      $blockqty       = $value->qty;
      $blockPercetage = $value->blockPercentage;
      if ($blockPercetage > 0 ) {
        // echo "blockPercetage ===>".$blockPercetage.'<br>';
        $total          =  $blockPercetage / 100;
        // echo "total ===>".$total.'<br>';
        $total          = $total * $blockqty;
        // echo "total ===>".$total.'<br>';
        $totals          +=  $total;
      }
    }
    // echo 'sdsdf=>'.$total;
  }
    $totals + 0;
    $totals  = (float) $totals ? $totals : number_format($totals,2);
  return $totals;
}



?>
