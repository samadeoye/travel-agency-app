<?php
namespace AbcTravels\Admin\Image;

use Exception;

class Image
{
    static $acceptedOrigins = [DEF_LOCAL_SERVER, DEF_LIVE_SERVER];
    static $arExtensions = ['jpg', 'jpeg', 'png'];
    static $maxSize = 2048576;
    static $directory = 'images/tour/';//'assets/img/tour/';
    static $fieldId = 'featuredImg';
    public static function uploadTinyMCEImage()
    {
        $imageFolder = self::$directory;

        if (isset($_SERVER['HTTP_ORIGIN']))
        {
            if (in_array($_SERVER['HTTP_ORIGIN'], self::$acceptedOrigins))
            {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            }
            else
            {
                header('HTTP/1.1 403 Origin Denied');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            header('Access-Control-Allow-Methods: POST, OPTIONS');
            exit;
        }

        reset($_FILES);
        $temp = current($_FILES);
        $imgFileSize = $temp['size'];
        if (is_uploaded_file($temp['tmp_name']))
        {
            //sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name']))
            {
                header("HTTP/1.1 400 Invalid file name");
                exit;
            }

            $fileExt = strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExt, self::$arExtensions))
            {
                header('HTTP/1.1 400 Invalid extension.');
                exit;
            }
            elseif ($imgFileSize > self::$maxSize)
            {
                header('HTTP/1.1 400 file too large');
                exit;
            }
            $newFileName = uniqid().'.'.$fileExt;
            if (move_uploaded_file($temp['tmp_name'], DEF_DOC_ROOT . $imageFolder . $newFileName))
            {
                //echo json_encode(['location' => DEF_FULL_ROOT_PATH . '/' . $imageFolder . $newFileName]);
                echo json_encode(['location' => $imageFolder . $newFileName]);
            }
            else
            {
                header('HTTP/1.1 500 Server Error');
                exit;
            }
        }
        else
        {
            //notify editor that the upload failed
            header('HTTP/1.1 500 Server Error');
        }
    }

    public static function uploadImage()
    {
        $imgFileSize = $_FILES[self::$fieldId]['size'];
        $imgFileName = $_FILES[self::$fieldId]['name'];
        $imgTmpFileName = $_FILES[self::$fieldId]['tmp_name'];
        
        if ($imgFileSize > 0)
        {
            $arFileExt = explode('.', $imgFileName);
            $fileExt = strtolower(end($arFileExt));
    
            if (!in_array($fileExt, self::$arExtensions))
            {
                $extensions = implode(', ', self::$arExtensions);
                throw new Exception("Invalid extension! Only {$extensions} are allowed");
            }
            elseif ($imgFileSize > self::$maxSize)
            {
                throw new Exception('The file is too large');
            }
            else
            {
                $newFileName = uniqid().'.'.$fileExt;
                if (move_uploaded_file($imgTmpFileName, DEF_DOC_ROOT.self::$directory.$newFileName))
                {
                    return $newFileName;
                }
                else
                {
                    throw new Exception('An error occurred while uploading your file');
                }
            }
        }
    }
}