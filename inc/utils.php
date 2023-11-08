<?php
session_start();

$httpHost = $_SERVER['HTTP_HOST'];
$httpFolderPath = '';
$isProductionServer = true;
$isLocal = false;
//if ($httpHost == 'localhost')
if (in_array($httpHost, ['localhost', '127.0.0.1']))
{
    //LOCAL
    $httpFolderPath = '/abctravels';
    $httpHost = 'http://'.$httpHost;
    $isProductionServer = false;
    $isLocal = true;
}
else
{
    //PRODUCTION
    $httpHost = 'https://'.$httpHost;
}
define('DEF_ROOT_PATH', $httpFolderPath);
define('DEF_ROOT_PATH_ADMIN', DEF_ROOT_PATH.'/admin');
define('DEF_FULL_ROOT_PATH', $httpHost.$httpFolderPath);
define('DEF_IS_PRODUCTION', $isProductionServer);
define('DEF_IS_LOCAL', $isLocal);
define('DEF_CORE_PATH_LOCAL', 'http://localhost/abctravels-admin/');
define('DEF_CORE_PATH_LIVE', 'https://abctravels-admin.com/');
define('DEF_CORE_PATH', DEF_CORE_PATH_LOCAL);
define('DEF_CORE_IMG_PATH', DEF_CORE_PATH.'assets/img/');

if(DEF_IS_LOCAL)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

define('DEF_DOC_ROOT', $_SERVER['DOCUMENT_ROOT'] .'/'. $httpFolderPath . '/');
define('DEF_DOC_ROOT_ADMIN', DEF_DOC_ROOT.'admin/');

require_once DEF_DOC_ROOT.'vendor/autoload.php';
require_once DEF_DOC_ROOT.'inc/functions.php';
require_once DEF_DOC_ROOT.'inc/constants.php';
require_once DEF_DOC_ROOT.'inc/connect.php';

if (isset($_SESSION['user']))
{
    $arUser = getUserSession();
    $userId = $arUser['id'];
}

$arAdditionalCSS = $arAdditionalJs = $arAdditionalJsScripts = $arAdditionalJsOnLoad = [];
$arSiteSettings = AbcTravels\Settings\Settings::getSettings();