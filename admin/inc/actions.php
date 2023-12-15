<?php
require_once '../../inc/utils.php';

use AbcTravels\Admin\Param\Param;
use AbcTravels\Admin\User\User;
use AbcTravels\Admin\Destination\Destination;
use AbcTravels\Admin\Tour\Tour;
use AbcTravels\Submission\Submission;
use AbcTravels\Vehicle\Vehicle;
use AbcTravels\Analytics;
use AbcTravels\ImageSlider\ImageSlider;
use AbcTravels\Subscription\Subscription;
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
    $data = $extraData = [];
    $db->beginTransaction();

    switch($action)
    {
        case 'register':
            AbcTravels\Admin\Auth\Register::registerUser();
        break;

        case 'login':
            AbcTravels\Admin\Auth\Login::loginUser();
        break;

        case 'updateprofile':
            User::updateUser();
            $rs = User::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'changepassword':
            User::changePassword();
        break;

        case 'forgotpassverifyemail':
            User::verifyEmailForPasswordReset();
        break;

        case 'resetpassword':
            User::resetPassword();
        break;

        case 'adddestination':
            Destination::addDestination();
        break;
        
        case 'updatedestination':
            Destination::updateDestination();
            $extraData = Destination::$data;
        break;

        case 'getdestinations':
            Destination::getDestinationsList();
            $rs = Destination::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'deletedestination':
            Destination::deleteDestination();
        break;

        case 'addtour':
        case 'updatetour':
            Tour::addOrUpdateTour();
            $rs = Tour::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'gettours':
            Tour::getToursList();
            $rs = Tour::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'getvehicles':
            Vehicle::getVehiclesList();
            $rs = Vehicle::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'addvehicle':
        case 'updatevehicle':
            Vehicle::addOrUpdateVehicle();
            $extraData = Vehicle::$data;
        break;

        case 'deletevehicle':
            Vehicle::deleteVehicle();
        break;

        case 'gethotelrooms':
            Hotel::getHotelRoomsList();
            $rs = Hotel::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'addhotelroom':
        case 'updatehotelroom':
            Hotel::addOrUpdateHotelRoom();
            $extraData = Hotel::$data;
        break;

        case 'deletehotelroom':
            Hotel::deleteHotelRoom();
        break;

        case 'updatesettings':
            AbcTravels\Settings\Settings::updateSettings();
        break;

        case 'getsubmissions':
        case 'getdashboardsubmissions':
            Submission::getSubmissionsList();
            $rs = Submission::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'deletesubmission':
            Submission::deleteSubmission();
        break;
        
        case 'getsubscriptions':
            Subscription::getSubscriptionsList();
            $rs = Subscription::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'deletesubscription':
            Subscription::deleteSubscription();
        break;

        case 'updateterms':
            AbcTravels\Terms\Terms::updateTerms();
        break;
        
        case 'getanalytics':
            Analytics::getAnalyticsList();
            $rs = Analytics::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'getanalyticschartdata':
            Analytics::getAnalyticsChartData();
            $rs = Analytics::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'getsliders':
            ImageSlider::getSlidersList();
            $rs = ImageSlider::$data;
            if (count($rs) > 0)
            {
                $data = $rs;
            }
        break;

        case 'addslider':
        case 'updateslider':
            ImageSlider::addOrUpdateSlider();
            $extraData = ImageSlider::$data;
        break;

        case 'deleteslider':
            ImageSlider::deleteSlider();
        break;
    }

    $db->commit();
    if (count($data) > 0)
    {
        getJsonList($data);
    }
    getJsonRow(true, 'Operation successful!', $extraData);
}
catch(Exception $ex)
{
	$db->rollBack();
	// $ex->getMessage();exit;
    getJsonRow(false, $ex->getMessage());
}