<?php
function getJsonRow($status, $msg, $extraData=[])
{
  $response['status'] = $status;
  $response['msg'] = $msg;

  if (count($extraData) > 0)
  {
    foreach($extraData as $key => $value)
    {
      $response[$key] = $value;
    }
  }
  getJsonList($response);
}
function getJsonList($row) {
  if(count($row) > 0) {
    echo json_encode($row, JSON_PRETTY_PRINT);
  }
  exit;
}

function issetParam($param, $method) {
  if(strtolower($method) == 'post') {
    return isset($_POST[$param]);
  }
  elseif(strtolower($method) == 'get') {
    return isset($_GET[$param]);
  }
  return isset($_REQUEST[$param]);
}

function notEmptyParam($param, $method) {
  if($method == 'post') {
    return !empty($_POST[$param]);
  }
  return !empty($_GET[$param]);
}

function issetNotEmpty($param, $method, $connector) {
  return issetParam($param, $method) .$connector. notEmptyParam($param, $method);
}

function cleanme($text) {
	$cleanit = strip_tags(trim($text));
	return $cleanit;
}

function doTypeCastDouble($number) {
  return doubleval($number);
}

function doNumberFormat($number) {
  return number_format($number, 2);
}

function doTypeCastInt($number)
{
    return intval($number);
}

function getUniqIdUpper()
{
  return strtoupper(uniqid());
}

function getTransactionReference()
{
  return SITE_ABR.'-REF-'.getUniqIdUpper();
}

function doCheckIfEmpty($data) {
  if(count($data) > 0) {
    foreach($data as $dt) {
      if(gettype($dt) == 'string') {
        if(strlen($dt) == 0) {
          getJsonRow(false, 'Please fill all required fields.');
        }
      }
      if(gettype($dt) == 'array') {
        if(count($dt) == 0) {
          getJsonRow(false, 'Please fill all required fields.');
        }
      }
    }
  }
}

function doValidateRequestParams($data)
{
  if(count($data) > 0)
  {
    foreach($data as $key => $val)
    {
      $validate = doCheckParamIssetEmpty($key, $val);
      if(!$validate['status'])
      {
        getJsonRow(false, $validate['msg']);
      }
    }
  }
}

function doCheckParamIssetEmpty($param, $data)
{
  $datax = [
    'status' => true,
    'msg' => ''
  ];
  
  $method = $data['method'];
  $label = $data['label'];
  $length = isset($data['length']) ? $data['length'] : [0,0];
  $required = isset($data['required']) ? $data['required'] : false;
  $type = isset($data['type']) ? $data['type'] : "";
  $isEmail = isset($data['is_email']) ? $data['is_email'] : false;

  if(empty($label))
  {
    $label = $param;
  }
  if(strtolower($method) == 'post')
  {
    $isset = isset($_POST[$param]);
    $value = isset($_POST[$param]) ? $_POST[$param] : "";
  }
  elseif(strtolower($method) == 'get')
  {
    $isset = isset($_GET[$param]);
    $value = $isset ? $_GET[$param] : "";
  }
  else
  {
    $isset = isset($_REQUEST[$param]);
    $value = $isset ? $_REQUEST[$param] : "";
  }
  
  if($required)
  {
    $isset = $isset && !empty($value);
    if(!$isset)
    {
      $datax['status'] = false;
      $datax['msg'] = $label . ' is required.';
      return $datax;
    }
  }
  if(!empty($type) && !empty($value))
  {
    if($type == 'string')
    {
      if(!is_string($value))
      {
        $datax['status'] = false;
        $datax['msg'] = $label . ' must be a string.';
        return $datax;
      }
    }
    elseif($type == 'number')
    {
      if(!is_numeric($value))
      {
        $datax['status'] = false;
        $datax['msg'] = $label . ' must contain only digits.';
        return $datax;
      }
    }
  }
  if((!empty($value) && $isEmail) || (!empty($value) && trim($param) == 'email'))
  {
    if(!filter_var($value, FILTER_VALIDATE_EMAIL))
    {
      $datax['status'] = false;
      $datax['msg'] = $label . ' must contain a valid email.';
      return $datax;
    }
  }
  if($length[0] > 0 && $length[1] > 0 && $length[0] == $length[1] && !empty($value))
  {
    $isset = $isset && strlen($value) == $length[0];
    if(!$isset)
    {
      $datax['status'] = false;
      if(strpos($param, '_id') !== false || $param == 'id')
      {
        $datax['msg'] = $label . ' in invalid.';
      }
      else
      {
        $datax['msg'] = $label . ' must be equal to ' . $length[0] .' characters.';
      }
      return $datax;
    }
  }
  if($length[0] > 0 && !empty($value))
  {
      $isset = $isset && strlen($value) >= $length[0];
      if(!$isset)
      {
        $datax['status'] = false;
        if(strpos($param, '_id') !== false || $param == 'id')
        {
          $datax['msg'] = $label . ' in invalid.';
        }
        else
        {
          $datax['msg'] = $label . ' must be greater than or equal to ' . $length[0] .' characters.';
        }
        return $datax;
      }
  }
  if($length[1] > 0 && !empty($value))
  {
    $isset = $isset && strlen($value) <= $length[1];
    if(!$isset)
    {
      $datax['status'] = false;
      if(strpos($param, '_id') !== false || $param == 'id')
      {
        $datax['msg'] = $label . ' in invalid.';
      }
      else
      {
        $datax['msg'] = $label . ' must be less than or equal to ' . $length[1] .' characters.';
      }
      return $datax;
    }
  }
  return $datax;
}

function getLoginTempSessions($emailPhone, $key)
{
  if(!isset($_SESSION['loginTemp']))
  {
    $_SESSION['loginTemp']['count'][$emailPhone] = 1;
    $_SESSION['loginTemp']['locked'][$emailPhone] = false;
  }
  
  if($key == 'count')
  {
    if(!isset($_SESSION['loginTemp']['count'][$emailPhone]))
    {
      $_SESSION['loginTemp']['count'][$emailPhone] = 1;
    }
    return $_SESSION['loginTemp']['count'][$emailPhone];
  }
  elseif($key == 'locked')
  {
    if(!isset($_SESSION['loginTemp']['locked'][$emailPhone]))
    {
      $_SESSION['loginTemp']['locked'][$emailPhone] = false;
    }
    return $_SESSION['loginTemp']['locked'][$emailPhone];
  }
}
function updateTempSessions($emailPhone, $data)
{
  if(array_key_exists('count', $data))
  {
    if($data['count'] == 'inc')
    {
      $_SESSION['loginTemp']['count'][$emailPhone]++;
    }
    elseif($data['count'] == 'reset')
    {
      $_SESSION['loginTemp']['count'][$emailPhone] = 0;
    }
  }
  if(array_key_exists('locked', $data))
  {
    $_SESSION['loginTemp']['locked'][$emailPhone] = $data['locked'];
  }
}

function getMinutesDiff($time1, $time2)
{
  $time1 = strtotime(date('H:i:s', $time1));
  $time2 = strtotime(date('H:i:s', $time2));
  return round((abs($time1) / 60) - (abs($time2) / 60));
}
function getNewId()
{
  mt_srand((int)microtime()*10000);
  $charId = strtoupper(md5(uniqid(rand(), true)));
  $hyphen = chr(45);
  $id = substr($charId, 0, 8).$hyphen
  .substr($charId, 8, 4).$hyphen
  .substr($charId, 12, 4).$hyphen
  .substr($charId, 16, 4).$hyphen
  .substr($charId, 20, 12);
  return $id;
}

function getOptionsWithIds($type, $option)
{
  if($type == 'yesno')
  {
    if(doTypeCastInt($option) == 1)
    {
      return 'YES';
    }
    return 'NO';
  }
  if($type == 'styleclass')
  {
    if(doTypeCastInt($option) == 1)
    {
      return 'success';
    }
    elseif(doTypeCastInt($option) == 0)
    {
      return 'danger';
    }
    return 'warning';
  }
  if($type == 'activeinactive')
  {
    if(doTypeCastInt($option) == 1)
    {
      return 'ACTIVE';
    }
    return 'INACTIVE';
  }
}

function getFormattedDate($date, $format='')
{
  if ($date != '')
  {
    if(strlen($date) == 10)
    {
      $format = !empty($format) ? $format : 'Y-m-d H:i';
      return date($format, $date);
    }
  }
  else
  {
    return '';
  }
}

function getAlertWrapper($id, $close=false)
{
  $closeIcon = $closeAble = '';
  if ($close)
  {
    $closeIcon = '<a class="close" href="#"></a>';
    $closeAble = 'closeable';
  }
  return <<<EOQ
  <div class="row" id="{$id}_div" style="display:none;">
    <div class="col-md-12">
      <div class="notification {$closeAble} margin-bottom-10" id="{$id}_wrapper">
        <p id="{$id}"></p>
        {$closeIcon}
      </div>
    </div>
  </div>
EOQ;
}

function getAlertWrapperDisplay($msg, $alertClass='error', $close=true)
{
  $closeIcon = $closeAble = '';
  if ($close)
  {
    $closeIcon = '<a class="close" href="#"></a>';
    $closeAble = 'closeable';
  }
  return <<<EOQ
  <div class="row">
    <div class="col-md-12">
      <div class="notification {$alertClass} {$closeAble} margin-bottom-10">
        <p>{$msg}</p>
        {$closeIcon}
      </div>
    </div>
  </div>
EOQ;
}

function blockOutToMainPage()
{
  header('Location: '.DEF_ROOT_PATH_ADMIN.'/app/login');
  exit;
}

function getCurrentPageAdmin($pageTitle)
{
  $arCurrentPage = [
    'dashboard' => '',
    'destinations' => '',
    'tours' => '',
    'addtour' => '',
    'alltours' => '',
    'submissions' => '',
    'submission' => '',
    'vehicles' => '',
    'termsandconditions' => '',
    'profile' => '',
    'settings' => '',
    'changepassword' => ''
  ];
  $lblActive = 'active';
  $pageTitle = str_replace(' ', '', $pageTitle);
  switch(strtolower($pageTitle))
  {
    case 'dashboard':
      $arCurrentPage['dashboard'] = $lblActive;
    break;
    case 'destinations':
      $arCurrentPage['destinations'] = $lblActive;
    break;
    case 'tours':
      $arCurrentPage['tours'] = $lblActive;
    break;
    case 'addtour':
      $arCurrentPage['addtour'] = $lblActive;
      $arCurrentPage['tours'] = $lblActive;
    break;
    case 'updatetour':
      $arCurrentPage['tours'] = $lblActive;
    break;
    case 'alltours':
      $arCurrentPage['alltours'] = $lblActive;
      $arCurrentPage['tours'] = $lblActive;
    break;
    case 'submissions':
    case 'submission':
      $arCurrentPage['submissions'] = $lblActive;
    break;
    case 'vehicles':
      $arCurrentPage['vehicles'] = $lblActive;
    break;
    case 'termsandconditions':
      $arCurrentPage['termsandconditions'] = $lblActive;
    break;
    case 'profile':
      $arCurrentPage['profile'] = $lblActive;
    break;
    case 'settings':
      $arCurrentPage['settings'] = $lblActive;
    break;
    case 'changepassword':
      $arCurrentPage['settings'] = $lblActive;
    break;
  }
  
  return $arCurrentPage;
}

function getCurrentPage($pageTitle)
{
  $arCurrentPage = [
    'home' => '',
    'about' => '',
    'contact' => '',
    'tours' => '',
    'vehicles' => '',
    'terms' => ''
  ];
  $lblActive = 'active';
  $pageTitle = str_replace(' ', '', $pageTitle);
  switch(strtolower($pageTitle))
  {
    case 'home':
      $arCurrentPage['home'] = $lblActive;
    break;
    case 'about':
      $arCurrentPage['about'] = $lblActive;
    break;
    case 'contact':
      $arCurrentPage['contact'] = $lblActive;
    break;
    case 'tours':
      $arCurrentPage['tours'] = $lblActive;
    break;
    case 'vehicles':
      $arCurrentPage['vehicles'] = $lblActive;
    break;
    case 'termsandconditions':
      $arCurrentPage['terms'] = $lblActive;
    break;
  }
  
  return $arCurrentPage;
}

function getUserSessionFields()
{
  return 'id, fname, lname, email, password, status';
}

function getUserSession()
{
  return $_SESSION['user'];
}
function stringToUpper($text)
{
  if ($text != '')
  {
    return strtoupper(strtolower($text));
  }
  return $text;
}
function stringToTitle($text)
{
  if ($text != '')
  {
    return ucwords(strtolower($text));
  }
  return $text;
}

function getTextEditorAllowedTags()
{
  $allowedTags = '<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
  $allowedTags .= '<li><ol><ul><span><div><br><ins><del>';
  return $allowedTags;
}

function getSubmissionType($typeId)
{
  $title = '';
  switch($typeId)
  {
    case DEF_SUBMISSION_TYPE_COMMON_ENQUIRY:
      $title = 'General Enquiry';
    break;
    case DEF_SUBMISSION_TYPE_TOUR_ENQUIRY:
      $title = 'Tour Enquiry';
    break;
    case DEF_SUBMISSION_TYPE_CUSTOMIZED_TOUR:
      $title = 'Customized Tour Request';
    break;
    case DEF_SUBMISSION_TYPE_CONTACT:
      $title = 'Contact Form';
    break;
  }

  return $title;
}
?>