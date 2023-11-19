<?php
namespace AbcTravels\Admin\Auth;

use Exception;
use AbcTravels\Crud\Crud;
use AbcTravels\Admin\User\User;
use AbcTravels\Settings\Settings;

class Register
{
    static $table = DEF_TBL_USERS;
    public static function registerUser()
    {
        $fname = stringToUpper(trim($_REQUEST['fname']));
        $lname = stringToUpper(trim($_REQUEST['lname']));
        $email = strtolower(trim($_REQUEST['email']));
        $password1 = trim($_REQUEST['password1']);
        $password2 = trim($_REQUEST['password2']);

        if ($password1 != $password2)
        {
            getJsonRow(false, 'Passwords do not match');
        }

        //check if a user exists with the same email
        if (User::checkIfUserExists('email', $email))
        {
            throw new Exception('A user already exists with this email');
        }

        //proceed to register
        $id = getNewId();
        $data = [
            'id' => $id,
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'password' => md5($password1),
            'cdate' => time()
        ];
        if (Crud::insert(self::$table, $data))
        {
            $rs = Crud::select(
                self::$table,
                [
                    'columns' => getUserSessionFields(),
                    'where' => [
                        'id' => $id
                    ]
                ]
            );
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
                $rs['hotelLink'] = $rsx['hotel_link'];
                $rs['siteFacebook'] = $rsx['facebook'];
                $rs['siteTwitter'] = $rsx['twitter'];
                $rs['siteInstagram'] = $rsx['instagram'];
                $rs['siteLinkedin'] = $rsx['linkedin'];
                $rs['siteYoutube'] = $rsx['youtube'];
            }
            $_SESSION['user'] = $rs;
        }
        else
        {
            throw new Exception('An error occured');
        }
    }
}