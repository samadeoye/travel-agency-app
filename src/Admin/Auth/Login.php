<?php
namespace AbcTravels\Admin\Auth;

use Exception;
use AbcTravels\Crud\Crud;
use AbcTravels\Settings\Settings;

class Login
{
    static $table = DEF_TBL_USERS;
    public static function loginUser()
    {
        $email = trim($_REQUEST['email']);
        $password = trim($_REQUEST['password']);

        //check if a user exists with the email
        $rs = Crud::select(
            self::$table,
            [
                'columns' => getUserSessionFields(),
                'where' => [
                    'email' => $email,
                    'deleted' => 0
                ]
            ]
        );
        if ($rs)
        {
            if ($rs['status'] != 1)
            {
                throw new Exception('Your account is disabled. Please contact the admin.');
            }
            elseif (md5($password) != $rs['password'])
            {
                throw new Exception('Email or Password is incorrect');
            }
            else
            {
                //get site settings
                $rsx = Settings::getSettings();
                if ($rsx)
                {
                    $rs['siteName'] = $rsx['name'];
                    $rs['siteEmail'] = $rsx['email'];
                    $rs['bookingEmail'] = $rsx['booking_email'];
                    $rs['sitePhone'] = $rsx['phone'];
                    $rs['sitePhoneOthers'] = $rsx['phone_others'];
                    $rs['siteAddress'] = $rsx['address'];
                    $rs['licenseNumber'] = $rsx['license_number'];
                    $rs['setSubscriptionPopup'] = $rsx['set_subscription_popup'];
                    $rs['subscriptionText'] = $rsx['subscription_text'];
                    $rs['hotelName'] = $rsx['hotel_name'];
                    $rs['hotelLink'] = $rsx['hotel_link'];
                    $rs['siteFacebook'] = $rsx['facebook'];
                    $rs['siteTwitter'] = $rsx['twitter'];
                    $rs['siteInstagram'] = $rsx['instagram'];
                    $rs['siteLinkedin'] = $rsx['linkedin'];
                    $rs['siteYoutube'] = $rsx['youtube'];
                }
                //login
                $_SESSION['user'] = $rs;
            }
        }
        else
        {
            throw new Exception('User with this email does not exist');
        }
    }
}
?>