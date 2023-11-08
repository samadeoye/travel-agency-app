<?php
require_once 'config.php';
define('SITE_URL', 'http://localhost/abctravels');
define('SITE_DOMAIN', 'abctravels.com');
define('DEF_LOCAL_SERVER', 'http://localhost');
define('DEF_LIVE_SERVER', 'https://abctravels.com');

define('DEF_SUBMISSION_TYPE_COMMON_ENQUIRY', 1);
define('DEF_SUBMISSION_TYPE_TOUR_ENQUIRY', 2);
define('DEF_SUBMISSION_TYPE_CUSTOMIZED_TOUR', 3);
define('DEF_SUBMISSION_TYPE_CONTACT', 4);

//TABLES
define('DEF_TBL_USERS', 'users');
define('DEF_TBL_DESTINATIONS', 'destinations');
define('DEF_TBL_TOURS', 'tours');
define('DEF_TBL_CONTACTS', 'contacts');
define('DEF_TBL_PASSWORD_RESET', 'password_reset');
define('DEF_TBL_SETTINGS', 'settings');
define('DEF_TBL_SUBMISSIONS', 'submissions');
define('DEF_TBL_TERMS', 'terms');
define('DEF_TBL_VEHICLES', 'vehicles');
?>