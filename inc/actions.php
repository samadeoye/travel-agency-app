<?php
require_once 'utils.php';

use AbcTravels\Param\Param;
use AbcTravels\Destination\Destination;
use AbcTravels\Tour\Tour;
use AbcTravels\Submission\Submission;
use AbcTravels\Vehicle\Vehicle;
use AbcTravels\Hotel\Hotel;

$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';
if ($action == '')
{
    getJsonRow(false, 'Invalid request!');
}

$params = Param::getRequestParams($action);
doValidateRequestParams($params);

try
{
    $data = [];
    $db->beginTransaction();

    switch($action)
    {
        case 'getDestinationsPaginationData':
            Destination::getAppDestinationsPaginationData();
            $rs = Destination::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'getToursPaginationData':
            Tour::getAppToursPaginationData();
            $rs = Tour::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'getSpecialToursPaginationData':
            Tour::getAppSpecialToursPaginationData();
            $rs = Tour::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'addCommonEnquiry':
        case 'addTourEnquiry':
        case 'addHotelEnquiry':
            Submission::addEnquiry();
        break;

        case 'addContact':
            Submission::addContact();
        break;

        case 'customizeTrip':
            Submission::customizeTrip();
        break;

        case 'getVehiclesPaginationData':
            Vehicle::getAppVehiclesPaginationData();
            $rs = Vehicle::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'getRoomsPaginationData':
            Hotel::getAppRoomsPaginationData();
            $rs = Hotel::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'subscribe':
            AbcTravels\Subscription\Subscription::addSubscription();
        break;
    }

    $db->commit();
    if (count($data) > 0)
    {
        getJsonList($data);
    }
    getJsonRow(true, 'Operation successful!');
}
catch(Exception $ex)
{
	$db->rollBack();
	// $ex->getMessage();exit;
    getJsonRow(false, $ex->getMessage());
}