<?php
namespace AbcTravels\UserAnalytics;

use Exception;

class UserAnalytics
{
    private $geoInfo;
    private $userAgent;

    public function __construct()
    {
        try
        {
            $this->userAgent =  $_SERVER['HTTP_USER_AGENT'];
            $rs = $this->getGeoInfo();
            if  (array_key_exists('status', $rs))
            {
                if ($rs['status'] == 'success')
                {
                    $this->geoInfo = $rs;
                }
            }
        }
        catch(Exception $e)
        {
            $this->geoInfo = [];
        }

    }

    public function getBrowser()
    {
        $arBrowser = [
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        ];

        $browser = '';
        $userAgent = $this->userAgent;
        foreach ($arBrowser as $regex => $value)
        {
            if (preg_match($regex, $userAgent))
            {
                $browser = $value;
            }
        }
        return $browser;
    }

    public function getIP()
    {
        $result = null;
        //for proxy servers
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $result = array_filter(array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));
            $result = end($result);
        }
        else
        {
            $result = $_SERVER['REMOTE_ADDR'];
        }
        return $result;
    }

    public function getReverseDNS()
    {
        return gethostbyaddr($this->getIP());
    }

    public function getCurrentURL()
    {
        return 'http'. (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's': '') 
        . '://' . $_SERVER["SERVER_NAME"]
        . $_SERVER["REQUEST_URI"];
    }

    public function getRefererURL()
    {
        return (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
    }

    public function getLanguage() {
        return strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
    }

    public function getCountryCode()
    {
        $result = '';
        if (is_array($this->geoInfo) && isset($this->geoInfo['countryCode']))
        {
            $result = $this->geoInfo['countryCode'];
        }
        return $result;
    }

    public function getCountryName()
    {
        $result = '';
        if (is_array($this->geoInfo) && isset($this->geoInfo['country']))
        {
            $result = $this->geoInfo['country'];
        }
        return $result;
    }

    public function getRegionCode()
    {
        $result = '';
        if (is_array($this->geoInfo) && isset($this->geoInfo['region']))
        {
            $result = $this->geoInfo['region'];
        }
        return $result;
    }

    public function getRegionName()
    {
        $result = '';
        if (is_array($this->geoInfo) && isset($this->geoInfo['regionName']))
        {
            $result = $this->geoInfo['regionName'];
        }
        return $result;
    }

    public function getCity()
    {
        $result = '';
        if (is_array($this->geoInfo) && isset($this->geoInfo['city']))
        {
            $result = $this->geoInfo['city'];
        }
        return $result;
    }

    public function getTimeZone()
    {
        $result = '';
        if (is_array($this->geoInfo) && isset($this->geoInfo['timezone']))
        {
            $result = $this->geoInfo['timezone'];
        }
        return $result;
    }

    public function getZipcode()
    {
        $result = '';
        if (is_array($this->geoInfo) && isset($this->geoInfo['zip']))
        {
            $result = $this->geoInfo['zip'];
        }
        return $result;
    }

    public function getLatitude()
    {
        $result = '';
        if (is_array($this->geoInfo) && isset($this->geoInfo['lat']))
        {
            $result = $this->geoInfo['lat'];
        }
        return $result;
    }

    public function getLongitude()
    {
        $result = '';
        if (is_array($this->geoInfo) && isset($this->geoInfo['lon']))
        {
            $result = $this->geoInfo['lon'];
        }
        return $result;
    }

    public function isProxy()
    {
        $result = false;
        //for proxy servers
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $addresses = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            if (count($addresses) > 0)
            {
                $result = true;
            }
        }
        return $result;
    }

    private function getGeoInfo()
    {
        $url = DEF_GEO_INFO_API_URL . $this->getIP();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return $result;
    }
}