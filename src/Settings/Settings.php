<?php
namespace AbcTravels\Settings;

use AbcTravels\Crud\Crud;

class Settings
{
    public static $table = DEF_TBL_SETTINGS;

    public static function updateSettings()
    {
        $siteName = trim($_REQUEST['siteName']);
        $siteEmail = trim($_REQUEST['siteEmail']);
        $bookingEmail = trim($_REQUEST['bookingEmail']);
        $sitePhone = trim($_REQUEST['sitePhone']);
        $sitePhoneOthers = trim($_REQUEST['sitePhoneOthers']);
        $siteAddress = trim($_REQUEST['siteAddress']);
        $licenseNumber = trim($_REQUEST['licenseNumber']);
        $setSubscriptionPopup = doTypeCastInt($_REQUEST['setSubscriptionPopup']);
        $subscriptionPopupText = trim($_REQUEST['subscriptionPopupText']);
        //$hotelLink = trim($_REQUEST['hotelLink']);
        $siteFacebook = trim($_REQUEST['siteFacebook']);
        $siteTwitter = trim($_REQUEST['siteTwitter']);
        $siteInstagram = trim($_REQUEST['siteInstagram']);
        $siteLinkedin = trim($_REQUEST['siteLinkedin']);
        $siteYoutube = trim($_REQUEST['siteYoutube']);

        $data = [
            'name' => $siteName,
            'email' => $siteEmail,
            'booking_email' => $bookingEmail,
            'phone' => $sitePhone,
            'phone_others' => $sitePhoneOthers,
            'address' => $siteAddress,
            'license_number' => $licenseNumber,
            'set_subscription_popup' => $setSubscriptionPopup,
            'subscription_text' => $subscriptionPopupText,
            //'hotel_link' => $hotelLink,
            'facebook' => $siteFacebook,
            'twitter' => $siteTwitter,
            'instagram' => $siteInstagram,
            'linkedin' => $siteLinkedin,
            'youtube' => $siteYoutube
        ];

        $update = Crud::update(
            self::$table,
            $data,
            []
        );
        if ($update)
        {
            $rs = $_SESSION['user'];
            $rs['siteName'] = $siteName;
            $rs['siteEmail'] = $siteEmail;
            $rs['bookingEmail'] = $bookingEmail;
            $rs['sitePhone'] = $sitePhone;
            $rs['sitePhoneOthers'] = $sitePhoneOthers;
            $rs['siteAddress'] = $siteAddress;
            $rs['licenseNumber'] = $licenseNumber;
            $rs['setSubscriptionPopup'] = $setSubscriptionPopup;
            $rs['subscriptionPopupText'] = $subscriptionPopupText;
            //$rs['hotelLink'] = $hotelLink;
            $rs['siteFacebook'] = $siteFacebook;
            $rs['siteTwitter'] = $siteTwitter;
            $rs['siteInstagram'] = $siteInstagram;
            $rs['siteLinkedin'] = $siteLinkedin;
            $rs['siteYoutube'] = $siteYoutube;
            //update session data
            $_SESSION['user'] = $rs;
        }
    }

    public static function getSettings()
    {
        return Crud::select(
            self::$table
        );
    }
}