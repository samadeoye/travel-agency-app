<?php
namespace AbcTravels\Admin\User;

use Exception;
use AbcTravels\Crud\Crud;
use AbcTravels\SendMail\SendMail;

class User
{
    static $table = DEF_TBL_USERS;
    static $tablePasswordReset = DEF_TBL_PASSWORD_RESET;
    static $data = [];
    public static function getUser($id, $arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(', ', $arFields) : $arFields;
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => [
                    'id' => $id
                ]
            ]
        );
    }
    public static function checkIfUserExists($field, $value)
    {
        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'id',
                'where' => [
                    $field => $value
                ]
            ]
        );
        if ($rs)
        {
            return true;
        }
        return false;
    }
    public static function changePassword()
    {
        global $userId;

        $currentPassword = trim($_REQUEST['currentPassword']);
        $newPassword = trim($_REQUEST['newPassword']);
        $confirmPassword = trim($_REQUEST['confirmPassword']);
        $userPassword = $_SESSION['user']['password'];

        if ($newPassword != $confirmPassword)
        {
            throw new Exception('Passwords do not match');
        }
        elseif ($userPassword != md5($currentPassword))
        {
            throw new Exception('Old password is incorrect');
        }
        else
        {
            $newPassword = md5($newPassword);
            $data = [
                'password' => $newPassword,
                'mdate' => time()
            ];
            $update = Crud::update(
                self::$table,
                $data,
                [
                    'id' => $userId
                ]
            );
            if ($update)
            {
                $rs = $_SESSION['user'];
                $rs = array_merge($rs, ['password' => $newPassword]);
                $_SESSION['user'] = $rs;
            }
        }
    }
    public static function updateUser()
    {
        global $userId;

        $fname = stringToUpper(trim($_REQUEST['fname']));
        $lname = stringToUpper(trim($_REQUEST['lname']));

        $data = [
            'fname' => $fname,
            'lname' => $lname,
            'mdate' => time()
        ];
        $update = Crud::update(
            self::$table,
            $data,
            [
                'id' => $userId
            ]
        );
        if ($update)
        {
            $rs = $_SESSION['user'];
            $rs = array_merge($rs, $data);
            $_SESSION['user'] = $rs;

            $data = [
                'status' => true,
                'data' => $_SESSION['user']
            ];
            self::$data = $data;
        }
    }

    public static function verifyEmailForPasswordReset()
    {
        $email = strtolower(trim($_REQUEST['email']));

        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'fname, lname',
                'where' => [
                    'email' => $email
                ]
            ]
        );
        if ($rs)
        {
            global $arSiteSettings;

            //send password reset email
            $id = getNewId();
            $name = $rs['fname'] .' '. $rs['lname'];
            $siteName = $arSiteSettings['name'];
            $siteRootPath = DEF_FULL_ROOT_PATH;

            $body = <<<EOQ
                Dear {$rs['fname']},<br>
                Use the link below to complete your password reset on {$siteName}.<br>
                <a href="{$siteRootPath}/app/resetpassword?token={$id}">Reset Password</a>

EOQ;

            $arParams = [
                'mailTo' => $email,
                'toName' => $name,
                'mailFrom' => $arSiteSettings['email'],
                'fromName' => $arSiteSettings['name'],
                'isHtml' => true,
                'bodyHtml' => $body
            ];
            SendMail::sendMail($arParams);
            if (SendMail::$isSent)
            {
                $data = [
                    'id' => $id,
                    'email' => $email,
                    'cdate' => time()
                ];
                Crud::insert(self::$tablePasswordReset, $data);
            }
            else
            {
                throw new Exception('An error occured. Please try again.');
            }
        }
        else
        {
            throw new Exception('This email does not exist on the system');
        }
    }

    public static function resetPassword()
    {
        $token = trim($_REQUEST['token']);
        $password = trim($_REQUEST['password']);
        $passwordConfirm = trim($_REQUEST['passwordConfirm']);

        if ($password != $passwordConfirm)
        {
            throw new Exception('Passwords do not match!');
        }

        $rs = Crud::select(
            self::$tablePasswordReset,
            [
                'columns' => 'email',
                'where' => [
                    'id' => $token
                ],
                'order' => 'cdate DESC',
                'limit' => 1
            ]
        );

        if ($rs)
        {
            Crud::update(
                self::$table,
                ['password' => md5($password)],
                ['email' => $rs['email']]
            );
            //delete password reset log
            Crud::delete(self::$tablePasswordReset, ['email' => $rs['email']]);
        }
        else
        {
            throw new Exception('Token is invalid. Please click the link from your email.');
        }
    }
}