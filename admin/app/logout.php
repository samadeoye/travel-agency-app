<?php
require_once '../../inc/utils.php';

unset($_SESSION['user']);
session_destroy();

header('Location: '.DEF_ROOT_PATH_ADMIN.'/app/login');