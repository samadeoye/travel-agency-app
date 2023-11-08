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
        $siteAddress = trim($_REQUEST['siteAddress']);
        $siteFacebook = trim($_REQUEST['siteFacebook']);
        $siteTwitter = trim($_REQUEST['siteTwitter']);
        $siteInstagram = trim($_REQUEST['siteInstagram']);
        $siteLinkedin = trim($_REQUEST['siteLinkedin']);

        $data = [
            'name' => $siteName,
            'email' => $siteEmail,
            'booking_email' => $bookingEmail,
            'phone' => $sitePhone,
            'address' => $siteAddress,
            'facebook' => $siteFacebook,
            'twitter' => $siteTwitter,
            'instagram' => $siteInstagram,
            'linkedin' => $siteLinkedin
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
            $rs['siteAddress'] = $siteAddress;
            $rs['siteFacebook'] = $siteFacebook;
            $rs['siteTwitter'] = $siteTwitter;
            $rs['siteInstagram'] = $siteInstagram;
            $rs['siteLinkedin'] = $siteLinkedin;
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